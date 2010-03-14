<?php

/**
 * contest class model
 */
class ContestClassTest extends WCFModelTest {

	public function testCreate() {
		require_once(WCF_DIR.'lib/data/contest/class/ContestClassEditor.class.php');
		$this->deleteArray[] = $class = ContestClassEditor::create(
			$title = __METHOD__.' title'
		);
	
		// basic checks
		$this->assertType('ContestClass', $class);
		$this->assertGreaterThan(0, $class->classID);
	}

	public function testContestsCounter() {
		require_once(WCF_DIR.'lib/data/contest/class/ContestClassEditor.class.php');
		$this->deleteArray[] = $class = ContestClassEditor::create(
			$title = __METHOD__.' title'
		);
		
		$this->assertEquals($class->contests, 0);
		
		require_once(WCF_DIR.'lib/data/contest/ContestEditor.class.php');
		$this->deleteArray[] = $contest = ContestEditor::create(
			$userID = 0,
			$groupID = 0,
			$subject = __METHOD__.' subject',
			$message = __METHOD__.' message',
			$options = array(),
			$classIDArray = array($class->classID)
		);
		
		// increase
		$class = new ContestClass($class->classID);
		$this->assertEquals($class->contests, 1);

		// decrease
		$contest->delete();
		$class = new ContestClass($class->classID);
		$this->assertEquals($class->contests, 0);
	}
	
	public function testReflectionAPI() {
		require_once(WCF_DIR.'lib/data/contest/class/ContestClassEditor.class.php');
		$this->deleteArray[] = $class = ContestClassEditor::create(
			$title = __METHOD__.' title'
		);
		
		$this->callAllMethodsWithoutRequiredParameters($class);
	}
}
?>
