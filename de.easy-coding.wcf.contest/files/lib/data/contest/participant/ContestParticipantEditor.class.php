<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipant.class.php');

/**
 * Provides functions to manage contest participants.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
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
		// check primary keys
		$existing = self::find($contestID, $userID, $groupID);
		
		if($existing) {
			$update = false;

			if(($existing->state == 'invited' && $state == 'applied')
			  || ($existing->state == 'applied' && $state == 'invited')) {
				$state = 'accepted';
				$update = true;
			} else if ($state != $existing->state){
				$update = true;
			}
			
			if($update) {
				$existing->update($contestID, $userID, $groupID, $state);
			}
			return $existing;
		}
	
		$sql = "INSERT INTO	wcf".WCF_N."_contest_participant
					(contestID, userID, groupID, state, time)
			VALUES		(".intval($contestID).", ".intval($userID).", ".intval($groupID).", '".escapeString($state)."', ".TIME_NOW.")";
		WCF::getDB()->sendQuery($sql);
		
		// get new id
		$participantID = WCF::getDB()->getInsertID("wcf".WCF_N."_contest_participant", 'participantID');

		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	participants = participants + 1
			WHERE	contestID = ".$contestID;
		WCF::getDB()->sendQuery($sql);
		
		// send event
		require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
		require_once(WCF_DIR.'lib/data/contest/event/ContestEventEditor.class.php');
		ContestEventEditor::create($contestID, $userID, $groupID, __CLASS__, array(
			'state' => $state,
			'participantID' => $participantID,
			'owner' => ContestOwner::get($userID, $groupID)->getName()
		));
		
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
			WHERE	participantID = ".intval($this->participantID);
		WCF::getDB()->sendQuery($sql);
		
		// send event
		require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
		require_once(WCF_DIR.'lib/data/contest/event/ContestEventEditor.class.php');
		ContestEventEditor::create($contestID, $userID, $groupID, __CLASS__, array(
			'state' => $state,
			'participantID' => $this->participantID,
			'owner' => ContestOwner::get($userID, $groupID)->getName()
		));
	}
	
	/**
	 * Deletes this participant.
	 */
	public function delete() {
		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	participants = participants - 1
			WHERE	contestID = ".intval($this->contestID);
		WCF::getDB()->sendQuery($sql);
		
		// delete participant
		$sql = "DELETE FROM	wcf".WCF_N."_contest_participant
			WHERE		participantID = ".intval($this->participantID);
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * 'invited', 'accepted', 'declined', 'applied'
	 */
	public static function getStates($current = '', $flag = 0) {
		require_once(WCF_DIR.'lib/data/contest/state/ContestState.class.php');

		$arr = array($current);
		switch($current) {
			case 'invited':
				if($flag & (ContestState::FLAG_USER | ContestState::FLAG_CREW)) {
					$arr[] = 'accepted';
					$arr[] = 'declined';
				}
			break;
			case 'accepted':
			case 'declined':
			case 'applied':
				if($flag & (ContestState::FLAG_CONTESTOWNER | ContestState::FLAG_CREW)) {
					$arr[] = 'accepted';
					$arr[] = 'declined';
				}
			break;
		}
		return ContestState::translateArray($arr);
	}
}
?>
