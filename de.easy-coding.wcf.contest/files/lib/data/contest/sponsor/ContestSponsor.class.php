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
			$sql = "SELECT		contest_sponsor.*
				FROM 		wcf".WCF_N."_contest_sponsor contest_sponsor
				WHERE 		contest_sponsor.sponsorID = ".$sponsorID;
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
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
		
		return in_array($this->groupID, WCF::getUser()->getGroupIDs());
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
