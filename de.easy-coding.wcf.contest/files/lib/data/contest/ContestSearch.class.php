<?php
// wcf imports
require_once(WCF_DIR.'lib/data/message/search/AbstractSearchableMessageType.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestSearchResult.class.php');

/**
 * An implementation of SearchableMessageType for searching in user contests.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSearch extends AbstractSearchableMessageType {
	protected $messageCache = array();
	
	/**
	 * Caches the data of the messages with the given ids.
	 */
	public function cacheMessageData($messageIDs, $additionalData = null) {
		// get entries
		$sql = "SELECT		user_table.*, avatar.*, contest.*
			FROM		wcf".WCF_N."_contest contest
			LEFT JOIN	wcf".WCF_N."_user user_table
			ON		(user_table.userID = contest.userID)
			LEFT JOIN	wcf".WCF_N."_avatar avatar
			ON		(avatar.avatarID = user_table.avatarID)
			WHERE		contest.contestID IN (".$messageIDs.")";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$entry = new ContestSearchResult(null, $row);
			if($entry->isViewable()) {
				$this->messageCache[$row['contestID']] = array('type' => 'contestEntry', 'message' => $entry);
			}
		}
	}
	
	/**
	 * @see SearchableMessageType::getMessageData()
	 */
	public function getMessageData($messageID, $additionalData = null) {
		if (isset($this->messageCache[$messageID])) return $this->messageCache[$messageID];
		return null;
	}
	
	/**
	 * Returns the database table name for this search type.
	 */
	public function getTableName() {
		return 'wcf'.WCF_N.'_contest';
	}
	
	/**
	 * Returns the message id field name for this search type.
	 */
	public function getIDFieldName() {
		return 'contestID';
	}
	
	/**
	 * @see SearchableMessageType::getResultTemplateName()
	 */
	public function getResultTemplateName() {
		return 'searchResultContest';
	}
}
?>
