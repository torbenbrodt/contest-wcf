<?php

class WCFDirectoryTest extends PHPUnit_Framework_TestCase {
	protected $pattern = '';
	protected $files = array();

	/**
	 *
	 */
	protected function setUp() {
		$ite = new RecursiveDirectoryIterator(dirname(__FILE__).'/..');
		foreach (new RecursiveIteratorIterator($ite) as $filename => $cur) {
			if(!preg_match($this->pattern, $filename)) {
				continue;
			}
			$this->files[] = $filename;
		}
	}
	
}
