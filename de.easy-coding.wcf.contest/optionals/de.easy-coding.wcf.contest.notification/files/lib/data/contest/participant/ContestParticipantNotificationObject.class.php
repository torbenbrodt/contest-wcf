<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/event/AbstractContestNotificationObject.class.php');

/**
 * An implementation of NotificationObject to support the usage of an user contest entry participant as a notification object.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.notification
 */
class ContestParticipantNotificationObject extends AbstractContestNotificationObject {
	protected $className = 'ContestParticipant';
	protected $primarykey = 'participantID';
		
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
			case 'invited':
				$ids = array_merge($ids, $this->getInstance()->getOwner()->getUserIDs());
			break;
			// tell recipient that s.o. did moderator interaction
			case 'accepted':
				$ids = array_merge($ids, $this->getInstance()->getOwner()->getUserIDs());
				
				// maybe the user applied himself, then tell the owners
				require_once(WCF_DIR.'lib/data/contest/Contest.class.php');
				$contest = new Contest($this->contestID);
				if($contest->enableParticipantCheck == false) {
					$ids = array_merge($ids, $contest->getOwner()->getUserIDs());
				}
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
		return 'index.php?page=Contest&contestID='.$this->contestID.'&participantID='.$this->participantID.'#participant'.$this->participantID;
	}

	/**
	 * @see ViewableContestParticipant::getFormattedParticipant()
	 */
	public function getFormattedMessage($outputType = 'text/html') {
		require_once(WCF_DIR.'lib/data/message/bbcode/SimpleMessageParser.class.php');
		return SimpleMessageParser::getInstance()->parse($this->participant);
	}
}
?>
