<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * show interests, show buttons
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.price.interest
 */
class ContestPricePageInterestListener implements EventListener {

	/**
	 * price ids
	 * @var array<integer>
	 */
	protected $myInterests = array();

	/**
	 * first dimension will be price, second dimension will be interest
	 * @var array
	 */
	protected $additionalMessageContents = array();

	/**
	 * key is price id, val is template
	 * @var array<string>additionalMessageContents
	 */
	protected $additionalSmallButtons = array();

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		$this->eventObj = $eventObj;
		$this->className = $className;
		$this->$eventName();
	}

	/**
	 *
	 */
	public function readData() {
		// show interests
		$fetch = false;
		foreach($this->eventObj->priceList->getObjects() as $price) {
			if($price->interests) {
				$fetch = true;
			}
		}

		// show interested users
		if($fetch) {
			$sql = "SELECT		avatar.*,
						user_table.*,
						contest_price_interest.*
				FROM		wcf".WCF_N."_contest_price contest_price
				INNER JOIN	additionalSmallButtonswcf".WCF_N."_contest_price_interest contest_price_interest
				ON		contest_price.priceID = contest_price_interest.priceID
				INNER JOIN	wcf".WCF_N."_contest_participant contest_participant
				ON		contest_participant.participantID = contest_price_interest.participantID
				LEFT JOIN	wcf".WCF_N."_user user_table
				ON		(user_table.userID = contest_participant_interest.userID)
				LEFT JOIN	wcf".WCF_N."_avatar avatar
				ON		(avatar.avatarID = user_table.avatarID)
				WHERE		contest_price.contestID = ".intval($this->eventObj->contestID);
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				$interest = new ContestPriceInterest(null, $row)
				$this->additionalMessageContents[$row['priceID']][] = $interest;
				if($interest->isOwner()) {
					$this->myInterests[] = $row['priceID'];
				}
			}
		}
		
		// only valid users can make interests
		if(WCF::getUser()->userID) {
			foreach($this->eventObj->priceList->getObjects() as $price) {
				$this->additionalSmallButtons[$price->priceID] = WCF::getTPL()->fetch('contetsPriceInterestButton');
			}
		}
	}

	/**
	 *
	 */
	public function assignVariables() {
		WCF::getTPL()->assign(array(
			'contestMyInterests' => $this->myInterests,
			'additionalMessageContents' => $this->additionalMessageContents,
			'additionalSmallButtons' => $this->additionalSmallButtons
		));
	}
}
?>
