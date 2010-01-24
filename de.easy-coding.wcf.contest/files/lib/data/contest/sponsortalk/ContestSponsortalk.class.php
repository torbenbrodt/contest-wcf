<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a contest entry sponsortalk.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Jurys
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
	 * Returns true, if the active user can edit this sponsortalk.
	 * 
	 * @return	boolean
	 */
	public function isEditable() {
		if (($this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canEditJury')) || ($this->userID && $this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canEditOwnJury')) || WCF::getUser()->getPermission('mod.contest.canEditJury')) {
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
		if (($this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canDeleteJury')) || ($this->userID && $this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canDeleteOwnJury')) || WCF::getUser()->getPermission('mod.contest.canDeleteJury')) {
			return true;
		}
		return false;
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
}
?>
