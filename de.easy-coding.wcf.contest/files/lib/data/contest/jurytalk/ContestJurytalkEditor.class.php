<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/jurytalk/ContestJurytalk.class.php');

/**
 * Provides functions to manage entry jurytalks.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestJurytalkEditor extends ContestJurytalk {
	/**
	 * Creates a new entry jurytalk.
	 *
	 * @param	integer		$contestID
	 * @param	string		$message
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
			WHERE	contestID = ".intval($contestID);
		WCF::getDB()->sendQuery($sql);
		
		// sent event
		require_once(WCF_DIR.'lib/data/contest/event/ContestEventEditor.class.php');
		require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
		ContestEventEditor::create($contestID, $userID, $groupID = 0, __CLASS__, array(
			'jurytalkID' => $jurytalkID,
			'owner' => ContestOwner::get($userID, $groupID = 0)->getName()
		));
		
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
			WHERE	jurytalkID = ".intval($this->jurytalkID);
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Deletes this entry jurytalk.
	 */
	public function delete() {
		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	jurytalks = jurytalks - 1
			WHERE	contestID = ".intval($this->contestID);
		WCF::getDB()->sendQuery($sql);
		
		// delete jurytalk
		$sql = "DELETE FROM	wcf".WCF_N."_contest_jurytalk
			WHERE		jurytalkID = ".intval($this->jurytalkID);
		WCF::getDB()->sendQuery($sql);
	}
}
?>
