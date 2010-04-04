<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/sponsortalk/ContestSponsortalk.class.php');

/**
 * Provides functions to manage entry sponsortalks.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSponsortalkEditor extends ContestSponsortalk {
	/**
	 * Creates a new entry sponsortalk.
	 *
	 * @param	integer		$contestID
	 * @param	string		$sponsortalk
	 * @param	integer		$userID
	 * @param	string		$username
	 * @param	integer		$time
	 * @return	ContestSponsortalkEditor
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
		
		// sent event
		require_once(WCF_DIR.'lib/data/contest/event/ContestEventEditor.class.php');
		require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
		ContestEventEditor::create($contestID, $userID, $groupID = 0, __CLASS__, array(
			'sponsortalkID' => $sponsortalkID,
			'owner' => ContestOwner::get($userID, $groupID = 0)->getName()
		));
		
		return new ContestSponsortalkEditor($sponsortalkID);
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
