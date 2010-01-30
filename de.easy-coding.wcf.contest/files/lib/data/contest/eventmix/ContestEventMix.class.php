<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a contest entry event.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestEventMix extends DatabaseObject {
	/**
	 * Creates a new ContestEventMix object.
	 *
	 * @param	integer		$eventID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($eventID, $row = null) {
		if ($eventID !== null) {
			$sql = "SELECT	*
				FROM 	wcf".WCF_N."_contest_event
				WHERE 	eventID = ".intval($eventID);
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}
	
	/**
	 * Returns true, if the active user can edit this event.
	 * 
	 * @return	boolean
	 */
	public function isEditable() {
		if (($this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canEditEventMix')) || ($this->userID && $this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canEditOwnEventMix')) || WCF::getUser()->getPermission('mod.contest.canEditEventMix')) {
			return true;
		}
		return false;
	}
	
	/**
	 * Returns true, if the active user can delete this event.
	 * 
	 * @return	boolean
	 */
	public function isDeletable() {
		if (($this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canDeleteEventMix')) || ($this->userID && $this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canDeleteOwnEventMix')) || WCF::getUser()->getPermission('mod.contest.canDeleteEventMix')) {
			return true;
		}
		return false;
	}
	
	/**
	 * Returns an editor object for this event.
	 *
	 * @return	ContestEventMixEditor
	 */
	public function getEditor() {
		require_once(WCF_DIR.'lib/data/contest/event/ContestEventMixEditor.class.php');
		return new ContestEventMixEditor(null, $this->data);
	}
	
	/**
	 * gets event name from static class method
	 *
	 * @param 	string
	 */
	public static function getEventMixName($string) {
		$string = explode('Editor::', $string);
		return substr(strtolower($string[0]).ucfirst($string[1]), 7);
	}
}
?>
