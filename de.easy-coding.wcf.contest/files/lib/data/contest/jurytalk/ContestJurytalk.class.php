<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');

/**
 * Represents a contest entry jurytalk.
 * 
 * a jurytalk can only be changed if the following conditions are true
 * - current user is owner
 *
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestJurytalk extends DatabaseObject {
	/**
	 * Creates a new ContestJurytalk object.
	 *
	 * @param	integer		$jurytalkID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($jurytalkID, $row = null) {
		if ($jurytalkID !== null) {
			$sql = "SELECT	*
				FROM 	wcf".WCF_N."_contest_jurytalk
				WHERE 	jurytalkID = ".intval($jurytalkID);
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}
	
	/**
	 * Returns an editor object for this jurytalk.
	 *
	 * @return	ContestJurytalkEditor
	 */
	public function getEditor() {
		require_once(WCF_DIR.'lib/data/contest/jurytalk/ContestJurytalkEditor.class.php');
		return new ContestJurytalkEditor(null, $this->data);
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
	 *    first jurytalk entry can be viewn
	 *
	 * - accepted
	 *    all jurytalk entries can be viewn
	 */
	public static function getStateConditions() {
		$userID = WCF::getUser()->userID;
		$userID = $userID ? $userID : -1;
		$groupIDs = array_keys(ContestUtil::readAvailableGroups());
		$groupIDs = empty($groupIDs) ? array(-1) : $groupIDs; // makes easier sql queries

		return "(
			-- accepted jury
			SELECT  COUNT(contestID) 
			FROM 	wcf".WCF_N."_contest_jury contest_jury
			WHERE	contest_jury.contestID = contest_jurytalk.contestID
			AND (	contest_jury.groupID IN (".implode(",", $groupIDs).")
			  OR	contest_jury.userID = ".$userID."
			)
			AND	contest_jury.state = 'accepted'
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
				-- invited jury and current entry is first entry
				SELECT  COUNT(contestID) 
				FROM 	wcf".WCF_N."_contest_jury contest_jury
				WHERE	contest_jury.contestID = contest_jurytalk.contestID
				AND (	contest_jury.groupID IN (".implode(",", $groupIDs).")
				  OR	contest_jury.userID = ".$userID."
				)
				AND	contest_jury.state = 'invited'
			) AND (
				-- invited and currenty entry is first entry
				SELECT  MIN(jurytalkID)
				FROM 	wcf".WCF_N."_contest_jurytalk x
				WHERE	x.contestID = contest_jurytalk.contestID
			) = contest_jurytalk.jurytalkID
		)";
	}
}
?>
