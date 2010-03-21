<?php

/**
 * allows all functions calls but always returns null if methods does not exist
 */
class WCFNullwrapper {
	protected $instances = array();

	/**
	 *
	 */
	public function __construct() {
		$this->instances = func_get_args();
	}

	/**
	 * pass magic method to instance object
	 */
	public function __set($name, $value) {
		foreach($this->instances as $inst) {
			$inst->$name = $value;
		}
	}
	
	/**
	 * pass magic method to instance object
	 */
	public function __call($method, $args) {
		foreach($this->instances as $inst) {
			if(method_exists($inst, $method)) {
				return call_user_func_array(array($inst, $method), $args);
			}
		}
		
		return null;
	}

	/**
	 * pass magic method to instance object
	 */
	public function __get($name) {
		foreach($this->instances as $inst) {
			if($inst->$name !== null) {
				return $inst->$name;
			}
		}
		return null;
	}

}
?>
