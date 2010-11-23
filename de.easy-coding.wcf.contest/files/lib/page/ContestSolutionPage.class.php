<?php
// wcf imports
require_once(WCF_DIR.'lib/page/MultipleLinkPage.class.php');
require_once(WCF_DIR.'lib/data/contest/ViewableContest.class.php');
require_once(WCF_DIR.'lib/data/contest/solution/ContestSolutionList.class.php');
require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
require_once(WCF_DIR.'lib/page/util/menu/ContestMenu.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestSidebar.class.php');

/**
 * show/edit solution entries
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSolutionPage extends MultipleLinkPage {
	// system
	public $templateName = 'contestSolution';
	
	/**
	 * entry id
	 *
	 * @var	integer
	 */
	public $contestID = 0;
	
	/**
	 * entry object
	 * 
	 * @var	ContestSolution
	 */
	public $entry = null;
	
	/**
	 * list of solutions
	 *
	 * @var ContestSolutionList
	 */
	public $solutionList = null;
	
	/**
	 * 
	 * @var ContestParticipantTodoList
	 */
	public $todoList = null;
	
	/**
	 * solution id
	 * 
	 * @var	integer
	 */
	public $solutionID = 0;
	
	/**
	 * solution object
	 * 
	 * @var	ContestSolution
	 */
	public $solution = null;
	
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
		
		// init solution list
		$this->solutionList = new ContestSolutionList();
		$this->solutionList->sqlConditions .= 'contest_solution.contestID = '.$this->contestID;
	}
	
	/**
	 * @see Form::readData()
	 */
	public function readData() {
		parent::readData();
		
		// read objects
		$this->solutionList->sqlOffset = ($this->pageNo - 1) * $this->itemsPerPage;
		$this->solutionList->sqlLimit = $this->itemsPerPage;
		$this->solutionList->readObjects();
		
		// init todo list
		require_once(WCF_DIR.'lib/data/contest/participant/todo/ContestParticipantTodoList.class.php');
		$this->todoList = new ContestParticipantTodoList();
		$this->todoList->sqlConditions .= 'contest_participant.contestID = '.intval($this->contestID);
		$this->todoList->readObjects();
		
		// init sidebar
		$this->sidebar = new ContestSidebar($this->entry);
	}
	
	/**
	 * @see MultipleLinkForm::countItems()
	 */
	public function countItems() {
		parent::countItems();
		
		return $this->solutionList->countObjects();
	}
	
	/**
	 * @see Form::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		// display branding
		require_once(WCF_DIR.'lib/util/ContestUtil.class.php');
		ContestUtil::assignVariablesBranding();
		
		if(!$this->entry->enableOpenSolutions && (
			$this->entry->state != 'scheduled' || !($this->entry->fromTime < TIME_NOW && TIME_NOW < $this->entry->untilTime)
		)) {
			WCF::getTPL()->append('userMessages', '<p class="info">'.WCF::getLanguage()->get('wcf.contest.enableOpenSolutions.info').'</p>');
		}
		
		if($this->entry->enableParticipantCheck) {
			WCF::getTPL()->append('userMessages', '<p class="info">'.WCF::getLanguage()->get('wcf.contest.enableParticipantCheck.info').'</p>');
		}

		$this->sidebar->assignVariables();
		WCF::getTPL()->assign(array(
			'entry' => $this->entry,
			'contestID' => $this->contestID,
			'userID' => $this->entry->userID,
			'solutions' => $this->solutionList->getObjects(),
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
		ContestMenu::getInstance()->setActiveMenuItem('wcf.contest.menu.link.solution');
		
		// check permission
		WCF::getUser()->checkPermission('user.contest.canViewContest');
		
		if (!MODULE_CONTEST) {
			throw new IllegalLinkException();
		}
		
		parent::show();
	}
}
?>
