<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/price/ContestPrice.class.php');

/**
 * Provides functions to manage contest prices.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestPriceEditor extends ContestPrice {
	const STATE_FLAG_WINNER = 8;
	const STATE_FLAG_SPONSOR = 16;
	
	/**
	 * Creates a new price.
	 *
	 * @param	integer		$contestID
	 * @param	integer		$sponsorID
	 * @param	string		$subject
	 * @param	string		$message
	 * @param	integer		$position	if zero, position will automatically be determined
	 * @param	integer		$time
	 * @return	ContestPriceEditor
	 */
	public static function create($contestID, $sponsorID, $subject, $message, $position = 0, $time = TIME_NOW) {
		if($position === 0) {
			// check maximum position
			$position = self::getMaxPosition($contestID) + 1;
		}
		
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
			WHERE	contestID = ".intval($contestID);
		WCF::getDB()->sendQuery($sql);
		
		// sent event
		require_once(WCF_DIR.'lib/data/contest/event/ContestEventEditor.class.php');
		require_once(WCF_DIR.'lib/data/contest/sponsor/ViewableContestSponsor.class.php');
		$sponsor = new ViewableContestSponsor($sponsorID);
		ContestEventEditor::create($contestID, $sponsor->userID, $sponsor->groupID, __CLASS__, array(
			'priceID' => $priceID,
			'owner' => $sponsor->getOwner()->getName()
		));

		return new ContestPriceEditor($priceID);
	}
	
	/**
	 * Updates this price.
	 *
	 * @param	string		$subject
	 * @param	string		$message
	 * @param	string		$state
	 */
	public function update($subject, $message, $state) {
		$sql = "UPDATE	wcf".WCF_N."_contest_price
			SET	subject = '".escapeString($subject)."',
				message = '".escapeString($message)."',
				state = '".escapeString($state)."'
			WHERE	priceID = ".intval($this->priceID);
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Updates this price and set the solution winner.
	 *
	 * @param	integer		$solutionID
	 */
	public function pick($solutionID, $position) {
		$sql = "UPDATE	wcf".WCF_N."_contest_price
			SET	solutionID = ".intval($solutionID).",
				position = ".intval($position)."
			WHERE	priceID = ".intval($this->priceID);
		WCF::getDB()->sendQuery($sql);

		// TODO: pricepick: send event to sponsor
		require_once(WCF_DIR.'lib/data/contest/event/ContestEventEditor.class.php');
		$owner = $this->getOwner();
		ContestEventEditor::create($contestID, $owner->userID, $owner->groupID, 'ContestPricePick', array(
			'priceID' => $this->priceID,
			'owner' => $owner->getName()
		));

		// update price expirations, next winner may only have 24 hours from now on
		require_once(WCF_DIR.'lib/data/contest/ContestEditor.class.php');
		$contest = new ContestEditor();
		$contest->updatePriceExpirations();
	}
	
	/**
	 * updates positions
	 * @param	array		$data
	 */
	public static function updatePositions($data) {
		if(count($data) == 0) {
			return;
		}
		
		$positionData = 1;
		$keys = array();
		foreach($data as $priceID => $position) {
			$positionData = "IF(priceID=".intval($priceID).", ".intval($position).", $positionData)";
			$keys[] = intval($priceID);
		}
		
		$sql = "UPDATE	wcf".WCF_N."_contest_price
			SET	position = $positionData
			WHERE	priceID IN (".implode(",", $keys).")";
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Deletes this price.
	 */
	public function delete() {
		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	prices = prices - 1
			WHERE	contestID = ".intval($this->contestID);
		WCF::getDB()->sendQuery($sql);

		// delete price
		$sql = "DELETE FROM	wcf".WCF_N."_contest_price
			WHERE		priceID = ".intval($this->priceID);
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * current may be 'applied', 'accepted', 'declined', 'sent', 'received'
	 *
	 * flag may be bitmask of ContestState::FLAG_USER, ContestState::FLAG_CONTESTOWNER, 
	 * ContestState::FLAG_CREW, ContestPriceEditor::STATE_FLAG_WINNER, ContestPriceEditor::STATE_FLAG_SPONSOR
	 */
	public static function getStates($current = '', $flag = 0) {
		require_once(WCF_DIR.'lib/data/contest/state/ContestState.class.php');
		
		$arr = array($current);
		switch($current) {
			case 'applied':
			case 'accepted':
			case 'declined':
				if($flag & (ContestState::FLAG_CONTESTOWNER | ContestState::FLAG_CREW)) {
					$arr[] = 'applied';
					$arr[] = 'accepted';
					$arr[] = 'declined';
				}

				if($current == 'accepted' && $flag & (self::STATE_FLAG_SPONSOR | ContestState::FLAG_CREW)) {
					$arr[] = 'sent';
				}
			break;
			case 'sent':
				if($flag & (self::STATE_FLAG_WINNER | ContestState::FLAG_CREW)) {
					$arr[] = 'received';
				}

				if($flag & (self::STATE_FLAG_SPONSOR | ContestState::FLAG_CREW)) {
					$arr[] = 'accepted';
				}
			break;
		}
		return ContestState::translateArray($arr);
	}
}
?>
