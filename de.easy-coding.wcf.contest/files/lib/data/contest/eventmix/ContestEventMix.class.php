<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');

/**
 * Represents a contest entry event.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestEventMix extends DatabaseObject {

	/**
	 * protected instance
	 *
	 * @var ViewableContestEvent|ViewableContestComment
	 */
	protected $mix = null;

	/**
	 * Creates a new ViewableContest object.
	 *
	 * @param 	array<mixed>	$row
	 */
	public function __construct(array $row = array()) {
		if(!isset($row['className'])) {
			throw new SystemException('missing className');
		}
		$className = StringUtil::getClassName($row['className']);
		$dir = StringUtil::toLowercase(StringUtil::substring($className, StringUtil::length('ViewableContest')));
		
		if(empty($dir)) {
			throw new SystemException('wrong dir: '.$dir);
		}
		
		$file = WCF_DIR.'lib/data/contest/'.$dir.'/'.$className.'.class.php';
		if(!is_file($file)) {
			throw new SystemException('wrong file: '.$file);
		}
		require_once($file);
		
		if(!class_exists($className)) {
			throw new SystemException('class does not exist: '.$className);
		}
		
		$this->mix = new $className(null, $row);
	}

	/**
	 * pass magic method to owner object
	 */
	public function __set($name, $value) {
		$this->mix->$name = $value;
	}
	
	/**
	 * pass magic method to owner object
	 */
	public function __call($method, $args) {
		return call_user_func_array(array($this->mix, $method), $args);
	}

	/**
	 * pass magic method to owner object
	 */
	public function __get($name) {
		return $this->mix->$name;
	}
	
	/**
	 * never allow to edit the entry
	 */
	public function isEditable() {
		return false;
	}
	
	/**
	 * never allow to edit the entry
	 */
	public function isDeletable() {
		return false;
	}
	
	/**
	 * gets event name from static class method
	 *
	 * @param 	string
	 */
	public static function getEventMixName($string) {
		$string = explode('Editor::', $string);
		return substr(strtolower($string[0]).ucfirst($string[1]), 7);
	}
}
?>
