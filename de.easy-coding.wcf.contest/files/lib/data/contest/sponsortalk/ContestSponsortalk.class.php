<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');

/**
 * Represents a contest entry sponsortalk.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
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
	 * Returns an editor object for this sponsortalk.
	 *
	 * @return	ContestSponsortalkEditor
	 */
	public function getEditor() {
		require_once(WCF_DIR.'lib/data/contest/sponsortalk/ContestSponsortalkEditor.class.php');
		return new ContestSponsortalkEditor(null, $this->data);
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
