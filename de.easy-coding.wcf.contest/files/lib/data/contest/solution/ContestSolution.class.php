<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');

/**
 * Represents a contest entry solution.
 * 
 * a solution can only be created if the following conditions are true
 * - contest:scheduled AND start_time < x < end_time
 * - current user is not owner, jury or sponsor
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
				FROM 	wcf".WCF_N."_contest_solution contest_solution
				WHERE 	solutionID = ".intval($solutionID);
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}

	/**
	 * @see DatabaseObject::handleData()
	 */
	protected function handleData($data) {
		parent::handleData($data);

		if($this->isViewable() == false) {
			$this->subject = '*hidden*';
			$this->message = '*hidden*';
		}
	}
	
	/**
	 * solution can be viewed by owner, by jury or if the contest is over
	 */
	public function isViewable() {
		return $this->isOwner() || ($this->state == 'scheduled' && $this->untilTime < TIME_NOW);
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

	/**
	 * thats how the states are implemented
	 *
	 * - private
	 *    the solution can be viewn by the owner
	 *
	 * - applied
	 *    jury has to accept or decline answer
	 *
	 * - accepted
	 *    the solution cannot be changed by anybody
	 *    the state can be changed by the jury
	 *
	 * - declined
	 *    the solution cannot be changed by anybody
	 *    the state can be changed by the jury
	 */
	public static function getStateConditions() {
		$userID = WCF::getUser()->userID;
		$userID = $userID ? $userID : -1;
		$groupIDs = array_keys(ContestUtil::readAvailableGroups());
		$groupIDs = empty($groupIDs) ? array(-1) : $groupIDs; // makes easier sql queries

		return "(
			-- owner
			IF(
				contest_solution.groupID > 0,
				contest_solution.groupID IN (".implode(",", $groupIDs)."), 
				contest_solution.userID > 0 AND contest_solution.userID = ".$userID."
			)
		) OR (
			-- solution has been submitted, and user is jury
			contest_solution.state IN ('applied', 'accepted', 'declined')
			
			AND (
				SELECT  COUNT(contestID) FROM wcf".WCF_N."_contest_jury contest_jury
				WHERE	contest_jury.contestID = contest_solution.contestID
				AND (	contest_jury.groupID IN (".implode(",", $groupIDs).")
				  OR	contest_jury.userID = ".$userID."
				)
			) > 0
			
		) OR (
			-- solution has been submitted, and contest is finished
			contest_solution.state IN ('applied', 'accepted', 'declined')
			
			AND (
				SELECT  COUNT(contestID) FROM wcf".WCF_N."_contest contest
				WHERE	contest.contestID = contest_solution.contestID
				AND	contest.state = 'scheduled'
				AND 	contest.untilTime < UNIX_TIMESTAMP(NOW())
			) > 0
		)";
	}
}
?>
