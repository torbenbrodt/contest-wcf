<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/user/notification/NotificationHandler.class.php');

/**
 * Handles the notification system regarding the user contest entry jurytalk.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.commentNotification
 */
class ContestJurytalkNotificationListener implements EventListener {

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (!MODULE_USER_NOTIFICATION) return;
		switch ($className) {
		        // fire events
		        case 'ContestJurytalkAddForm':
				if (WCF::getUser()->userID != $eventObj->entry->userID) {
					// unfortunately WoltLab leaves me no other choice here
					$sql = "SELECT	jurytalkID
						FROM 	wcf".WCF_N."_contest_jurytalk
						WHERE 	userID = ".WCF::getUser()->userID."
						AND	time = ".TIME_NOW."";
					$result = WCF::getDB()->sendQuery($sql);
					while ($row = WCF::getDB()->fetchArray($result)) {
						$this->jurytalkID = $row['jurytalkID'];
					}
					NotificationHandler::fireEvent('newContestJurytalk', 'contestEntry', $this->jurytalkID, $eventObj->entry->userID);
				}
				break;
       			 // revoke events
			case 'ContestJurytalkDeleteAction':
				NotificationHandler::revokeEvent(array('newContestJurytalk'), 'contestEntry', $eventObj->jurytalk);
				break;
			// confirm notifications
			case 'ContestPage':
				if (WCF::getUser()->userID) {
					// determine users which might be affected by confirmations
					$userIDScope = array($eventObj->entry->userID);
					$objectIDScope = array();
					foreach ($eventObj->jurytalkList->getObjects() as $jurytalk) {
						$objectIDScope[] = $jurytalk->jurytalkID;
					}
					if (count($objectIDScope) && in_array(WCF::getUser()->userID, $userIDScope)) {
						$user = new NotificationUser(null, WCF::getUser(), false);
						$objectTypeObject = NotificationHandler::getNotificationObjectTypeObject('contestEntry');
						if (isset($user->notificationFlags[$objectTypeObject->getPackageID()]) 
						    && $user->notificationFlags[$objectTypeObject->getPackageID()] > 0) {
							$count = NotificationEditor::markConfirmedByObjectVisit(
								$user->userID, 
								array('newContestJurytalk'), 
								'contestEntry', 
								$objectIDScope
							);
							$user->removeOutstandingNotification($objectTypeObject->getPackageID(), $count);
						}
					}
				}
				break;
		}
	}
}
?>
