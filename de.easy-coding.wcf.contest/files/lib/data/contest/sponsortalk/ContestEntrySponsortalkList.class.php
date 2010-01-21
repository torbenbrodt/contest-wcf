<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/contest/sponsortalk/ViewableContestEntrySponsortalk.class.php');

/**
 * Represents a list of contest entry sponsortalks.
 * 
 * @author	Torben Brodt
 * @copyright	2009 TBR Sponsors
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestEntrySponsortalkList extends DatabaseObjectList {
	/**
	 * list of sponsortalks
	 * 
	 * @var array<ViewableContestEntrySponsortalk>
	 */
	public $sponsortalks = array();

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
			FROM	wcf".WCF_N."_contest_sponsortalk contest_sponsortalk
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '');
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}
	
	/**
	 * @see DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
					avatar.*, user_table.*, contest_sponsortalk.*
			FROM		wcf".WCF_N."_contest_sponsortalk contest_sponsortalk
			LEFT JOIN	wcf".WCF_N."_user user_table
			ON		(user_table.userID = contest_sponsortalk.userID)
			LEFT JOIN	wcf".WCF_N."_avatar avatar
			ON		(avatar.avatarID = user_table.avatarID)
			".$this->sqlJoins."
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '')."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->sponsortalks[] = new ViewableContestEntrySponsortalk(null, $row);
		}
	}
	
	/**
	 * @see DatabaseObjectList::getObjects()
	 */
	public function getObjects() {
		return $this->sponsortalks;
	}
}
?>
