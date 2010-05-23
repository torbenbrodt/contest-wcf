<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectListCached.class.php');
require_once(WCF_DIR.'lib/data/contest/event/ViewableContestEvent.class.php');

/**
 * Represents a list of contest events.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestEventList extends DatabaseObjectListCached {

	/**
	 * sql order by statement
	 *
	 * @var	string
	 */
	public $sqlOrderBy = 'contest_event.eventID';
	
	/**
	 * @see DatabaseObjectListCached::countObjects()
	 */
	public function _countObjects() {
		$sql = "SELECT	COUNT(*) AS count
			FROM	wcf".WCF_N."_contest_event contest_event
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '');
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}
	
	/**
	 * @see DatabaseObjectListCached::readObjects()
	 */
	public function _readObjects() {
		$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
					avatar_table.*,
					contest_event.*,
					group_table.groupName, 
					user_table.username
			FROM		wcf".WCF_N."_contest_event contest_event
			LEFT JOIN	wcf".WCF_N."_user user_table
			ON		(user_table.userID = contest_event.userID)
			LEFT JOIN	wcf".WCF_N."_avatar avatar_table
			ON		(avatar_table.avatarID = user_table.avatarID)
			LEFT JOIN	wcf".WCF_N."_group group_table
			ON		(group_table.groupID = contest_event.groupID)
			".$this->sqlJoins."
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '')."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$events[] = new ViewableContestEvent(null, $row);
		}
		return $events;
	}
}
?>
