<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/contest/price/todo/ViewableContestPriceTodo.class.php');

/**
 * what tasks does the price still have to do?
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestPriceTodoList extends DatabaseObjectList {
	/**
	 * list of todos
	 * 
	 * @var array<ContestPrice>
	 */
	public $todos = array();

	/**
	 * sql order by statement
	 *
	 * @var	string
	 */
	public $sqlOrderBy = 'contest_price.priceID';
	
	/**
	 * @see DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		$sql = "SELECT	COUNT(*) AS count
			FROM	wcf".WCF_N."_contest_price contest_price

			WHERE (".ContestPrice::getStateConditions().")
			".(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '');
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}
	
	/**
	 * @see DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
					'price.pick' AS action,
					avatar_table.*, 
					contest_solution.contestID,
					contest_solution.solutionID,
					contest_participant.userID,
					contest_participant.groupID,
					user_table.username, 
					group_table.groupName
			FROM		wcf".WCF_N."_contest_solution contest_solution

			LEFT JOIN	wcf".WCF_N."_contest_participant contest_participant
			ON		contest_solution.participantID = contest_participant.participantID
			
			LEFT JOIN	wcf".WCF_N."_user user_table
			ON		(user_table.userID = contest_participant.userID)
			LEFT JOIN	wcf".WCF_N."_avatar avatar_table
			ON		(avatar_table.avatarID = user_table.avatarID)
			LEFT JOIN	wcf".WCF_N."_group group_table
			ON		(group_table.groupID = contest_participant.groupID)

			LEFT JOIN	wcf".WCF_N."_contest_price contest_price
			ON		contest_solution.solutionID = contest_price.solutionID

			".$this->sqlJoins."

			WHERE		ISNULL(contest_price.solutionID)
			".(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '')."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->todos[] = new ViewableContestPriceTodo($row);
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
