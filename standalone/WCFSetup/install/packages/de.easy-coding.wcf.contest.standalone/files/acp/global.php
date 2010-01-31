<?php
// define paths
define('RELATIVE_CONTEST_DIR', '../');

//initialize package array
$packageDirs = array();

//include config
require_once(dirname(dirname(__FILE__)).'/config.inc.php');

//include WCF
require_once(RELATIVE_WCF_DIR.'global.php');
if(!count($packageDirs)) $packageDirs[] = CONTEST_DIR;
$packageDirs[] = WCF_DIR;

// starting acp
require_once(CONTEST_DIR.'lib/system/CONTESTACP.class.php');
new CONTESTACP();
?>