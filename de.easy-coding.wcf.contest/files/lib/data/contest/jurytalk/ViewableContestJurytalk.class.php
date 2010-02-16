<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/jurytalk/ContestJurytalk.class.php');
require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');
require_once(WCF_DIR.'lib/data/message/bbcode/MessageParser.class.php');

/**
 * Represents a viewable contest entry jurytalk.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ViewableContestJurytalk extends ContestJurytalk {
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
	 * Returns the formatted jurytalk.
	 * 
	 * @return	string
	 */
	public function getFormattedMessage() {
		$enableSmilies = 1; 
		$enableHtml = 0; 
		$enableBBCodes = 1;
	
		MessageParser::getInstance()->setOutputType('text/html');
		return MessageParser::getInstance()->parse($this->message, $enableSmilies, $enableHtml, $enableBBCodes);
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
