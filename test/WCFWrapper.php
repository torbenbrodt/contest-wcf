<?php

/**
 * used as a workaround to reload user object through WCF::getUser()
 */
class WCFWrapper extends WCF {

	/**
	 * this is a useful helper method for unittests
	 * @param	integer		$userID
	 * @return 	void
	 */
	public static function setUser($userID) {
		require_once(WCF_DIR.'lib/system/session/UserSession.class.php');
		self::$userObj = new WCFNullwrapper(new UserSession($userID));
	}

}
?>
