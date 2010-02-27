<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/contest/eventmix/ViewableContestEventMix.class.php');

/**
 * Represents a mixture of contest events and contest comments.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestEventMixList extends DatabaseObjectList {
	/**
	 * list of events
	 *
	 * @var array<ContestEventMix>
	 */
	public $events = array();

	/**
	 * sql order by statement
	 *
	 * @var	string
	 */
	public $sqlOrderBy = 'contest_eventmix.time';
	
	/**
	 * excluded events
	 *
	 * @var	string
	 */
	public $excludeEvents = array('commentCreate');
	
	/**
	 * @see DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		$sql = "SELECT	COUNT(contestID) AS count
			FROM (
				SELECT contestID FROM wcf".WCF_N."_contest_event contest_event
					WHERE eventName NOT IN ('".implode("','", $this->excludeEvents)."')
					".(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '')."
				UNION ALL
				
				SELECT contestID FROM wcf".WCF_N."_contest_comment contest_comment
					".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '')."
			) contest_event";
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}
	
	/**
	 * @see DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
					avatar_table.*,
					contest_eventmix.id,
					contest_eventmix.contestID,
					contest_eventmix.className,
					group_table.groupID,
					group_table.groupName,
					user_table.userID,
					IF(ISNULL(contest_eventmix.username), user_table.username, contest_eventmix.username) AS username,
					contest_eventmix.time,
					contest_event.eventName,
					contest_event.placeholders,
					contest_comment.comment
			FROM (
				SELECT 	eventID AS id,
					contestID,
					userID,
					groupID,
					time,
					NULL AS username,
					'ViewableContestEvent' AS className
				FROM wcf".WCF_N."_contest_event contest_event
					WHERE eventName NOT IN ('".implode("','", $this->excludeEvents)."')
					".(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '')."
				UNION
				
				SELECT 	commentID AS id,
					contestID,
					userID,
					0 AS groupID,
					time,
					username,
					'ViewableContestComment' AS className
				FROM wcf".WCF_N."_contest_comment contest_comment
					".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '')."
			) contest_eventmix
			LEFT JOIN	wcf".WCF_N."_contest_event contest_event
			ON		(contest_eventmix.className = 'ViewableContestEvent' AND contest_event.eventID = contest_eventmix.id)			
			LEFT JOIN	wcf".WCF_N."_contest_comment contest_comment
			ON		(contest_eventmix.className = 'ViewableContestComment' AND contest_comment.commentID = contest_eventmix.id)
			
			LEFT JOIN	wcf".WCF_N."_user user_table
			ON		(user_table.userID = contest_eventmix.userID)
			LEFT JOIN	wcf".WCF_N."_avatar avatar_table
			ON		(avatar_table.avatarID = user_table.avatarID)
			LEFT JOIN	wcf".WCF_N."_group group_table
			ON		(group_table.groupID = contest_eventmix.groupID)
			".$this->sqlJoins."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->events[] = new ViewableContestEventMix($row);
		}
	}
	
	/**
	 * @see DatabaseObjectList::getObjects()
	 */
	public function getObjects() {
		return $this->events;
	}
}
?>
