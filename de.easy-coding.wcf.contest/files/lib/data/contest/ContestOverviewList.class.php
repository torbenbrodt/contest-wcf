<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/ViewableContestList.class.php');

/**
 * Represents a list of contest entries.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestOverviewList extends ViewableContestList {
	/**
	 * Creates a new ContestOverviewList object.
	 */
	public function __construct() {
		$this->sqlSelects = 'user_table.*, avatar.*';
		$this->sqlJoins = "LEFT JOIN wcf".WCF_N."_user user_table 
			ON (user_table.userID = contest.userID) 
			LEFT JOIN wcf".WCF_N."_avatar avatar 
			ON (avatar.avatarID = user_table.avatarID)";
	}
}
?>
