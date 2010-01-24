<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/ViewableContestList.class.php');
require_once(WCF_DIR.'lib/data/tag/TagEngine.class.php');

/**
 * Represents a list of tagged contest entries.
 * 
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class TaggedContestList extends ViewableContestList {
	/**
	 * tag id
	 * 
	 * @var	integer
	 */
	public $tagID = 0;
	
	/**
	 * taggable object
	 * 
	 * @var	Taggable
	 */
	public $taggable = null;

	/**
	 * Creates a new TaggedContestList object.
	 */
	public function __construct($tagID) {
		$this->tagID = $tagID;
		$this->taggable = TagEngine::getInstance()->getTaggable('de.easy-coding.wcf.contest.entry');
	}
	
	/**
	 * @see DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		if (!empty($this->sqlConditions)) {
			$sql = "SELECT	COUNT(*) AS count
				FROM	wcf".WCF_N."_tag_to_object tag_to_object,
					wcf".WCF_N."_contest contest
				WHERE	tag_to_object.tagID = ".$this->tagID."
					AND tag_to_object.taggableID = ".$this->taggable->getTaggableID()."
					AND contest.contestID = tag_to_object.objectID
					AND ".$this->sqlConditions;
		}
		else {
			$sql = "SELECT	COUNT(*) AS count
				FROM	wcf".WCF_N."_tag_to_object
				WHERE	tagID = ".$this->tagID."
					AND taggableID = ".$this->taggable->getTaggableID();
		}
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}
	
	/**
	 * Gets the object ids.
	 */
	protected function readObjectIDArray() {
		$sql = "SELECT		contest.contestID, contest.attachments
			FROM		wcf".WCF_N."_tag_to_object tag_to_object,
					wcf".WCF_N."_contest contest
			WHERE		tag_to_object.tagID = ".$this->tagID."
					AND tag_to_object.taggableID = ".$this->taggable->getTaggableID()."
					AND contest.contestID = tag_to_object.objectID
					".(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '')."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->objectIDArray[] = $row['contestID'];
			if ($row['attachments']) $this->attachmentEntryIDArray[] = $row['contestID'];
		}
	}
}
?>