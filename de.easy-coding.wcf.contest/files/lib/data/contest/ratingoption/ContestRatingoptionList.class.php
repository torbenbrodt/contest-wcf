<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/contest/ratingoption/ContestRatingoption.class.php');

/**
 * Represents a list of contest entry ratings.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestRatingoptionList extends DatabaseObjectList {
	/**
	 * list of ratings
	 * 
	 * @var array<ContestRatingoption>
	 */
	public $ratings = array();

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
			FROM	wcf".WCF_N."_contest_ratingoption contest_ratingoption
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '');
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}
	
	/**
	 * @see DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
					contest_ratingoption.*
			FROM 		wcf".WCF_N."_contest_ratingoption contest_ratingoption
			".$this->sqlJoins."
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '')."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->ratings[] = new ContestRatingoption(null, $row);
		}
	}
	
	/**
	 * @see DatabaseObjectList::getObjects()
	 */
	public function getObjects() {
		return $this->ratings;
	}
}
?>
