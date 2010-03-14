<?php

/**
 * contest jury model
 */
class ContestJuryTest extends WCFModelTest {

	public function testCreate() {
		require_once(WCF_DIR.'lib/data/contest/jury/ContestJuryEditor.class.php');
		$this->deleteArray[] = $user = $this->createUser();
		$this->deleteArray[] = $jury = ContestJuryEditor::create(
			$contestID = 0,
			$userID = $user->userID,
			$groupID = 0,
			$state = 'private'
		);
	
		// basic checks
		$this->assertType('ContestJury', $jury);
		$this->assertGreaterThan(0, $jury->juryID);
		
		// owner check
		$this->assertFalse($jury->isOwner());
		$this->setCurrentUser($user);
		$this->assertTrue($jury->isOwner());
	}
	
	public function testReflectionAPI() {
		require_once(WCF_DIR.'lib/data/contest/jury/ContestJuryEditor.class.php');
		$this->deleteArray[] = $jury = ContestJuryEditor::create(
			$contestID = 0,
			$userID = 0,
			$groupID = 0,
			$state = 'private'
		);
		
		$this->callAllMethodsWithoutRequiredParameters($jury);
	}
}
?>
