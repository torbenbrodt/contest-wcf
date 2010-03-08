<?php
// wcf imports
require_once(WCF_DIR.'lib/data/tag/TagList.class.php');

/**
 * Represents a list of tags.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestTagList extends TagList {
	/**
	 * contest
	 *
	 * @var	Contest
	 */
	public $contest = null;
	
	/**
	 * Creates a new ContestTagList object.
	 *
	 * @param	Contest		$contest
	 * @param	array<integer>	$languageIDArray
	 */
	public function __construct(Contest $contest = null, $languageIDArray = array()) {
		parent::__construct(array('de.easy-coding.wcf.contest.entry'), $languageIDArray);
		$this->contest = $contest;
	}
	
	/**
	 * Gets the tag ids.
	 */
	public function getTagsIDArray() {
		$tagIDArray = array();
		$contestID = $this->contest !== null ? $this->contest->contestID : 0;

		$sql = "SELECT		COUNT(*) AS counter, object.tagID
			FROM 		wcf".WCF_N."_contest contest,
					wcf".WCF_N."_tag_to_object object
			WHERE 		".($contestID ? "contest.contestID = ".$contestID : 1)."
					AND object.taggableID IN (".implode(',', $this->taggableIDArray).")
					AND object.languageID IN (".implode(',', $this->languageIDArray).")
					AND object.objectID = contest.contestID
			GROUP BY 	object.tagID
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			if ($row['counter'] > $this->maxCounter) $this->maxCounter = $row['counter'];
			if ($row['counter'] < $this->minCounter) $this->minCounter = $row['counter'];
			$tagIDArray[$row['tagID']] = $row['counter'];
		}
		
		return $tagIDArray;
	}
}
?>
