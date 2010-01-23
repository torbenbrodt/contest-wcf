<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/contest/participant/ViewableContestParticipant.class.php');

/**
 * Represents a list of contest participants.
 * 
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestParticipantList extends DatabaseObjectList {
	/**
	 * list of participants
	 * 
	 * @var array<ViewableContestParticipant>
	 */
	public $participants = array();

	/**
	 * sql order by statement
	 *
	 * @var	string
	 */
	public $sqlOrderBy = 'contest_participant.participantID';
	
	/**
	 * @see DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		$sql = "SELECT	COUNT(*) AS count
			FROM	wcf".WCF_N."_contest_participant contest_participant
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '');
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}
	
	/**
	 * @see DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
					contest_participant.*, 
					IF(
						contest_participant.groupID > 0, 
						wcf_group.groupName, 
						wcf_user.username
					) AS title
			FROM		wcf".WCF_N."_contest_participant contest_participant
			LEFT JOIN	wcf".WCF_N."_user wcf_user
			ON		(wcf_user.userID = contest_participant.userID)
			LEFT JOIN	wcf".WCF_N."_group wcf_group
			ON		(wcf_group.groupID = contest_participant.groupID)
			".$this->sqlJoins."
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '')."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->participants[] = new ViewableContestParticipant(null, $row);
		}
	}
	
	/**
	 * @see DatabaseObjectList::getObjects()
	 */
	public function getObjects() {
		return $this->participants;
	}
}
?>
