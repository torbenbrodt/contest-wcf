<?php

class WCFTest extends PHPUnit_Framework_TestCase {

	/**
	 * in setUp method we will jummp to wbb directory - in tearDown method we can jump back to returndir
	 * @var string
	 */
	protected $returndir = '';

	/**
	 * for model tests
	 * 
	 * @var	array<DatabaseObject>
	 */
	protected $deleteArray = array();

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
	 *
	 */
	protected function createGroup($groupOptions = array()) {
		require_once(WCF_DIR.'lib/util/StringUtil.class.php');
		require_once(WCF_DIR.'lib/data/user/group/GroupEditor.class.php');
		
		$group = GroupEditor::create(
			$groupName = StringUtil::getRandomID(),
			$groupOptions
		);
		
		// WCF does not clear instance caches... so rebuild
		$classFile = WCF_DIR.'lib/system/cache/CacheBuilderGroups.class.php';
		WCF::getCache()->rebuild(array(
			'cache' => 'groups',
			'file' => WCF_DIR.'cache/cache.groups.php', 
			'className' => StringUtil::getClassName($classFile), 
			'classFile' => $classFile
		));
		
		return $group;
	}
	
	/**
	 * set the current session user
	 *
	 * @param	User		$user
	 */
	protected function setCurrentUser(User $user) {
		if(!class_exists('WCFWrapper')) {
			require_once('WCFWrapper.php');
		}
		WCFWrapper::setUser($user->userID);
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
		foreach($this->deleteArray as $delete) {
			if(class_exists('UserEditor') && $delete instanceof User) {
				UserEditor::deleteUsers(array($delete->userID));
			} else if (class_exists('GroupEditor') && $delete instanceof Group) {
				GroupEditor::deleteGroups(array($delete->groupID));
			} else {
				$delete->delete();
			}
		}

		chdir($this->returndir);
	}
}
?>
