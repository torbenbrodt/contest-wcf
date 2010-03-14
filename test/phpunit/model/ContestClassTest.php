<?php

/**
 * contest class model
 */
class ContestClassTest extends WCFModelTest {

	public function testCreate() {
		require_once(WCF_DIR.'lib/data/contest/class/ContestClassEditor.class.php');
		$this->deleteArray[] = $user = $this->createUser();
		$this->deleteArray[] = $class = ContestClassEditor::create(
			$title = __METHOD__.' title'
		);
	
		// basic checks
		$this->assertType('ContestClass', $class);
		$this->assertGreaterThan(0, $class->classID);
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
