<?php
// wcf imports
require_once(WCF_DIR.'lib/page/MultipleLinkPage.class.php');
require_once(WCF_DIR.'lib/data/contest/ViewableContest.class.php');
require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipantList.class.php');
require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
require_once(WCF_DIR.'lib/page/util/menu/ContestMenu.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestSidebar.class.php');

/**
 * show/edit participant entries
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestParticipantPage extends MultipleLinkPage {
	// system
	public $templateName = 'contestParticipant';
	
	// form
	public $ownerID = 0;
	
	/**
	 * entry id
	 *
	 * @var	integer
	 */
	public $contestID = 0;
	
	/**
	 * entry object
	 * 
	 * @var	ContestParticipant
	 */
	public $entry = null;
	
	/**
	 * list of participants
	 *
	 * @var ContestParticipantList
	 */
	public $participantList = null;
	
	/**
	 * 
	 * @var ContestParticipantTodoList
	 */
	public $todoList = null;
	
	/**
	 * action
	 * 
	 * @var	string
	 */
	public $action = '';
	
	/**
	 * contest sidebar
	 * 
	 * @var	ContestSidebar
	 */
	public $sidebar = null;
	
	/**
	 * @see Form::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get entry
		if (isset($_REQUEST['contestID'])) $this->contestID = intval($_REQUEST['contestID']);
		$this->entry = new ViewableContest($this->contestID);
		if (!$this->entry->contestID) {
			throw new IllegalLinkException();
		}
		
		// init participant list
		$this->participantList = new ContestParticipantList();
		$this->participantList->sqlConditions .= 'contest_participant.contestID = '.$this->contestID;
		$this->participantList->sqlOrderBy = 'contest_participant.time DESC';
	}
	
	/**
	 * @see Form::readData()
	 */
	public function readData() {
		parent::readData();
		
		// read objects
		$this->participantList->sqlOffset = ($this->pageNo - 1) * $this->itemsPerPage;
		$this->participantList->sqlLimit = $this->itemsPerPage;
		$this->participantList->readObjects();
		
		// init todo list
		require_once(WCF_DIR.'lib/data/contest/participant/todo/ContestParticipantTodoList.class.php');
		$this->todoList = new ContestParticipantTodoList();
		$this->todoList->sqlConditions .= 'contest_participant.contestID = '.intval($this->contestID);
		$this->todoList->readObjects();
		
		// init sidebar
		$this->sidebar = new ContestSidebar($this->entry, array(
			'participantList',
			'advertiseParticipant'
		));
	}
	
	/**
	 * @see MultipleLinkForm::countItems()
	 */
	public function countItems() {
		parent::countItems();
		
		return $this->participantList->countObjects();
	}
	
	/**
	 * @see Form::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		// display branding
		require_once(WCF_DIR.'lib/util/ContestUtil.class.php');
		ContestUtil::assignVariablesBranding();
		
		if($this->entry->isOwner()) {
			require_once(WCF_DIR.'lib/form/ContestParticipantInviteForm.class.php');
			new ContestParticipantInviteForm($this->entry);
		}
		
		// init form
		if ($this->action == 'edit') {
			require_once(WCF_DIR.'lib/form/ContestParticipantEditForm.class.php');
			new ContestParticipantEditForm($this->entry);
		}
		else if($this->entry->isParticipantable()) {
			require_once(WCF_DIR.'lib/form/ContestParticipantAddForm.class.php');
			new ContestParticipantAddForm($this->entry);
		}
		
		if($this->entry->enableParticipantCheck) {
			WCF::getTPL()->append('userMessages', '<p class="info">'.WCF::getLanguage()->get('wcf.contest.enableParticipantCheck.info').'</p>');
		}

		$this->sidebar->assignVariables();		
		WCF::getTPL()->assign(array(
			'entry' => $this->entry,
			'contestID' => $this->contestID,
			'userID' => $this->entry->userID,
			'participants' => $this->participantList->getObjects(),
			'todos' => $this->todoList ? $this->todoList->getObjects() : array(),
			'templateName' => $this->templateName,
			'allowSpidersToIndexThisForm' => true,
			
			'contestmenu' => ContestMenu::getInstance(),
		));
	}
	
	/**
	 * @see Form::show()
	 */
	public function show() {
		// set active header menu item
		PageMenu::setActiveMenuItem('wcf.header.menu.user.contest');
		
		// set active menu item
		ContestMenu::getInstance()->setContest($this->entry);
		ContestMenu::getInstance()->setActiveMenuItem('wcf.contest.menu.link.participant');
		
		// check permission
		WCF::getUser()->checkPermission('user.contest.canViewContest');
		
		if (!MODULE_CONTEST) {
			throw new IllegalLinkException();
		}
		
		parent::show();
	}
}
?>
