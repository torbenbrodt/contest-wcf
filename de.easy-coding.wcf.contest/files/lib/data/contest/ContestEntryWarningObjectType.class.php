<?php
// wcf imports
require_once(WCF_DIR.'lib/data/user/infraction/warning/object/WarningObjectType.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestEntryWarningObject.class.php');

/**
 * An implementation of WarningObjectType to support the usage of an entry as a warning object.
 * 
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestEntryWarningObjectType implements WarningObjectType {
	/**
	 * @see WarningObjectType::getObjectByID()
	 */
	public function getObjectByID($objectID) {
		if (is_array($objectID)) {
			$entrys = array();
			$sql = "SELECT		*
				FROM 		wcf".WCF_N."_contest
				WHERE 		contestID IN (".implode(',', $objectID).")";
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				$entrys[$row['contestID']] = new ContestEntryWarningObject(null, $row);
			}
			
			return (count($entrys) > 0 ? $entrys : null); 
		}
		else {
			// get object
			$entry = new ContestEntryWarningObject($objectID);
			if (!$entry->contestID) return null;
			
			// return object
			return $entry;
		}
	}
}
?>