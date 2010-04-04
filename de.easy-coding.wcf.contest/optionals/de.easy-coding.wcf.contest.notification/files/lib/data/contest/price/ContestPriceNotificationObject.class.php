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
class ContestPriceNotificationObject extends AbstractContestNotificationObject {
	protected $className = 'ContestPrice';
	protected $primarykey = 'priceID';
		
	/**
	 * @see ContestNotificationInterface::getRecipients()
	 */
	public function getRecipients() {
		$ids = array();
		switch($this->state) {
			// tell contest owner that s.o. did apply
			case 'applied':
				require_once(WCF_DIR.'lib/data/contest/Contest.class.php');
				$contest = new Contest($this->contestID);
				$ids = array_merge($ids, $contest->getOwner()->getUserIDs());
			break;
			
			// tell recipient that s.o. did moderator interaction
			case 'accepted':
			case 'invited':
				$ids = array_merge($ids, $this->getInstance()->getOwner()->getUserIDs());
			break;
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
