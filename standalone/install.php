<?php
/**
 * This script tries to find the temp folder and unzip all setup files into.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2007 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
// define constants
define('INSTALL_SCRIPT_DIR', dirname(__FILE__).'/');
define('SETUP_FILE', INSTALL_SCRIPT_DIR . 'WCFSetup.tar.gz');
define('NO_IMPORTS', true);

// set exception handler
set_exception_handler('handleException');
// set php error handler
set_error_handler('handleError', E_ALL);

// define list of needed file
$neededFilesPattern = array(
	'!^setup/.*!',
	'!^install/files/lib/data/.*!',
	'!^install/files/lib/acp/package/PackageArchive\.class\.php!',
	'!^install/files/lib/acp/package/Package\.class\.php!',
	'!^install/files/lib/system/.*!',
	'!^install/files/lib/util/.*!',
	'!^install/files/acp/images/setup.*!',
	'!^install/lang/.*!',
	'!^install/packages/.*!');
	
// define needed functions and classes
/**
 * WCF::handleException() calls the show method on exceptions that implement this interface.
 * 
 * @package	com.woltlab.wcf.system.exception
 * @author	Marcel Werk
 */
interface PrintableException {
	public function show();
}

// define needed classes
// needed are:
// SystemException, PrintableException, BasicFileUtil, Tar, File, ZipFile
/**
 * A SystemException is thrown when an unexpected error occurs.
 * 
 * @package	com.woltlab.wcf.system.exception
 * @author	Marcel Werk
 */
class SystemException extends Exception implements PrintableException {
	protected $description;
	protected $information = '';
	protected $functions = '';
	
	/**
	 * Creates a new SystemException.
	 * 
	 * @param	message		string		error message
	 * @param	code		integer		error code
	 * @param	description	string		description of the error	
	 */
	public function __construct($message = '', $code = 0, $description = '') {
		parent::__construct($message, $code);
		$this->description = $description;
	}
	
	/**
	 * Returns the description of this exception.
	 * 
	 * @return 	string
	 */
	public function getDescription() {
		return $this->description;
	}
	
	/**
	 * Prints this exception.
	 * This method is called by WCF::handleException().
	 */
	public function show() {
		?>
<html>
<head>
<title>Fatal error: <?php echo htmlspecialchars($this->getMessage()); ?></title>
<style type="text/css">
body {
	font-family: Verdana, Helvetica, sans-serif;
	font-size: 0.8em;
}
div {
	border: 1px outset lightgrey;
	padding: 3px;
	background-color: lightgrey;
}

div div {
	border: 1px inset lightgrey;
	padding: 4px;
}

h1 {
	background-color: #154268;
	padding: 4px;
	color: #fff;
	margin: 0 0 3px 0;
	font-size: 1.15em;
}
h2 {
	font-size: 1.1em;
	margin-bottom: 0;
}

pre, p {
	margin: 0;
}
</style>
</head>
<body>
	<div>
		<h1>Fatal error: <?php echo htmlspecialchars($this->getMessage()); ?></h1>
	
		<div>
			<p><?php echo $this->getDescription(); ?></p>
			<?php if ($this->getCode()) { ?><p>You get more information about the problem in our knowledge base: <a href="http://www.woltlab.com/help/?code=<?php echo intval($this->getCode()); ?>">http://www.woltlab.com/help/?code=<?php echo intval($this->getCode()); ?></a></p><?php } ?>
			
			<h2>Information:</h2>
			<p>
				<b>error message:</b> <?php echo htmlspecialchars($this->getMessage()); ?><br />
				<b>error code:</b> <?php echo intval($this->getCode()); ?><br />
				<?php echo $this->information; ?>
				<b>file:</b> <?php echo htmlspecialchars($this->getFile()); ?> (<?php echo $this->getLine(); ?>)<br />
				<b>php version:</b> <?php echo htmlspecialchars(phpversion()); ?><br />
				<b>wcf version:</b> <?php if (defined('WCF_VERSION')) echo WCF_VERSION; ?><br />
				<b>date:</b> <?php echo gmdate('r'); ?><br />
				<b>request:</b> <?php if (isset($_SERVER['REQUEST_URI'])) echo htmlspecialchars($_SERVER['REQUEST_URI']); ?><br />
				<b>referer:</b> <?php if (isset($_SERVER['HTTP_REFERER'])) echo htmlspecialchars($_SERVER['HTTP_REFERER']); ?><br />
			</p>
			
			<h2>Stacktrace:</h2>
			<pre><?php echo htmlspecialchars($this->getTraceAsString()); ?></pre>
		</div>
		
		<?php echo $this->functions; ?>
	</div>
</body>
</html>

<?php
	}
}

/**
 * Loads the required classes automatically.
 */
function __autoload($className) {
	// test whether the file exist
	if (!file_exists(TMP_DIR . TMP_FILE_PREFIX . $className . '.class.php')) {
		throw new SystemException('Unable to find class ' . $className . ' in temp folder');
	}

	// include file
	require_once(TMP_DIR . TMP_FILE_PREFIX . $className . '.class.php');
}

/**
 * Escapes strings for execution in sql queries.
 */
function escapeString($string) {
	return WCF::getDB()->escapeString($string);	
}

/**
 * Calls the show method on the given exception.
 * 
 * @param	Exception	$e	
 */
function handleException(Exception $e) {
	if ($e instanceof PrintableException) {
		$e->show();
		exit;
	}
	
	print $e;
}

/**
 * Catches php errors and throws instead a system exception.
 * 
 * @param	integer		$errorNo
 * @param	string		$message
 * @param	string		$filename
 * @param	integer		$lineNo
 */
function handleError($errorNo, $message, $filename, $lineNo) { 
	if (error_reporting() != 0) {
		$type = 'error';
		switch ($errorNo) {
			case 2: $type = 'warning';
				break;
			case 8: $type = 'notice';
				break;
		}
		
		throw new SystemException('PHP '.$type.' in file '.$filename.' ('.$lineNo.'): '.$message, 0);
	}
}

/**
 * BasicFileUtil contains file-related functions.
 * 
 * @package 	com.woltlab.wcf.util
 * @author	Marcel Werk
 */
class BasicFileUtil {
	/**
	 * Tries to find the temp folder.
	 * 
	 * @return	string
	 */
	public static function getTempFolder() {
		// use tmp folder in document root by default
		if (!empty($_SERVER['DOCUMENT_ROOT'])) {
			if (strpos($_SERVER['DOCUMENT_ROOT'], 'strato') !== false) {
				// strato bugfix
				// create tmp folder in document root automatically
				if (!@file_exists($_SERVER['DOCUMENT_ROOT'].'/tmp')) { 
					@mkdir($_SERVER['DOCUMENT_ROOT'].'/tmp/', 0777);
					@chmod($_SERVER['DOCUMENT_ROOT'].'/tmp/', 0777);
				}
			}
			if (@file_exists($_SERVER['DOCUMENT_ROOT'].'/tmp') && @is_writable($_SERVER['DOCUMENT_ROOT'].'/tmp')) {
				return $_SERVER['DOCUMENT_ROOT'].'/tmp/';
			}
		}
		
		if (isset($_ENV['TMP']) && @is_writable($_ENV['TMP'])) {
			return $_ENV['TMP'] . '/';
		}
		if (isset($_ENV['TEMP']) && @is_writable($_ENV['TEMP'])) {
			return $_ENV['TEMP'] . '/';
		}
		if (isset($_ENV['TMPDIR']) && @is_writable($_ENV['TMPDIR'])) {
			return $_ENV['TMPDIR'] . '/';
		}
		
		// workaround for a bug in php 5.1.2 that returns true for is_writable('/tmp/') with safe_mode = on
		if (!preg_match('/^5\.1\.2(?![.0-9])/', phpversion())) {
			if (($path = ini_get('upload_tmp_dir')) && @is_writable($path)) {
				return $path . '/';
			}
			if (@file_exists('/tmp/') && @is_writable('/tmp/')) {
				return '/tmp/';
			}
			if (function_exists('session_save_path') && ($path = session_save_path()) && @is_writable($path)) {
				return $path . '/';
			}
		}
		
		$path = INSTALL_SCRIPT_DIR.'tmp/';
		if (@file_exists($path) && @is_writable($path)) {
			return $path;
		}
		else {
			if (ini_get('safe_mode')) $reason = "due to php safe_mode restrictions";
			else $reason = "due to an unknown reason";
			throw new SystemException('There is no access to the system temporary folder '.$reason.' and no user specific temporary folder exists in '.INSTALL_SCRIPT_DIR.'! This is a misconfiguration of your webserver software! Please create a folder called '.$path.' using your favourite ftp program, make it writable and then retry this installation.', 10000);
		}
	}
}

/**
 * Opens tar or tar.gz archives.
 * 
 * Usage:
 * ------
 * $tar = new Tar('archive.tar');
 * $contentList = $tar->getContentList();
 * foreach ($contentList as $key => $val) {
 * 	$tar->extract($key, DESTINATION);
 * }
 */
class Tar {
	protected $archiveName = '';
	protected $contentList = array();
	protected $opened = false;
	protected $read = false;
	protected $file = null;
	protected $isZipped = false;
	protected $mode = 'rb';
	
	/**
	 * Creates a new Tar object.
	 * archiveName must be tarball or gzipped tarball
	 * 
	 * @param 	string 		$archiveName
	 */
	public function __construct($archiveName) {
		$match = array();
		if (!is_file($archiveName)) {
			throw new SystemException("unable to find tar archive '".$archiveName."'", 11002);
		}
		
		$this->archiveName = $archiveName;
		$this->open();
		$this->readContent();
	}
	
	/**
	 * Destructor of this class, closes tar archive.
	 */
	public function __destruct() {
		$this->close();
	}
	
	/**
	 * Opens the tar archive and stores filehandle.
	 */
	public function open() {
		if (!$this->opened) {
			if ($this->isZipped) $this->file = new ZipFile($this->archiveName, $this->mode);
			else {
				// test compression
				$this->file = new File($this->archiveName, $this->mode);
				if ($this->file->read(2) == "\37\213") {
					$this->file->close();
					$this->isZipped = true;
					$this->file = new ZipFile($this->archiveName, $this->mode);
				}
				else {
					$this->file->seek(0);
				}
			}
			$this->opened = true;
		}
	}

	/**
	 * Closes the opened file.
	 */
	public function close() {
		if ($this->opened) {
			$this->file->close();
			$this->opened = false;
		}
	}
	
	/** 
	 * Returns the table of contents (TOC) list for this tar archive.
	 * 
	 * @return 	array 		list of content
	 */
	public function getContentList() {
		if (!$this->read) {
			$this->open();
			$this->readContent();
		}
		return $this->contentList;
	}
	
	/**
	 * Returns an associative array with information
	 * about a specific file in the archive.
	 *
	 * @param 	mixed 	$fileindex	index or name of the requested file
	 * @return 	array 	$fileInfo
	 */
	public function getFileInfo($fileIndex) {
		if (!is_int($fileIndex)) {
			$fileIndex = $this->getIndexByFilename($fileIndex);
		}
		
		if (!isset($this->contentList[$fileIndex])) {
			throw new SystemException("Tar: could find file '$index' in archive", 11013);
		}
		return $this->contentList[$fileIndex];
	}
	
	/**
	 * Searchs a file in the tar archive
	 * and returns the numeric fileindex.
	 * Returns false if not found.
	 *
	 * @param 	string 		$filename
	 * @return 	integer 			index of the requested file
	 */
	public function getIndexByFilename($filename) {
		foreach ($this->contentList as $index => $file) {
			if ($file['filename'] == $filename) {
				return $index;
			}
		}
		return false;
	}
	
	/**
	 * Extracts a specific file and returns the content as string.
	 * Returns false if extraction failed.
	 * 
	 * @param 	mixed 		$index		index or name of the requested file
	 * @return 	string 				content of the requested file
	 */
	public function extractToString($index) {
		if (!$this->read) {
			$this->open();
			$this->readContent();
		}
		$header = $this->getFileInfo($index);
		
		// can not extract a folder
		if ($header['type'] != 'file') {
			return false;
		}
		
		// seek to offset
		$this->file->seek($header['offset']);
		
		// read data
		$content = '';
		$n = floor($header['size'] / 512);
		for($i = 0; $i < $n; $i++) {
			$content .= $this->file->read(512);
		}
		if(($header['size'] % 512) != 0) {
			$buffer = $this->file->read(512);
			$content .= substr($buffer, 0, ($header['size'] % 512));
		}
		
		return $content;
	}
	
	/**
	 * Extracts a specific file and writes it's content
	 * to the file specified with $destination.
	 * 
	 * @param 	mixed 		$index		index or name of the requested file
	 * @param 	string 		$destination
	 * @return 	boolean 	$success
	 */
	public function extract($index, $destination) {
		if (!$this->read) {
			$this->open();
			$this->readContent();
		}
		$header = $this->getFileInfo($index);
		
		// can not extract a folder
		if ($header['type'] != 'file') {
			return false;
		}
		
		// seek to offset
		$this->file->seek($header['offset']);
		
		$targetFile = new File($destination);
		
		// read data
		$n = floor($header['size'] / 512);
		for ($i = 0; $i < $n; $i++) {
			$content = $this->file->read(512);
			$targetFile->write($content, 512);
		}
		if (($header['size'] % 512) != 0) {
			$content = $this->file->read(512);
			$targetFile->write($content, ($header['size'] % 512));
		}
		
		$targetFile->close();
		if (function_exists('apache_get_version') || !@$targetFile->is_writable()) {
			@$targetFile->chmod(0777);
		}
		else {
			@$targetFile->chmod(0755);
		}	
		
		if ($header['mtime']) {
			@$targetFile->touch($header['mtime']);
		}
		
		// check filesize
		if (filesize($destination) != $header['size']) {
			throw new SystemException("Could not untar file '".$header['filename']."' to '".$destination."'. Maybe disk quota exceeded in folder '".dirname($destination)."'.", 11015);
		}
		
		return true;
	}	
	
	/**
	 * Reads table of contents (TOC) from tar archive.
	 * This does not get the entire to memory but only parts of it.
	 */
	protected function readContent() {
		$this->contentList = array();
		$this->read = true;
		$i = 0;
		
		// Read the 512 bytes header
		while (strlen($binaryData = $this->file->read(512)) != 0) {
			// read header
			$header = $this->readHeader($binaryData);
			if ($header === false) {
				continue;	
			}
			$this->contentList[$i] = $header;
			$this->contentList[$i]['index'] = $i;
			$i++;
			
			$this->file->seek($this->file->tell() + (512 * ceil(($header['size'] / 512))));
		}
	}
	
	/**
	 * Unpacks file header for one file entry.
	 *
	 * @param 	string 		$binaryData
	 * @return 	array 		$fileheader
	 */
	protected function readHeader($binaryData) {
		if (strlen($binaryData) != 512) {
			return false;	
		}

		$header = array();
		$checksum = 0;
		// First part of the header
		for ($i = 0; $i < 148; $i++) {
			$checksum += ord(substr($binaryData, $i, 1));
		}
		// Calculate the checksum
		// Ignore the checksum value and replace it by ' ' (space)
		for ($i = 148; $i < 156; $i++) {
			$checksum += ord(' ');
		}
		// Last part of the header
		for ($i = 156; $i < 512; $i++) {
			$checksum += ord(substr($binaryData, $i, 1));
		}

		// Extract the values
		//$data = unpack("a100filename/a8mode/a8uid/a8gid/a12size/a12mtime/a8checksum/a1typeflag/a100link/a6magic/a2version/a32uname/a32gname/a8devmajor/a8devminor", $binaryData);
		$data = unpack("a100filename/a8mode/a8uid/a8gid/a12size/a12mtime/a8checksum/a1typeflag/a100link/a6magic/a2version/a32uname/a32gname/a8devmajor/a8devminor/a155prefix", $binaryData);
		
		// Extract the properties
		$header['checksum'] = octDec(trim($data['checksum']));
		if ($header['checksum'] == $checksum) {
			$header['filename'] = trim($data['filename']);
			$header['mode'] = octDec(trim($data['mode']));
			$header['uid'] = octDec(trim($data['uid']));
			$header['gid'] = octDec(trim($data['gid']));
			$header['size'] = octDec(trim($data['size']));
			$header['mtime'] = octDec(trim($data['mtime']));
			$header['prefix'] = trim($data['prefix']);
			if ($header['prefix']) {
				$header['filename'] = $header['prefix'].'/'.$header['filename'];
			}
			if (($header['typeflag'] = $data['typeflag']) == '5') {
				$header['size'] = 0;
				$header['type'] = 'folder';
			}
			else {
				$header['type'] = 'file';
			}
			$header['offset'] = $this->file->tell();
			
			return $header;
		}
		else {
			return false;
		}
	}
}

/**
 * The File class handles all file operations.
 * 
 * Example:
 * using php functions:
 * $fp = fopen('filename', 'wb');
 * fwrite($fp, '...');
 * fclose($fp);
 * 
 * using this class:
 * $file = new File('filename');
 * $file->write('...');
 * $file->close();
 * 
 * @author	Marcel Werk
 */
class File {
	protected $resource = null;
	protected $filename;
	
	/**
	 * Opens a new file.
	 * 
	 * @param 	string		$filename
	 * @param 	string		$mode
	 */
	public function __construct($filename, $mode = 'wb') {
		$this->filename = $filename;
		$this->resource = fopen($filename, $mode);
		if ($this->resource === false) {
			throw new SystemException('Can not open file ' . $filename, 11012);
		}
	}
	
	/**
	 * Calls the specified function on the open file.
	 * Do not call this function directly. Use $file->write('') instead.
	 * 
	 * @param 	string		$function
	 * @param 	array		$arguments
	 */
	public function __call($function, $arguments) {
		if (function_exists('f' . $function)) {
			array_unshift($arguments, $this->resource);
	       		return call_user_func_array('f' . $function, $arguments);
		}
		else if (function_exists($function)) {
			array_unshift($arguments, $this->filename);
	       		return call_user_func_array($function, $arguments);
		}
		else {
			throw new SystemException('Can not call file method ' . $function, 11003);
		}
	}
}

/**
 * The File class handles all file operations on a zipped file.
 *  
 * @author	Marcel Werk
 */
class ZipFile extends File {
	/**
	 * Opens a new zipped file.
	 * 
	 * @param  	string		$filename
	 * @param 	string		$mode
	 */
	public function __construct($filename, $mode = 'wb') {
		$this->filename = $filename;
		if (!function_exists('gzopen')) {
			throw new SystemException('Can not find functions of the zlib extension', 11004);
		}
		$this->resource = @gzopen($filename, $mode);
		if ($this->resource === false) {
			throw new SystemException('Can not open file ' . $filename, 11012);
		}
	}
	
	/**
	 * Calls the specified function on the open file.
	 * 
	 * @param 	string		$function
	 * @param 	array		$arguments
	 */
	public function __call($function, $arguments) {
		if (function_exists('gz' . $function)) {
			array_unshift($arguments, $this->resource);
	       		return call_user_func_array('gz' . $function, $arguments);
		}
		else if (function_exists($function)) {
			array_unshift($arguments, $this->filename);
	       		return call_user_func_array($function, $arguments);
		}
		else {
			throw new SystemException('Can not call method ' . $function, 11003);
		}
	}
	
	/**
	 * Returns the filesize of the unzipped file
	 */
	public function getFileSize() {
		$byteBlock = 1<<14;
		$eof = $byteBlock;
		
		// the correction is for zip files that are too small 
		// to get in the first while loop 
		$correction = 1;
		while ($this->seek($eof) == 0) {
			$eof += $byteBlock;
			$correction = 0;
		}
		
		while ($byteBlock > 1) {
			$byteBlock >>= 1;
			$eof += $byteBlock * ($this->seek($eof) ? -1 : 1);
		}
		
		if ($this->seek($eof) == -1) $eof -= 1;
		
		$this->rewind();
		return $eof - $correction;
	}
}

// let's go
// try to find the temp folder
define('TMP_DIR', BasicFileUtil::getTempFolder());

// get temp file prefix
if (isset($_REQUEST['tmpFilePrefix'])) {
	$prefix = preg_replace('/[^a-f0-9_]+/', '', $_REQUEST['tmpFilePrefix']);	
}
else {
	$prefix = substr(sha1(uniqid(microtime())), 0, 8) . '_';
}
define('TMP_FILE_PREFIX', $prefix);

// show image from temp folder
if (isset($_GET['showImage'])) {
	if (preg_match('~[\w\-]+\.(jpg|png)~', $_GET['showImage'], $match)) {
		if ($match[1] == 'jpg') header('Content-Type: image/jpg');
		else header('Content-Type: image/png');
		readfile(TMP_DIR . TMP_FILE_PREFIX . $_GET['showImage']);
	}
	exit;
}

// check whether setup files already unzipped
if (!file_exists(TMP_DIR . TMP_FILE_PREFIX . 'WCFSetup.class.php')) {
	// try to unzip all setup files into temp folder
	$tar = new Tar(SETUP_FILE);
	$contentList = $tar->getContentList();
	if (!count($contentList)) {
		throw new SystemException("Can not unpack 'WCFSetup.tar.gz'. File is probably broken.", 11016);
	}
	
	foreach ($contentList as $file) {
		// we don't need any folders
		if ($file['type'] != 'folder') {
			// unzip file if pattern match
			foreach ($neededFilesPattern as $pattern) {
				if (preg_match($pattern, $file['filename'])) {
					$tar->extract($file['index'], TMP_DIR . TMP_FILE_PREFIX . basename($file['filename']));
				}
			}
		}
	}
	$tar->close();
}

if (!class_exists('WCFSetup')) {
	throw new SystemException("Can not find class 'WCFSetup'", 11006);
}

// start setup
new WCFSetup();
?>
