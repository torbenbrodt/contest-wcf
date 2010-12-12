<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/jury/ContestJury.class.php');

/**
 * Represents a viewable contest entry jury.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ViewableContestJury extends ContestJury {
	/**
	 * owner object
	 *
	 * @var ContestOwner
	 */
	protected $owner = null;

	/**
	 * Creates a new ViewableContest object.
	 *
	 * @param	integer		$juryID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($juryID, $row = null) {
		if ($juryID !== null) {
			$sql = "SELECT		avatar_table.*, 
						contest_jury.*,
						user_table.username, 
						group_table.groupName
				FROM 		wcf".WCF_N."_contest_jury contest_jury
				LEFT JOIN	wcf".WCF_N."_user user_table
				ON		(user_table.userID = contest_jury.userID)
				LEFT JOIN	wcf".WCF_N."_avatar avatar_table
				ON		(avatar_table.avatarID = user_table.avatarID)
				LEFT JOIN	wcf".WCF_N."_group group_table
				ON		(group_table.groupID = contest_jury.groupID)
				WHERE 		contest.juryID = ".intval($juryID)."
				AND		(".ContestJury::getStateConditions().")";
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
