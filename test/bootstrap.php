<?php
require_once('config.php');

/**
 * loads config oder global files from wcf to append global packageDir array
 *
 * @param	string		$file		full path to file
 */
function loadFile($file) {
	$path = dirname($file);
	$returndir = getcwd();
	chdir($path);
	$foo = substr(substr(file_get_contents($file), 5), 0, -2);
	$foo = str_replace('$packageDirs', '$GLOBALS[\'packageDirs\']', $foo);
	$foo = str_replace('dirname(__FILE__)', '\''.dirname($file).'\'', $foo);
	$foo = str_replace('require_once', 'void', $foo);
	eval($foo);
	chdir($returndir);
}

function void($x) {
}

loadFile(WCFTEST_STANDALONE_PATH.'config.inc.php');
loadFile(WBB_DIR.RELATIVE_WCF_DIR.'global.php');
loadFile(WCFTEST_STANDALONE_PATH.'options.inc.php');
$GLOBALS['packageDirs'][] = WCF_DIR;

// base classes
require_once('WCFTest.php');
require_once('WCFModelTest.php');
require_once('WCFDirectoryTest.php');
require_once('WCFHTTPTest.php');
?>
