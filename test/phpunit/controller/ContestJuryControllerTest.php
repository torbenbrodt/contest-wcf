<?php

/**
 * searches for all xml files and tries to parse xml
 */
class ContestJuryControllerTest extends WCFHTTPTest {
	protected $contest = null;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		parent::setUp();
		
		require_once(WCF_DIR.'lib/data/contest/ContestEditor.class.php');
		$this->deleteArray[] = $this->user = $this->createUser();
		$this->deleteArray[] = $this->contest = ContestEditor::create(
			$userID = $this->user->userID,
			$groupID = 0,
			$subject = __METHOD__.' subject',
			$message = __METHOD__.' message',
			$options = array()
		);
	}
	
	public function testContestPage() {
		$user = $this->user;
		$contest = $this->contest;

		$raised = false;
		try {
			$this->runHTTP('page=ContestJury&contestID='.$contest->contestID);
		} catch(Exception $e) {
			$raised = true;
		}
		$this->assertTrue($raised, "user should not be allowed to access a private contest");
		
		// now try with real user
		$this->setCurrentUser($user);
		$this->runHTTP('page=ContestJury&contestID='.$contest->contestID);
	}
}
?>
