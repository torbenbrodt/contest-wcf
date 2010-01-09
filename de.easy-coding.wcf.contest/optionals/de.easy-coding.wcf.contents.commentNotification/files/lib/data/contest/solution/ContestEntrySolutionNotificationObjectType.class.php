<?php
// wcf imports
require_once(WCF_DIR.'lib/data/user/notification/object/AbstractNotificationObjectType.class.php');
require_once(WCF_DIR.'lib/data/contest/solution/ContestEntrySolutionNotificationObject.class.php');

/**
 * An implementation of NotificationObjectType to support the usage of an user contest solutions as a warning object.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.commentNotification
 */
class ContestEntrySolutionNotificationObjectType extends AbstractNotificationObjectType {

	/**
	 * @see NotificationObjectType::getObjectByID()
	 */
	public function getObjectByID($objectID) {
		// get object
		$solution = new ContestEntrySolutionNotificationObject($objectID);
		if (!$solution->solutionID) return null;

		// return object
		return $solution;
	}

	/**
	 * @see NotificationObjectType::getObjectByObject()
	 */
	public function getObjectByObject($object) {
		// build object using its data array
		$solution = new ContestEntrySolutionNotificationObject(null, $object);
		if (!$solution->solutionID) return null;

		// return object
		return $solution;
	}

	/**
	 * @see NotificationObjectType::getObjectsByIDArray()
	 */
	public function getObjectsByIDArray($objectIDArray) {
		$solutions = array();
		$sql = "SELECT		*
			FROM 		wcf".WCF_N."_contest_solution
			WHERE 		solutionID IN (".implode(',', $objectID).")";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$solutions[$row['solutionID']] = new ContestEntrySolutionNotificationObject(null, $row);
		}
		
		// return objects
		return $solutions;
	}

	/**
	 * @see NotificationObjectType::getPackageID()
	 */
	public function getPackageID() {
		return WCF::getPackageID('com.woltlab.wcf.user.contest');
	}
}
?>
