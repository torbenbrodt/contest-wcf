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
	 * is pickable?
	 * TODO: return solutionid by which this price is pickable!!!
	 * currently... 1st price taken, then just the 2nd one is possible...
	 */
	public function isPickable() {
		return true; // TODO : is pickable
		if(WCF::getUser()->userID == 0) {
			return false;
		}
		$contest = new Contest($this->contestID);
		if($contest->state != 'closed') {
			return false;
		}
		
		return true;
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
	 * Returns true, if the active user is winner/participant
	 * 
	 * @return	boolean
	 */
	public function isWinner() {
		return ContestOwner::isOwner($this->winner_userID, $this->winner_groupID);
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
