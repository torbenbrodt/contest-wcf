<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');

/**
 * Represents a contest sponsor.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSponsor extends DatabaseObject {
	/**
	 * Creates a new ContestSponsor object.
	 *
	 * @param	integer		$sponsorID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($sponsorID, $row = null) {
		if ($sponsorID !== null) {
			$sql = "SELECT		*, 
						IF(
							contest_sponsor.groupID > 0, 
							wcf_group.groupName, 
							wcf_user.username
						) AS title
				FROM		wcf".WCF_N."_contest_sponsor contest_sponsor
				LEFT JOIN	wcf".WCF_N."_user wcf_user
				ON		(wcf_user.userID = contest_sponsor.userID)
				LEFT JOIN	wcf".WCF_N."_group wcf_group
				ON		(wcf_group.groupID = contest_sponsor.groupID)
				WHERE		contest_sponsor.sponsorID = ".intval($sponsorID);
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}
        
	/**
	 * finds existing sponsor by foreign key combination
	 * 
	 * @param       integer         $contestID
	 * @param       integer         $userID
	 * @param       integer         $groupID
	 * @return      ContestSponsor
	 */
	public static function find($contestID, $userID, $groupID) {
		$sql = "SELECT          *
			FROM            wcf".WCF_N."_contest_sponsor
			WHERE           contestID = ".intval($contestID)."
			AND             userID = ".intval($contestID)."
			AND             groupID = ".intval($contestID);
		$row = WCF::getDB()->getFirstRow($sql);

		if($row) {
			return new self(null, $row);
		} else {
			return null;
		}
	}

	/**
	 * Returns true, if the active user is member
	 * 
	 * @return	boolean
	 */
	public function isOwner() {
		return ContestOwner::isOwner($this->userID, $this->groupID);
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
		$contest = new Contest($this->contestID);
		if($contest->isOwner()) {
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
		$contest = new Contest($this->contestID);
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
	 *    contest owner, the rest of the sponsor and the user/group itself can view entry
	 *
	 * - accepted
	 *    everybody can see the entry
	 */
	public static function getStateConditions() {
		$userID = WCF::getUser()->userID;
		$groupIDs = array_keys(ContestUtil::readAvailableGroups());
		$groupIDs = empty($groupIDs) ? array(-1) : $groupIDs; // makes easier sql queries

		return "(
			-- entry is accepted
			contest_sponsor.state = 'accepted'
		) OR (
			-- entry owner
			IF(
				contest_sponsor.groupID > 0,
				contest_sponsor.groupID IN (".implode(",", $groupIDs)."), 
				contest_sponsor.userID > 0 AND contest_sponsor.userID = ".$userID."
			)
		) OR (
			-- contest owner
			SELECT  COUNT(contestID) 
			FROM 	wcf".WCF_N."_contest contest
			WHERE	contest.contestID = contest.contestID
			AND	contest.groupID IN (".implode(",", $groupIDs).")
			AND	contest.userID > 0 AND contest.userID = ".$userID."
		) > 0
		OR (
			-- in the sponsor
			SELECT  COUNT(contestID) 
			FROM 	wcf".WCF_N."_contest_sponsor contest_sponsor
			WHERE	contest_sponsor.contestID = contest_sponsor.contestID
			AND	contest_sponsor.groupID IN (".implode(",", $groupIDs).")
			AND	contest_sponsor.userID > 0 AND contest_sponsor.userID = ".$userID."
		) > 0";
	}
}
?>
