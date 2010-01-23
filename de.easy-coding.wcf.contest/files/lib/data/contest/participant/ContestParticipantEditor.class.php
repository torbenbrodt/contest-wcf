<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipant.class.php');

/**
 * Provides functions to manage contest participants.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestParticipantEditor extends ContestParticipant {
	/**
	 * Creates a new participant.
	 *
	 * @param	integer		$contestID
	 * @param	integer		$userID
	 * @param	integer		$groupID
	 * @param	string		$state
	 * @return	ContestParticipantEditor
	 */
	public static function create($contestID, $userID, $groupID, $state) {
		$sql = "INSERT INTO	wcf".WCF_N."_contest_participant
					(contestID, userID, groupID, state)
			VALUES		(".intval($contestID).", ".intval($userID).", ".intval($groupID).", '".escapeString($state)."')";
		WCF::getDB()->sendQuery($sql);
		
		// get new id
		$participantID = WCF::getDB()->getInsertID("wcf".WCF_N."_contest_participant", 'participantID');
		return new ContestParticipantEditor($participantID);
	}
	
	/**
	 * Updates this participant.
	 *
	 * @param	integer		$contestID
	 * @param	integer		$userID
	 * @param	integer		$groupID
	 * @param	string		$state
	 */
	public function update($contestID, $userID, $groupID, $state) {
		$sql = "UPDATE	wcf".WCF_N."_contest_participant
			SET	contestID = ".intval($contestID).", 
				userID = ".intval($userID).", 
				groupID = ".intval($groupID).", 
				state = '".escapeString($state)."'
			WHERE	participantID = ".$this->participantID;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Deletes this participant.
	 */
	public function delete() {
		// delete participant
		$sql = "DELETE FROM	wcf".WCF_N."_contest_participant
			WHERE		participantID = ".$this->participantID;
		WCF::getDB()->sendQuery($sql);
	}
	
	public static function getStates() {
		return array(
			'invited',
			'accepted',
			'declined',
			'left'
		);
	}
}
?>
