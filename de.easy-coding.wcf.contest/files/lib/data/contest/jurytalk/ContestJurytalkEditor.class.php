<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/jurytalk/ContestJurytalk.class.php');

/**
 * Provides functions to manage entry jurytalks.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Jurys
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestJurytalkEditor extends ContestJurytalk {
	/**
	 * Creates a new entry jurytalk.
	 *
	 * @param	integer		$contestID
	 * @param	string		$jurytalk
	 * @param	integer		$userID
	 * @param	string		$username
	 * @param	integer		$time
	 * @return	ContestJurytalkEditor
	 */
	public static function create($contestID, $message, $userID, $username, $time = TIME_NOW) {
		$sql = "INSERT INTO	wcf".WCF_N."_contest_jurytalk
					(contestID, userID, username, message, time)
			VALUES		(".intval($contestID).", ".intval($userID).", '".escapeString($username)."', '".escapeString($message)."', ".$time.")";
		WCF::getDB()->sendQuery($sql);
		
		// get id
		$jurytalkID = WCF::getDB()->getInsertID("wcf".WCF_N."_contest_jurytalk", 'jurytalkID');
		
		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	jurytalks = jurytalks + 1
			WHERE	contestID = ".$contestID;
		WCF::getDB()->sendQuery($sql);
		
		return new ContestJurytalkEditor($jurytalkID);
	}
	
	/**
	 * Updates this entry jurytalk.
	 *
	 * @param	string		$message
	 */
	public function update($message) {
		$sql = "UPDATE	wcf".WCF_N."_contest_jurytalk
			SET	message = '".escapeString($message)."'
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
