<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/event/AbstractContestNotificationObject.class.php');

/**
 * An implementation of NotificationObject to support the usage of an user contest entry jurytalk as a notification object.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.notification
 */
class ContestJurytalkNotificationObject extends AbstractContestNotificationObject {
	protected $className = 'ContestJurytalk';
	protected $primarykey = 'jurytalkID';
		
	/**
	 * @see ContestNotificationInterface::getRecipients()
	 */
	public function getRecipients() {
		$ids = array();
		// tell all jury members, that a new entry exists
		require_once(WCF_DIR.'lib/data/contest/Contest.class.php');
		$contest = Contest::getInstance($this->contestID);
		foreach($contest->getJurys() as $jury) {
			$ids = array_merge($ids, $jury->getOwner()->getUserIDs());
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
		return 'index.php?page=Contest&contestID='.$this->contestID.'&jurytalkID='.$this->jurytalkID.'#jurytalk'.$this->jurytalkID;
	}

	/**
	 * @see ViewableContestJurytalk::getFormattedJurytalk()
	 */
	public function getFormattedMessage($outputType = 'text/html') {
		require_once(WCF_DIR.'lib/data/message/bbcode/SimpleMessageParser.class.php');
		return SimpleMessageParser::getInstance()->parse($this->jurytalk);
	}
}
?>
