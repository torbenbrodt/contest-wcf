<?php

/**
 * contest event model
 */
class ContestEventTest extends WCFModelTest {

	public function testCreate() {
		require_once(WCF_DIR.'lib/data/contest/event/ContestEventEditor.class.php');
		$this->deleteArray[] = $user = $this->createUser();
		$this->deleteArray[] = $event = ContestEventEditor::create(
			$contestID = 0,
			$userID = $user->userID,
			$groupID = 0,
			$eventName = __METHOD__.'.eventName'
		);
	
		// basic checks
		$this->assertType('ContestEvent', $event);
		$this->assertGreaterThan(0, $event->eventID);
		
		// owner check
		$this->assertFalse($event->isOwner());
		$this->setCurrentUser($user);
		$this->assertTrue($event->isOwner());
	}
	
	public function testReflectionAPI() {
		require_once(WCF_DIR.'lib/data/contest/event/ContestEventEditor.class.php');
		$this->deleteArray[] = $event = ContestEventEditor::create(
			$contestID = 0,
			$userID = 0,
			$groupID = 0,
			$eventName = __METHOD__.'.eventName'
		);
		
		$this->callAllMethodsWithoutRequiredParameters($event);
	}
}
?>
