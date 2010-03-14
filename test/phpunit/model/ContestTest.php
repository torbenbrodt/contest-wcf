<?php

/**
 * contest model
 */
class ContestTest extends WCFModelTest {

	public function testCreate() {
		require_once(WCF_DIR.'lib/data/contest/ContestEditor.class.php');
		$this->deleteArray[] = $user = $this->createUser();
		$this->deleteArray[] = $contest = ContestEditor::create(
			$userID = $user->userID,
			$groupID = 0,
			$subject = __METHOD__.' subject',
			$message = __METHOD__.' message',
			$options = array()
		);
	
		// basic checks
		$this->assertType('Contest', $contest);
		$this->assertGreaterThan(0, $contest->contestID);
		
		// owner check
		$this->assertFalse($contest->isOwner());
		$this->setCurrentUser($user);
		$this->assertTrue($contest->isOwner());
	}
	
	public function testReflectionAPI() {
		require_once(WCF_DIR.'lib/data/contest/ContestEditor.class.php');
		$this->deleteArray[] = $contest = ContestEditor::create(
			$userID = 0,
			$groupID = 0,
			$subject = __METHOD__.' subject',
			$message = __METHOD__.' message',
			$options = array()
		);
		
		$this->callAllMethodsWithoutRequiredParameters($contest);
	}
}
?>
