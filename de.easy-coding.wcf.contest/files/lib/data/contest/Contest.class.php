<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
require_once(WCF_DIR.'lib/data/contest/crew/ContestCrew.class.php');

/**
 * Represents a contest entry.
 *
 * a contest can only be created if the following conditions are true
 * - user is registered
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class Contest extends DatabaseObject {
	/**
	 * jury list
	 *
	 * @var array<ContestJury>
	 */
	protected $juryList = null;

	/**
	 * sponsor list
	 *
	 * @var array<ContestSponsor>
	 */
	protected $sponsorList = null;

	/**
	 * participant list
	 *
	 * @var array<ContestParticipant>
	 */
	protected $participantList = null;

	/**
	 * @see isCloseable
	 */
	public $closableChecks = array();
	
	/**
	 * holds singleton instances
	 *
	 * @var array<Contest>
	 */
	protected static $instances = array();

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
				WHERE 	contestID = ".intval($contestID);
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}
	
	/**
	 * returns singleton instance
	 *
	 * @param	integer		$contestID
	 * @param 	array<mixed>	$row
	 */
	public static function getInstance($contestID) {
		if(!isset(self::$instances[$contestID])) {
			self::$instances[$contestID] = new self($contestID);
		}
		return self::$instances[$contestID];
	}

	/**
	 * Returns the tags of this entry.
	 *
	 * @return	array<Tag>
	 */
	public function getTags($languageIDArray) {
		if($this->isViewable() == false) {
			return array();
		}

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
			INNER JOIN	wcf".WCF_N."_contest_class contest_class
			ON		(contest_class.classID = contest_to_class.classID)
			WHERE		contest_to_class.contestID = ".intval($this->contestID)."
			ORDER BY	contest_class.position";
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
		$this->participantList->sqlConditions .= 'contest_participant.contestID = '.intval($this->contestID);
		$this->participantList->readObjects();

		return $this->participantList->getObjects();
	}

	/**
	 * Gets the solutions of this entry.
	 *
	 * @return	array<ContestSolution>
	 */
	public function getSolutions() {
		if($this->solutionList !== null) {
			return $this->solutionList->getObjects();
		}

		require_once(WCF_DIR.'lib/data/contest/solution/ContestSolutionList.class.php');
		$this->solutionList = new ContestSolutionList();
		$this->solutionList->sqlConditions .= 'contest_solution.contestID = '.intval($this->contestID);
		$this->solutionList->readObjects();

		return $this->solutionList->getObjects();
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
		$this->juryList->sqlConditions .= 'contest_jury.contestID = '.intval($this->contestID);
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
		$this->sponsorList->sqlConditions .= 'contest_sponsor.contestID = '.intval($this->contestID);
		$this->sponsorList->readObjects();

		return $this->sponsorList->getObjects();
	}

	/**
	 * Returns true, if the active user can solution this entry.
	 *
	 * @return	boolean
	 */
	public function isSolutionable() {
		if(ContestCrew::isMember()) {
			return true;
		}
		if(WCF::getUser()->getPermission('user.contest.canSolution') == false) {
			return false;
		}
		if($this->isParticipantable() == false) {
			return false;
		}
		if($this->enableParticipantCheck) {
			foreach($this->getParticipants() as $participant) {
				if($jury->state == 'accepted' && $participant->isOwner()) {
					return true;
				}
			}
			return false;
		}
		return true;
	}

	/**
	 * is closeable by current user
	 * status close is a successfull finish, if you want to stop the contest use decline status
	 *
	 * @return	boolean
	 */
	public function isClosable() {
		if(WCF::getUser()->userID == 0 || $this->isOwner() == false
		  || !($this->state == 'scheduled' && $this->untilTime < TIME_NOW)) {
			return false;
		}

		try {
			// call assignVariables event
			EventHandler::fireAction($this, 'isClosable');

			// check if all solutions have been judged
			$this->closableChecks[] = array(
				'className' => 'ContestJuryTodoList',
				'classPath' => WCF_DIR.'lib/data/contest/jury/todo/ContestJuryTodoList.class.php'
			);

			foreach($this->closableChecks as $check) {
				require_once($check['classPath']);
				$todoList = new $check['className']();
				$todoList->sqlConditions .= 'contestID = '.intval($this->contestID);
				if($num = $todoList->countObjects()) {
					throw new Exception($row['className'].' returns '.$num.' todo objects.');
				}
			}
		} catch(Exception $e) {
			return false;
		}

		return true;
	}

	/**
	 * Returns true, if the active user is member of the jury
	 *
	 * @return	boolean
	 */
	public function isJury() {
		foreach($this->getJurys() as $jury) {
			if($jury->state == 'accepted' && $jury->isOwner()) {
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
	public function isJurytalkable() {
		if($this->isOwner()) {
			return true;
		}
		foreach($this->getJurys() as $jury) {
			if(in_array($jury->state, array('accepted', 'invited')) && $jury->isOwner()) {
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
	 * Returns true, if the active user is member of the sponsor team
	 *
	 * @return	boolean
	 */
	public function isSponsor() {
		foreach($this->getSponsors() as $sponsor) {
			if($sponsor->state == 'accepted' && $sponsor->isOwner()) {
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
	public function isSponsorable($userCheck = true) {
		return (!$userCheck || WCF::getUser()->userID) && $this->state != 'closed';
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
		foreach($this->getSponsors() as $sponsor) {
			if(in_array($sponsor->state, array('accepted', 'invited')) && $sponsor->isOwner()) {
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
	public function isParticipantable($userCheck = true) {
		if(($userCheck && WCF::getUser()->userID == 0) || $this->isOwner() || $this->state == 'closed'
		  || !($this->state == 'scheduled' && $this->untilTime < TIME_NOW)) {
			return false;
		}
		
		// is in jury?
		foreach($this->getJurys() as $jury) {
			if($jury->isOwner()) {
				return false;
			}
		}
		
		// alreay participant
		foreach($this->getParticipants() as $participant) {
			if($participant->isOwner()) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Returns true, if the active user can join the jury.
	 *
	 * @return	boolean
	 */
	public function isJuryable() {
		if(WCF::getUser()->userID == 0 || $this->state == 'closed') {
			return false;
		}
		
		// already in jury?
		foreach($this->getJurys() as $jury) {
			if($jury->isOwner()) {
				return false;
			}
		}
		
		return true;
	}

	/**
	 * Returns true, if the active user can add prices to this entry.
	 *
	 * @return	boolean
	 */
	public function isPriceable() {
		if(WCF::getUser()->userID == 0 || $this->state == 'closed') {
			return false;
		}
		if($this->enableSponsorCheck) {
			foreach($this->getSponsors() as $sponsor) {
				if($jury->state == 'accepted' && $sponsor->isOwner()) {
					return true;
				}
			}
			return false;
		}
		return true;
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
			WHERE	userID = ".intval($userID);
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['entries'];
	}

	/**
	 * Returns owner
	 *
	 * @return	ContestOwner
	 */
	public function getOwner() {
		return ContestOwner::get($this->userID, $this->groupID);
	}

	/**
	 * Returns true, if the active user is member
	 *
	 * @return	boolean
	 */
	public function isOwner() {
		return ContestOwner::get($this->userID, $this->groupID)->isCurrentUser();
	}

	/**
	 * Returns true, if the active user can edit this entry.
	 *
	 * @return	boolean
	 */
	public function isEditable() {
		return ContestCrew::isMember() || (in_array($this->state, array('private', 'applied')) && $this->isOwner());
	}

	/**
	 * Returns true, if the active user can delete this entry.
	 *
	 * @return	boolean
	 */
	public function isDeletable() {
		return ContestCrew::isMember() || (in_array($this->state, array('private', 'applied')) && $this->isOwner());
	}

	public function isViewable() {
		return $this->messagePreview || $this->isOwner() || $this->state == 'closed' || ($this->state == 'scheduled' && $this->fromTime < TIME_NOW);
	}

	/**
	 * @see DatabaseObject::handleData()
	 */
	protected function handleData($data) {
		parent::handleData($data);

		if($this->isViewable() == false) {
			$this->subject = WCF::getLanguage()->get('wcf.contest.subject.hidden');
			$this->message = WCF::getLanguage()->get('wcf.contest.message.hidden');
			$this->attachments = 0;
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
	 * - applied
	 *    owner can view and edit the entry
	 *    accepted jurys can see the full entry
	 *    admin team should review the entry and schedule a time
	 *    the state can be changed by the admin team
	 *
	 * - accepted
	 *    owner cannot change the entry any more
	 *    entry can be shown if start_time is over
	 *    state cannot be changed any longer
	 *
	 * - scheduled
	 *    upcoming
	 *
	 * - closed
	 *    no ratings can be given, no jurys can be added
	 */
	public static function getStateConditions() {
		$userID = WCF::getUser()->userID;
		$userID = $userID ? $userID : -1;
		$groupIDs = array_keys(ContestUtil::readAvailableGroups());
		$groupIDs = empty($groupIDs) ? array(-1) : $groupIDs; // makes easier sql queries

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
			contest.state = 'closed'
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
			WHERE	x.contestID = contest.contestID
			AND (	x.groupID IN (".implode(",", $groupIDs).")
			  OR	x.userID = ".$userID."
			)
		) > 0";
	}
}
?>
