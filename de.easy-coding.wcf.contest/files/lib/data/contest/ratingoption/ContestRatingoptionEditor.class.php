<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/ratingoption/ContestRatingoption.class.php');
require_once(WCF_DIR.'lib/system/language/LanguageEditor.class.php');

/**
 * Provides functions to manage entry ratings.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestRatingoptionEditor extends ContestRatingoption {
	/**
	 * Creates a new entry rating.
	 *
	 * @param	integer		$name
	 * @return	ContestRatingoptionEditor
	 */
	public static function create($title, $text = '', $classID = 0, $position = 0, $languageID = 0) {
		if ($position == 0) {
			// get next number in row
			$sql = "SELECT	MAX(position) AS position
				FROM	wcf".WCF_N."_contest_ratingoption
				WHERE	classID = ".intval($classID);
			$row = WCF::getDB()->getFirstRow($sql);
			if (!empty($row)) $position = intval($row['position']) + 1;
			else $position = 1;
		}
		
		$sql = "INSERT INTO	wcf".WCF_N."_contest_ratingoption
					(optionID, classID, position)
			VALUES		('".escapeString($title)."', ".intval($classID).", ".intval($position).")";
		WCF::getDB()->sendQuery($sql);
		
		// get new id
		$optionID = WCF::getDB()->getInsertID("wcf".WCF_N."_contest_ratingoption", 'optionID');

		$obj = new ContestRatingoptionEditor($optionID);
		$obj->updateTranslation($title, $text, $languageID);		
		return $obj;
	}
	
	/**
	 *
	 */
	public function updateTranslation($title, $text, $languageID) {
		if($languageID == 0) return;
		// save language variables
		$language = new LanguageEditor($languageID);
		$language->updateItems(array(
				'wcf.contest.ratingoption.item.'.$this->optionID => $title, 
				'wcf.contest.ratingoption.item.'.$this->optionID.'.description' => $text
			), 0, WCF::getPackageID('de.easy-coding.wcf.contest'));
		LanguageEditor::deleteLanguageFiles($languageID, 'wcf.contest.ratingoption.item');
	}
	
	/**
	 * Updates this class.
	 *
	 * @param	string		$title
	 */
	public function update($classID = 0, $title, $text = '', $languageID = 0) {
		$sql = "UPDATE		wcf".WCF_N."_contest_ratingoption
			SET		classID = ".intval($classID)."
			WHERE		optionID = ".intval($this->optionID);
		WCF::getDB()->sendQuery($sql);
		
		$this->updateTranslation($title, $text, $languageID);
	}
	
	/**
	 * Deletes this entry rating.
	 */
	public function delete() {
		// delete rating
		$sql = "DELETE FROM	wcf".WCF_N."_contest_ratingoption
			WHERE		optionID = ".intval($this->optionID);
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
		foreach($data as $optionID => $position) {
			$positionData = "IF(optionID=".intval($optionID).", ".intval($position).", $positionData)";
			$keys[] = intval($optionID);
		}
		
		$sql = "UPDATE	wcf".WCF_N."_contest_ratingoption
			SET	position = $positionData
			WHERE	optionID IN (".implode(",", $keys).")";
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
		foreach($data as $optionID => $classID) {
			$parentData = "IF(optionID=".intval($optionID).", ".intval($classID).", $parentData)";
			$keys[] = intval($optionID);
		}
		
		$sql = "UPDATE	wcf".WCF_N."_contest_ratingoption
			SET	classID = $parentData
			WHERE	optionID IN (".implode(",", $keys).")";
		WCF::getDB()->sendQuery($sql);
	}
}
?>
