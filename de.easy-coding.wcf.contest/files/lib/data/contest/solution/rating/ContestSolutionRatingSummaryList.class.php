<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/contest/solution/rating/ViewableContestSolutionRating.class.php');

/**
 * Represents a list of contest entry ratings.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSolutionRatingSummaryList extends DatabaseObjectList {
	/**
	 * list of ratings
	 * 
	 * @var array<ViewableContestSolutionRating>
	 */
	public $ratings = array();

	/**
	 * sql order by statement
	 *
	 * @var	string
	 */
	public $sqlOrderBy = '';
	
	/**
	 * @see DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		$sql = "SELECT	COUNT(*) AS count
			FROM	wcf".WCF_N."_contest_solution_rating contest_solution_rating
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '');
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}
	
	/**
	 * @see DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
	
		$userID = WCF::getUser()->userID;
		$userID = $userID ? $userID : -1;
	
		$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
					contest_solution_rating.optionID,
					title,
					score,
					count,
					juryscore,
					jurycount,
					myscore
			FROM (
				-- total score
				SELECT		a.optionID,
						a.title,
						AVG(score) AS score,
						COUNT(score) AS count
				FROM		wcf".WCF_N."_contest_ratingoption a
				LEFT JOIN	wcf".WCF_N."_contest_solution_rating contest_solution_rating
				ON		a.optionID = contest_solution_rating.optionID
				".(!empty($this->sqlConditions) ? "OR (".$this->sqlConditions.')' : '')."
				GROUP BY	a.optionID
			) contest_solution_rating
			LEFT JOIN (
				-- jury score
				SELECT		optionID,
						AVG(score) AS juryscore,
						COUNT(score) AS jurycount
				FROM		wcf".WCF_N."_contest_solution_rating contest_solution_rating
				INNER JOIN	wcf".WCF_N."_contest_jury contest_jury
				ON		contest_jury.userID = contest_solution_rating.userID
				WHERE 		contest_jury.state = 'accepted'
				".(!empty($this->sqlConditions) ? "AND (".$this->sqlConditions.')' : '')."
				GROUP BY	optionID
				HAVING		NOT ISNULL(optionID)
			) x  ON contest_solution_rating.optionID = x.optionID
			LEFT JOIN (
				-- my score
				SELECT		optionID,
						score AS myscore
				FROM		wcf".WCF_N."_contest_solution_rating contest_solution_rating
				WHERE 		contest_solution_rating.userID = ".$userID."
				".(!empty($this->sqlConditions) ? "AND (".$this->sqlConditions.')' : '')."
				GROUP BY	optionID
				HAVING		NOT ISNULL(optionID)
			) y ON contest_solution_rating.optionID = y.optionID
			
			".$this->sqlJoins."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->ratings[] = new ViewableContestSolutionRating(null, $row);
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
