<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/contest/owner/todo/ViewableContestOwnerTodo.class.php');

/**
 * what tasks does the contest owner still has to do
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestOwnerTodoList extends DatabaseObjectList {
	/**
	 * list of todos
	 * 
	 * @var array<ViewableContestOwnerTodo>
	 */
	public $todos = array();
	
	/**
	 * @see DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		$sql = "SELECT		COUNT(contestID) AS count
			FROM		wcf".WCF_N."_contest_solution contest
			WHERE		state = 'applied'
			".(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '');
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}
	
	/**
	 * @see DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		$sql_todo = array();
		
		// care for solution which applied
		$sql_todos[] = "SELECT		'owner.solution.applied' AS action
				FROM		wcf".WCF_N."_contest_solution contest
				WHERE		state = 'applied'
				".(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '');
		
		// care for sponsors' prices which were applied
		$sql_todos[] = "SELECT		'owner.price.applied' AS action
				FROM		wcf".WCF_N."_contest_price contest
				WHERE		state = 'applied'
				".(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '');
	
		$sql = "SELECT	*
			FROM (
				".implode(" UNION ", $sql_todos)."
			) contest
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->todos[] = new ViewableContestOwnerTodo($row);
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
