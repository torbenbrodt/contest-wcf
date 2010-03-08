<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');

/**
 * Represents a list of contest entries.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestList extends DatabaseObjectList {
	/**
	 * list of entries
	 * 
	 * @var array<Contest>
	 */
	public $entries = array();

	/**
	 * sql order by statement
	 *
	 * @var	string
	 */
	public $sqlOrderBy = 'contest.time DESC';
	
	/**
	 * @see DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		$sql = "SELECT	COUNT(*) AS count
			FROM	wcf".WCF_N."_contest contest
				".$this->sqlJoins."
			
			WHERE	(".Contest::getStateConditions().")
			".(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '');
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}
	
	/**
	 * @see DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
					contest.*
			FROM		wcf".WCF_N."_contest contest
			".$this->sqlJoins."

			WHERE		(".Contest::getStateConditions().")
			".(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '')."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->entries[] = new Contest(null, $row);
		}
	}
	
	/**
	 * @see DatabaseObjectList::getObjects()
	 */
	public function getObjects() {
		return $this->entries;
	}
}
?>
