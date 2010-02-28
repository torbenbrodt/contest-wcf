<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/contest/solution/rating/ViewableSolutionContestRating.class.php');

/**
 * Represents a list of contest entry ratings.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSolutionRatingList extends DatabaseObjectList {
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
	public $sqlOrderBy = 'time ASC';
	
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
		$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
					avatar_table.*,
					contest_solution_rating.*,
					user_table.username,
					user_table.disableAvatar, 
					user_table.avatarID, 
					user_table.gravatar,
					group_table.groupName,
					contest_jury.userID,
					contest_jury.groupID
			FROM 		wcf".WCF_N."_contest_solution_rating contest_solution_rating
			LEFT JOIN	wcf".WCF_N."_contest_jury contest_jury
			ON		(contest_jury.juryID = contest_solution_rating.juryID)
			LEFT JOIN	wcf".WCF_N."_user user_table
			ON		(user_table.userID = contest_jury.userID)
			LEFT JOIN	wcf".WCF_N."_group group_table
			ON		(group_table.groupID = contest_jury.groupID)
			LEFT JOIN	wcf".WCF_N."_avatar avatar_table
			ON		(avatar_table.avatarID = user_table.avatarID)
			".$this->sqlJoins."
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '')."
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
