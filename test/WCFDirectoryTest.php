<?php

class WCFDirectoryTest extends PHPUnit_Framework_TestCase {
	protected $pattern = '';
	protected $files = array();
	
	protected function getBase() {
		return dirname(__FILE__).'/..';
	}

	/**
	 *
	 */
	protected function setUp() {
		$ite = new RecursiveDirectoryIterator($this->getBase());
		foreach (new RecursiveIteratorIterator($ite) as $filename => $cur) {
			if(!preg_match($this->pattern, $filename)) {
				continue;
			}
			$this->files[] = $filename;
		}
	}
	
}
