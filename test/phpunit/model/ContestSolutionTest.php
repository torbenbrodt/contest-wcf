<?php

/**
 * contest solution model
 */
class ContestSolutionTest extends WCFModelTest {

	public function testCreate() {
		require_once(WCF_DIR.'lib/data/contest/solution/ContestSolutionEditor.class.php');
		require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipantEditor.class.php');
		$this->deleteArray[] = $user = $this->createUser();
		$this->deleteArray[] = $participant = ContestParticipantEditor::create(
			$contestID = 0,
			$userID = $user->userID,
			$groupID = 0,
			$state = 'private'
		);
		$this->deleteArray[] = $solution = ContestSolutionEditor::create(
			$contestID = 0,
			$participantID = $participant->participantID,
			$message = __METHOD__.' message',
			$state = 'private'
		);
	
		// basic checks
		$this->assertType('ContestSolution', $solution);
		$this->assertGreaterThan(0, $solution->solutionID);
		
		// owner check
		$this->assertFalse($solution->isOwner());
		$this->setCurrentUser($user);
		$this->assertTrue($solution->isOwner());
	}
	
	public function testReflectionAPI() {
		require_once(WCF_DIR.'lib/data/contest/solution/ContestSolutionEditor.class.php');
		require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipantEditor.class.php');
		$this->deleteArray[] = $user = $this->createUser();
		$this->deleteArray[] = $participant = ContestParticipantEditor::create(
			$contestID = 0,
			$userID = $user->userID,
			$groupID = 0,
			$state = 'private'
		);
		$this->deleteArray[] = $solution = ContestSolutionEditor::create(
			$contestID = 0,
			$participantID = $participant->participantID,
			$message = __METHOD__.' message',
			$state = 'private'
		);
		
		$this->callAllMethodsWithoutRequiredParameters($solution);
	}
}
?>
