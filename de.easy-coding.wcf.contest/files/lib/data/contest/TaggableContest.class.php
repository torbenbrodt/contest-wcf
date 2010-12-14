<?php
// wcf imports
require_once(WCF_DIR.'lib/data/tag/AbstractTaggableObject.class.php');
require_once(WCF_DIR.'lib/data/contest/TaggedContest.class.php');

/**
 * An implementation of Taggable to support the tagging of contest entries.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class TaggableContest extends AbstractTaggableObject {
	/**
	 * @see Taggable::getObjectsByIDs()
	 */
	public function getObjectsByIDs($objectIDs, $taggedObjects) {
		$sql = "SELECT		*
			FROM		wcf".WCF_N."_contest
			WHERE		contestID IN (" . implode(",", $objectIDs) . ")";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$row['taggable'] = $this;
			$taggedObjects[] = new TaggedContest(null, $row);
		}
		return $taggedObjects;
	}
	
	/**
	 * @see Taggable::countObjectsByTagID()
	 */
	public function countObjectsByTagID($tagID) {
		if (!WCF::getUser()->getPermission('user.contest.canViewContest')) {
			return 0;
		}
		
		$sql = "SELECT	COUNT(*) AS count
			FROM	wcf".WCF_N."_tag_to_object
			WHERE	tagID = ".intval($tagID)."
				AND taggableID = ".$this->getTaggableID();
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}
	
	/**
	 * @see Taggable::getObjectsByTagID()
	 */
	public function getObjectsByTagID($tagID, $limit = 0, $offset = 0) {
		if (!WCF::getUser()->getPermission('user.contest.canViewContest')) {
			return array();
		}
		
		$entrys = array();
		$sql = "SELECT		contest.*, user_table.username
			FROM		wcf".WCF_N."_tag_to_object tag_to_object
			LEFT JOIN	wcf".WCF_N."_contest contest
			ON		(contest.contestID = tag_to_object.objectID)
			LEFT JOIN	wcf".WCF_N."_user user_table
			ON		(user_table.userID = contest.userID)
			WHERE		tag_to_object.tagID = ".intval($tagID)."
					AND tag_to_object.taggableID = ".$this->getTaggableID()."
			ORDER BY	contest.time DESC";
		$result = WCF::getDB()->sendQuery($sql, $limit, $offset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$row['taggable'] = $this;
			$entrys[] = new TaggedContest(null, $row);
		}
		return $entrys;
	}

	/**
	 * @see Taggable::getIDFieldName()
	 */
	public function getIDFieldName() {
		return 'contestID';
	}
	
	/**
	 * @see Taggable::getResultTemplateName()
	 */
	public function getResultTemplateName() {
		return 'taggedContestEntries';
	}
	
	/**
	 * @see Taggable::getSmallSymbol()
	 */
	public function getSmallSymbol() {
		return StyleManager::getStyle()->getIconPath('contestS.png');
	}

	/**
	 * @see Taggable::getMediumSymbol()
	 */
	public function getMediumSymbol() {
		return StyleManager::getStyle()->getIconPath('contestM.png');
	}
	
	/**
	 * @see Taggable::getLargeSymbol()
	 */
	public function getLargeSymbol() {
		return StyleManager::getStyle()->getIconPath('contestL.png');
	}
}
?>
