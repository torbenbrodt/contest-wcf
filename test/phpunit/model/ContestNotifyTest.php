<?php

/**
 * contest notification test
 */
class ContestNotifyTest extends WCFModelTest {
	
	protected function setUp() {
		parent::setUp();
		
		WCF::getDB()->sendQuery('TRUNCATE TABLE wcf'.WCF_N.'_user_notification_event_to_user');
		WCF::getDB()->sendQuery('TRUNCATE TABLE wcf'.WCF_N.'_user_notification_message');
		WCF::getDB()->sendQuery('TRUNCATE TABLE wcf'.WCF_N.'_user_notification');
	}

	/**
	 * 
	 */
	public function testCreate() {
		require_once(WCF_DIR.'lib/data/contest/crew/ContestCrew.class.php');
		
		// create group
		$this->deleteArray[] = $group = $this->createGroup(array(
			array(
				'optionID' => ContestCrew::getOptionID(),
				'optionValue' => true
			)
		));
		$this->deleteArray[] = $user = $this->createUser($group->groupID);
	
		require_once(WCF_DIR.'lib/data/contest/ContestEditor.class.php');
		$this->deleteArray[] = $user = $this->createUser();
		$this->deleteArray[] = $contest = ContestEditor::create(
			$userID = $user->userID,
			$groupID = 0,
			$subject = __METHOD__.' subject',
			$message = __METHOD__.' message',
			$options = array(),
			$state = 'applied'
		);
		
		require_once(WCF_DIR.'lib/data/contest/jury/ContestJuryEditor.class.php');
		$this->deleteArray[] = $jury = ContestJuryEditor::create(
			$contestID = 0,
			$userID = $user->userID,
			$groupID = 0,
			$state = 'private'
		);
	}
}
?>
