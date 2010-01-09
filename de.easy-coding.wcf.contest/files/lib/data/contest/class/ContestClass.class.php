<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a contest class.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestClass extends DatabaseObject {
	/**
	 * Creates a new ContestClass object.
	 *
	 * @param	integer		$classID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($classID, $row = null) {
		if ($classID !== null) {
			$sql = "SELECT		contest_class.*
				FROM 		wcf".WCF_N."_contest_class contest_class
				WHERE 		contest_class.classID = ".$classID;
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}
	
	/**
	 * Returns the title of this class.
	 * 
	 * @return	string
	 */
	public function __toString() {
		return $this->title;
	}
	
	/**
	 * Returns the formatted description of this class.
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
	 * Returns a list of all classes of a user.
	 * 
	 * @param	integer			$userID
	 * @return	array<ContestClass>
	 */
	public static function getClasses() {
		$classes = array();
		$sql = "SELECT		*
			FROM 		wcf".WCF_N."_contest_class
			ORDER BY	title";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$classes[$row['classID']] = new ContestClass(null, $row);
		}
		
		return $classes;
	}
	
	/**
	 * Returns true, if the active user can edit this class.
	 * 
	 * @return	boolean
	 */
	public function isEditable() {
		return false;
	}
	
	/**
	 * Returns true, if the active user can delete this class.
	 * 
	 * @return	boolean
	 */
	public function isDeletable() {
		return false;
	}
}
?>