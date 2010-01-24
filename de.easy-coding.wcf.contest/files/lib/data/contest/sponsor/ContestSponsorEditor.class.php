<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/sponsor/ContestSponsor.class.php');

/**
 * Provides functions to manage contest sponsors.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
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
		$sql = "INSERT INTO	wcf".WCF_N."_contest_sponsor
					(contestID, userID, groupID, state)
			VALUES		(".intval($contestID).", ".intval($userID).", ".intval($groupID).", '".escapeString($state)."')";
		WCF::getDB()->sendQuery($sql);
		
		// get new id
		$sponsorID = WCF::getDB()->getInsertID("wcf".WCF_N."_contest_sponsor", 'sponsorID');
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
		// delete sponsor
		$sql = "DELETE FROM	wcf".WCF_N."_contest_sponsor
			WHERE		sponsorID = ".$this->sponsorID;
		WCF::getDB()->sendQuery($sql);
	}
	
	public static function getStates() {
		return array(
			'unknown',
			'accepted',
			'declined'
		);
	}
}
?>
