<?php

/**
 * contest comment model
 */
class ContestCommentTest extends WCFModelTest {

	public function testCreate() {
		require_once(WCF_DIR.'lib/data/contest/comment/ContestCommentEditor.class.php');
		$this->deleteArray[] = $user = $this->createUser();
		$this->deleteArray[] = $comment = ContestCommentEditor::create(
			$contestID = 0,
			$comment = __METHOD__.' comment',
			$userID = $user->userID,
			$username = $user->username
		);
	
		// basic checks
		$this->assertType('ContestComment', $comment);
		$this->assertGreaterThan(0, $comment->commentID);
		
		// owner check
		$this->assertFalse($comment->isOwner());
		$this->setCurrentUser($user);
		$this->assertTrue($comment->isOwner());
	}
	
	public function testReflectionAPI() {
		require_once(WCF_DIR.'lib/data/contest/comment/ContestCommentEditor.class.php');
		$this->deleteArray[] = $comment = ContestCommentEditor::create(
			$contestID = 0,
			$comment = __METHOD__.' comment',
			$userID = 0,
			$username = __METHOD__.' username'
		);
		
		$this->callAllMethodsWithoutRequiredParameters($comment);
	}
}
?>
