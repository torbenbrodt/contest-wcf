<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a contest sponsor.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
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
	 * Returns the title of this class.
	 * 
	 * @return	string
	 */
	public function __toString() {
		return "".$this->title;
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
	 * Returns true, if the active user can edit this sponsor.
	 * 
	 * @return	boolean
	 */
	public function isEditable() {
		$userID = WCF::getUser()->userID;
		if(empty($userID)) {
			return false;
		}
		
		return $this->userID == $userID || in_array($this->groupID, WCF::getUser()->getGroupIDs());
	}
	
	/**
	 * Returns true, if the active user can delete this sponsor.
	 * 
	 * @return	boolean
	 */
	public function isDeletable() {
		return $this->isEditable();
	}
}
?>
