<?php

/**
 * contest sponsor model
 */
class ContestSponsorTest extends WCFModelTest {

	public function testCreate() {
		require_once(WCF_DIR.'lib/data/contest/sponsor/ContestSponsorEditor.class.php');
		$this->deleteArray[] = $user = $this->createUser();
		$this->deleteArray[] = $sponsor = ContestSponsorEditor::create(
			$contestID = 0,
			$userID = $user->userID,
			$groupID = 0,
			$state = 'private'
		);
	
		// basic checks
		$this->assertType('ContestSponsor', $sponsor);
		$this->assertGreaterThan(0, $sponsor->sponsorID);
		
		// owner check
		$this->assertFalse($sponsor->isOwner());
		$this->setCurrentUser($user);
		$this->assertTrue($sponsor->isOwner());
	}
	
	public function testReflectionAPI() {
		require_once(WCF_DIR.'lib/data/contest/sponsor/ContestSponsorEditor.class.php');
		$this->deleteArray[] = $sponsor = ContestSponsorEditor::create(
			$contestID = 0,
			$userID = 0,
			$groupID = 0,
			$state = 'private'
		);
		
		$this->callAllMethodsWithoutRequiredParameters($sponsor);
	}
}
?>
