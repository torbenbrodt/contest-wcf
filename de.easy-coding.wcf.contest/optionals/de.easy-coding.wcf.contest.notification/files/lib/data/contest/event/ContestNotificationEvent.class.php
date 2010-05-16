<?php
// wcf imports
require_once(WCF_DIR.'lib/data/user/notification/event/DefaultNotificationEvent.class.php');

/**
 * event supports all notification types, since it provides a fallback solution
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestNotificationEvent extends DefaultNotificationEvent {

	/**
	 * fallback solution language variable name, @see getMessage
	 *
	 * @var string
	 */
	const FALLBACK_NOTIFICATION_TYPE = 'title';

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
		$key = $this->languageCategory.'.'.$this->getEventName().'.'.$notificationType->getName();
		$tmp = $this->getLanguageVariable($key, $additionalVariables);
		if($tmp) {
			return $tmp;
		} else {
			$key = $this->languageCategory.'.'.$this->getEventName().'.'.self::FALLBACK_NOTIFICATION_TYPE;
			return $this->getLanguageVariable($key, $additionalVariables);
		} 
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
