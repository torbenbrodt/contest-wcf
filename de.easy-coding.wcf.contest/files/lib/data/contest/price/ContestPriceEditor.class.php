<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/price/ContestPrice.class.php');

/**
 * Provides functions to manage contest prices.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
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
	 * @param	integer		$position
	 * @param	integer		$time
	 * @return	ContestPriceEditor
	 */
	public static function create($contestID, $sponsorID, $subject, $message, $position, $time = TIME_NOW) {
		$sql = "INSERT INTO	wcf".WCF_N."_contest_price
					(contestID, sponsorID, subject, message, time, position)
			VALUES		(".intval($contestID).", ".intval($sponsorID).", '".escapeString($subject)."', 
					'".escapeString($message)."', ".intval($time).", ".intval($position).")";
		WCF::getDB()->sendQuery($sql);
		
		// get new id
		$priceID = WCF::getDB()->getInsertID("wcf".WCF_N."_contest_price", 'priceID');
		
		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	prices = prices + 1
			WHERE	contestID = ".$contestID;
		WCF::getDB()->sendQuery($sql);
		
		// sent event
		require_once(WCF_DIR.'lib/data/contest/event/ContestEventEditor.class.php');
		$eventName = ContestEvent::getEventName(__METHOD__);
		$sponsor = new ContestSponsor($sponsorID);
		ContestEventEditor::create($contestID, $sponsor->userID, $sponsor->groupID, $eventName, array(
			'priceID' => $priceID,
			'owner' => $sponsor->getOwner()->getName()
		));

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
		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	prices = prices - 1
			WHERE	contestID = ".$this->contestID;
		WCF::getDB()->sendQuery($sql);

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
