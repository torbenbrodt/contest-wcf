<?php

/**
 * searches for all xml files and tries to parse xml
 */
class ContestJurytalkControllerTest extends WCFHTTPTest {
	protected $user = null;
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
		
		// save two jurytalk entries
		require_once(WCF_DIR.'lib/data/contest/jurytalk/ContestJurytalkEditor.class.php');
		$this->deleteArray[] = $jurytalk = ContestJurytalkEditor::create(
			$contestID = $contest->contestID,
			$jurytalk = __METHOD__.' jurytalk #1',
			$userID = $user->userID,
			$username = $user->username
		);
		$this->deleteArray[] = $jurytalk = ContestJurytalkEditor::create(
			$contestID = $contest->contestID,
			$jurytalk = __METHOD__.' jurytalk #2',
			$userID = $user->userID,
			$username = $user->username
		);

		$raised = false;
		try {
			$this->runHTTP('page=ContestJurytalk&contestID='.$contest->contestID);
		} catch(Exception $e) {
			$raised = true;
		}
		$this->assertTrue($raised, "user should not be allowed to access a private contest");
		
		// now try with owner
		$this->setCurrentUser($user);
		$this->runHTTP('page=ContestJurytalk&contestID='.$contest->contestID);
		$this->assertEquals(count(WCF::getTPL()->get('jurytalks')), 2);
		
		// now try with jury member who was invited
		$this->deleteArray[] = $juryuser = $this->createUser();
		
		require_once(WCF_DIR.'lib/data/contest/jury/ContestJuryEditor.class.php');
		$this->deleteArray[] = $jury = ContestJuryEditor::create(
			$contestID = $contest->contestID,
			$userID = $juryuser->userID,
			$groupID = 0,
			$state = 'invited'
		);
		
		$this->setCurrentUser($juryuser);
		$this->runHTTP('page=ContestJurytalk&contestID='.$contest->contestID);
		
		// invited members should only see first entry
		$this->assertEquals(count(WCF::getTPL()->get('jurytalks')), 1);
		
		// accepted members should have 2 entries
		$jury->update($contestID, $userID, $groupID, 'accepted');
		$this->runHTTP('page=ContestJurytalk&contestID='.$contest->contestID);
		$this->assertEquals(count(WCF::getTPL()->get('jurytalks')), 2);
	}
}
?>
