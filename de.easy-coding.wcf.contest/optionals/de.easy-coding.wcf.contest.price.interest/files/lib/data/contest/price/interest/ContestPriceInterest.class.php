<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');

/**
 * Represents a contest price interest.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.price.interest
 */
class ContestPriceInterest extends DatabaseObject {
	/**
	 * Creates a new ContestPriceInterest object.
	 *
	 * @param	integer		$interestID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($interestxID, $row = null) {
		if ($interestID !== null) {
			$sql = "SELECT	*
				FROM 	wcf".WCF_N."_contest_price_interest
				WHERE 	interestID = ".intval($interestID);
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}
	
	/**
	 * Returns an editor object for this comment.
	 *
	 * @return	ContestPriceInterestEditor
	 */
	public function getEditor() {
		require_once(WCF_DIR.'lib/data/contest/price/interest/ContestPriceInterestEditor.class.php');
		return new ContestPriceInterestEditor(null, $this->data);
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
