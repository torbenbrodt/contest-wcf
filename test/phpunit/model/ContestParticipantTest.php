<?php

/**
 * contest participant model
 */
class ContestParticipantTest extends WCFModelTest {

	public function testCreate() {
		require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipantEditor.class.php');
		$this->deleteArray[] = $user = $this->createUser();
		$this->deleteArray[] = $participant = ContestParticipantEditor::create(
			$contestID = 0,
			$userID = $user->userID,
			$groupID = 0,
			$state = 'private'
		);
	
		// basic checks
		$this->assertType('ContestParticipant', $participant);
		$this->assertGreaterThan(0, $participant->participantID);
		
		// owner check
		$this->assertFalse($participant->isOwner());
		$this->setCurrentUser($user);
		$this->assertTrue($participant->isOwner());
	}
	
	public function testReflectionAPI() {
		require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipantEditor.class.php');
		$this->deleteArray[] = $participant = ContestParticipantEditor::create(
			$contestID = 0,
			$userID = 0,
			$groupID = 0,
			$state = 'private'
		);
		
		$this->callAllMethodsWithoutRequiredParameters($participant);
	}
}
?>
