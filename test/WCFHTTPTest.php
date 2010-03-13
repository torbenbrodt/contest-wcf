<?php

class WCFHTTPTest extends WCFTest {

	/**
	 * in setUp method we will jummp to wbb directory - in tearDown method we can jump back to returndir
	 * @var string
	 */
	private $returndir = '';
	protected $packagedirs = array(WCFHTTP_PACKAGEDIR_STANDALONE, WCFHTTP_PACKAGEDIR_WCF);

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		try {
			$this->returndir = getcwd();
		
			$GLOBALS['packageDirs'] = $packageDirs = $this->packagedirs;
			chdir($packageDirs[0]);
		
			ob_start();
			require_once('./global.php');
			ob_end_clean();
		} catch(Exception $e) {
			throw new Exception('WCFHTTPTest basesystem could not be initialized');
		}
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
		chdir($this->returndir);
	}
	
	public function runHTTP($url, $userID = 0, $post = array()) {
		parse_str($url, $_GET);
		$_SERVER['REQUEST_URI'] = $url;
		$_SERVER['HTTP_HOST'] = 'localhost'; // TODO: get via global.php
		$_POST = $post;
		$_REQUEST = array_merge($_GET, $_POST);
		
		$GLOBALS['packageDirs'] = $packageDirs = $this->packagedirs;
		try {
			include('index.php');
		
		} catch(Exception $e) {
			var_dump($e);
		}
	}	
}
?>
