<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectListCached.class.php');
require_once(WCF_DIR.'lib/data/contest/class/ContestClass.class.php');

/**
 * Represents a list of contest classes.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestClassTree extends DatabaseObjectListCached {

	/**
	 * id of the root node
	 *
	 * @var integer
	 */
	protected $rootID = 0;

	/**
	 * sql order by statement
	 *
	 * @var	string
	 */
	public $sqlOrderBy = 'contest_class.contests DESC';
	
	/**
	 * list of classes, built in makeContestClassList
	 *
	 * @var	array<array>
	 */
	protected $contestClassList = array();
	
	/**
	 * counts number of entries in first level
	 * @see DatabaseObjectListCached::_countObjects()
	 */
	public function _countObjects() {
		$sql = "SELECT	COUNT(*) AS count
			FROM	wcf".WCF_N."_contest_class contest_class
			WHERE	parentClassID = ".intval($this->rootID)."
			".(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '');
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}
	
	/**
	 * @see DatabaseObjectListCached::_readObjects()
	 */
	public function _readObjects() {
		$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
					contest_class.*
			FROM		wcf".WCF_N."_contest_class contest_class
			".$this->sqlJoins."
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '')."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');

		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		$classes = array();
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->contestClasses[$row['parentClassID']][] = new ContestClass(null, $row);
		}
		
		$this->makeContestClassList($this->rootID);
		
		return $this->contestClassList;
	}

	/**
	 * Renders one level of the help item structure.
	 *
	 * @param	integer		$parentHelpItemID
	 * @param	integer		$depth
	 * @param	integer		$openParents
	 */
	protected function makeContestClassList($parentClassID = 0, $depth = 1, $openParents = 0) {
		if (!isset($this->contestClasses[$parentClassID])) return;

		$i = 0; 
		$children = count($this->contestClasses[$parentClassID]);
		foreach ($this->contestClasses[$parentClassID] as $contestClass) {
			$childrenOpenParents = $openParents + 1;
			$hasChildren = isset($this->contestClasses[$contestClass->classID]);
			$last = $i == count($this->contestClasses[$parentClassID]) - 1;
			if ($hasChildren && !$last) $childrenOpenParents = 1;
			$this->contestClassList[] = array('depth' => $depth, 'hasChildren' => $hasChildren, 'openParents' => ((!$hasChildren && $last) ? ($openParents) : (0)), 'contestClass' => $contestClass, 'position' => $i + 1, 'maxPosition' => $children);

			// make next level of the list
			$this->makeContestClassList($contestClass->classID, $depth + 1, $childrenOpenParents);
			$i++;
		}
	}
}
?>
