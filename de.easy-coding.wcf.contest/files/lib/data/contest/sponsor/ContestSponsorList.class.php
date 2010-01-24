<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/contest/sponsor/ViewableContestSponsor.class.php');

/**
 * Represents a list of contest sponsors.
 * 
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSponsorList extends DatabaseObjectList {
	/**
	 * list of sponsors
	 * 
	 * @var array<ViewableContestSponsor>
	 */
	public $sponsors = array();

	/**
	 * sql order by statement
	 *
	 * @var	string
	 */
	public $sqlOrderBy = 'contest_sponsor.sponsorID';
	
	/**
	 * @see DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		$sql = "SELECT	COUNT(*) AS count
			FROM	wcf".WCF_N."_contest_sponsor contest_sponsor
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '');
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}
	
	/**
	 * @see DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
					contest_sponsor.*, 
					IF(
						contest_sponsor.groupID > 0, 
						wcf_group.groupName, 
						wcf_user.username
					) AS title
			FROM		wcf".WCF_N."_contest_sponsor contest_sponsor
			LEFT JOIN	wcf".WCF_N."_user wcf_user
			ON		(wcf_user.userID = contest_sponsor.userID)
			LEFT JOIN	wcf".WCF_N."_group wcf_group
			ON		(wcf_group.groupID = contest_sponsor.groupID)
			".$this->sqlJoins."
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '')."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->sponsors[] = new ViewableContestSponsor(null, $row);
		}
	}
	
	/**
	 * @see DatabaseObjectList::getObjects()
	 */
	public function getObjects() {
		return $this->sponsors;
	}
}
?>
