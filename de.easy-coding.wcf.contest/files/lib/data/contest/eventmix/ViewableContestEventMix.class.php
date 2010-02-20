<?php
// wcf imports
require_once(WCF_DIR.'lib/util/StringUtil.class.php');
require_once(WCF_DIR.'lib/data/contest/eventmix/ContestEventMix.class.php');

/**
 * Represents a mixture of contest events and contest comments.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ViewableContestEventMix extends ContestEventMix {

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
}
?>
