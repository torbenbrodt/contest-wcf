<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a contest entry rating.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestRatingoption extends DatabaseObject {
	/**
	 * Creates a new ContestRatingoption object.
	 *
	 * @param	integer		$optionID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($optionID, $row = null) {
		if ($optionID !== null) {
			$sql = "SELECT		contest_ratingoption.*
				FROM 		wcf".WCF_N."_contest_ratingoption contest_ratingoption
				WHERE 		contest_ratingoption.optionID = ".intval($optionID);
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}

	/**
	 * @see DatabaseObject::handleData()
	 */
	protected function handleData($data) {
		parent::handleData($data);

		$this->title = 'wcf.contest.ratingoption.item.'.$this->optionID;
	}
	
	/**
	 * Returns the title of this class.
	 * 
	 * @return	string
	 */
	public function __toString() {
		return "".WCF::getLanguage()->get($this->title);
	}
	
	/**
	 * Returns an editor object for this rating.
	 *
	 * @return	ContestRatingoptionEditor
	 */
	public function getEditor() {
		require_once(WCF_DIR.'lib/data/contest/ratingoption/ContestRatingoptionEditor.class.php');
		return new ContestRatingoptionEditor(null, $this->data);
	}
}
?>
