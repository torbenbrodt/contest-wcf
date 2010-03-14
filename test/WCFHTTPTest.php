<?php

class WCFHTTPTest extends WCFTest {
	
	/**
	 * this function will stop with a RuntimeException if woltlab script uses exit (e.g. for redirects)
	 *
	 * @param 	string		$path 		e.g. page=ContestOverview
	 */
	public function runHTTP($path, $userID = 0, $post = array()) {
		parse_str($path, $_GET);
		$dispatcher = 'index.php';
		
		// act to be a webserver
		$_SERVER['REQUEST_URI'] = parse_url(PAGE_URL, PHP_URL_PATH).'/'.$dispatcher.'?'.$path;
		$_SERVER['HTTP_HOST'] = parse_url(PAGE_URL, PHP_URL_HOST);
		$_POST = $post;
		$_REQUEST = array_merge($_GET, $_POST);
		
		$packageDirs = $GLOBALS['packageDirs'];
		try {
			ob_start();
			include($dispatcher);
			ob_end_clean();
		} catch(Exception $e) {
			throw new Exception($e->getMessage());
		}
	}	
}
?>
