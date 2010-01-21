<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a contest entry sponsortalk.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestEntrySponsortalk extends DatabaseObject {
	/**
	 * Creates a new ContestEntrySponsortalk object.
	 *
	 * @param	integer		$sponsortalkID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($sponsortalkID, $row = null) {
		if ($sponsortalkID !== null) {
			$sql = "SELECT	*
				FROM 	wcf".WCF_N."_contest_sponsortalk
				WHERE 	sponsortalkID = ".$sponsortalkID;
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}
	
	/**
	 * Returns true, if the active user can edit this sponsortalk.
	 * 
	 * @return	boolean
	 */
	public function isEditable() {
		if (($this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canEditSponsor')) || ($this->userID && $this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canEditOwnSponsor')) || WCF::getUser()->getPermission('mod.contest.canEditSponsor')) {
			return true;
		}
		return false;
	}
	
	/**
	 * Returns true, if the active user can delete this sponsortalk.
	 * 
	 * @return	boolean
	 */
	public function isDeletable() {
		if (($this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canDeleteSponsor')) || ($this->userID && $this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canDeleteOwnSponsor')) || WCF::getUser()->getPermission('mod.contest.canDeleteSponsor')) {
			return true;
		}
		return false;
	}
	
	/**
	 * Returns an editor object for this sponsortalk.
	 *
	 * @return	ContestEntrySponsortalkEditor
	 */
	public function getEditor() {
		require_once(WCF_DIR.'lib/data/contest/sponsortalk/ContestEntrySponsortalkEditor.class.php');
		return new ContestEntrySponsortalkEditor(null, $this->data);
	}
}
?>
