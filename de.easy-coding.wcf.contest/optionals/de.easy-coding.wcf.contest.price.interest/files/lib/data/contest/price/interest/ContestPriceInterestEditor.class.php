<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/price/interest/ContestPriceInterest.class.php');

/**
 * Provides functions to manage page interests.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.price.interest
 */
class ContestPriceInterestEditor extends ContestPriceInterest {

	/**
	 * Creates a new price interest.
	 *
	 * @param	integer		$priceID
	 * @param	integer		$participantID
	 * @param	integer		$time
	 * @return	ContestPriceInterestEditor
	 */
	public static function create($priceID, $participantID, $time = TIME_NOW) {
		$sql = "INSERT INTO	wcf".WCF_N."_contest_price_interest
					(priceID, participantID, username, comment, time)
			VALUES		(".intval($priceID).", ".intval($participantID).", '".escapeString($username)."', '".escapeString($comment)."', ".$time.")";
		WCF::getDB()->sendQuery($sql);
		
		// get id
		$interestID = WCF::getDB()->getInsertID("wcf".WCF_N."_contest_price_interest", 'interestID');
		
		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest_price
			SET	interests = interests + 1
			WHERE	priceID = ".intval($priceID);
		WCF::getDB()->sendQuery($sql);
		
		return new ContestPriceInterestEditor($interestID);
	}
	
	/**
	 * Deletes this entry comment.
	 */
	public function delete() {
		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest_price
			SET	interests = interests - 1
			WHERE	priceID = ".intval($this->priceID);
		WCF::getDB()->sendQuery($sql);
		
		// delete comment
		$sql = "DELETE FROM	wcf".WCF_N."_contest_price_interest
			WHERE		interestID = ".intval($this->interestID);
		WCF::getDB()->sendQuery($sql);
	}
}
?>
