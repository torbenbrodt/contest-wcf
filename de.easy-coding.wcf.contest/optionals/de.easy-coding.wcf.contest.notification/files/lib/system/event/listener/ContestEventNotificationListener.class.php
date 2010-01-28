<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/user/notification/NotificationHandler.class.php');

/**
 * Handles the notification system regarding the user contest entry event.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.notification
 */
class ContestEventNotificationListener implements EventListener {

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (!MODULE_USER_NOTIFICATION) return;
		
		$objectType = 'contestEntry';
		$notificationObject = null; // TODO: notification api, read manual
		
		switch($eventName) {
			case 'create':
				$recipientUserID = 0; // TODO: notification api, read manual
				NotificationHandler::fireEvent($eventObj->eventName, $objectType, $notificationObject, $recipientUserID);
			break;
			case 'delete':
				NotificationHandler::revokeEvent(array($eventObj->eventName), $objectType, array($notificationObject));
			break;
			case 'confirm':
				$recipientUserID = 0; // TODO: notification api, read manual
				$objectIDScope = null; // TODO: notification api, read manual
				NotificationEditor::markConfirmedByObjectVisit(
					$recipientUserID, 
					array($eventObj->eventName), 
					$objectType, 
					$objectIDScope
				);
			break;
		}
	}
}
?>
