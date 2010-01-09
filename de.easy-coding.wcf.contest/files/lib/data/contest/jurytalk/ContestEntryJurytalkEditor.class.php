<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/jurytalk/ContestEntryJury.class.php');

/**
 * Provides functions to manage entry jurytalks.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Jurys
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestEntryJuryEditor extends ContestEntryJury {
	/**
	 * Creates a new entry jurytalk.
	 *
	 * @param	integer		$contestID
	 * @param	string		$jurytalk
	 * @param	integer		$userID
	 * @param	string		$username
	 * @param	integer		$time
	 * @return	ContestEntryJuryEditor
	 */
	public static function create($contestID, $jurytalk, $userID, $username, $time = TIME_NOW) {
		$sql = "INSERT INTO	wcf".WCF_N."_contest_jurytalk
					(contestID, userID, username, jurytalk, time)
			VALUES		(".$contestID.", ".$userID.", '".escapeString($username)."', '".escapeString($jurytalk)."', ".$time.")";
		WCF::getDB()->sendQuery($sql);
		
		// get id
		$jurytalkID = WCF::getDB()->getInsertID("wcf".WCF_N."_contest_jurytalk", 'jurytalkID');
		
		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	jurytalks = jurytalks + 1
			WHERE	contestID = ".$contestID;
		WCF::getDB()->sendQuery($sql);
		
		return new ContestEntryJuryEditor($jurytalkID);
	}
	
	/**
	 * Updates this entry jurytalk.
	 *
	 * @param	string		$jurytalk
	 */
	public function update($jurytalk) {
		$sql = "UPDATE	wcf".WCF_N."_contest_jurytalk
			SET	jurytalk = '".escapeString($jurytalk)."'
			WHERE	jurytalkID = ".$this->jurytalkID;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Deletes this entry jurytalk.
	 */
	public function delete() {
		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	jurytalks = jurytalks - 1
			WHERE	contestID = ".$this->contestID;
		WCF::getDB()->sendQuery($sql);
		
		// delete jurytalk
		$sql = "DELETE FROM	wcf".WCF_N."_contest_jurytalk
			WHERE		jurytalkID = ".$this->jurytalkID;
		WCF::getDB()->sendQuery($sql);
	}
}
?>
