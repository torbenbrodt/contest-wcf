<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/TaggedContestList.class.php');

/**
 * Represents a list of tagged contest entries.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class TaggedContestOverviewList extends TaggedContestList {
	/**
	 * Creates a new TaggedContestOverviewList object.
	 */
	public function __construct($tagID) {
		$this->sqlSelects = 'user_table.*, avatar.*';
		$this->sqlJoins = "LEFT JOIN wcf".WCF_N."_user user_table ON (user_table.userID = contest.userID) LEFT JOIN wcf".WCF_N."_avatar avatar ON (avatar.avatarID = user_table.avatarID)";
		parent::__construct($tagID);
	}
}
?>