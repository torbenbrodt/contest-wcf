<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/solution/ContestSolution.class.php');
require_once(WCF_DIR.'lib/data/message/bbcode/MessageParser.class.php');

/**
 * Represents a viewable contest entry solution.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ViewableContestSolution extends ContestSolution {
	/**
	 * owner object
	 *
	 * @var ContestOwner
	 */
	protected $owner = null;

	/**
	 * Creates a new ViewableContest object.
	 *
	 * @param	integer		$solutionID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($solutionID, $row = null) {
		if ($solutionID !== null) {
			$sql = "SELECT		avatar_table.*, 
						contest_solution.*,
						user_table.username, 
						group_table.groupName
				FROM 		wcf".WCF_N."_contest_solution contest_solution
				LEFT JOIN	wcf".WCF_N."_user user_table
				ON		(user_table.userID = contest_solution.userID)
				LEFT JOIN	wcf".WCF_N."_avatar avatar_table
				ON		(avatar_table.avatarID = user_table.avatarID)
				LEFT JOIN	wcf".WCF_N."_group group_table
				ON		(group_table.groupID = contest_solution.groupID)
				WHERE 		contest_solution.solutionID = ".intval($solutionID)."
				AND		".ContestSolution::getStateConditions();
			$row = WCF::getDB()->getFirstRow($sql);
		}
		DatabaseObject::__construct($row);
	}
	
	/**
	 * @see DatabaseObject::handleData()
	 */
	protected function handleData($data) {
		parent::handleData($data);
		$this->owner = new ContestOwner($data, $this->userID, $this->groupID);
	}
	
	/**
	 * Returns the formatted solution.
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
	 * Returns an excerpt of the message.
	 * 
	 * @return	string
	 */
	public function getExcerpt() {
		$enableSmilies = 1; 
		$enableHtml = 0; 
		$enableBBCodes = 1;
	
		MessageParser::getInstance()->setOutputType('text/plain');
		$message = MessageParser::getInstance()->parse($this->message, $enableSmilies, $enableHtml, $enableBBCodes);
		
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
