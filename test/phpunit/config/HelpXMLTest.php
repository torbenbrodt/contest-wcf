<?php

/**
 * searches for all xml files and tries to parse xml
 */
class HelpXMLTest extends WCFDirectoryTest {
	protected $pattern = '/help\.xml$/';
	
	public function testAll() {
		$i = 0;
		$example = 'index.php?page=Contest';
		foreach($this->files as $filename) {
			$xml = simplexml_load_file($filename);
			foreach($xml->import->helpitem as $x) {
				$i++;
				$regex = (string)$x->refererpattern;
				try {
					preg_match('/^'.$regex.'$/', $example);
				} catch(Exception $e) {
					throw new Exception('wrong refererpattern in '.$filename.' for pattern #'.$i.': '.$regex);
				}
			}
		}
	}
}
?>
