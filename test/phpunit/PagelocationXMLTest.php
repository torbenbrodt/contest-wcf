<?php

/**
 * searches for all xml files and tries to parse xml
 */
class PagelocationXMLTest extends WCFDirectoryTest {
	protected $pattern = '/pagelocation\.xml$/';
	
	public function testAll() {
		$i = 0;
		$example = 'index.php?page=Contest';
		foreach($this->files as $filename) {
			$xml = simplexml_load_file($filename);
			foreach($xml->import->pagelocation as $x) {
				$i++;
				$regex = (string)$x->pattern;
				try {
					preg_match('/^'.$regex.'$/', $example);
				} catch(Exception $e) {
					throw new Exception('wrong pattern in '.$filename.' for pattern #'.$i.': '.$regex);
				}
			}
		}
	}
}
?>
