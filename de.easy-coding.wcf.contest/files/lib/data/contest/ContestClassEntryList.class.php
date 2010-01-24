<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/ViewableContestList.class.php');

/**
 * Represents a list of contest entries of a class.
 * 
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestClassEntryList extends ViewableContestList {
	/**
	 * class id
	 * 
	 * @var	integer
	 */
	public $classID = 0;
	
	/**
	 * Creates a new ContestClassEntryList object.
	 * 
	 * @param	integer		$classID
	 */
	public function __construct($classID) {
		$this->classID = $classID;
	}
	
	/**
	 * @see DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		if (!empty($this->sqlConditions)) {
			$sql = "SELECT	COUNT(*) AS count
				FROM	wcf".WCF_N."_contest_to_class contest_to_class,
					wcf".WCF_N."_contest contest
				WHERE	contest_to_class.classID = ".$this->classID."
					AND contest.contestID = contest_to_class.contestID
					AND ".$this->sqlConditions;
		}
		else {
			$sql = "SELECT	COUNT(*) AS count
				FROM	wcf".WCF_N."_contest_to_class
				WHERE	classID = ".$this->classID;
		}
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}
	
	/**
	 * Gets the object ids.
	 */
	protected function readObjectIDArray() {
		$sql = "SELECT		contest.contestID, contest.attachments
			FROM		wcf".WCF_N."_contest_to_class contest_to_class,
					wcf".WCF_N."_contest contest
			WHERE		contest_to_class.classID = ".$this->classID."
					AND contest.contestID = contest_to_class.contestID
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