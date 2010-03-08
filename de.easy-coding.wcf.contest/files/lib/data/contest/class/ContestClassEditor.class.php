<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/class/ContestClass.class.php');

/**
 * Provides functions to manage contest classs.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestClassEditor extends ContestClass {
	/**
	 * Creates a new class.
	 *
	 * @param	string		$title
	 * @return	ContestClassEditor
	 */
	public static function create($title) {
		$sql = "INSERT INTO	wcf".WCF_N."_contest_class
					(title)
			VALUES		('".escapeString($title)."')";
		WCF::getDB()->sendQuery($sql);
		
		// get new id
		$classID = WCF::getDB()->getInsertID("wcf".WCF_N."_contest_class", 'classID');
		return new ContestClassEditor($classID);
	}
	
	/**
	 * Updates this class.
	 *
	 * @param	string		$title
	 */
	public function update($title) {
		$sql = "UPDATE	wcf".WCF_N."_contest_class
			SET	title = '".escapeString($title)."'
			WHERE	classID = ".$this->classID;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Deletes this class.
	 */
	public function delete() {
		// update entries of this class
		$sql = "DELETE FROM	wcf".WCF_N."_contest_to_class
			WHERE		classID = ".$this->classID;
		WCF::getDB()->sendQuery($sql);
		
		// delete class
		$sql = "DELETE FROM	wcf".WCF_N."_contest_class
			WHERE		classID = ".$this->classID;
		WCF::getDB()->sendQuery($sql);
	}
}
?>
