<?php

/**
 * searchs for all template files and checks if language data is complete
 */
class LanguageXMLTest extends WCFDirectoryTest {
	protected $pattern = '/\.tpl$/';
	protected $prefix = 'wcf.contest';
	protected $dictionary = array();

	/**
	 *
	 */
	protected function setUp() {
		parent::setUp();
		
		$this->loadFiles();
	}
	
	public function testAll() {
		$i = 0;
		$missing = array();
		foreach($this->files as $filename) {
			$content = file_get_contents($filename);
			if(preg_match_all('/\{lang\}(.+)\{\/lang\}/U', $content, $matches)) {
				$matches = $matches[1];
				$matches = array_filter($matches, array($this, "filterPrefix"));
				$matches = array_filter($matches, array($this, "filterVariables"));
				foreach($this->dict as $languagecode => $list) {
					foreach(array_diff($matches, $list) as $diff) {
						$missing[$languagecode][$diff][] = basename($filename);
					}
				}
			}
		}
		if(count($missing)) {
			$str = "";
			foreach($missing as $languagecode => $list) {
				$str .= "missing language variables $languagecode\n";
				foreach($list as $var => $list) {
					$str .= " * ".$var."\n";
					foreach($list as $filename) {
						$str .= "  * ".$filename."\n";
					}
					$str .= "\n";
				}
			}
			throw new Exception(rtrim($str));
		}
	}
	
	protected function loadFiles() {
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
	
	protected function filterPrefix($val) {
		$pos = strpos($val, $this->prefix);
		return $pos !== false && $pos == 0;
	}
	
	protected function filterVariables($val) {
		$pos = strpos($val, '{');
		return $pos === false;
	}
}
?>
