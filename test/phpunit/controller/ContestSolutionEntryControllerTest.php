<?php

/**
 * searches for all xml files and tries to parse xml
 */
class ContestSolutionEntryControllerTest extends WCFHTTPTest {
	protected $contest = null;
	protected $participant = null;
	protected $solution = null;
	protected $user = null;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		parent::setUp();

		require_once(WCF_DIR.'lib/data/contest/solution/ContestSolutionEditor.class.php');
		require_once(WCF_DIR.'lib/data/contest/ContestEditor.class.php');
		require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipantEditor.class.php');
		$this->deleteArray[] = $this->contest = ContestEditor::create(
			$userID = 0,
			$groupID = 0,
			$subject = __METHOD__.' subject',
			$message = __METHOD__.' message',
			$options = array()
		);
		$this->deleteArray[] = $this->user = $this->createUser();
		$this->deleteArray[] = $this->participant = ContestParticipantEditor::create(
			$contestID = $this->contest->contestID,
			$userID = $this->user->userID,
			$groupID = 0,
			$state = 'accepted'
		);
		$this->deleteArray[] = $this->solution = ContestSolutionEditor::create(
			$contestID = $this->contest->contestID,
			$participantID = $this->participant->participantID,
			$message = __METHOD__.' message',
			$state = 'private'
		);
	}
	
	public function testContestPage() {return;
		$user = $this->user;
		$contest = $this->contest;
		$solution = $this->solution;

		$raised = false;
		try {
			$this->runHTTP('page=ContestSolutionEntry&contestID='.$contest->contestID.'&solutionID='.$solution->solutionID);
		} catch(Exception $e) {
			$raised = true;
		}
		$this->assertTrue($raised, "user should not be allowed to access a private contest");
		
		// now try with real user
		$this->setCurrentUser($user);
		$this->runHTTP('page=ContestSolutionEntry&contestID='.$contest->contestID.'&solutionID='.$solution->solutionID);
	}
}
?>
