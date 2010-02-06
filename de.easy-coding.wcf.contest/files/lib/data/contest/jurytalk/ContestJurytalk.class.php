<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');

/**
 * Represents a contest entry jurytalk.
 * 
 * a jurytalk can only be changed if the following conditions are true
 * - current user is owner
 *
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
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
		return ContestOwner::isOwner($this->userID, $this->groupID);
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
}
?>
