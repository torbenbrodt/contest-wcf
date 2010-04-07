<?php

/**
 * contest class model
 */
class ContestSolutionRatingTest extends WCFModelTest {

	public function testSolutionCounter() {
		require_once(WCF_DIR.'lib/data/contest/solution/ContestSolutionEditor.class.php');
		require_once(WCF_DIR.'lib/data/contest/solution/rating/ContestSolutionRatingEditor.class.php');
		require_once(WCF_DIR.'lib/data/contest/ContestEditor.class.php');
		$this->deleteArray[] = $contest = ContestEditor::create(
			$userID = 0,
			$groupID = 0,
			$subject = __METHOD__.' subject',
			$message = __METHOD__.' message',
			$options = array()
		);
		
		$this->deleteArray[] = $solution = ContestSolutionEditor::create(
			$contestID = $contest->contestID,
			$participantID = 0,
			$message = __METHOD__.' message',
			$state = 'accepted'
		);

		$this->deleteArray[] = $user = $this->createUser();
		$this->deleteArray[] = $rating = ContestSolutionRatingEditor::create(
			$solution->solutionID,
			$optionID = 0,
			$score = 3,
			$user->userID
		);
		
		// increase
		$solution = new ContestSolutionEditor($solution->solutionID);
		$this->assertEquals($solution->ratings, 1);

		// decrease
		$rating->delete();
		$solution = new ContestSolutionEditor($solution->solutionID);
		$this->assertEquals($solution->ratings, 0);
	}
	
	public function testReflectionAPI() {
		require_once(WCF_DIR.'lib/data/contest/class/ContestClassEditor.class.php');
		$this->deleteArray[] = $solution = ContestClassEditor::create(
			$title = __METHOD__.' title'
		);
		
		$this->callAllMethodsWithoutRequiredParameters($solution);
	}
}
?>
