<?php

/**
 * contest price model
 */
class ContestPriceTest extends WCFModelTest {

	public function testCreate() {
		require_once(WCF_DIR.'lib/data/contest/price/ContestPriceEditor.class.php');
		require_once(WCF_DIR.'lib/data/contest/sponsor/ContestSponsorEditor.class.php');
		$this->deleteArray[] = $user = $this->createUser();
		$this->deleteArray[] = $sponsor = ContestSponsorEditor::create(
			$contestID = 0,
			$userID = $user->userID,
			$groupID = 0,
			$state = 'private'
		);
		$this->deleteArray[] = $price = ContestPriceEditor::create(
			$contestID = 0,
			$sponsorID = $sponsor->sponsorID,
			$subject = __METHOD__.' subject',
			$message = __METHOD__.' message'
		);
	
		// basic checks
		$this->assertType('ContestPrice', $price);
		$this->assertGreaterThan(0, $price->priceID);
		
		// owner check
		$this->assertFalse($price->isOwner());
		$this->setCurrentUser($user);
		$this->assertTrue($price->isOwner());
	}
	
	public function testReflectionAPI() {
		require_once(WCF_DIR.'lib/data/contest/price/ContestPriceEditor.class.php');
		require_once(WCF_DIR.'lib/data/contest/sponsor/ContestSponsorEditor.class.php');
		$this->deleteArray[] = $user = $this->createUser();
		$this->deleteArray[] = $sponsor = ContestSponsorEditor::create(
			$contestID = 0,
			$userID = $user->userID,
			$groupID = 0,
			$state = 'private'
		);
		$this->deleteArray[] = $price = ContestPriceEditor::create(
			$contestID = 0,
			$sponsorID = $sponsor->sponsorID,
			$subject = __METHOD__.' subject',
			$message = __METHOD__.' message'
		);
		
		$this->callAllMethodsWithoutRequiredParameters($price);
	}
}
?>
