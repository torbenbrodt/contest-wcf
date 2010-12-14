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
	
		$sql = "INSERT INTO	wcf".WCF_N."_contest_sponsor
					(contestID, userID, groupID, state, time)
			VALUES		(".intval($contestID).", ".intval($userID).", ".intval($groupID).", '".escapeString($state)."', ".TIME_NOW.")";
		WCF::getDB()->sendQuery($sql);
		
		// get new id
		$sponsorID = WCF::getDB()->getInsertID("wcf".WCF_N."_contest_sponsor", 'sponsorID');

		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	sponsors = sponsors + 1
			WHERE	contestID = ".intval($contestID);
		WCF::getDB()->sendQuery($sql);
		
		// sent event
		require_once(WCF_DIR.'lib/data/contest/event/ContestEventEditor.class.php');
		require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
		ContestEventEditor::create($contestID, $userID, $groupID, __CLASS__, array(
			'state' => $state,
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
			WHERE	sponsorID = ".intval($this->sponsorID);
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Deletes this sponsor.
	 */
	public function delete() {
		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	sponsors = sponsors - 1
			WHERE	contestID = ".intval($this->contestID);
		WCF::getDB()->sendQuery($sql);
		
		// delete sponsor
		$sql = "DELETE FROM	wcf".WCF_N."_contest_sponsor
			WHERE		sponsorID = ".intval($this->sponsorID);
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
