<?php
// wcf imports
require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');
require_once(WCF_DIR.'lib/data/user/group/ContestGroupProfile.class.php');

/**
 * Represents a list of contest entries.
 *
 * Most common usage:
 * @code
 * ContestOwner::get($userID, $groupID)->isCurrentUser();
 * @endcode
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestOwner {
	/**
	 * pseudo singleton for using ContestOwner::get(...)
	 * constructor is not private
	 *
	 * @var array<ContestOwner>
	 */
	public static $instances = array();

	/**
	 * cache in getGroupids, will become array after first call
	 * 
	 * @var null|array
	 */
	private static $currentGroupIDs = null;

	/**
	 * the meaningful instance 
	 *
	 * @var UserProfile|ContestGroupProfile
	 */
	protected $owner = null;

	/**
	 * Creates a new ContestOverviewList object.
	 *
	 * @param	mixed		$data
	 * @param	integer		$userID
	 * @param	integer		$groupID
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
		
		// remember for singleton usage
		if($groupID || $userID) {
			$key = self::key($userID, $groupID);
			self::$instances[$key] = $this;
		}
	}
	
	/**
	 * static method to construct
	 *
	 * @param	integer		$userID
	 * @param	integer		$groupID
	 */
	public static function get($userID, $groupID) {
		$key = md5(serialize(array($userID, $groupID)));
		if(!isset(self::$instances[$key])) {
			self::$instances[$key] = new self(null, $userID, $groupID);
		}
		return self::$instances[$key];
	}

	/**
	 * static method get a singleton key
	 *
	 * @param	integer		$userID
	 * @param	integer		$groupID
	 */
	protected static function key($userID, $groupID) {
		return md5(serialize(array($userID, $groupID)));
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
	 *
	 * @return boolean
	 */
	public function isCurrentUser() {
		$myuserID = WCF::getUser()->userID;
		if(empty($myuserID)) {
			return false;
		}

		return $myuserID == $this->userID || in_array($this->groupID, self::getCurrentGroupIDs());
	}
	
	/**
	 * i don't care for blackbox thinking ;)
	 * calling the wcf class for a hundred times in inperformat, thats why i use this wrapper method
	 * 
	 * @return array<integer>
	 */
	private static function getCurrentGroupIDs() {
		return self::$currentGroupIDs !== null ? self::$currentGroupIDs : self::$currentGroupIDs = WCF::getUser()->getGroupIDs();
	}
}
?>
