<?php
// wcf imports
require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');
require_once(WCF_DIR.'lib/data/user/group/ContestGroupProfile.class.php');

/**
 * Represents a list of contest entries.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestOwner {

	/**
	 * cache in getGroupids, will become array after first call
	 * 
	 * @var null|array
	 */
	private static $groupIDs = null;

	/**
	 * the meaningful instance 
	 *
	 * @var UserProfile|ContestGroupProfile
	 */
	protected $owner = null;

	/**
	 * Creates a new ContestOverviewList object.
	 */
	public function __construct($data, $userID, $groupID) {
		if($groupID) {
			if(isset($data['groupID'], $data['groupName'])) {
				$this->owner = new ContestGroupProfile(null, $data);
			} else {
				$this->owner = new ContestGroupProfile($groupID);
			}
		} else {
			if(isset($data['userID'], $data['username'])) {
				$this->owner = new UserProfile(null, $data);
			} else {
				$this->owner = new UserProfile($userID);
			}
		}
	}
	
	/**
	 * static method to construct
	 */
	public static function get($userID, $groupID) {
		return new self(null, $userID, $groupID);
	}

	/**
	 * pass magic method to owner object
	 */
	public function __set($name, $value) {
		$this->owner->$name = $value;
	}
	
	/**
	 * pass magic method to owner object
	 */
	public function __call($method, $args) {
		return call_user_func_array(array($this->owner, $method), $args);
	}

	/**
	 * pass magic method to owner object
	 */
	public function __get($name) {
		return $this->owner->$name;
	}
	
	/**
	 * common method to access username or groupname
	 */
	public function getName() {
		if($this->owner instanceof UserProfile) {
			return $this->owner->username;
		} else {
			return $this->owner->groupName;
		}
	}
	
	/**
	 * common method to generate link to user- or group profile page
	 */
	public function getLink() {
		if($this->owner instanceof UserProfile) {
			return $this->owner->userID > 0 ? 'index.php?page=User&userID='.$this->owner->userID : '';
		} else {
			return 'index.php?page=ContestGroup&groupID='.$this->owner->groupID;
		}
	}
	
	/**
	 * is the current user member of this?
	 */
	public static function isOwner($userID, $groupID) {
		$myuserID = WCF::getUser()->userID;
		if(empty($myuserID)) {
			return false;
		}

		return $myuserID == $userID || in_array($groupID, self::getGroupIDs());
	}
	
	private static function getGroupIDs() {
		return self::$groupIDs !== null ? $groupIDs : $groupIDs = WCF::getUser()->getGroupIDs();
	}
}
?>
