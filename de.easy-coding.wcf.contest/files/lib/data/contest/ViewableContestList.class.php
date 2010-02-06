<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/ContestList.class.php');
require_once(WCF_DIR.'lib/data/contest/ViewableContest.class.php');
require_once(WCF_DIR.'lib/data/contest/class/ContestClass.class.php');

/**
 * Represents a list of contest entries.
 * 
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ViewableContestList extends ContestList {
	/**
	 * list of object ids
	 * 
	 * @var	array<integer>
	 */
	public $objectIDArray = array();
	
	/**
	 * list of object ids
	 * 
	 * @var	array<integer>
	 */
	public $attachmentEntryIDArray = array();
	
	/**
	 * attachment list object
	 * 
	 * @var	MessageAttachmentList
	 */
	public $attachmentList = null;
	
	/**
	 * list of attachments
	 * 
	 * @var	array
	 */
	public $attachments = array();
	
	/**
	 * list of classes
	 * 
	 * @var	array
	 */
	public $classes = array();
	
	/**
	 * list of jurys
	 * 
	 * @var	array
	 */
	public $jurys = array();
	
	/**
	 * list of participants
	 * 
	 * @var	array
	 */
	public $participants = array();
	
	/**
	 * list of sponsors
	 * 
	 * @var	array
	 */
	public $sponsors = array();
	
	/**
	 * list of prices
	 * 
	 * @var	array
	 */
	public $prices = array();
	
	/**
	 * list of tags
	 * 
	 * @var	array
	 */
	public $tags = array();
	
	/**
	 * Gets the object ids.
	 */
	protected function readObjectIDArray() {
		$sql = "SELECT		contest.contestID, contest.attachments
			FROM		wcf".WCF_N."_contest contest
				".$this->sqlJoins."
			WHERE ".Contest::getStateConditions()."
			".(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '')."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->objectIDArray[] = $row['contestID'];
			if ($row['attachments']) $this->attachmentEntryIDArray[] = $row['contestID'];
		}
	}
	
	/**
	 * Gets a list of attachments.
	 */
	protected function readAttachments() {
		// read attachments
		if (MODULE_ATTACHMENT == 1 && count($this->attachmentEntryIDArray) > 0) {
			require_once(WCF_DIR.'lib/data/attachment/MessageAttachmentList.class.php');
			$this->attachmentList = new MessageAttachmentList($this->attachmentEntryIDArray, 'contestEntry', '', WCF::getPackageID('de.easy-coding.wcf.contest'));
			$this->attachmentList->readObjects();
			$this->attachments = $this->attachmentList->getSortedAttachments(WCF::getUser()->getPermission('user.contest.canViewAttachmentPreview'));
			
			// set embedded attachments
			if (WCF::getUser()->getPermission('user.contest.canViewAttachmentPreview')) {
				require_once(WCF_DIR.'lib/data/message/bbcode/AttachmentBBCode.class.php');
				AttachmentBBCode::setAttachments($this->attachments);
			}
			
			// remove embedded attachments from list
			if (count($this->attachments) > 0) {
				MessageAttachmentList::removeEmbeddedAttachments($this->attachments);
			}
		}
	}
	
	/**
	 * Gets the list of classes.
	 */
	protected function readClasses() {
		$sql = "SELECT		contest_to_class.contestID,
					contest_class.classID, contest_class.title
			FROM		wcf".WCF_N."_contest_to_class contest_to_class
			LEFT JOIN	wcf".WCF_N."_contest_class contest_class
			ON		(contest_class.classID = contest_to_class.classID)
			WHERE		contest_to_class.contestID IN (".implode(',', $this->objectIDArray).")
			ORDER BY	contest_class.title";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			if (!isset($this->classes[$row['contestID']])) $this->classes[$row['contestID']] = array();
			$this->classes[$row['contestID']][] = new ContestClass(null, $row);
		}
	}
	
	/**
	 * Gets the list of jurys.
	 */
	protected function readJurys() {
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
			WHERE		contest_jury.contestID IN (".implode(',', $this->objectIDArray).")
			ORDER BY	title";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			if (!isset($this->jurys[$row['contestID']])) $this->jurys[$row['contestID']] = array();
			$this->jurys[$row['contestID']][] = new ContestJury(null, $row);
		}
	}
	
	/**
	 * Gets the list of participants.
	 */
	protected function readParticipants() {
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
			WHERE		contest_participant.contestID IN (".implode(',', $this->objectIDArray).")
			ORDER BY	title";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			if (!isset($this->participants[$row['contestID']])) $this->participants[$row['contestID']] = array();
			$this->participants[$row['contestID']][] = new ContestParticipant(null, $row);
		}
	}
	
	/**
	 * Gets the list of sponsors.
	 */
	protected function readSponsors() {
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
			WHERE		contest_sponsor.contestID IN (".implode(',', $this->objectIDArray).")
			ORDER BY	title";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			if (!isset($this->sponsors[$row['contestID']])) $this->sponsors[$row['contestID']] = array();
			$this->sponsors[$row['contestID']][] = new ContestSponsor(null, $row);
		}
	}
	
	/**
	 * Gets the list of prices.
	 */
	protected function readPrices() {
		$sql = "SELECT		contestID,
					contest_price.priceID, 
					contest_price.subject
			FROM		wcf".WCF_N."_contest_price contest_price
			WHERE		contestID IN (".implode(',', $this->objectIDArray).")
			ORDER BY	contest_price.position";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			if (!isset($this->prices[$row['contestID']])) $this->prices[$row['contestID']] = array();
			$this->prices[$row['contestID']][] = new ContestPrice(null, $row);
		}
	}
	
	/**
	 * Gets the list of tags.
	 */
	protected function readTags() {
		if (MODULE_TAGGING) {
			require_once(WCF_DIR.'lib/data/tag/TagEngine.class.php');
			$taggable = TagEngine::getInstance()->getTaggable('de.easy-coding.wcf.contest.entry');
			$sql = "SELECT		tag_to_object.objectID AS contestID,
						tag.tagID, tag.name
				FROM		wcf".WCF_N."_tag_to_object tag_to_object
				LEFT JOIN	wcf".WCF_N."_tag tag
				ON		(tag.tagID = tag_to_object.tagID)
				WHERE		tag_to_object.taggableID = ".$taggable->getTaggableID()."
						AND tag_to_object.languageID IN (".implode(',', (count(WCF::getSession()->getVisibleLanguageIDArray()) ? WCF::getSession()->getVisibleLanguageIDArray() : array(0))).")
						AND tag_to_object.objectID IN (".implode(',', $this->objectIDArray).")";
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				if (!isset($this->tags[$row['contestID']])) $this->tags[$row['contestID']] = array();
				$this->tags[$row['contestID']][] = new Tag(null, $row);
			}
		}
	}
	
	/**
	 * @see DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		// get ids
		$this->readObjectIDArray();
		
		// get entries
		if (count($this->objectIDArray)) {
			$this->readAttachments();
			$this->readClasses();
			$this->readJurys();
			$this->readParticipants();
			$this->readPrices();
			$this->readTags();
			
			$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
						contest.*
				FROM		wcf".WCF_N."_contest contest
				".$this->sqlJoins."
				WHERE 		contest.contestID IN (".implode(',', $this->objectIDArray).")
				".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				$this->entries[] = new ViewableContest(null, $row);
			}
		}
	}
	
	/**
	 * Returns the list of attachments.
	 * 
	 * @return	array
	 */
	public function getAttachments() {
		return $this->attachments;
	}
	
	/**
	 * Returns the list of classes.
	 * 
	 * @return	array
	 */
	public function getClasses() {
		return $this->classes;
	}
	
	/**
	 * Returns the list of jurys.
	 * 
	 * @return	array
	 */
	public function getJurys() {
		return $this->jurys;
	}
	
	/**
	 * Returns the list of jurys.
	 * 
	 * @return	array
	 */
	public function getParticipants() {
		return $this->participants;
	}
	
	/**
	 * Returns the list of prices.
	 * 
	 * @return	array
	 */
	public function getPrices() {
		return $this->prices;
	}
	
	/**
	 * Returns the list of tags.
	 * 
	 * @return	array
	 */
	public function getTags() {
		return $this->tags;
	}
}
?>
