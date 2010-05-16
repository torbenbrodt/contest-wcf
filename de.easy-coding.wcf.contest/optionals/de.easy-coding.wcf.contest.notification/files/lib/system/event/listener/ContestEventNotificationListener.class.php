<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/user/notification/NotificationHandler.class.php');

/**
 * Handles the notification system regarding the user contest entry event.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.notification
 */
class ContestEventNotificationListener implements EventListener {
	const OBJECT_TYPE = 'contestEntry';

	/**
	 *
	 * @param 	string
	 */
	public function getNotificationObject($eventName, array $data = array()) {
		if($eventName == 'contest') {
			$className = 'ContestNotificationObject';
			$classPath = WCF_DIR.'lib/data/contest/'.$className.'.class.php';	
		} else {
			$className = 'Contest'.ucfirst($eventName).'NotificationObject';
			$classPath = WCF_DIR.'lib/data/contest/'.$eventName.'/'.$className.'.class.php';
		}
		
		// include class file
		if (!file_exists($classPath)) {
			throw new SystemException("unable to find class file '".$classPath."'", 11000);
		}
		require_once($classPath);
	
		// create instance
		if (!class_exists($className)) {
			throw new SystemException("unable to find class '".$className."'", 11001);
		}

		return new $className($data);
	}

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (!MODULE_USER_NOTIFICATION) return;

		try {
			$notificationObject = $this->getNotificationObject($eventObj->eventName, $eventObj->placeholders + array(
				'contestID' => $eventObj->contestID,
			));
		} catch(Exception $e) {
			// just fun, errors don't need to be handled
			return;
		}

		switch($eventName) {
			case 'create':
				foreach($notificationObject->getRecipients() as $recipientUserID) {
					// remove current user from recipient list
					if($recipientUserID == WCF::getUser()->userID) {
						continue;
					}
						
					NotificationHandler::fireEvent(
						$eventObj->eventName,
						self::OBJECT_TYPE,
						$notificationObject,
						$recipientUserID
					);
				}
			break;
			case 'delete':
				NotificationHandler::revokeEvent(array($eventObj->eventName), self::OBJECT_TYPE, array($notificationObject));
			break;
			case 'confirm':
				// anybody affected by current confirmation?
                                $objectIDScope = array();
                                foreach($notificationObject->getObjects() as $objectID) {
                                        $objectIDScope[] = $objectID;
                                }
				$recipientUserID = WCF::getUser()->userID;
				NotificationEditor::markConfirmedByObjectVisit(
					$recipientUserID,
					array($eventObj->eventName),
					self::OBJECT_TYPE,
					$objectIDScope
				);
			break;
		}
	}
}
?>
