<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');

/**
 * Represents a contest entry.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class Contest extends DatabaseObject {
	
	/**
	 * @see getUser
	 */
	protected $user = null;

	/**
	 * Creates a new Contest object.
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
		require_once(WCF_DIR.'lib/data/contest/TaggedContest.class.php');
		
		// get tags
		return TagEngine::getInstance()->getTagsByTaggedObject(new TaggedContest(null, array(
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
		
		$sql = "SELECT		contest_participant.contestID,
					contest_participant.participantID, 
					IF(
						contest_participant.groupID > 0, 
						wcf_group.groupName, 
						wcf_user.username
					) AS title
			FROM		wcf".WCF_N."_contest_participant contest_participant
			LEFT JOIN	wcf".WCF_N."_user wcf_user
			ON		(wcf_user.userID = contest_participant.userID)
			LEFT JOIN	wcf".WCF_N."_group wcf_group
			ON		(wcf_group.groupID = contest_participant.groupID)
			WHERE		contest_participant.contestID IN (".$this->contestID.")
			ORDER BY	title";
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
		
		$sql = "SELECT		contest_jury.contestID,
					contest_jury.juryID, 
					IF(
						contest_jury.groupID > 0, 
						wcf_group.groupName, 
						wcf_user.username
					) AS title
			FROM		wcf".WCF_N."_contest_jury contest_jury
			LEFT JOIN	wcf".WCF_N."_user wcf_user
			ON		(wcf_user.userID = contest_jury.userID)
			LEFT JOIN	wcf".WCF_N."_group wcf_group
			ON		(wcf_group.groupID = contest_jury.groupID)
			WHERE		contest_jury.contestID IN (".$this->contestID.")
			ORDER BY	title";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$classes[$row['juryID']] = new ContestJury(null, $row);
		}
		
		return $classes;
	}
	
	/**
	 * Gets the sponsors of this entry.
	 * 
	 * @return	array<ContestSponsor>
	 */
	public function getSponsors() {
		require_once(WCF_DIR.'lib/data/contest/sponsor/ContestSponsor.class.php');
		$classes = array();
		
		$sql = "SELECT		contest_sponsor.contestID,
					contest_sponsor.sponsorID, 
					IF(
						contest_sponsor.groupID > 0, 
						wcf_group.groupName, 
						wcf_user.username
					) AS title
			FROM		wcf".WCF_N."_contest_sponsor contest_sponsor
			LEFT JOIN	wcf".WCF_N."_user wcf_user
			ON		(wcf_user.userID = contest_sponsor.userID)
			LEFT JOIN	wcf".WCF_N."_group wcf_group
			ON		(wcf_group.groupID = contest_sponsor.groupID)
			WHERE		contest_sponsor.contestID IN (".$this->contestID.")
			ORDER BY	title";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$classes[$row['sponsorID']] = new ContestSponsor(null, $row);
		}
		
		return $classes;
	}
	
	/**
	 * return the creator
	 */
	public function getUser() {
		return $this->user !== null ? $this->user : $this->user = new User($this->userID);
	}
	
	/**
	 * Gets the prices of this entry.
	 * 
	 * @return	array<ContestPrice>
	 * @deprecated
	 */
/*
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
*/	
	/**
	 * Returns true, if the active user can solution this entry.
	 * 
	 * @return	boolean
	 */
	public function isSolutionable() {
		return WCF::getUser()->getPermission('user.contest.canSolution');
	}
		
	/**
	 * Returns true, if the active user can solution this entry.
	 * 
	 * @return	boolean
	 */
	public function isJurytalkable() {
		return true; // TODO: isJurytalkable
	}
		
	/**
	 * Returns true, if the active user can solution this entry.
	 * 
	 * @return	boolean
	 */
	public function isCommentable() {
		return true; // TODO: isCommentable
	}
		
	/**
	 * Returns true, if the active user can solution this entry.
	 * 
	 * @return	boolean
	 */
	public function isSponsorable() {
		return true; // TODO: isSponsorable
	}
		
	/**
	 * Returns true, if the active user can solution this entry.
	 * 
	 * @return	boolean
	 */
	public function isSponsortalkable() {
		return true; // TODO: isSponsortalkable
	}
		
	/**
	 * Returns true, if the active user can solution this entry.
	 * 
	 * @return	boolean
	 */
	public function isParticipantable() {
		return true; // TODO: isParticipantable
	}
		
	/**
	 * Returns true, if the active user can solution this entry.
	 * 
	 * @return	boolean
	 */
	public function isJuryable() {
		return true; // TODO: isJuryable
	}
		
	/**
	 * Returns true, if the active user can solution this entry.
	 * 
	 * @return	boolean
	 */
	public function isPriceable() {
		return true; // TODO: isPriceable
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

	/**
	 * Returns true, if the active user is member
	 * 
	 * @return	boolean
	 */
	public function isMember() {
		return ContestOwner::isMember($this->userID, $this->groupID);
	}
	
	/**
	 * Returns true, if the active user can edit this entry.
	 * 
	 * @return	boolean
	 */
	public function isEditable() {
		return $this->isMember();
	}
	
	/**
	 * Returns true, if the active user can delete this entry.
	 * 
	 * @return	boolean
	 */
	public function isDeletable() {
		return $this->isMember();
	}
}
?>
