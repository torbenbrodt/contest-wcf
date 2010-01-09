<?php
// wcf imports
require_once(WCF_DIR.'lib/data/user/infraction/warning/object/WarningObject.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestEntry.class.php');

/**
 * An implementation of WarningObject to support the usage of an entry as a warning object.
 * 
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestEntryWarningObject extends ContestEntry implements WarningObject {
	/**
	 * @see WarningObject::getTitle()
	 */
	public function getTitle() {
		return $this->data['subject'];
	}
	
	/**
	 * @see WarningObject::getURL()
	 */
	public function getURL() {
		return 'index.php?page=ContestEntry&contestID='.$this->contestID;
	}
}
?>