<?php

/**
 * contest model
 */
class ContestTest extends WCFModelTest {
	protected $contest = null;
	protected $user = null;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		parent::setUp();
		
		require_once(WCF_DIR.'lib/data/contest/ContestEditor.class.php');
		$this->deleteArray[] = $this->user = $this->createUser();
		$this->deleteArray[] = $this->contest = ContestEditor::create(
			$userID = $this->user->userID,
			$groupID = 0,
			$subject = __METHOD__.' subject',
			$message = __METHOD__.' message',
			$options = array()
		);
	}

	public function testBasic() {
		$user = $this->user;
		$contest = $this->contest;
		
		// basic checks
		$this->assertType('Contest', $contest);
		$this->assertGreaterThan(0, $contest->contestID);
		
		// owner check
		$this->assertFalse($contest->isOwner());
		$this->setCurrentUser($user);
		$this->assertTrue($contest->isOwner());
	}

	/**
	 * when entry is private, there should be a todo, to publish the contest
	 */
	public function testTodoOwner() {
		$user = $this->user;
		$contest = $this->contest;
		
		$this->assertEquals('private', $contest->state);
		$this->setCurrentUser($user);
		
		require_once(WCF_DIR.'lib/data/contest/owner/todo/ContestOwnerTodoList.class.php');
		$todo = new ContestOwnerTodoList();
		$todo->sqlConditions .= 'contest.contestID = '.$contest->contestID;
		$todo->readObjects();
		$task = array_pop($todo->getObjects());
		$this->assertEquals($task->action, 'owner.contest.private');
	}

	/**
	 * when entry is applied, there should be a todo for the contest crew to check it
	 */
	public function testTodoCrew() {
		require_once(WCF_DIR.'lib/data/contest/crew/ContestCrew.class.php');
		
		// create group
		$this->deleteArray[] = $group = $this->createGroup(array(
			array(
				'optionID' => ContestCrew::getOptionID(),
				'optionValue' => true
			)
		));
		$this->deleteArray[] = $user = $this->createUser($group->groupID);
		$this->setCurrentUser($user);
		
		$this->contest->update($userID = 0, $groupID = 0, $subject = '', $message = '', $fromTime = '', $untilTime = '', $state = 'applied');
		
		require_once(WCF_DIR.'lib/data/contest/crew/todo/ContestCrewTodoList.class.php');
		$todo = new ContestCrewTodoList();
		$todo->readObjects();
		$task = array_pop($todo->getObjects());
		$this->assertEquals($task->action, 'crew.contest.applied');
	}
	
	public function testReflectionAPI() {
		$this->callAllMethodsWithoutRequiredParameters($this->contest);
	}
}
?>
