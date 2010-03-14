<?php

class WCFHTTPTest extends WCFTest {
	
	/**
	 * this function will stop with a RuntimeException if woltlab script uses exit (e.g. for redirects)
	 *
	 * @param 	string		$path 		e.g. page=ContestOverview
	 */
	public function runHTTP($path, $userID = 0, $post = array()) {
		parse_str($path, $_GET);
		$_SERVER['REQUEST_URI'] = '/wbb/index.php?'.$path; // TODO: get via global.php
		$_SERVER['HTTP_HOST'] = 'localhost'; // TODO: get via global.php
		$_POST = $post;
		$_REQUEST = array_merge($_GET, $_POST);
		
		$packageDirs = $GLOBALS['packageDirs'];
		
		try {
			ob_start();
			include('index.php');
			ob_end_clean();
		} catch(Exception $e) {
			throw new Exception($e->getMessage());
		}
	}	
}
?>
