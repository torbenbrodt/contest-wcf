<?php

class WCFTest extends PHPUnit_Framework_TestCase {

	/**
	 * in setUp method we will jummp to wbb directory - in tearDown method we can jump back to returndir
	 * @var string
	 */
	protected $returndir = '';

	/**
	 *
	 */
	protected function createUser($groupIDs = array()) {
		require_once(WCF_DIR.'lib/util/StringUtil.class.php');
		require_once(WCF_DIR.'lib/data/user/UserEditor.class.php');
		
		return UserEditor::create(
			$username = StringUtil::getRandomID(), 
			$email = StringUtil::getRandomID(), 
			$password = StringUtil::getRandomID(), 
			$groupIDs
		);
	}
	
	/**
	 * set the current session user
	 *
	 * @param	User		$user
	 */
	protected function setCurrentUser(User $user) {
		$keys = array('userID', 'username', 'email', 'password', 'salt');
		foreach($keys as $key) {
			WCF::getUser()->$key = $user->$key;
		}
	}

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		try {
			$this->returndir = getcwd();
		
			$packageDirs = $GLOBALS['packageDirs'];
			chdir($packageDirs[0]);
		
			require_once('./global.php');
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
}
?>
