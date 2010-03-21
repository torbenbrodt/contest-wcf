<?php

/**
 * searches for all xml files and tries to parse xml
 */
class ContestControllerTest extends WCFHTTPTest {
	
	public function testPage() {
		require_once(WCF_DIR.'lib/data/contest/ContestEditor.class.php');
		$this->deleteArray[] = $user = $this->createUser();
		$this->deleteArray[] = $contest = ContestEditor::create(
			$userID = $user->userID,
			$groupID = 0,
			$subject = __METHOD__.' subject',
			$message = __METHOD__.' message',
			$options = array()
		);

		$raised = false;
		try {
			$this->runHTTP('page=Contest&contestID='.$contest->contestID);
		} catch(Exception $e) {
			$raised = true;
		}
		$this->assertTrue($raised, "user should not be allowed to access a private contest");
		
		// now try with real user
		$this->setCurrentUser($user);
		$this->runHTTP('page=Contest&contestID='.$contest->contestID);
	}
}
?>
