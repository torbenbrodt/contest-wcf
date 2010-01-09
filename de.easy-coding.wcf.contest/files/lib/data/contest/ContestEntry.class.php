<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a contest entry.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestEntry extends DatabaseObject {
	/**
	 * Creates a new ContestEntry object.
	 *
	 * @param	integer		$contestID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($contestID, $row = null) {
		if ($contestID !== null) {
			$sql = "SELECT	*
				FROM 	wcf".WCF_N."_contest
				WHERE 	contestID = ".$contestID;
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}
	
	/**
	 * Returns the title of this entry.
	 * 
	 * @return	string
	 */
	public function __toString() {
		return $this->title;
	}
	
	/**
	 * Returns the tags of this entry.
	 * 
	 * @return	array<Tag>
	 */
	public function getTags($languageIDArray) {
		// include files
		require_once(WCF_DIR.'lib/data/tag/TagEngine.class.php');
		require_once(WCF_DIR.'lib/data/contest/TaggedContestEntry.class.php');
		
		// get tags
		return TagEngine::getInstance()->getTagsByTaggedObject(new TaggedContestEntry(null, array(
			'contestID' => $this->contestID,
			'taggable' => TagEngine::getInstance()->getTaggable('de.easy-coding.wcf.contest.entry')
		)), $languageIDArray);
	}
	
	/**
	 * Gets the classes of this entry.
	 * 
	 * @return	array<ContestClass>
	 */
	public function getClasses() {
		require_once(WCF_DIR.'lib/data/contest/class/ContestClass.class.php');
		$classes = array();
		$sql = "SELECT		contest_class.*
			FROM		wcf".WCF_N."_contest_to_class contest_to_class
			LEFT JOIN	wcf".WCF_N."_contest_class contest_class
			ON		(contest_class.classID = contest_to_class.classID)
			WHERE		contest_to_class.contestID = ".$this->contestID."
			ORDER BY	contest_class.title";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$classes[$row['classID']] = new ContestClass(null, $row);
		}
		
		return $classes;
	}
	
	/**
	 * Gets the participants of this entry.
	 * 
	 * @return	array<ContestParticipant>
	 */
	public function getParticipants() {
		require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipant.class.php');
		$classes = array();
		$sql = "SELECT		contest_participant.*
			FROM		wcf".WCF_N."_contest_participant contest_participant
			LEFT JOIN	wcf".WCF_N."_contest_participant contest_participant
			ON		(contest_participant.participantID = contest_participant.participantID)
			WHERE		contest_participant.contestID = ".$this->contestID."
			ORDER BY	contest_participant.title";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$classes[$row['participantID']] = new ContestParticipant(null, $row);
		}
		
		return $classes;
	}
	
	/**
	 * Gets the jurys of this entry.
	 * 
	 * @return	array<ContestJury>
	 */
	public function getJurys() {
		require_once(WCF_DIR.'lib/data/contest/jury/ContestJury.class.php');
		$classes = array();
		$sql = "SELECT		contest_jury.*
			FROM		wcf".WCF_N."_contest_jury contest_jury
			LEFT JOIN	wcf".WCF_N."_contest_jury contest_jury
			ON		(contest_jury.juryID = contest_jury.juryID)
			WHERE		contest_jury.contestID = ".$this->contestID."
			ORDER BY	contest_jury.title";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$classes[$row['juryID']] = new ContestJury(null, $row);
		}
		
		return $classes;
	}
	
	/**
	 * Gets the prices of this entry.
	 * 
	 * @return	array<ContestPrice>
	 */
	public function getPrices() {
		require_once(WCF_DIR.'lib/data/contest/price/ContestPrice.class.php');
		$classes = array();
		$sql = "SELECT		contest_price.*
			FROM		wcf".WCF_N."_contest_price contest_price
			WHERE		contestID = ".$this->contestID."
			ORDER BY	contest_price.position";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$classes[$row['priceID']] = new ContestPrice(null, $row);
		}
		
		return $classes;
	}
	
	/**
	 * Returns true, if the active user can solution this entry.
	 * 
	 * @return	boolean
	 */
	public function isSolutionable() {
		return WCF::getUser()->getPermission('user.contest.canSolution');
	}
	
	/**
	 * Returns true, if the active user can edit this entry.
	 * 
	 * @return	boolean
	 */
	public function isEditable() {
		if (($this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canUseContest')) || WCF::getUser()->getPermission('mod.contest.canEditEntry')) {
			return true;
		}
		return false;
	}
	
	/**
	 * Returns true, if the active user can delete this entry.
	 * 
	 * @return	boolean
	 */
	public function isDeletable() {
		if (($this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canUseContest')) || WCF::getUser()->getPermission('mod.contest.canDeleteEntry')) {
			return true;
		}
		return false;
	}
	
	/**
	 * Returns the number of quotes of this entry.
	 * 
	 * @return	integer
	 */
	public function isQuoted() {
		require_once(WCF_DIR.'lib/data/message/multiQuote/MultiQuoteManager.class.php');
		return MultiQuoteManager::getQuoteCount($this->contestID, 'contestEntry');
	}
	
	/**
	 * Counts the entrys of a user.
	 * 
	 * @param	integer		$userID
	 * @return	integer
	 */
	public static function countUserEntries($userID = null) {
		if ($userID === null) $userID = WCF::getUser()->userID;
		
		$sql = "SELECT	COUNT(*) AS entries
			FROM	wcf".WCF_N."_contest
			WHERE	userID = ".$userID;
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['entries'];
	}
}
?>
