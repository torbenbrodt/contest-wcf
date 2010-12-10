<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');

/**
 * Represents a contest jury.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestJury extends DatabaseObject {
	/**
	 * Creates a new ContestJury object.
	 *
	 * @param	integer		$juryID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($juryID, $row = null) {
		if ($juryID !== null) {
			$sql = "SELECT		*, 
						IF(
							contest_jury.groupID > 0, 
							wcf_group.groupName, 
							wcf_user.username
						) AS title
				FROM		wcf".WCF_N."_contest_jury contest_jury
				LEFT JOIN	wcf".WCF_N."_user wcf_user
				ON		(wcf_user.userID = contest_jury.userID)
				LEFT JOIN	wcf".WCF_N."_group wcf_group
				ON		(wcf_group.groupID = contest_jury.groupID)
				WHERE		contest_jury.juryID = ".intval($juryID);
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}

	/**
	 * returns owner object
	 * 
	 * @return	ContestOwner
	 */
	public function getOwner() {
		return ContestOwner::get($this->userID, $this->groupID);
	}

	/**
	 * Returns true, if the active user is member
	 * 
	 * @return	boolean
	 */
	public function isOwner() {
		return $this->getOwner()->isCurrentUser();
	}
	
	/**
	 * Returns true, if the active user can edit this entry.
	 * 
	 * @return	boolean
	 */
	public function isEditable() {
		if($this->isOwner()) {
			return true;
		}
		$contest = Contest::getInstance($this->contestID);
		if($contest->isOwner()) {
			return true;
		}
		if($contest->isJury()) {
			return true;
		}
		return false;
	}
	
	/**
	 * Returns true, if the active user can delete this entry.
	 * 
	 * @return	boolean
	 */
	public function isDeletable() {
		$contest = Contest::getInstance($this->contestID);
		if($contest->isOwner()) {
			return true;
		}
		return false;
	}

	/**
	 * thats how the states are implemented
	 *
	 * - invited
	 * - declined
	 * - applied
	 *    contest owner, the rest of the jury and the user/group itself can view entry
	 *
	 * - accepted
	 *    everybody can see the entry
	 */
	public static function getStateConditions() {
		$userID = WCF::getUser()->userID;
		$userID = $userID ? $userID : -1;
		$groupIDs = array_keys(ContestUtil::readAvailableGroups());
		$groupIDs = empty($groupIDs) ? array(-1) : $groupIDs; // makes easier sql queries

		return "(
			-- entry is accepted
			contest_jury.state = 'accepted'
		) OR (
			-- entry owner
			IF(
				contest_jury.groupID > 0,
				contest_jury.groupID IN (".implode(",", $groupIDs)."), 
				contest_jury.userID = ".$userID."
			)
		) OR (
			-- contest owner
			SELECT  COUNT(contestID) 
			FROM 	wcf".WCF_N."_contest contest
			WHERE	contest.contestID = contest_jury.contestID
			AND (	contest.groupID IN (".implode(",", $groupIDs).")
			  OR	contest.userID = ".$userID."
			)
		) > 0
		OR (
			-- in the jury
			SELECT  COUNT(contestID) 
			FROM 	wcf".WCF_N."_contest_jury x
			WHERE	x.contestID = contest_jury.contestID
			AND (	x.groupID IN (".implode(",", $groupIDs).")
			  OR	x.userID = ".$userID."
			)
			AND	x.state = 'accepted'
		) > 0";
	}
}
?>
