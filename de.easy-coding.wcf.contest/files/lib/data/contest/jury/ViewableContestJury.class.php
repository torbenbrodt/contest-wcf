<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/jury/ContestJury.class.php');
require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');
require_once(WCF_DIR.'lib/data/message/bbcode/MessageParser.class.php');

/**
 * Represents a viewable contest entry jury.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Jurys
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ViewableContestJury extends ContestJury {
	/**
	 * user object
	 *
	 * @var UserProfile
	 */
	protected $user = null;
	
	/**
	 * @see DatabaseObject::handleData()
	 */
	protected function handleData($data) {
		parent::handleData($data);
		$this->user = new UserProfile(null, $data);
	}
	
	/**
	 * Returns the user object.
	 * 
	 * @return	UserProfile
	 */
	public function getUser() {
		return $this->user;
	}
}
?>
