<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/event/AbstractContestNotificationObject.class.php');

/**
 * An implementation of NotificationObject to support the usage of an user contest entry  as a notification object.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.notification
 */
class ContestNotificationObject extends AbstractContestNotificationObject {
	protected $className = 'Contest';
	protected $primarykey = 'contestID';
		
	/**
	 * @see ContestNotificationInterface::getRecipients()
	 */
	public function getRecipients() {
		$ids = array();
		switch($this->state) {
			// tell contest crew that new contest applied
			case 'applied':
				require_once(WCF_DIR.'lib/data/contest/crew/ContestCrew.class.php');
				$ids = array_merge($ids, ContestCrew::getUserIDs());
			break;
			
			// tell contest owner, that moderator did interaction
			case 'accepted':
			case 'declined':
			case 'scheduled':
			case 'closed':
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
		return 'index.php?page=Contest&contestID='.$this->contestID.'&ID='.$this->ID.'#'.$this->ID;
	}

	/**
	 * @see ViewableContest::getFormatted()
	 */
	public function getFormattedMessage($outputType = 'text/html') {
		require_once(WCF_DIR.'lib/data/message/bbcode/SimpleMessageParser.class.php');
		return SimpleMessageParser::getInstance()->parse($this->text);
	}

}
?>
