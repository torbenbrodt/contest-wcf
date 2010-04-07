<?php
// wcf imports
require_once(WCF_DIR.'lib/data/user/notification/event/DefaultNotificationEvent.class.php');

/**
 * 
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestNotificationEvent extends DefaultNotificationEvent {

	/**
	 * @see NotificationEvent::supportsNotificationType()
	 */
	public function supportsNotificationType(NotificationType $notificationType) {
		return true;
	}

	/**
	 * this is body of mail + pm
	 * @see NotificationEvent::getMessage()
	 */
	public function getMessage(NotificationType $notificationType, $additionalVariables = array()) {
		$additionalVariables = array_merge($additionalVariables, $this->getObject()->getData());
                return $this->getLanguageVariable($this->languageCategory.'.'.$this->getEventName().'.'.$notificationType->getName(), $additionalVariables);
        }

	/**
	 * this is subject of mail + pm
	 * @see NotificationEvent::getShortOutput()
	 */
	public function getShortOutput() {
		require_once(WCF_DIR.'lib/data/contest/event/ViewableContestEvent.class.php');
		
		$data = $this->data;
		$data['placeholders'] = $this->getObject()->getData();
		$x = new ViewableContestEvent(null, $data);
		return $x->getFormattedMessage();
	}
}
?>
