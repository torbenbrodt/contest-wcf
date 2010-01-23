<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a contest price.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestPrice extends DatabaseObject {
	/**
	 * Creates a new ContestPrice object.
	 *
	 * @param	integer		$priceID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($priceID, $row = null) {
		if ($priceID !== null) {
			$sql = "SELECT		contest_price.*
				FROM 		wcf".WCF_N."_contest_price contest_price
				WHERE 		contest_price.priceID = ".intval($priceID);
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}
	
	/**
	 * Returns the title of this price.
	 * 
	 * @return	string
	 */
	public function __toString() {
		return $this->subject;
	}
	
	/**
	 * Returns the formatted description of this price.
	 * 
	 * @return	string
	 */
	public function getFormattedDescription() {
		if ($this->description) {
			return nl2br(StringUtil::encodeHTML($this->description));
		}
		
		return '';
	}
	
	/**
	 * Returns a list of all prices of a user.
	 * 
	 * @param	integer			$userID
	 * @return	array<ContestPrice>
	 */
	public static function getPrices() {
		$prices = array();
		$sql = "SELECT		*
			FROM 		wcf".WCF_N."_contest_price
			ORDER BY	title";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$prices[$row['priceID']] = new ContestPrice(null, $row);
		}
		
		return $prices;
	}
	
	/**
	 * Returns true, if the active user can edit this price.
	 * 
	 * @return	boolean
	 */
	public function isEditable() {
		return false;
	}
	
	/**
	 * Returns true, if the active user can delete this price.
	 * 
	 * @return	boolean
	 */
	public function isDeletable() {
		return false;
	}
}
?>
