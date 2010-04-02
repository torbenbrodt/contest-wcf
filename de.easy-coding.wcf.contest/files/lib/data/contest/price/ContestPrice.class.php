<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');

/**
 * Represents a contest price.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestPrice extends DatabaseObject {
	/**
	 * cache for winner solutions
	 *
	 * @var Array key: contestID, val: [ContestSolution, ContestSolution, ...]
	 */
	protected static $winners = array();
	
	/**
	 * did call readWinners before?
	 *
	 * @var boolean
	 */
	protected static $stateReadWinners = false;

	/**
	 * Creates a new ContestPrice object.
	 *
	 * @param	integer		$priceID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($priceID, $row = null) {
		if ($priceID !== null) {
			$sql = "SELECT		contest_sponsor.userID, 
						contest_sponsor.groupID,
						contest_participant.userID AS winner_userID,
						contest_participant.groupID AS winner_groupID,
						contest_price.*
				FROM 		wcf".WCF_N."_contest_price contest_price
				LEFT JOIN	wcf".WCF_N."_contest_sponsor contest_sponsor
				ON		(contest_sponsor.sponsorID = contest_price.sponsorID)
				
				LEFT JOIN	wcf".WCF_N."_contest_solution contest_solution
				ON		(contest_solution.solutionID = contest_price.solutionID)
				LEFT JOIN	wcf".WCF_N."_contest_participant contest_participant
				ON		(contest_participant.participantID = contest_solution.participantID)
				
				WHERE 		contest_price.priceID = ".intval($priceID);
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}
	
	/**
	 * Returns the formatted description of this price.
	 * 
	 * @return	string
	 */
	public function getFormattedDescription() {
		if ($this->description) {
			return nl2br(StringUtil::encodeHTML($this->description));
		}
		
		return '';
	}
	
	/**
	 * maximum position number
	 *
	 * @return integer	position
	 */
	public static function getMaxPosition($contestID) {
		$sql = "SELECT		MAX(position) AS pos
			FROM		wcf".WCF_N."_contest_price
			WHERE		contestID = ".intval($contestID);
		$row = WCF::getDB()->getFirstRow($sql);
	
		return $row ? $row['pos'] : 0;
	}
	
	/**
	 * fills cache
	 *
	 * @param	integer		$contestID
	 */
	protected static function readWinners($contestID) {
		if(self::$stateReadWinners) {
			return;
		}
		self::$stateReadWinners = true;
	
		// get ordered list of winners
		require_once(WCF_DIR.'lib/data/contest/solution/ContestSolutionList.class.php');
		$solutionList = new ContestSolutionList();
		$solutionList->debug = true;
		$solutionList->sqlConditions .= 'contest_solution.contestID = '.$contestID;
		$solutionList->sqlLimit = self::getMaxPosition($contestID);
		$solutionList->readObjects();

		self::$winners[$contestID] = array();
		foreach($solutionList->getObjects() as $solution) {
			self::$winners[$contestID][] = $solution;
		}
	}
	
	/**
	 * by which solution/winner is this price pickable
	 *
	 * @return 	ContestSolution|null		$solution
	 */
	public function pickableByWinner() {
		if(WCF::getUser()->userID == 0 || $this->hasWinner()) {
			return null;
		}
		$contest = new Contest($this->contestID);
		if($contest->state != 'closed') {
			return null;
		}
		self::readWinners($this->contestID);
		
		foreach(self::$winners[$this->contestID] as $solution) {
			if($solution->hasPrice() == false) {
				return $solution->isOwner() ? $solution : null;
			}
		}
		return null;
	}
	
	/**
	 * is pickable?
	 * currently... 1st price taken, then just the 2nd one is possible...
	 *
	 * @return	boolean
	 */
	public function isPickable() {
		return $this->pickableByWinner() !== null;
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
	 * Returns true, if the active user is winner/participant
	 * 
	 * @return	boolean
	 */
	public function isWinner() {
		return ContestOwner::get($this->winner_userID, $this->winner_groupID)->isCurrentUser();
	}
	
	/**
	 * Returns true, if the price has been taken
	 * 
	 * @return	boolean
	 */
	public function hasWinner() {
		return $this->winner_userID > 0 || $this->winner_groupID > 0;
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
	 */
	public static function getStateConditions() {
		$userID = WCF::getUser()->userID;
		$userID = $userID ? $userID : -1;
		$groupIDs = array_keys(ContestUtil::readAvailableGroups());
		$groupIDs = empty($groupIDs) ? array(-1) : $groupIDs; // makes easier sql queries

		return "(
			-- entry is accepted
			contest_price.state = 'accepted'
		) OR (
			-- entry owner
			IF(
				contest_sponsor.groupID > 0,
				contest_sponsor.groupID IN (".implode(",", $groupIDs)."), 
				contest_sponsor.userID = ".$userID."
			)
		)";
	}
}
?>
