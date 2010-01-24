<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a contest entry jurytalk.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Jurys
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
	 * Returns true, if the active user can edit this jurytalk.
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
	 * Returns true, if the active user can delete this jurytalk.
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
	 * Returns an editor object for this jurytalk.
	 *
	 * @return	ContestJurytalkEditor
	 */
	public function getEditor() {
		require_once(WCF_DIR.'lib/data/contest/jurytalk/ContestJurytalkEditor.class.php');
		return new ContestJurytalkEditor(null, $this->data);
	}
}
?>
