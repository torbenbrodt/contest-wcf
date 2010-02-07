<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/sponsor/ContestSponsor.class.php');

/**
 * Provides functions to manage contest sponsors.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSponsorEditor extends ContestSponsor {
	/**
	 * Creates a new sponsor.
	 *
	 * @param	integer		$contestID
	 * @param	integer		$userID
	 * @param	integer		$groupID
	 * @param	string		$state
	 * @return	ContestSponsorEditor
	 */
	public static function create($contestID, $userID, $groupID, $state) {
		// check primary keys
		$sql = "SELECT		*
			FROM		wcf".WCF_N."_contest_sponsor
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
	
		$sql = "INSERT INTO	wcf".WCF_N."_contest_sponsor
					(contestID, userID, groupID, state)
			VALUES		(".intval($contestID).", ".intval($userID).", ".intval($groupID).", '".escapeString($state)."')";
		WCF::getDB()->sendQuery($sql);
		
		// get new id
		$sponsorID = WCF::getDB()->getInsertID("wcf".WCF_N."_contest_sponsor", 'sponsorID');

		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	sponsors = sponsors + 1
			WHERE	contestID = ".$contestID;
		WCF::getDB()->sendQuery($sql);
		
		// sent event
		require_once(WCF_DIR.'lib/data/contest/event/ContestEventEditor.class.php');
		require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
		$eventName = ContestEvent::getEventName(__METHOD__);
		ContestEventEditor::create($contestID, $userID, $groupID, $eventName, array(
			'sponsorID' => $sponsorID,
			'owner' => ContestOwner::get($userID, $groupID)->getName()
		));
		
		return new ContestSponsorEditor($sponsorID);
	}
	
	/**
	 * Updates this sponsor.
	 *
	 * @param	integer		$contestID
	 * @param	integer		$userID
	 * @param	integer		$groupID
	 * @param	string		$state
	 */
	public function update($contestID, $userID, $groupID, $state) {
		$sql = "UPDATE	wcf".WCF_N."_contest_sponsor
			SET	contestID = ".intval($contestID).", 
				userID = ".intval($userID).", 
				groupID = ".intval($groupID).", 
				state = '".escapeString($state)."'
			WHERE	sponsorID = ".$this->sponsorID;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Deletes this sponsor.
	 */
	public function delete() {
		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	sponsors = sponsors - 1
			WHERE	contestID = ".$this->contestID;
		WCF::getDB()->sendQuery($sql);
		
		// delete sponsor
		$sql = "DELETE FROM	wcf".WCF_N."_contest_sponsor
			WHERE		sponsorID = ".$this->sponsorID;
		WCF::getDB()->sendQuery($sql);
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
