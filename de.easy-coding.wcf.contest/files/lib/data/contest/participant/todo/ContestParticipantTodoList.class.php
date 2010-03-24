<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/contest/participant/todo/ViewableContestParticipantTodo.class.php');

/**
 * what tasks does the participant still have to do?
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestParticipantTodoList extends DatabaseObjectList {
	/**
	 * list of todos
	 * 
	 * @var array<ContestParticipant>
	 */
	public $todos = array();

	/**
	 * sql order by statement
	 *
	 * @var	string
	 */
	public $sqlOrderBy = 'contest_participant.participantID';
	
	/**
	 * @see DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		return count($this->readObjects());
	}
	
	/**
	 * @see DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		// get currently active user or group
		$userID = WCF::getUser()->userID;
		$userID = $userID ? $userID : -1;
		$groupIDs = array_keys(ContestUtil::readAvailableGroups());
		$groupIDs = empty($groupIDs) ? array(-1) : $groupIDs; // makes easier sql queries
	
		$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
					'participant.solution.private' AS action,
					contest_participant.participantID
			FROM		wcf".WCF_N."_contest_participant contest_participant

			INNER JOIN	wcf".WCF_N."_contest_solution contest_solution
			ON		contest_solution.contestID = contest_participant.contestID
			".$this->sqlJoins."

			WHERE		IF(
				contest_participant.groupID > 0,
				contest_participant.groupID IN (".implode(",", $groupIDs)."), 
				contest_participant.userID > 0 AND contest_participant.userID = ".$userID."
			)
			AND		contest_solution.state = 'private'
			".(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '')."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->todos[] = new ViewableContestParticipantTodo($row);
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
