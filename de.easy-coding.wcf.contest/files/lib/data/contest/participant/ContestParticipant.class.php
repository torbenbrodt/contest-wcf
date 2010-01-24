<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a contest participant.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestParticipant extends DatabaseObject {
	/**
	 * Creates a new ContestParticipant object.
	 *
	 * @param	integer		$participantID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($participantID, $row = null) {
		if ($participantID !== null) {
			$sql = "SELECT		*, 
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
				WHERE		contest_participant.participantID = ".intval($participantID);
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}
	
	/**
	 * Returns the title of this class.
	 * 
	 * @return	string
	 */
	public function __toString() {
		return "".$this->title;
	}
	
	/**
	 * Returns a list of all participants of a contest.
	 * 
	 * @param	integer			$contestID
	 * @param	string			$state
	 * @return	array<ContestParticipant>
	 */
	public static function getParticipants($contestID, $state = null) {
		$participants = array();
		$sql = "SELECT		*
			FROM 		wcf".WCF_N."_contest_participant
			WHERE		contestID = ".intval($contestID)."
			
			".($state === null ? "" : "state = '".escapeString($state)."'")."
			
			ORDER BY	participantID";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$participants[$row['participantID']] = new self(null, $row);
		}
		
		return $participants;
	}
	
	/**
	 * Returns true, if the active user can edit this participant.
	 * 
	 * @return	boolean
	 */
	public function isEditable() {
		$userID = WCF::getUser()->userID;
		if(empty($userID)) {
			return false;
		}

		return $this->userID == $userID || in_array($this->groupID, WCF::getUser()->getGroupIDs());
	}
	
	/**
	 * Returns true, if the active user can delete this participant.
	 * 
	 * @return	boolean
	 */
	public function isDeletable() {
		return $this->isEditable();
	}
}
?>
