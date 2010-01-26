<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a contest jury.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
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
	 * Returns a list of all jurys of a contest.
	 * 
	 * @param	integer			$contestID
	 * @param	string			$state
	 * @return	array<ContestJury>
	 */
	public static function getJurys($contestID, $state = null) {
		$jurys = array();
		$sql = "SELECT		*
			FROM 		wcf".WCF_N."_contest_jury
			WHERE		contestID = ".intval($contestID)."
			
			".($state === null ? "" : "state = '".escapeString($state)."'")."
			
			ORDER BY	juryID";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$jurys[$row['juryID']] = new self(null, $row);
		}
		
		return $jurys;
	}
	
	/**
	 * Returns true, if the active user can edit this jury.
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
	 * Returns true, if the active user can delete this jury.
	 * 
	 * @return	boolean
	 */
	public function isDeletable() {
		return $this->isEditable();
	}
}
?>
