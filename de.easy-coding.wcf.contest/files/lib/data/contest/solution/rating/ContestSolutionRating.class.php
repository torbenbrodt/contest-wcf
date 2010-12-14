<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');

/**
 * Represents a contest entry rating.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSolutionRating extends DatabaseObject {
	/**
	 * Creates a new ContestSolutionRating object.
	 *
	 * @param	integer		$ratingID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($ratingID, $row = null) {
		if ($ratingID !== null) {
			$sql = "SELECT		*
				FROM 		wcf".WCF_N."_contest_solution_rating
				LEFT JOIN	wcf".WCF_N."_contest_jury contest_jury
				ON		(contest_jury.juryID = contest_jury.juryID)
				WHERE 		ratingID = ".intval($ratingID);
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}
	
	/**
	 * Returns an editor object for this rating.
	 *
	 * @return	ContestSolutionRatingEditor
	 */
	public function getEditor() {
		require_once(WCF_DIR.'lib/data/contest/solution/rating/ContestSolutionRatingEditor.class.php');
		return new ContestSolutionRatingEditor(null, $this->data);
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
