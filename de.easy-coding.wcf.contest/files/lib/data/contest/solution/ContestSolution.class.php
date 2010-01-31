<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');

/**
 * Represents a contest entry solution.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSolution extends DatabaseObject {
	/**
	 * Creates a new ContestSolution object.
	 *
	 * @param	integer		$solutionID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($solutionID, $row = null) {
		if ($solutionID !== null) {
			$sql = "SELECT	*
				FROM 	wcf".WCF_N."_contest_solution
				WHERE 	solutionID = ".intval($solutionID);
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}
	
	/**
	 * Returns an editor object for this solution.
	 *
	 * @return	ContestSolutionEditor
	 */
	public function getEditor() {
		require_once(WCF_DIR.'lib/data/contest/solution/ContestSolutionEditor.class.php');
		return new ContestSolutionEditor(null, $this->data);
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
