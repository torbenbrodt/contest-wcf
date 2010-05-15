<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/ContestList.class.php');
require_once(WCF_DIR.'lib/data/contest/ViewableContest.class.php');
require_once(WCF_DIR.'lib/data/contest/class/ContestClass.class.php');

/**
 * Represents a list of contest entries.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
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
					contest_class.classID
			FROM		wcf".WCF_N."_contest_to_class contest_to_class
			LEFT JOIN	wcf".WCF_N."_contest_class contest_class
			ON		(contest_class.classID = contest_to_class.classID)
			WHERE		contest_to_class.contestID IN (".implode(',', $this->objectIDArray).")
			ORDER BY	contest_class.position";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			if (!isset($this->classes[$row['contestID']])) $this->classes[$row['contestID']] = array();
			$this->classes[$row['contestID']][] = new ContestClass(null, $row);
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
			$this->readTags();
			
			$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
						contest.*
				FROM		wcf".WCF_N."_contest contest
				".$this->sqlJoins."
				WHERE 		contest.contestID IN (".implode(',', $this->objectIDArray).")
				GROUP BY 	contestID
				".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '')."";
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
	 * Returns the list of tags.
	 * 
	 * @return	array
	 */
	public function getTags() {
		return $this->tags;
	}
}
?>
