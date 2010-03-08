<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/ContestList.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestFeedEntry.class.php');

/**
 * Represents a list of contest entries.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestFeedEntryList extends ContestList {
	/**
	 * @see DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
					user_table.username, contest.*
			FROM		wcf".WCF_N."_contest contest
			LEFT JOIN	wcf".WCF_N."_user user_table
			ON		(user_table.userID = contest.userID)
			".$this->sqlJoins."
			".(!empty($this->sqlConditions) ? "WHERE 1 ".$this->sqlConditions : '')."
			AND ".Contest::getStateConditions()."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->entries[] = new ContestFeedEntry(null, $row);
		}
	}
}
?>
