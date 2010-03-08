<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');

/**
 * Represents a contest entry sponsortalk.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSponsortalk extends DatabaseObject {
	/**
	 * Creates a new ContestSponsortalk object.
	 *
	 * @param	integer		$sponsortalkID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($sponsortalkID, $row = null) {
		if ($sponsortalkID !== null) {
			$sql = "SELECT	*
				FROM 	wcf".WCF_N."_contest_sponsortalk
				WHERE 	sponsortalkID = ".intval($sponsortalkID);
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}
	
	/**
	 * Returns an editor object for this sponsortalk.
	 *
	 * @return	ContestSponsortalkEditor
	 */
	public function getEditor() {
		require_once(WCF_DIR.'lib/data/contest/sponsortalk/ContestSponsortalkEditor.class.php');
		return new ContestSponsortalkEditor(null, $this->data);
	}

	/**
	 * Returns true, if the active user is member
	 * 
	 * @return	boolean
	 */
	public function isOwner() {
		return ContestOwner::get($this->userID, $this->groupID)->isCurrentUser();
	}
	
	/**
	 * Returns true, if the active user can edit this entry.
	 * 
	 * @return	boolean
	 */
	public function isEditable() {
		return $this->isOwner();
	}
	
	/**
	 * Returns true, if the active user can delete this entry.
	 * 
	 * @return	boolean
	 */
	public function isDeletable() {
		return $this->isOwner();
	}

	/**
	 * thats how the states are implemented
	 *
	 * - invited
	 *    first sponsortalk entry can be viewn
	 *
	 * - accepted
	 *    all sponsortalk entries can be viewn
	 */
	public static function getStateConditions() {
		$userID = WCF::getUser()->userID;
		$userID = $userID ? $userID : -1;
		$groupIDs = array_keys(ContestUtil::readAvailableGroups());
		$groupIDs = empty($groupIDs) ? array(-1) : $groupIDs; // makes easier sql queries

		return "(
			-- accepted sponsor
			SELECT  COUNT(contestID) 
			FROM 	wcf".WCF_N."_contest_sponsor contest_sponsor
			WHERE	contest_sponsor.contestID = contest_sponsortalk.contestID
			AND (	contest_sponsor.groupID IN (".implode(",", $groupIDs).")
			  OR	contest_sponsor.userID = ".$userID."
			)
			AND	contest_sponsor.state = 'accepted'
		) OR (
			-- contest owner
			SELECT  COUNT(contestID) 
			FROM 	wcf".WCF_N."_contest contest
			WHERE	contest.contestID = contest.contestID
			AND (	contest.groupID IN (".implode(",", $groupIDs).")
			  OR	contest.userID = ".$userID."
			)
		) > 0
		OR (
			(
				-- invited sponsor and current entry is first entry
				SELECT  COUNT(contestID) 
				FROM 	wcf".WCF_N."_contest_sponsor contest_sponsor
				WHERE	contest_sponsor.contestID = contest_sponsortalk.contestID
				AND (	contest_sponsor.groupID IN (".implode(",", $groupIDs).")
				  OR	contest_sponsor.userID = ".$userID."
				)
				AND	contest_sponsor.state = 'invited'
			) AND (
				-- invited and currenty entry is first entry
				SELECT  MIN(sponsortalkID)
				FROM 	wcf".WCF_N."_contest_sponsortalk x
				WHERE	x.contestID = contest_sponsortalk.contestID
			) = contest_sponsortalk.sponsortalkID
		)";
	}
}
?>
