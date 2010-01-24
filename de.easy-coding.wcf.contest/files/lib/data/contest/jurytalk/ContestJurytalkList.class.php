<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/contest/jurytalk/ViewableContestJurytalk.class.php');

/**
 * Represents a list of contest entry jurytalks.
 * 
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestJurytalkList extends DatabaseObjectList {
	/**
	 * list of jurytalks
	 * 
	 * @var array<ViewableContestJurytalk>
	 */
	public $jurytalks = array();

	/**
	 * sql order by statement
	 *
	 * @var	string
	 */
	public $sqlOrderBy = 'time ASC';
	
	/**
	 * @see DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		$sql = "SELECT	COUNT(*) AS count
			FROM	wcf".WCF_N."_contest_jurytalk contest_jurytalk
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '');
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}
	
	/**
	 * @see DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
					avatar.*, user_table.*, contest_jurytalk.*
			FROM		wcf".WCF_N."_contest_jurytalk contest_jurytalk
			LEFT JOIN	wcf".WCF_N."_user user_table
			ON		(user_table.userID = contest_jurytalk.userID)
			LEFT JOIN	wcf".WCF_N."_avatar avatar
			ON		(avatar.avatarID = user_table.avatarID)
			".$this->sqlJoins."
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '')."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->jurytalks[] = new ViewableContestJurytalk(null, $row);
		}
	}
	
	/**
	 * @see DatabaseObjectList::getObjects()
	 */
	public function getObjects() {
		return $this->jurytalks;
	}
}
?>
