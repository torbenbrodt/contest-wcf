<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/event/AbstractContestNotificationObject.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestNotificationObject.class.php');

/**
 * An implementation of NotificationObject to support the usage of an user contest entry  as a notification object.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.notification
 */
class ContestNotificationObjectType extends AbstractNotificationObjectType {
        /**
         * @see NotificationObjectType::getObjectByID()
         */
        public function getObjectByID($objectID) {

                // get object
                $entry = new ContestNotificationObject($objectID);
                if (!$entry->entryID) return null;

                // return object
                return $entry;
        }

        /**
         * @see NotificationObjectType::getObjectByObject()
         */
        public function getObjectByObject($object) {
                // build object using its data array
                $entry = new ContestNotificationObject(null, $object);
                if (!$entry->entryID) return null;

                // return object
                return $entry;
        }

        /**
         * @see NotificationObjectType::getObjectsByIDArray()
         */
        public function getObjectsByIDArray($objectIDArray) {
                $entries = array();
                $sql = "SELECT		*
			FROM 		wcf".WCF_N."_contest_event
			WHERE 		eventID IN (".implode(',', $objectID).")";
                $result = WCF::getDB()->sendQuery($sql);
                while ($row = WCF::getDB()->fetchArray($result)) {
                        $entries[$row['eventID']] = new ContestNotificationObject(null, $row);
                }

                return $entries;
        }

        /**
         * @see NotificationObjectType::getPackageID()
         */
        public function getPackageID() {
                return WCF::getPackageID('de.easy-coding.wcf.contest');
        }
}
?>
