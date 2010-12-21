<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/contest/price/ViewableContestPrice.class.php');

/**
 * Represents a list of contest prices.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestPriceList extends DatabaseObjectList {
	/**
	 * list of prices
	 *
	 * @var array<ContestPrice>
	 */
	public $prices = array();

	/**
	 * sql order by statement
	 *
	 * @var	string
	 */
	public $sqlOrderBy = 'contest_price.position';
	
	/**
	 * @see DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		$sql = "SELECT		COUNT(*) AS count
			FROM		wcf".WCF_N."_contest_price contest_price
			INNER JOIN	wcf".WCF_N."_contest_sponsor contest_sponsor
			ON		contest_price.sponsorID = contest_sponsor.sponsorID
			
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
					avatar_table.*,
					contest_price.*,
					user_table.username,
					user_table.disableAvatar, 
					user_table.avatarID, 
					user_table.gravatar,
					group_table.groupName,
					contest_sponsor.userID,
					contest_sponsor.groupID,
					user_table_winner.userID AS winner_userID,
					user_table_winner.username AS winner_username,
					group_table_winner.groupID AS winner_groupID,
					group_table_winner.groupName AS winner_groupName,
					avatar_table_winner.avatarID AS winner_avatarID,
					avatar_table_winner.avatarCategoryID AS winner_avatarCategoryID,
					avatar_table_winner.avatarName AS winner_avatarName,
					avatar_table_winner.avatarExtension AS winner_avatarExtension,
					avatar_table_winner.width AS winner_width,
					avatar_table_winner.height  AS winner_height
			FROM 		wcf".WCF_N."_contest_price contest_price
			LEFT JOIN	wcf".WCF_N."_contest_sponsor contest_sponsor
			ON		(contest_sponsor.sponsorID = contest_price.sponsorID)
			LEFT JOIN	wcf".WCF_N."_user user_table
			ON		(user_table.userID = contest_sponsor.userID)
			LEFT JOIN	wcf".WCF_N."_group group_table
			ON		(group_table.groupID = contest_sponsor.groupID)
			LEFT JOIN	wcf".WCF_N."_avatar avatar_table
			ON		(avatar_table.avatarID = user_table.avatarID)
				
			LEFT JOIN	wcf".WCF_N."_contest_solution contest_solution
			ON		(contest_solution.solutionID = contest_price.solutionID)
			LEFT JOIN	wcf".WCF_N."_contest_participant contest_participant
			ON		(contest_participant.participantID = contest_solution.participantID)
			LEFT JOIN	wcf".WCF_N."_user user_table_winner
			ON		(user_table_winner.userID = contest_participant.userID)
			LEFT JOIN	wcf".WCF_N."_avatar avatar_table_winner
			ON		(avatar_table_winner.avatarID = user_table_winner.avatarID)
			LEFT JOIN	wcf".WCF_N."_group group_table_winner
			ON		(group_table_winner.groupID = contest_participant.groupID)
			".$this->sqlJoins."

			WHERE (".ContestPrice::getStateConditions().")
			".(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '')."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->prices[] = new ViewableContestPrice(null, $row);
		}
	}
	
	/**
	 * @see DatabaseObjectList::getObjects()
	 */
	public function getObjects() {
		return $this->prices;
	}
}
?>
