<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/contest/crew/ContestCrew.class.php');
require_once(WCF_DIR.'lib/data/contest/crew/todo/ViewableContestCrewTodo.class.php');

/**
 * what tasks does the crew still has to do
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestCrewTodoList extends DatabaseObjectList {
	/**
	 * list of todos
	 * 
	 * @var array<ViewableContestCrewTodo>
	 */
	public $todos = array();

	/**
	 * sql order by statement
	 *
	 * @var	string
	 */
	public $sqlOrderBy = 'contest.contestID';
	
	/**
	 * @see DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		if(ContestCrew::isMember() == false) {
			return 0;
		}

		$sql = "SELECT		COUNT(contestID) AS count
			FROM		wcf".WCF_N."_contest contest
			WHERE		state = 'applied'
			".(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '');
		$row = WCF::getDB()->getFirstRow($sql);
		return max($row['count'], 1);
	}
	
	/**
	 * @see DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		if(ContestCrew::isMember() == false) {
			return;
		}
		
		$sql = "SELECT		*,
					'crew.contest.applied' AS action
			FROM		wcf".WCF_N."_contest contest
			WHERE		state = 'applied'
			".(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '')."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->todos[] = new ViewableContestCrewTodo($row);
		}
		
		// say hello to all crew members, even when no contest needs to be accepted
		if(count($this->todos) == 0) {
			$this->todos[] = new ViewableContestCrewTodo(array(
				'action' => 'crew.contest.applied.none'
			));
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
