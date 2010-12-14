<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/event/ContestEvent.class.php');

/**
 * Provides functions to manage entry events.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestEventEditor extends ContestEvent {

	/**
	 * Creates a new entry event.
	 *
	 * @param	integer		$contestID
	 * @param	integer		$userID
	 * @param	integer		$groupID
	 * @param	string		$eventName
	 * @param	mixed		$placeholders
	 * @param	integer		$time
	 * @return	ContestEventEditor
	 */
	public static function create($contestID, $userID, $groupID, $eventName, array $placeholders = array(), $time = TIME_NOW) {
		$eventName = preg_replace('/^Contest(.*)Editor(.*)$/', '$1$2', $eventName);
		$eventName = empty($eventName) ? 'contest' : StringUtil::toLowerCase($eventName);
		
		$sql = "INSERT INTO	wcf".WCF_N."_contest_event
					(contestID, userID, groupID, eventName, placeholders, time)
			VALUES		(".intval($contestID).", ".intval($userID).", ".intval($groupID).", '".escapeString($eventName)."', 
					'".escapeString(serialize($placeholders))."', ".intval($time).")";
		WCF::getDB()->sendQuery($sql);
		
		// get id
		$eventID = WCF::getDB()->getInsertID("wcf".WCF_N."_contest_event", 'eventID');
		
		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	events = events + 1
			WHERE	contestID = ".intval($contestID);
		WCF::getDB()->sendQuery($sql);
		
		$event = new ContestEventEditor($eventID);
		
		// any event handlers?
		EventHandler::fireAction($event, 'create');
		
		return $event;
	}
	
	/**
	 * Deletes this entry event.
	 */
	public function delete() {
		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	events = events - 1
			WHERE	contestID = ".intval($this->contestID);
		WCF::getDB()->sendQuery($sql);
		
		// delete event
		$sql = "DELETE FROM	wcf".WCF_N."_contest_event
			WHERE		eventID = ".intval($this->eventID);
		WCF::getDB()->sendQuery($sql);
	}
}
?>
