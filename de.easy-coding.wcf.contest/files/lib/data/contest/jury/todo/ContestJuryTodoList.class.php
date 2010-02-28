<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/contest/jury/todo/ViewableContestJuryTodo.class.php');

/**
 * what tasks does the jury still have to do?
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestJuryTodoList extends DatabaseObjectList {
	/**
	 * list of todos
	 * 
	 * @var array<ContestJury>
	 */
	public $todos = array();

	/**
	 * sql order by statement
	 *
	 * @var	string
	 */
	public $sqlOrderBy = 'contest_jury.juryID';
	
	/**
	 * @see DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		$sql = "SELECT	COUNT(*) AS count
			FROM	wcf".WCF_N."_contest_jury contest_jury

			WHERE (".ContestJury::getStateConditions().")
			".(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '');
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}
	
	/**
	 * @see DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
					'jury.missingRating' AS action,
					contest_jury.userID,
					contest_jury.groupID,
					contest_solution.solutionID,
					score,
					contest_ratingoption.optionID
			FROM		wcf".WCF_N."_contest_jury contest_jury

			INNER JOIN	wcf".WCF_N."_contest_solution contest_solution
			ON		contest_solution.contestID = contest_jury.contestID

			LEFT JOIN	wcf".WCF_N."_contest_ratingoption contest_ratingoption
			ON		contest_ratingoption.optionID > 0

			LEFT JOIN	wcf".WCF_N."_user_to_groups user_to_groups
			ON		contest_jury.groupID = user_to_groups.groupID

			LEFT JOIN	wcf".WCF_N."_contest_solution_rating contest_solution_rating
			ON		contest_solution_rating.solutionID = contest_solution.solutionID
			AND		contest_solution_rating.userID IN (contest_jury.userID, user_to_groups.userID)
			AND		contest_solution_rating.optionID = contest_ratingoption.optionID

			".$this->sqlJoins."

			WHERE		contest_jury.state = 'accepted'
			AND		ISNULL(contest_solution_rating.optionID)
			".(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '')."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->todos[] = new ViewableContestJuryTodo($row);
		}
	}
	
	/**
	 * @see DatabaseObjectList::getObjects()
	 */
	public function getObjects() {
		return $this->todos;
	}
}
?>
