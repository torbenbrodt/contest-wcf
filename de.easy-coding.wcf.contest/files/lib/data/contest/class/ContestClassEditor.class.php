<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/class/ContestClass.class.php');
require_once(WCF_DIR.'lib/system/language/LanguageEditor.class.php');

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
	public static function create($title, $text = '', $parentClassID = 0, $position = 0, $languageID = 0) {
		if ($position == 0) {
			// get next number in row
			$sql = "SELECT	MAX(position) AS position
				FROM	wcf".WCF_N."_contest_class
				WHERE	parentClassID = ".intval($parentClassID);
			$row = WCF::getDB()->getFirstRow($sql);
			if (!empty($row)) $position = intval($row['position']) + 1;
			else $position = 1;
		}
		
		$sql = "INSERT INTO	wcf".WCF_N."_contest_class
					(classID, parentClassID, position)
			VALUES		('".escapeString($title)."', ".intval($parentClassID).", ".intval($position).")";
		WCF::getDB()->sendQuery($sql);
		
		// get new id
		$classID = WCF::getDB()->getInsertID("wcf".WCF_N."_contest_class", 'classID');

		$obj = new ContestClassEditor($classID);
		$obj->updateTranslation($title, $text, $languageID);		
		return $obj;
	}
	
	/**
	 *
	 */
	public function updateTranslation($title, $text, $languageID) {
		if($languageID == 0) return;
		
		// fetch language category
		$sql = "SELECT	languageCategoryID
			FROM	wcf".WCF_N."_language_category
			WHERE	languageCategory = 'wcf.contest.class.item'";
		$row = WCF::getDB()->getFirstRow($sql);
		$languageCategoryID = $row['languageCategoryID'];
		
		// save language variables
		$language = new LanguageEditor($languageID);
		$language->updateItems(array(
				'wcf.contest.class.item.'.$this->classID => $title, 
				'wcf.contest.class.item.'.$this->classID.'.description' => $text
			), $languageCategoryID, PACKAGE_ID);
	}
	
	/**
	 * Updates this class.
	 *
	 * @param	string		$title
	 */
	public function update($title, $text = '', $languageID = 0) {
		$this->updateTranslation($title, $text, $languageID);
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
	
	/**
	 * updates positions
	 * @param	array		$data
	 */
	public static function updatePositions($data) {
		if(count($data) == 0) {
			return;
		}
		
		$positionData = 1;
		$keys = array();
		foreach($data as $classID => $position) {
			$positionData = "IF(classID=".intval($classID).", ".intval($position).", $positionData)";
			$keys[] = intval($classID);
		}
		
		$sql = "UPDATE	wcf".WCF_N."_contest_class
			SET	position = $positionData
			WHERE	classID IN (".implode(",", $keys).")";
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * updates parents
	 * @param	array		$data
	 */
	public static function updateParents($data) {
		if(count($data) == 0) {
			return;
		}
		
		$parentData = 1;
		$keys = array();
		foreach($data as $classID => $parentClassID) {
			$parentData = "IF(classID=".intval($classID).", ".intval($parentClassID).", $parentData)";
			$keys[] = intval($classID);
		}
		
		$sql = "UPDATE	wcf".WCF_N."_contest_class
			SET	parentClassID = $parentData
			WHERE	classID IN (".implode(",", $keys).")";
		WCF::getDB()->sendQuery($sql);
	}
}
?>
