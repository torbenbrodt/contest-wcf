<?php

/**
 * searches for all xml files and tries to parse xml
 */
class ACPMenuXMLTest extends WCFDirectoryTest {
	protected $pattern = '/acpmenu\.xml$/';
	protected $dictionary = array();
	
	protected function getBase() {
		return dirname(__FILE__).'/../../../de.easy-coding.wcf.contest/';
	}

	/**
	 *
	 */
	protected function setUp() {
		parent::setUp();
		$this->loadLanguageFiles();
	}
	
	protected function loadLanguageFiles() {
		// get packages
		$packages = array();
		$ite = new RecursiveDirectoryIterator(dirname(__FILE__).'/../../../');
		foreach (new RecursiveIteratorIterator($ite) as $filename => $cur) {
			if(!preg_match('/package\.xml$/', $filename)) {
				continue;
			}
			$packages[] = $filename;
		}
		
		// load config
		$files = array();
		foreach($packages as $filename) {
			$xml = simplexml_load_file($filename);
			$xml = $xml->xpath('//instructions[@type="install"]/languages');
			foreach($xml as $node) {
				$files[(string)$node['languagecode']][] = dirname($filename).'/'.(string)$node;
			}
		}
		
		// load files
		$this->dict = array();
		foreach($files as $languagecode => $data) {
			$tmp = array();
			foreach($data as $filename) {
				$xml = simplexml_load_file($filename);
				foreach($xml->category as $category) {
					foreach($category->item as $node) {
						$this->dict[$languagecode][] = (string)$node['name'];
					}
				}
			}
		}
	}
	
	public function testAll() {
		$missing = array();
		foreach($this->files as $filename) {
			$xml = simplexml_load_file($filename);
			foreach($xml->import->acpmenuitem as $x) {
				$attr = (string)$x->attributes()->name;
				foreach($this->dict as $languagecode => $dict) {
					if(!in_array($attr, $dict)) {
						$missing[$languagecode][] = $attr;
					}
				}
			}
		}
		
		if(count($missing)) {
			$str = "";
			foreach($missing as $languagecode => $list) {
				$str .= "missing language variables $languagecode\n";
				foreach($list as $var) {
					$str .= " * ".$var."\n";
				}
			}
			throw new Exception(rtrim($str));
		}
	}
}
?>
