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
 * - current user is not owner, jury or participant
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSolution extends DatabaseObject {
	/**
	 * cache for readWinners
	 * key = contestID, val = array<ContestSolution>
	 *
	 * @var array
	 */
	protected static $winners = array();

	/**
	 * Creates a new ContestSolution object.
	 *
	 * @param	integer		$solutionID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($solutionID, $row = null) {
		if ($solutionID !== null) {
			$sql = "SELECT		contest_solution.*,
						contest_participant.userID, 
						contest_participant.groupID
				FROM 		wcf".WCF_N."_contest_solution contest_solution
				LEFT JOIN	wcf".WCF_N."_contest_participant contest_participant
				ON		contest_participant.participantID = contest_solution.participantID

				LEFT JOIN	wcf".WCF_N."_contest_price contest_price
				ON		contest_price.contestID = contest_solution.contestID
				AND		contest_price.solutionID = contest_solution.solutionID

				WHERE 		contest_solution.solutionID = ".intval($solutionID);
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
			$this->message = WCF::getLanguage()->get('wcf.contest.solution.message.hidden');
			$this->attachments = 0;
		}

		$this->subject = WCF::getLanguage()->get('wcf.contest.solution.number', array(
			'$solutionID' => $this->solutionID,
			'$time' => $this->time,
		));
	}

	/**
	 * solution can be viewed by owner, by jury or if the contest is over
	 */
	public function isViewable() {
		if($this->messagePreview || $this->isOwner()) {
			return true;
		}
		$contest = Contest::getInstance($this->contestID);
		if($contest->state == 'closed' || ($contest->state == 'scheduled' && $contest->untilTime < TIME_NOW)) {
			return true;
		}
		return false;
	}

	/**
	 * solution can be rated by any registered user if solution is viewable, 
	 * the contest is not closed and the current user is not the owner
	 */
	public function isRateable() {
		if(WCF::getUser()->userID == 0 || $this->isOwner()) {
			return false;
		}
		$contest = Contest::getInstance($this->contestID);
		
		// guest users can give ratings, even after contest is closed
		if($contest->state == 'closed' && $contest->isJury()) {
			return false;
		}

		return true;
	}
	
	/**
	 * reset list
	 *
	 * @param	integer		$contestID
	 */
	public static function resetWinners($contestID) {
		if(isset(self::$winners[$contestID])) {
			unset(self::$winners[$contestID]);
		}
	}

	/**
	 * fills cache
	 *
	 * @param	integer		$contestID
	 */
	public static function getWinners($contestID) {
		if(isset(self::$winners[$contestID])) {
			return self::$winners[$contestID];
		}

		// get ordered list of winners
		require_once(WCF_DIR.'lib/data/contest/solution/ContestSolutionList.class.php');
		$solutionList = new ContestSolutionList();
		$solutionList->debug = true;
		$solutionList->sqlConditions .= 'contest_solution.contestID = '.intval($contestID);
		$solutionList->sqlLimit = ContestPrice::getMaxPosition($contestID);
		$solutionList->readObjects();

		self::$winners[$contestID] = array();
		foreach($solutionList->getObjects() as $solution) {
			self::$winners[$contestID][] = $solution;
		}
		return self::$winners[$contestID];
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
		return ContestOwner::get($this->userID, $this->groupID)->isCurrentUser();
	}

	/**
	 * Returns true, if the price has been taken
	 * 
	 * @return	boolean
	 */
	public function hasPrice() {
		$x = $this->priceID;
		return !empty($x);
	}

	/**
	 * Returns true, if the active user can edit this entry.
	 * the owner of the entry can only change the contest, if it has not been published yet.
	 * the jury can change the entry if the contest has not finished yet.
	 * 
	 * @return	boolean
	 */
	public function isEditable() {
		return $this->isOwner() && (
			in_array($this->state, array('private', 'applied'))
			|| (
				Contest::getInstance($this->contestID)->isJuryable()
				&& Contest::getInstance($this->contestID)->isJury()
			)
		);
	}

	/**
	 * Returns true, if the active user can delete this entry.
	 * 
	 * @return	boolean
	 */
	public function isDeletable() {
		return $this->isOwner() && in_array($this->state, array('private', 'applied'));
	}

	/**
	 * returns rank of winner list
	 *
	 * @return	integer
	 */
	public function getRank() {
		$i = 1;
		foreach(self::getWinners($this->contestID) as $solution) {
			if($solution->solutionID == $this->solutionID) {
				return $i;
			}
			$i++;
		}
		return 0;
	}

	/**
	 * before contest end - just jury and owner can add comments - after that, everybody can do so
	 * 
	 * @return	boolean
	 */
	public function isCommentable() {
		return true;
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
				contest_participant.groupID > 0,
				contest_participant.groupID IN (".implode(",", $groupIDs)."), 
				contest_participant.userID > 0 AND contest_participant.userID = ".$userID."
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
			contest_solution.state IN ('accepted', 'declined')

			AND (
				SELECT  COUNT(contestID) FROM wcf".WCF_N."_contest contest
				WHERE	contest.contestID = contest_solution.contestID
				AND ( 	contest.state = 'closed'
				  OR (
					contest.state = 'scheduled'
					AND 	contest.untilTime < UNIX_TIMESTAMP(NOW())
				  )
				)
			) > 0
		)";
	}
}
?>
