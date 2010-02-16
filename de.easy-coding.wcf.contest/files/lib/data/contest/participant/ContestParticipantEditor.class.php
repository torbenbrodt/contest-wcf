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
		$sql = "SELECT		*
			FROM		wcf".WCF_N."_contest_participant
			WHERE		contestID = ".intval($contestID)."
			AND		userID = ".intval($userID)."
			AND		groupID = ".intval($groupID);
		$row = WCF::getDB()->getFirstRow($sql);
		
		if($row) {
			$update = false;

			if(($row['state'] == 'invited' && $state == 'applied')
			  || ($row['state'] == 'applied' && $state == 'invited')) {
				$state = 'accepted';
				$update = true;
			} else if ($state != $row['state']){
				$update = true;
			}
			
			$entry = new self($row);
			if($update) {
				$entry->update($contestID, $userID, $groupID, $state);
			}
			return $entry;
		}
	
		$sql = "INSERT INTO	wcf".WCF_N."_contest_participant
					(contestID, userID, groupID, state)
			VALUES		(".intval($contestID).", ".intval($userID).", ".intval($groupID).", '".escapeString($state)."')";
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
		$eventName = ContestEvent::getEventName(__METHOD__.'.'.$state);
		ContestEventEditor::create($contestID, $userID, $groupID, $eventName, array(
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
			WHERE	participantID = ".$this->participantID;
		WCF::getDB()->sendQuery($sql);
		
		// send event
		require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
		require_once(WCF_DIR.'lib/data/contest/event/ContestEventEditor.class.php');
		$eventName = ContestEvent::getEventName(__METHOD__.'.'.$state);
		ContestEventEditor::create($contestID, $userID, $groupID, $eventName, array(
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
			WHERE	contestID = ".$this->contestID;
		WCF::getDB()->sendQuery($sql);
		
		// delete participant
		$sql = "DELETE FROM	wcf".WCF_N."_contest_participant
			WHERE		participantID = ".$this->participantID;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * send event
	 */
	protected static function sendEvent($contestID, $userID, $groupID, $eventName, array $data = array()) {
		require_once(WCF_DIR.'lib/data/contest/event/ContestEventEditor.class.php');
		require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
		$eventName = ContestEvent::getEventName($eventName);
		ContestEventEditor::create($contestID, $userID, $groupID, $eventName, $data);
	}
	
	/**
	 *
	 */
	public static function getStates($current = '', $isUser = false) {
		switch($current) {
			case 'invited':
				if($isUser) {
					$arr = array(
						'accepted',
						'declined'
					);
				} else {
					$arr = array(
						$current
					);
				}
			break;
			case 'accepted':
			case 'declined':
			case 'applied':
				if($isUser) {
					$arr = array(
						$current
					);
				} else {
					$arr = array(
						'accepted',
						'declined'
					);
				}
			break;
			default:
				$arr = array();
			break;
		}
		return count($arr) ? array_combine($arr, $arr) : $arr;
	}
}
?>
