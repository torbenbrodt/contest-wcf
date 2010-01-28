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
class ContestEvent extends DatabaseObject {
	/**
	 * Creates a new ContestEvent object.
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
		if (($this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canEditEvent')) || ($this->userID && $this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canEditOwnEvent')) || WCF::getUser()->getPermission('mod.contest.canEditEvent')) {
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
		if (($this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canDeleteEvent')) || ($this->userID && $this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canDeleteOwnEvent')) || WCF::getUser()->getPermission('mod.contest.canDeleteEvent')) {
			return true;
		}
		return false;
	}
	
	/**
	 * Returns an editor object for this event.
	 *
	 * @return	ContestEventEditor
	 */
	public function getEditor() {
		require_once(WCF_DIR.'lib/data/contest/event/ContestEventEditor.class.php');
		return new ContestEventEditor(null, $this->data);
	}
	
	/**
	 * gets event name from static class method
	 *
	 * @param 	string
	 */
	public static function getEventName($string) {
		$string = explode('Editor::', $string);
		return substr(strtolower($string[0]).ucfirst($string[1]), 7);
	}
}
?>
