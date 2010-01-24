<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/comment/ContestComment.class.php');
require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');
require_once(WCF_DIR.'lib/data/message/bbcode/MessageParser.class.php');

/**
 * Represents a viewable contest entry comment.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Comments
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ViewableContestComment extends ContestComment {
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
	 * Returns the formatted comment.
	 * 
	 * @return	string
	 */
	public function getFormattedComment() {
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
	 * Returns the user object.
	 * 
	 * @return	UserProfile
	 */
	public function getUser() {
		return $this->user;
	}
}
?>
