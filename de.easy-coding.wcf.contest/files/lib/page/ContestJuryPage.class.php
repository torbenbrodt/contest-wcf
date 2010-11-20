<?php
// wcf imports
require_once(WCF_DIR.'lib/page/MultipleLinkPage.class.php');
require_once(WCF_DIR.'lib/data/contest/ViewableContest.class.php');
require_once(WCF_DIR.'lib/data/contest/jury/ContestJuryList.class.php');
require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
require_once(WCF_DIR.'lib/page/util/menu/ContestMenu.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestSidebar.class.php');

/**
 * show/edit jury entries
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestJuryPage extends MultipleLinkPage {
	// system
	public $templateName = 'contestJury';
	
	// form
	public $ownerID = 0;
	
	/**
	 * is jury?
	 * 
	 * @var	boolean
	 */
	public $isJury = false;
	
	/**
	 * entry id
	 *
	 * @var	integer
	 */
	public $contestID = 0;
	
	/**
	 * entry object
	 * 
	 * @var	ViewableContest
	 */
	public $entry = null;
	
	/**
	 * list of jurys
	 *
	 * @var ContestJuryList
	 */
	public $juryList = null;
	
	/**
	 * 
	 * @var ContestJuryTodoList
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
		
		// init jury list
		$this->juryList = new ContestJuryList();
		$this->juryList->sqlConditions .= 'contest_jury.contestID = '.intval($this->contestID);
		$this->juryList->sqlOrderBy = 'contest_jury.time DESC';
	}
	
	/**
	 * @see Form::readData()
	 */
	public function readData() {
		parent::readData();
		
		// read objects
		$this->juryList->sqlOffset = ($this->pageNo - 1) * $this->itemsPerPage;
		$this->juryList->sqlLimit = $this->itemsPerPage;
		$this->juryList->readObjects();
		
		$this->isSponsor = $this->entry->isSponsor();
		
		// init sidebar
		$this->sidebar = new ContestSidebar($this->entry, array(
			'juryList',
			'advertiseJury'
		));
	}
	
	/**
	 * @see MultipleLinkForm::countItems()
	 */
	public function countItems() {
		parent::countItems();
		
		return $this->juryList->countObjects();
	}
	
	/**
	 * @see Form::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		// display branding
		require_once(WCF_DIR.'lib/util/ContestUtil.class.php');
		ContestUtil::assignVariablesBranding();
		
		// save invitations
		if($this->entry->isOwner()) {
			require_once(WCF_DIR.'lib/form/ContestJuryInviteForm.class.php');
			new ContestJuryInviteForm($this->entry);
		}
		
		// init form
		if ($this->action == 'edit') {
			require_once(WCF_DIR.'lib/form/ContestJuryEditForm.class.php');
			new ContestJuryEditForm($this->entry);
		}
		else if($this->entry->isJuryable()) {
			require_once(WCF_DIR.'lib/form/ContestJuryAddForm.class.php');
			new ContestJuryAddForm($this->entry);
		}
		
		if($this->entry->state != 'closed') {
			WCF::getTPL()->append('userMessages', '<p class="info">'.WCF::getLanguage()->get('wcf.contest.jury.closed.info').'</p>');

			// init todo list
			require_once(WCF_DIR.'lib/data/contest/jury/todo/ContestJuryTodoList.class.php');
			$this->todoList = new ContestJuryTodoList();
			$this->todoList->sqlConditions .= 'contest_jury.contestID = '.$this->contestID;
			$this->todoList->readObjects();
		}

		$this->sidebar->assignVariables();		
		WCF::getTPL()->assign(array(
			'entry' => $this->entry,
			'isJury' => $this->isJury,
			'contestID' => $this->contestID,
			'userID' => $this->entry->userID,
			'jurys' => $this->juryList->getObjects(),
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
		ContestMenu::getInstance()->setActiveMenuItem('wcf.contest.menu.link.jury');
		
		// check permission
		WCF::getUser()->checkPermission('user.contest.canViewContest');
		
		if (!MODULE_CONTEST) {
			throw new IllegalLinkException();
		}
		
		parent::show();
	}
}
?>
