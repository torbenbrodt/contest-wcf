<?php

/**
 * contest solution model
 */
class ContestSolutionTest extends WCFModelTest {

	public function testIsRateable() {
		require_once(WCF_DIR.'lib/data/contest/solution/ContestSolutionEditor.class.php');
		require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipantEditor.class.php');
		
		$this->deleteArray[] = $group = $this->createGroup();
		$group2 = new Group($group->groupID);
		$this->assertEquals($group->groupID, $group2->groupID);
		
		$this->deleteArray[] = $user = $this->createUser(array($group->groupID));
		$this->deleteArray[] = $participant = ContestParticipantEditor::create(
			$contestID = 0,
			$userID = 0,
			$groupID = $group->groupID,
			$state = 'private'
		);
		$this->deleteArray[] = $solution = ContestSolutionEditor::create(
			$contestID = 0,
			$participantID = $participant->participantID,
			$message = __METHOD__.' message',
			$state = 'private'
		);
		
		// solution should not be rateable by active user/group
		$this->setCurrentUser($user);
		$this->assertFalse($solution->isRateable());
		
		// but should be ratable by any registered user (not just jury)
		$this->deleteArray[] = $user2 = $this->createUser();
		$this->setCurrentUser($user2);
		$this->assertTrue($solution->isRateable());
	}

	public function testCreate() {
		require_once(WCF_DIR.'lib/data/contest/solution/ContestSolutionEditor.class.php');
		require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipantEditor.class.php');
		require_once(WCF_DIR.'lib/data/contest/ContestEditor.class.php');
		$this->deleteArray[] = $contest = ContestEditor::create(
			$userID = 0,
			$groupID = 0,
			$subject = __METHOD__.' subject',
			$message = __METHOD__.' message',
			$options = array()
		);
		$this->deleteArray[] = $user = $this->createUser();
		$this->deleteArray[] = $participant = ContestParticipantEditor::create(
			$contestID = $contest->contestID,
			$userID = $user->userID,
			$groupID = 0,
			$state = 'accepted'
		);
		$this->deleteArray[] = $solution = ContestSolutionEditor::create(
			$contestID = $contest->contestID,
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
		
		// solution owner should have task to publish his private solution
		require_once(WCF_DIR.'lib/data/contest/participant/todo/ContestParticipantTodoList.class.php');
		$todo = new ContestParticipantTodoList();
		$todo->sqlConditions .= 'contest_participant.contestID = '.intval($contest->contestID);
		$todo->readObjects();
		$task = array_pop($todo->getObjects());
		$this->assertEquals($task->action, 'participant.solution.private');
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
