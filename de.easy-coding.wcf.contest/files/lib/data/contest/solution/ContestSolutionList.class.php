<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/contest/solution/ViewableContestSolution.class.php');

/**
 * Represents a list of contest solutions.
 * 
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
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
	public $sqlOrderBy = 'contest_solution.solutionID';
	
	/**
	 * @see DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		$sql = "SELECT	COUNT(*) AS count
			FROM	wcf".WCF_N."_contest_solution contest_solution
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '');
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}
	
	/**
	 * @see DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
					group_table.groupName, 
					user_table.username,
					avatar_table.*,
					contest_solution.*
			FROM		wcf".WCF_N."_contest_solution contest_solution
			LEFT JOIN	wcf".WCF_N."_user user_table
			ON		(user_table.userID = contest_solution.userID)
			LEFT JOIN	wcf".WCF_N."_avatar avatar_table
			ON		(avatar_table.avatarID = user_table.avatarID)
			LEFT JOIN	wcf".WCF_N."_group group_table
			ON		(group_table.groupID = contest_solution.groupID)
			".$this->sqlJoins."
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '')."
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
