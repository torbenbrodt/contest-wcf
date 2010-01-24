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
	 * @return	ContestSolutionEditor
	 */
	public function getEditor() {
		require_once(WCF_DIR.'lib/data/contest/solution/ContestSolutionEditor.class.php');
		return new ContestSolutionEditor(null, $this->data);
	}
}
?>
