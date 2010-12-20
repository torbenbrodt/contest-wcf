<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/event/AbstractContestNotificationObject.class.php');

/**
 * An implementation of NotificationObject to support the usage of an user contest entry price as a notification object.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.notification
 */
class ContestPriceExpireNotificationObject extends AbstractContestNotificationObject {
	protected $className = 'ContestPrice';
	protected $primarykey = 'priceID';

	/**
	 * @see ContestNotificationInterface::getRecipients()
	 */
	public function getRecipients() {
		// TODO: read all contest solutions which do not have price yet, and where the prices did not expire
		
		return;
		
		$ids = array();
		
		require_once(WCF_DIR.'lib/data/contest/solution/ContestSolution.class.php');
		$winners = ContestSolution::getWinners($this->contestID);
		
		foreach($winners as $solution) {
			if($solution->hasPrice() == false) {
				if($solution->isPriceExpired() && $solution->isOwner() == false) {
					continue;
				}

				$ids = array_merge($ids, $this->getInstance()->getOwner()->getUserIDs());
			}
		}
		return array_unique($ids);
	}

	/**
	 * @see NotificationObject::getTitle()
	 */
	public function getTitle() {
	}

	/**
	 * @see NotificationObject::getURL()
	 */
	public function getURL() {
		return 'index.php?page=Contest&contestID='.$this->contestID.'&priceID='.$this->priceID.'#price'.$this->priceID;
	}

	/**
	 * @see ViewableContestPrice::getFormattedPrice()
	 */
	public function getFormattedMessage($outputType = 'text/html') {
		require_once(WCF_DIR.'lib/data/message/bbcode/SimpleMessageParser.class.php');
		return SimpleMessageParser::getInstance()->parse($this->price);
	}
}
?>
