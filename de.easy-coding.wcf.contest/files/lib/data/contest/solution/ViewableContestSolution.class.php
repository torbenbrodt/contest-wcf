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
	
			$userID = WCF::getUser()->userID;
			$userID = $userID ? $userID : -1;
		
			$sql = "SELECT		avatar_table.*, 
						contest_solution.*,
						contest_participant.userID,
						contest_participant.groupID,
						user_table.username, 
						group_table.groupName,
						score,
						count,
						juryscore,
						jurycount,
						myscore
				FROM 		wcf".WCF_N."_contest_solution contest_solution
				LEFT JOIN	wcf".WCF_N."_contest_participant contest_participant
				ON		(contest_participant.participantID = contest_solution.participantID)
				
				LEFT JOIN (
					-- total score
					SELECT		contest_solution.solutionID,
							AVG(score) AS score,
							COUNT(DISTINCT contest_solution_rating.userID) AS count
					FROM		wcf".WCF_N."_contest_solution contest_solution
					INNER JOIN	wcf".WCF_N."_contest_solution_rating contest_solution_rating
					ON		contest_solution.solutionID = contest_solution_rating.solutionID
					".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '')."
					GROUP BY	contest_solution.solutionID
					HAVING		NOT ISNULL(contest_solution.solutionID)
				) x ON contest_solution.solutionID = x.solutionID
			
				LEFT JOIN (
					-- jury score
					SELECT		contest_solution.solutionID,
							AVG(score) AS juryscore,
							COUNT(DISTINCT contest_solution_rating.userID) AS jurycount
					FROM		wcf".WCF_N."_contest_solution contest_solution
					INNER JOIN	wcf".WCF_N."_contest_solution_rating contest_solution_rating
					ON		contest_solution.solutionID = contest_solution_rating.solutionID
					INNER JOIN	wcf".WCF_N."_contest_jury contest_jury
					ON		contest_jury.userID = contest_solution_rating.userID
					WHERE 		contest_jury.state = 'accepted'
					".(!empty($this->sqlConditions) ? "AND (".$this->sqlConditions.')' : '')."
					GROUP BY	contest_solution.solutionID
					HAVING		NOT ISNULL(contest_solution.solutionID)
				) y ON contest_solution.solutionID = y.solutionID
			
				LEFT JOIN (
					-- my score
					SELECT		contest_solution.solutionID,
							AVG(score) AS myscore
					FROM		wcf".WCF_N."_contest_solution contest_solution
					INNER JOIN	wcf".WCF_N."_contest_solution_rating contest_solution_rating
					ON		contest_solution.solutionID = contest_solution_rating.solutionID
					WHERE 		contest_solution_rating.userID = ".$userID."
					".(!empty($this->sqlConditions) ? "AND (".$this->sqlConditions.')' : '')."
					GROUP BY	contest_solution.solutionID
					HAVING		NOT ISNULL(contest_solution.solutionID)
				) z ON contest_solution.solutionID = z.solutionID
				
				LEFT JOIN	wcf".WCF_N."_user user_table
				ON		(user_table.userID = contest_participant.userID)
				LEFT JOIN	wcf".WCF_N."_avatar avatar_table
				ON		(avatar_table.avatarID = user_table.avatarID)
				LEFT JOIN	wcf".WCF_N."_group group_table
				ON		(group_table.groupID = contest_participant.groupID)
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
	
	/**
	 * Gets the solutions rating result for template output.
	 *
	 * @return	string		solution rating result for template output
	 */
	public function getRatingOutput() {
		$score = $this->score;
		$roundedScore = $score === false ? 0 : round($score, 0);
		
		return '<img src="'.StyleManager::getStyle()->getIconPath('contestRating'.$roundedScore.'.png').'" alt="" />';
	}
	
	/**
	 * Gets the solutions rating result for template output.
	 *
	 * @return	string		solution rating result for template output
	 */
	public function getJuryRatingOutput() {
		$score = $this->juryscore;
		$roundedScore = $score === false ? 0 : round($score, 0);
		
		return '<img src="'.StyleManager::getStyle()->getIconPath('contestRating'.$roundedScore.'.png').'" alt="" />';
	}
}
?>
