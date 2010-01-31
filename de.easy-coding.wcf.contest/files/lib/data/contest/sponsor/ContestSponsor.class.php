<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');

/**
 * Represents a contest sponsor.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
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
	 * @param	integer		$contestID
	 * @param	integer		$userID
	 * @param	integer		$groupID
	 * @return	ContestSponsor
	 */
	public static function find($contestID, $userID, $groupID) {
		$sql = "SELECT		*
			FROM		wcf".WCF_N."_contest_sponsor
			WHERE		contestID = ".intval($contestID)."
			AND		userID = ".intval($contestID)."
			AND		groupID = ".intval($contestID);
		$row = WCF::getDB()->getFirstRow($sql);
		
		if($row) {
			return new self(null, $row);
		} else {
			return null;
		}
	}
	
	/**
	 * Returns a list of all sponsors of a contest.
	 * 
	 * @param	integer			$contestID
	 * @param	string			$state
	 * @return	array<ContestSponsor>
	 */
	public static function getSponsors($contestID, $state = null) {
		$sponsors = array();
		$sql = "SELECT		*
			FROM 		wcf".WCF_N."_contest_sponsor
			WHERE		contestID = ".intval($contestID)."
			
			".($state === null ? "" : "state = '".escapeString($state)."'")."
			
			ORDER BY	sponsorID";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$sponsors[$row['sponsorID']] = new self(null, $row);
		}
		
		return $sponsors;
	}

	/**
	 * Returns true, if the active user is member
	 * 
	 * @return	boolean
	 */
	public function isMember() {
		return ContestOwner::isMember($this->userID, $this->groupID);
	}
	
	/**
	 * Returns true, if the active user can edit this entry.
	 * 
	 * @return	boolean
	 */
	public function isEditable() {
		return $this->isMember();
	}
	
	/**
	 * Returns true, if the active user can delete this entry.
	 * 
	 * @return	boolean
	 */
	public function isDeletable() {
		return $this->isMember();
	}
}
?>
