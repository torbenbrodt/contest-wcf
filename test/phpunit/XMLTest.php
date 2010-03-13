<?php

/**
 * searches for all xml files and tries to parse xml
 */
class XMLTest extends WCFDirectoryTest {
	protected $pattern = '/\.xml$/';
	
	public function testAll() {
		foreach($this->files as $filename) {
			$xml = simplexml_load_file($filename);
			$this->assertGreaterThan(0, count($xml->children()), "$filename has no children");
		}
	}
}
?>
