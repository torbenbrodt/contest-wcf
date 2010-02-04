<?php
require_once(CONTEST_DIR.'lib/system/session/CONTESTSession.class.php');
require_once(CONTEST_DIR.'lib/data/user/CONTESTUserSession.class.php');
require_once(CONTEST_DIR.'lib/data/user/CONTESTGuestSession.class.php');
require_once(WCF_DIR.'lib/system/session/CookieSessionFactory.class.php');

class CONTESTSessionFactory extends CookieSessionFactory {
	protected $guestClassName = 'CONTESTGuestSession';
	protected $userClassName = 'CONTESTUserSession';
	protected $sessionClassName = 'CONTESTSession';
}
?>