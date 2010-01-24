<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');
require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');

/**
 * Represents a viewable contest entry.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ViewableContest extends Contest {
	/**
	 * user object
	 *
	 * @var UserProfile
	 */
	protected $user = null;

	/**
	 * Creates a new ViewableContest object.
	 *
	 * @param	integer		$contestID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($contestID, $row = null) {
		if ($contestID !== null) {
			$sql = "SELECT		user_table.username, contest.*
				FROM 		wcf".WCF_N."_contest contest
				LEFT JOIN	wcf".WCF_N."_user user_table
				ON		(user_table.userID = contest.userID)
				WHERE 		contest.contestID = ".$contestID;
			$row = WCF::getDB()->getFirstRow($sql);
		}
		DatabaseObject::__construct($row);
	}

	/**
	 * @see DatabaseObject::handleData()
	 */
	protected function handleData($data) {
		parent::handleData($data);
		$this->user = new UserProfile(null, $data);
	}
	
	/**
	 * Returns an excerpt of the message.
	 * 
	 * @return	string
	 */
	public function getExcerpt() {
		// pre-format
		$message = StringUtil::trim(StringUtil::unifyNewlines($this->message));
		
		// find 1st paragraph
		$excerpt = preg_replace('/^(.*?)\n\n.*/s', '$1', $message);
		if (StringUtil::length($excerpt) != StringUtil::length($message)) {
			$this->data['hasMoreText'] = 1;
		}
		
		// format
		require_once(WCF_DIR.'lib/data/message/bbcode/MessageParser.class.php');
		MessageParser::getInstance()->setOutputType('text/html');
		require_once(WCF_DIR.'lib/data/message/bbcode/AttachmentBBCode.class.php');
		AttachmentBBCode::setMessageID($this->contestID);
		return MessageParser::getInstance()->parse($excerpt, $this->enableSmilies, $this->enableHtml, $this->enableBBCodes);
	}
	
	/**
	 * Returns the formatted message.
	 * 
	 * @return	string
	 */
	public function getFormattedMessage() {
		require_once(WCF_DIR.'lib/data/message/bbcode/MessageParser.class.php');
		MessageParser::getInstance()->setOutputType('text/html');
		require_once(WCF_DIR.'lib/data/message/bbcode/AttachmentBBCode.class.php');
		AttachmentBBCode::setMessageID($this->contestID);
		return MessageParser::getInstance()->parse($this->message, $this->enableSmilies, $this->enableHtml, $this->enableBBCodes, !$this->messagePreview);
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