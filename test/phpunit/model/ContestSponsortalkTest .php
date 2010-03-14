<?php

/**
 * contest sponsortalk model
 */
class ContestSponsortalkTest extends WCFModelTest {

	public function testCreate() {
		require_once(WCF_DIR.'lib/data/contest/sponsortalk/ContestSponsortalkEditor.class.php');
		$this->deleteArray[] = $user = $this->createUser();
		$this->deleteArray[] = $sponsortalk = ContestSponsortalkEditor::create(
			$contestID = 0,
			$sponsortalk = __METHOD__.' sponsortalk',
			$userID = $user->userID,
			$username = $user->username
		);
	
		// basic checks
		$this->assertType('ContestSponsortalk', $sponsortalk);
		$this->assertGreaterThan(0, $sponsortalk->sponsortalkID);
		
		// owner check
		$this->assertFalse($sponsortalk->isOwner());
		$this->setCurrentUser($user);
		$this->assertTrue($sponsortalk->isOwner());
	}
	
	public function testReflectionAPI() {
		require_once(WCF_DIR.'lib/data/contest/sponsortalk/ContestSponsortalkEditor.class.php');
		$this->deleteArray[] = $sponsortalk = ContestSponsortalkEditor::create(
			$contestID = 0,
			$sponsortalk = __METHOD__.' sponsortalk',
			$userID = 0,
			$username = __METHOD__.' username'
		);
		
		$this->callAllMethodsWithoutRequiredParameters($sponsortalk);
	}
}
?>
