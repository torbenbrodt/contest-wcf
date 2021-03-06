<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipant.class.php');

/**
 * Represents a viewable contest entry participant.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ViewableContestParticipant extends ContestParticipant {
	/**
	 * owner object
	 *
	 * @var ContestOwner
	 */
	protected $owner = null;

	/**
	 * Creates a new ViewableContest object.
	 *
	 * @param	integer		$participantID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($participantID, $row = null) {
		if ($participantID !== null) {
			$sql = "SELECT		avatar_table.*, 
						contest_participant.*,
						user_table.username, 
						group_table.groupName
				FROM 		wcf".WCF_N."_contest_participant contest_participant
				LEFT JOIN	wcf".WCF_N."_user user_table
				ON		(user_table.userID = contest_participant.userID)
				LEFT JOIN	wcf".WCF_N."_avatar avatar_table
				ON		(avatar_table.avatarID = user_table.avatarID)
				LEFT JOIN	wcf".WCF_N."_group group_table
				ON		(group_table.groupID = contest_participant.groupID)
				WHERE 		contest_participant.participantID = ".intval($participantID)."
				AND		(".ContestParticipant::getStateConditions().")";
			$row = WCF::getDB()->getFirstRow($sql);
		}
		DatabaseObject::__construct($row);
	}
	
	/**
	 * @see DatabaseObject::handleData()
	 */
	protected function handleData($data) {
		parent::handleData($data);
		$this->owner = new ContestOwner($data, $this->userID, $this->groupID);
	}
	
	/**
	 * Returns the owner object.
	 * 
	 * @return	ContestOwner
	 */
	public function getOwner() {
		return $this->owner;
	}
	
	/**
	 * Returns a state object.
	 * 
	 * @return	ContestState
	 */
	public function getState() {
		require_once(WCF_DIR.'lib/data/contest/state/ContestState.class.php');
		return ContestState::get($this->state);
	}
	
	/**
	 * Returns the title of this class.
	 * 
	 * @return	string
	 */
	public function __toString() {
		return "".$this->owner->getName();
	}
}
?>
