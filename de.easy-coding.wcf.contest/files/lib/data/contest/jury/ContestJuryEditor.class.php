<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/jury/ContestJury.class.php');

/**
 * Provides functions to manage contest jurys.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestJuryEditor extends ContestJury {
	/**
	 * Creates a new jury.
	 *
	 * @param	integer		$contestID
	 * @param	integer		$userID
	 * @param	integer		$groupID
	 * @param	string		$state
	 * @return	ContestJuryEditor
	 */
	public static function create($contestID, $userID, $groupID, $state) {
		$sql = "INSERT INTO	wcf".WCF_N."_contest_jury
					(contestID, userID, groupID, state)
			VALUES		(".intval($contestID).", ".intval($userID).", ".intval($groupID).", '".escapeString($state)."')";
		WCF::getDB()->sendQuery($sql);
		
		// get new id
		$juryID = WCF::getDB()->getInsertID("wcf".WCF_N."_contest_jury", 'juryID');
		return new ContestJuryEditor($juryID);
	}
	
	/**
	 * Updates this jury.
	 *
	 * @param	integer		$contestID
	 * @param	integer		$userID
	 * @param	integer		$groupID
	 * @param	string		$state
	 */
	public function update($contestID, $userID, $groupID, $state) {
		$sql = "UPDATE	wcf".WCF_N."_contest_jury
			SET	contestID = ".intval($contestID).", 
				userID = ".intval($userID).", 
				groupID = ".intval($groupID).", 
				state = '".escapeString($state)."'
			WHERE	juryID = ".$this->juryID;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Deletes this jury.
	 */
	public function delete() {
		// delete jury
		$sql = "DELETE FROM	wcf".WCF_N."_contest_jury
			WHERE		juryID = ".$this->juryID;
		WCF::getDB()->sendQuery($sql);
	}
}
?>
