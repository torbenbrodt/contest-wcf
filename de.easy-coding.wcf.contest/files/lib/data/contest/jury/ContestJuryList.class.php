<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/contest/jury/ViewableContestJury.class.php');

/**
 * Represents a list of contest classes.
 * 
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestJuryList extends DatabaseObjectList {
	/**
	 * list of classes
	 * 
	 * @var array<ContestJury>
	 */
	public $classes = array();

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
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '');
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}
	
	/**
	 * @see DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
					contest_jury.*,
					IF(
						contest_jury.groupID > 0, 
						wcf_group.groupName, 
						wcf_user.username
					) AS title
			FROM		wcf".WCF_N."_contest_jury contest_jury
			LEFT JOIN	wcf".WCF_N."_user wcf_user
			ON		(wcf_user.userID = contest_jury.userID)
			LEFT JOIN	wcf".WCF_N."_group wcf_group
			ON		(wcf_group.groupID = contest_jury.groupID)
			".$this->sqlJoins."
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '')."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->classes[] = new ViewableContestJury(null, $row);
		}
	}
	
	/**
	 * @see DatabaseObjectList::getObjects()
	 */
	public function getObjects() {
		return $this->classes;
	}
}
?>
