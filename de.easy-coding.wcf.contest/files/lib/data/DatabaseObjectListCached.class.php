<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');

/**
 * easy way to implement caching in databaseobjectlist
 *
 * just overwrite the abstract methods _countObjects + _readObjects
 * _countObjects should do exactly the same as countObjects in DatabaseObjectList
 * _readObjects has a little difference, it has to return the data being read
 * getObjects is not longer abstract and does not need to be implemented by your own class
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
abstract class DatabaseObjectListCached extends DatabaseObjectList {

	/**
	 * contains cached data
	 *
	 * @var array<mixed>
	 */
	protected $cachedList = array();
	
	/**
	 * expiration time in seconds
	 *
	 * @var integer
	 */
	protected $maxLifetime = 180;
	
	/**
	 * 
	 * @var integer
	 */
	protected $minLifetime = 0;

	/**
	 * generated a hash key from membervariables
	 *
	 * @return string
	 */
	protected function getHash($key) {
		$members = array_filter(get_object_vars($this), create_function('$a', 'return !is_array($a) && !is_object($a);'));
		return sha1(serialize(array_merge($members, array(get_class($this), $key))));
	}
	
	/**
	 * counts number of entries in first level
	 * @see DatabaseObjectList::countObjects()
	 */
	protected function fromCache($method) {
		$key = 'DatabaseObjectListCached.'.$this->getHash($method);
		$cacheResource = array(
			'file' => WCF_DIR.'cache/cache.'.$key.'.php',
			'cache' => $key,
			'minLifetime' => $this->minLifetime,
			'maxLifetime' => $this->maxLifetime
		);
		
		if(($val = WCF::getCache()->getCacheSource()->get($cacheResource)) === null) {
			$val = $this->{'_'.$method}();
			WCF::getCache()->getCacheSource()->set($cacheResource, $val);
		}
		return $val;
	}
	
	/**
	 * Counts the number of objects.
	 * 
	 * @return	integer
	 */
	public function countObjects() {
		return $this->fromCache(StringUtil::replace(__CLASS__.'::', "", __METHOD__));
	}
	
	/**
	 * Reads the objects from database.
	 */
	public function readObjects() {
		$this->cachedList = $this->fromCache(StringUtil::replace(__CLASS__.'::', "", __METHOD__));
	}
	
	/**
	 * Returns the objects of the list.
	 * 
	 * @return	DatabaseObject
	 */
	public function getObjects() {
		return $this->cachedList;
	}
	
	/**
	 * Counts the number of objects.
	 * 
	 * @return	integer
	 */
	public abstract function _countObjects();
	
	/**
	 * Reads the objects from database.
	 */
	public abstract function _readObjects();
}
?>
