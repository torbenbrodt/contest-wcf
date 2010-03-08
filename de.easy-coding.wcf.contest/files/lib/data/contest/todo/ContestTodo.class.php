<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a viewable contest jury todo entry.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestTodo extends DatabaseObject {
	/**
	 * owner object
	 *
	 * @var ContestOwner
	 */
	protected $owner = null;
	
	/**
	 * @see DatabaseObject::handleData()
	 */
	protected function handleData($data) {
		parent::handleData($data);
		$this->owner = new ContestOwner($data, $this->userID, $this->groupID);
	}
	
	/**
	 * Returns the owner object.
	 * 
	 * @return	ContestOwner
	 */
	public function getOwner() {
		return $this->owner;
	}
	
	/**
	 * Wrapper to getMessage.
	 * 
	 * @return	string
	 */
	public function __toString() {
		return "".$this->getMessage();
	}
	
	/**
	 * Returns the translated title of this message.
	 * 
	 * @return	string
	 */
	public function getMessage() {
		return WCF::getLanguage()->get('wcf.contest.todo.'.$this->action, $this->data);
	}
}
?>
