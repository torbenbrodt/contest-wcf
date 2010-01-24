<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/user/notification/NotificationHandler.class.php');

/**
 * Handles the notification system regarding the user contest entry solution.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.commentNotification
 */
class ContestSolutionNotificationListener implements EventListener {

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (!MODULE_USER_NOTIFICATION) return;
		switch ($className) {
		        // fire events
		        case 'ContestSolutionAddForm':
				if (WCF::getUser()->userID != $eventObj->entry->userID) {
					// unfortunately WoltLab leaves me no other choice here
					$sql = "SELECT	solutionID
						FROM 	wcf".WCF_N."_contest_solution
						WHERE 	userID = ".WCF::getUser()->userID."
						AND	time = ".TIME_NOW."";
					$result = WCF::getDB()->sendQuery($sql);
					while ($row = WCF::getDB()->fetchArray($result)) {
						$this->solutionID = $row['solutionID'];
					}
					NotificationHandler::fireEvent('newContestSolution', 'contestEntry', $this->solutionID, $eventObj->entry->userID);
				}
				break;
       			 // revoke events
			case 'ContestSolutionDeleteAction':
				NotificationHandler::revokeEvent(array('newContestSolution'), 'contestEntry', $eventObj->solution);
				break;
			// confirm notifications
			case 'ContestPage':
				if (WCF::getUser()->userID) {
					// determine users which might be affected by confirmations
					$userIDScope = array($eventObj->entry->userID);
					$objectIDScope = array();
					foreach ($eventObj->solutionList->getObjects() as $solution) {
						$objectIDScope[] = $solution->solutionID;
					}
					if (count($objectIDScope) && in_array(WCF::getUser()->userID, $userIDScope)) {
						$user = new NotificationUser(null, WCF::getUser(), false);
						$objectTypeObject = NotificationHandler::getNotificationObjectTypeObject('contestEntry');
						if (isset($user->notificationFlags[$objectTypeObject->getPackageID()]) 
						    && $user->notificationFlags[$objectTypeObject->getPackageID()] > 0) {
							$count = NotificationEditor::markConfirmedByObjectVisit(
								$user->userID, 
								array('newContestSolution'), 
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
