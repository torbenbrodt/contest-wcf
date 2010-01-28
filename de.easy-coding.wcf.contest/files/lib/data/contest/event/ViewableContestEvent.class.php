<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/event/ContestEvent.class.php');
require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
require_once(WCF_DIR.'lib/data/message/bbcode/MessageParser.class.php');

/**
 * Represents a viewable contest entry event.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ViewableContestEvent extends ContestEvent {
	/**
	 * owner object
	 *
	 * @var ContestOwner
	 */
	protected $owner = null;

	/**
	 * Creates a new ViewableContest object.
	 *
	 * @param	integer		$contestID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($contestID, $row = null) {
		if ($contestID !== null) {
			$sql = "SELECT		user_table.username, 
						group_table.groupName,
						avatar_table.*, 
						contest_event.*
				FROM 		wcf".WCF_N."_contest_event contest_event
				LEFT JOIN	wcf".WCF_N."_user user_table
				ON		(user_table.userID = contest_event.userID)
				LEFT JOIN	wcf".WCF_N."_avatar avatar_table
				ON		(avatar_table.avatarID = user_table.avatarID)
				LEFT JOIN	wcf".WCF_N."_group group_table
				ON		(group_table.groupID = contest_event.groupID)
				WHERE 		contest.contestID = ".$contestID;
			$row = WCF::getDB()->getFirstRow($sql);
		}
		DatabaseObject::__construct($row);
	}
	
	/**
	 * @see DatabaseObject::handleData()
	 */
	protected function handleData($data) {

		if(isset($data['placeholders'])) {
			$data['placeholders'] = @unserialize($data['placeholders']);
		}
		if(!is_array($data['placeholders'])) {
			$data['placeholders'] = array();
		}

		parent::handleData($data);
		$this->owner = new ContestOwner($data, $this->userID, $this->groupID);
	}
	
	/**
	 * Returns the formatted event.
	 * 
	 * @return	string
	 */
	public function getFormattedMessage() {
		$languageItem = 'wcf.user.contest.event.notification.'.strtolower($this->eventName);
		return WCF::getLanguage()->get($languageItem, $this->placeholders);
	}
	
	/**
	 * Returns the owner object.
	 * 
	 * @return	ContestOwner
	 */
	public function getOwner() {
		return $this->owner;
	}
}
?>
