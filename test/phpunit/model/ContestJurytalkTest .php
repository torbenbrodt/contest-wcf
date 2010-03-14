<?php

/**
 * contest jurytalk model
 */
class ContestJurytalkTest extends WCFModelTest {

	public function testCreate() {
		require_once(WCF_DIR.'lib/data/contest/jurytalk/ContestJurytalkEditor.class.php');
		$this->deleteArray[] = $user = $this->createUser();
		$this->deleteArray[] = $jurytalk = ContestJurytalkEditor::create(
			$contestID = 0,
			$jurytalk = __METHOD__.' jurytalk',
			$userID = $user->userID,
			$username = $user->username
		);
	
		// basic checks
		$this->assertType('ContestJurytalk', $jurytalk);
		$this->assertGreaterThan(0, $jurytalk->jurytalkID);
		
		// owner check
		$this->assertFalse($jurytalk->isOwner());
		$this->setCurrentUser($user);
		$this->assertTrue($jurytalk->isOwner());
	}
	
	public function testReflectionAPI() {
		require_once(WCF_DIR.'lib/data/contest/jurytalk/ContestJurytalkEditor.class.php');
		$this->deleteArray[] = $jurytalk = ContestJurytalkEditor::create(
			$contestID = 0,
			$jurytalk = __METHOD__.' jurytalk',
			$userID = 0,
			$username = __METHOD__.' username'
		);
		
		$this->callAllMethodsWithoutRequiredParameters($jurytalk);
	}
}
?>
