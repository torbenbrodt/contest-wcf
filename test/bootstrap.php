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

$file = file(WCFTEST_STANDALONE_PATH.'config.inc.php');
$const = null;
foreach($file as $row) {
	if(preg_match('/define\(\'([A-Z]+_DIR)\'/', $row, $res)) {
		$const = $res[1];
	}
}

if(!$const) {
	throw new Exception('cannot find standalone installation #1');
}

// load wcf config
loadFile(WCFTEST_STANDALONE_PATH.'config.inc.php');

$standalone_dir = constant($const);
if(!$standalone_dir) {
	throw new Exception('cannot find standalone installation #2');
}

// load global file, including autoloader
loadFile($standalone_dir.RELATIVE_WCF_DIR.'global.php');
loadFile(WCFTEST_STANDALONE_PATH.'options.inc.php');
$GLOBALS['packageDirs'][] = WCF_DIR;

// base classes
require_once('WCFTest.php');
require_once('WCFModelTest.php');
require_once('WCFDirectoryTest.php');
require_once('WCFHTTPTest.php');
require_once('WCFNullWrapper.php');
?>
