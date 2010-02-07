<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');

/**
 * Represents a contest entry.
 * 
 * a contest can only be created if the following conditions are true
 * - user is registered
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class Contest extends DatabaseObject {
	protected $juryList = null;
	protected $sponsorList = null;
	protected $participantList = null;

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
		if($this->participantList !== null) {
			return $this->participantList->getObjects();
		}

		require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipantList.class.php');		
		$this->participantList = new ContestParticipantList();
		$this->participantList->sqlConditions .= 'contest_participant.contestID = '.$this->contestID;
		$this->participantList->readObjects();
		
		return $this->participantList->getObjects();
	}
	
	/**
	 * Gets the jurys of this entry.
	 * 
	 * @return	array<ContestJury>
	 */
	public function getJurys() {
		if($this->juryList !== null) {
			return $this->juryList->getObjects();
		}

		require_once(WCF_DIR.'lib/data/contest/jury/ContestJuryList.class.php');		
		$this->juryList = new ContestJuryList();
		$this->juryList->sqlConditions .= 'contest_jury.contestID = '.$this->contestID;
		$this->juryList->readObjects();
		
		return $this->juryList->getObjects();
	}
	
	/**
	 * Gets the sponsors of this entry.
	 * 
	 * @return	array<ContestSponsor>
	 */
	public function getSponsors() {
		if($this->sponsorList !== null) {
			return $this->sponsorList->getObjects();
		}

		require_once(WCF_DIR.'lib/data/contest/sponsor/ContestSponsorList.class.php');		
		$this->sponsorList = new ContestSponsorList();
		$this->sponsorList->sqlConditions .= 'contest_sponsor.contestID = '.$this->contestID;
		$this->sponsorList->readObjects();
		
		return $this->sponsorList->getObjects();
	}
	
	/**
	 * Returns true, if the active user can solution this entry.
	 * 
	 * @return	boolean
	 */
	public function isSolutionable() {
		if($this->isParticipantable() == false) {
			return false;
		}
		return WCF::getUser()->getPermission('user.contest.canSolution');
	}
		
	/**
	 * Returns true, if the active user can solution this entry.
	 * 
	 * @return	boolean
	 */
	public function isJurytalkable() {
		if($this->isOwner()) {
			return true;
		}
		foreach($this->getJurys() as $jury) {
			if($jury->isOwner()) {
				return true;
			}
		}
	
		return false;
	}
		
	/**
	 * everybody can add comments
	 * 
	 * @return	boolean
	 */
	public function isCommentable() {
		return true;
	}
		
	/**
	 * Returns true, if the active user can solution this entry.
	 * 
	 * @return	boolean
	 */
	public function isSponsorable() {
		return WCF::getUser()->userID && !($this->state == 'scheduled' && $this->untilTime < TIME_NOW);
	}
		
	/**
	 * Returns true, if the active user can solution this entry.
	 * 
	 * @return	boolean
	 */
	public function isSponsortalkable() {
		if($this->isOwner()) {
			return true;
		}
		foreach($this->getSponsors() as $jury) {
			if($jury->isOwner()) {
				return true;
			}
		}
	
		return false;
	}
		
	/**
	 * Returns true, if the active user can solution this entry.
	 * 
	 * @return	boolean
	 */
	public function isParticipantable() {
		if(WCF::getUser()->userID == 0 || $this->isOwner()
		  || ($this->state == 'scheduled' && $this->untilTime < TIME_NOW)) {
			return false;
		}
		foreach($this->getJurys() as $jury) {
			if($jury->isOwner()) {
				return false;
			}
		}
	
		return true;
	}
		
	/**
	 * Returns true, if the active user can solution this entry.
	 * 
	 * @return	boolean
	 */
	public function isJuryable() {
		return WCF::getUser()->userID && !($this->state == 'scheduled' && $this->untilTime < TIME_NOW);
	}
		
	/**
	 * Returns true, if the active user can solution this entry.
	 * 
	 * @return	boolean
	 */
	public function isPriceable() {
		return WCF::getUser()->userID && !($this->state == 'scheduled' && $this->untilTime < TIME_NOW);
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
	public function isOwner() {
		return ContestOwner::isOwner($this->userID, $this->groupID);
	}
	
	/**
	 * Returns true, if the active user can edit this entry.
	 * 
	 * @return	boolean
	 */
	public function isEditable() {
		return in_array($this->state, array('private', 'waiting')) && $this->isOwner();
	}
	
	/**
	 * Returns true, if the active user can delete this entry.
	 * 
	 * @return	boolean
	 */
	public function isDeletable() {
		return in_array($this->state, array('private', 'waiting')) && $this->isOwner();
	}
	
	public function isViewable() {
		return $this->isOwner() || ($this->state == 'scheduled' && $this->fromTime < TIME_NOW);
	}

	/**
	 * @see DatabaseObject::handleData()
	 */
	protected function handleData($data) {
		parent::handleData($data);

		if($this->isViewable() == false) {
			$this->subject = '*hidden*';
			$this->message = '*hidden*';
		}
	}

	/**
	 * thats how the states are implemented
	 * - private
	 *    only the owner can view and edit the entry
	 *    invited jurys can view the first entry of jurytalk
	 *    invited sponsors can view the first entry of sponsortalk
	 *    invited participants can view the first comment
	 *    all invited people can view basis data (without the real entry)
	 *    accepting an invitation enabled the users to reply to the talks
	 *    the state can be changed by the owner
	 *
	 * - waiting
	 *    owner can view and edit the entry
	 *    accepted jurys can see the full entry
	 *    admin team should review the entry and schedule a time
	 *    the state can be changed by the admin team
	 *
	 * - reviewed
	 *    owner cannot change the entry any more
	 *    entry can be shown if start_time is over
	 *    state cannot be changed any longer
	 *
	 * - scheduled
	 *    upcoming	
	 */
	public static function getStateConditions() {
		$userID = WCF::getUser()->userID;
		$groupIDs = array_keys(ContestUtil::readAvailableGroups());

		return "(
			-- owner
			IF(
				contest.groupID > 0,
				contest.groupID IN (".implode(",", $groupIDs)."), 
				contest.userID > 0 AND contest.userID = ".$userID."
			)
		) OR (
			contest.state = 'scheduled'
			AND contest.fromTime <= UNIX_TIMESTAMP(NOW())
		) OR (
			-- jury, sponsor, participant
			SELECT 	COUNT(*)
			FROM (
				SELECT contestID, userID, groupID FROM wcf".WCF_N."_contest_jury
				UNION
				SELECT contestID, userID, groupID FROM wcf".WCF_N."_contest_sponsor
				UNION
				SELECT contestID, userID, groupID FROM wcf".WCF_N."_contest_participant
			) x
			WHERE x.contestID = contest.contestID
			AND IF(
				x.groupID > 0,
				x.groupID IN (".implode(",", $groupIDs)."), 
				x.userID > 0 AND x.userID = ".$userID."
			)
		) > 0";
	}
}
?>
