<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/comment/ContestComment.class.php');
require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');
require_once(WCF_DIR.'lib/data/message/bbcode/MessageParser.class.php');

/**
 * Represents a viewable contest entry comment.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ViewableContestComment extends ContestComment {
	/**
	 * owner object
	 *
	 * @var ContestOwner
	 */
	protected $owner = null;
	
	/**
	 * @see DatabaseObject::handleData()
	 */
	protected function handleData($data) {
		parent::handleData($data);
		$this->owner = new ContestOwner($data, $this->userID, $groupID = 0);
	}
	
	/**
	 * Returns the formatted comment.
	 * 
	 * @return	string
	 */
	public function getFormattedMessage() {
		$enableSmilies = 1; 
		$enableHtml = 0; 
		$enableBBCodes = 1;
	
		MessageParser::getInstance()->setOutputType('text/html');
		return MessageParser::getInstance()->parse($this->comment, $enableSmilies, $enableHtml, $enableBBCodes);
	}
	
	/**
	 * Returns an excerpt of the message.
	 * 
	 * @return	string
	 */
	public function getExcerpt() {
		$enableSmilies = 1; 
		$enableHtml = 0; 
		$enableBBCodes = 1;
	
		MessageParser::getInstance()->setOutputType('text/plain');
		$message = MessageParser::getInstance()->parse($this->comment, $enableSmilies, $enableHtml, $enableBBCodes);
		
		// get abstract
		if (StringUtil::length($message) > 50) {
			$message = StringUtil::substring($message, 0, 47) . '...';
		}
		
		return $message;
	}
	
	/**
	 * Returns the owner object.
	 * 
	 * @return	ContestOwner
	 */
	public function getOwner() {
		return $this->owner;
	}
}
?>
