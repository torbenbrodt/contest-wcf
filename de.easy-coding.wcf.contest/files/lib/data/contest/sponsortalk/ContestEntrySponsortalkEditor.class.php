<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/sponsortalk/ContestEntrySponsortalk.class.php');

/**
 * Provides functions to manage entry sponsortalks.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Jurys
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestEntrySponsortalkEditor extends ContestEntrySponsortalk {
	/**
	 * Creates a new entry sponsortalk.
	 *
	 * @param	integer		$contestID
	 * @param	string		$sponsortalk
	 * @param	integer		$userID
	 * @param	string		$username
	 * @param	integer		$time
	 * @return	ContestEntrySponsortalkEditor
	 */
	public static function create($contestID, $message, $userID, $username, $time = TIME_NOW) {
		$sql = "INSERT INTO	wcf".WCF_N."_contest_sponsortalk
					(contestID, userID, username, message, time)
			VALUES		(".intval($contestID).", ".intval($userID).", '".escapeString($username)."', '".escapeString($message)."', ".$time.")";
		WCF::getDB()->sendQuery($sql);
		
		// get id
		$sponsortalkID = WCF::getDB()->getInsertID("wcf".WCF_N."_contest_sponsortalk", 'sponsortalkID');
		
		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	sponsortalks = sponsortalks + 1
			WHERE	contestID = ".$contestID;
		WCF::getDB()->sendQuery($sql);
		
		return new ContestEntrySponsortalkEditor($sponsortalkID);
	}
	
	/**
	 * Updates this entry sponsortalk.
	 *
	 * @param	string		$message
	 */
	public function update($message) {
		$sql = "UPDATE	wcf".WCF_N."_contest_sponsortalk
			SET	message = '".escapeString($message)."'
			WHERE	sponsortalkID = ".$this->sponsortalkID;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Deletes this entry sponsortalk.
	 */
	public function delete() {
		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	sponsortalks = sponsortalks - 1
			WHERE	contestID = ".$this->contestID;
		WCF::getDB()->sendQuery($sql);
		
		// delete sponsortalk
		$sql = "DELETE FROM	wcf".WCF_N."_contest_sponsortalk
			WHERE		sponsortalkID = ".$this->sponsortalkID;
		WCF::getDB()->sendQuery($sql);
	}
}
?>
