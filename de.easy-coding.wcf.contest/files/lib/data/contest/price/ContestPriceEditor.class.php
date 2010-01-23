<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/price/ContestPrice.class.php');

/**
 * Provides functions to manage contest prices.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestPriceEditor extends ContestPrice {
	/**
	 * Creates a new price.
	 *
	 * @param	integer		$contestID
	 * @param	integer		$sponsorID
	 * @param	string		$subject
	 * @param	string		$message
	 * @param	integer		$time
	 * @param	integer		$position
	 * @return	ContestPriceEditor
	 */
	public static function create($contestID, $sponsorID, $subject, $message, $time, $position) {
		$sql = "INSERT INTO	wcf".WCF_N."_contest_price
					(contestID, sponsorID, subject, message, time, position)
			VALUES		(".intval($contestID).", ".intval($sponsorID).", '".escapeString($subject)."', 
					'".escapeString($message)."', ".intval($time).", ".intval($position).")";
		WCF::getDB()->sendQuery($sql);
		
		// get new id
		$priceID = WCF::getDB()->getInsertID("wcf".WCF_N."_contest_price", 'priceID');
		return new ContestPriceEditor($priceID);
	}
	
	/**
	 * Updates this price.
	 *
	 * @param	integer		$contestID
	 * @param	integer		$sponsorID
	 * @param	string		$subject
	 * @param	string		$message
	 * @param	integer		$time
	 * @param	integer		$position
	 */
	public function update($contestID, $sponsorID, $subject, $message, $time, $position) {
		$sql = "UPDATE	wcf".WCF_N."_contest_price
			SET	contestID = ".intval($contestID).",
				sponsorID = ".intval($sponsorID).",
				subject = '".escapeString($subject)."',
				message = '".escapeString($message)."',
				time = ".intval($time).",
				position = ".intval($position)."
			WHERE	priceID = ".$this->priceID;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Deletes this price.
	 */
	public function delete() {
		// delete price
		$sql = "DELETE FROM	wcf".WCF_N."_contest_price
			WHERE		priceID = ".$this->priceID;
		WCF::getDB()->sendQuery($sql);
	}
	
	public static function getStates() {
		return array(
			'unknown',
			'accepted',
			'declined'
		);
	}
}
?>
