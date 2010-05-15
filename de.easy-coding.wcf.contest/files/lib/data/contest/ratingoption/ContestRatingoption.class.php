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
			$sql = "SELECT		*
				FROM 		wcf".WCF_N."_contest_ratingoption
				WHERE 		optionID = ".$optionID;
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
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