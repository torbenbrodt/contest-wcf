<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/contest/solution/ViewableContestSolution.class.php');

/**
 * Represents a list of contest solutions.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSolutionList extends DatabaseObjectList {
	/**
	 * list of solutions
	 *
	 * @var array<ContestSolution>
	 */
	public $solutions = array();

	/**
	 * sql order by statement
	 *
	 * @var	string
	 */
	public $sqlOrderBy = 'juryscore DESC';
	
	/**
	 * @see DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		$sql = "SELECT		COUNT(*) AS count
			FROM		wcf".WCF_N."_contest_solution contest_solution
			LEFT JOIN	wcf".WCF_N."_contest_participant contest_participant
			ON		contest_participant.participantID = contest_solution.participantID
			WHERE		(".ContestSolution::getStateConditions().")
			".(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '');
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}
	
	/**
	 * @see DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
	
		$userID = WCF::getUser()->userID;
		$userID = $userID ? $userID : -1;
		
		$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
					avatar_table.*,
					contest_participant.*,
					contest_solution.*,
					contest_price.priceID,
					group_table.groupName,
					user_table.username,
					score,
					count,
					juryscore,
					jurycount,
					myscore
			FROM		wcf".WCF_N."_contest_solution contest_solution
			LEFT JOIN	wcf".WCF_N."_contest_participant contest_participant
			ON		contest_participant.participantID = contest_solution.participantID
			
			LEFT JOIN (
				SELECT		priceID,
						solutionID
				FROM		wcf".WCF_N."_contest_price contest_solution
				".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '')."
				
			) contest_price	ON (contest_price.solutionID = contest_solution.solutionID)
			
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
			".$this->sqlJoins."

			WHERE		(".ContestSolution::getStateConditions().")
			".(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '')."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->solutions[] = new ViewableContestSolution(null, $row);
		}
	}
	
	/**
	 * @see DatabaseObjectList::getObjects()
	 */
	public function getObjects() {
		return $this->solutions;
	}
}
?>
