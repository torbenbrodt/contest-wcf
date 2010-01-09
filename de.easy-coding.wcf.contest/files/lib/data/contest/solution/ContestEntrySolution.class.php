<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a contest entry solution.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestEntrySolution extends DatabaseObject {
	/**
	 * Creates a new ContestEntrySolution object.
	 *
	 * @param	integer		$solutionID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($solutionID, $row = null) {
		if ($solutionID !== null) {
			$sql = "SELECT	*
				FROM 	wcf".WCF_N."_contest_solution
				WHERE 	solutionID = ".$solutionID;
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}
	
	/**
	 * Returns true, if the active user can edit this solution.
	 * 
	 * @return	boolean
	 */
	public function isEditable() {
		if (($this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canEditSolution')) || ($this->userID && $this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canEditOwnSolution')) || WCF::getUser()->getPermission('mod.contest.canEditSolution')) {
			return true;
		}
		return false;
	}
	
	/**
	 * Returns true, if the active user can delete this solution.
	 * 
	 * @return	boolean
	 */
	public function isDeletable() {
		if (($this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canDeleteSolution')) || ($this->userID && $this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canDeleteOwnSolution')) || WCF::getUser()->getPermission('mod.contest.canDeleteSolution')) {
			return true;
		}
		return false;
	}
	
	/**
	 * Returns an editor object for this solution.
	 *
	 * @return	ContestEntrySolutionEditor
	 */
	public function getEditor() {
		require_once(WCF_DIR.'lib/data/contest/solution/ContestEntrySolutionEditor.class.php');
		return new ContestEntrySolutionEditor(null, $this->data);
	}
}
?>
