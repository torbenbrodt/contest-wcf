<?php

/**
 * searches for all xml files and tries to parse xml
 */
class ContestSponsortalkControllerTest extends WCFHTTPTest {
	
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
		
		require_once(WCF_DIR.'lib/data/contest/sponsortalk/ContestSponsortalkEditor.class.php');
		$entry1 = ContestSponsortalkEditor::create($contest->contestID, __METHOD__.' message', 
			WCF::getUser()->userID, WCF::getUser()->username);
		$entry2 = ContestSponsortalkEditor::create($contest->contestID, __METHOD__.' message', 
			WCF::getUser()->userID, WCF::getUser()->username);
		
		// now try with real user
		$this->setCurrentUser($user);
		$this->runHTTP('page=ContestSponsortalk&contestID='.$contest->contestID);
		
		// try with another user
		$this->deleteArray[] = $user1 = $this->createUser();
		$this->setCurrentUser($user1);
		$raised = false;
		try {
			$this->runHTTP('page=ContestSponsortalk&contestID='.$contest->contestID);
		} catch(Exception $e) {
			$raised = true;
		}
		$this->assertTrue($raised, "user should not be allowed to access sponsortalk");
		
		// try with a applied sponsor
		require_once(WCF_DIR.'lib/data/contest/sponsor/ContestSponsorEditor.class.php');
		$this->deleteArray[] = $user2 = $this->createUser();
		$this->deleteArray[] = $sponsor = ContestSponsorEditor::create(
			$contestID = $contest->contestID,
			$userID = $user2->userID,
			$groupID = 0,
			$state = 'applied'
		);
		$this->setCurrentUser($user2);
		try {
			$this->runHTTP('page=ContestSponsortalk&contestID='.$contest->contestID);
		} catch(Exception $e) {
			$raised = true;
		}
		
		// try with a real sponsor
		require_once(WCF_DIR.'lib/data/contest/sponsor/ContestSponsorEditor.class.php');
		$this->deleteArray[] = $user3 = $this->createUser();
		$this->deleteArray[] = $sponsor = ContestSponsorEditor::create(
			$contestID = $contest->contestID,
			$userID = $user3->userID,
			$groupID = 0,
			$state = 'accepted'
		);
		$this->setCurrentUser($user3);
		$this->runHTTP('page=ContestSponsortalk&contestID='.$contest->contestID);
	}
}
?>
