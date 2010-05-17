<?php
// wcf imports
require_once(WCF_DIR.'lib/page/MultipleLinkPage.class.php');
require_once(WCF_DIR.'lib/data/contest/ViewableContest.class.php');
require_once(WCF_DIR.'lib/data/contest/sponsortalk/ContestSponsortalkList.class.php');
require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
require_once(WCF_DIR.'lib/page/util/menu/ContestMenu.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestSidebar.class.php');

/**
 * show/edit sponsortalk entries
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSponsortalkPage extends MultipleLinkPage {
	// system
	public $templateName = 'contestSponsortalk';
	
	/**
	 * entry id
	 *
	 * @var	integer
	 */
	public $contestID = 0;
	
	/**
	 * entry object
	 * 
	 * @var	ContestSponsortalk
	 */
	public $entry = null;
	
	/**
	 * list of sponsortalks
	 *
	 * @var ContestSponsortalkList
	 */
	public $sponsortalkList = null;
	
	/**
	 * sponsortalk id
	 * 
	 * @var	integer
	 */
	public $sponsortalkID = 0;
	
	/**
	 * sponsortalk object
	 * 
	 * @var	ContestSponsortalk
	 */
	public $sponsortalk = null;
	
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
		if (!$this->entry->contestID || !$this->entry->isSponsortalkable()) {
			throw new IllegalLinkException();
		}
		
		// init sponsortalk list
		$this->sponsortalkList = new ContestSponsortalkList();
		$this->sponsortalkList->sqlConditions .= 'contest_sponsortalk.contestID = '.$this->contestID;
		$this->sponsortalkList->sqlOrderBy = 'contest_sponsortalk.time DESC';
	}
	
	/**
	 * @see Form::readData()
	 */
	public function readData() {
		parent::readData();
		
		// read objects
		$this->sponsortalkList->sqlOffset = ($this->pageNo - 1) * $this->itemsPerPage;
		$this->sponsortalkList->sqlLimit = $this->itemsPerPage;
		$this->sponsortalkList->readObjects();
		
		// init sidebar
		$this->sidebar = new ContestSidebar($this->entry);
	}
	
	/**
	 * @see MultipleLinkForm::countItems()
	 */
	public function countItems() {
		parent::countItems();
		
		return $this->sponsortalkList->countObjects();
	}
	
	/**
	 * @see Form::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		// init form
		if ($this->action == 'edit') {
			require_once(WCF_DIR.'lib/form/ContestSponsortalkEditForm.class.php');
			new ContestSponsortalkEditForm($this->entry);
		}
		else if($this->entry->isSponsortalkable()) {
			require_once(WCF_DIR.'lib/form/ContestSponsortalkAddForm.class.php');
			new ContestSponsortalkAddForm($this->entry);
		}

		$this->sidebar->assignVariables();		
		WCF::getTPL()->assign(array(
			'entry' => $this->entry,
			'contestID' => $this->contestID,
			'userID' => $this->entry->userID,
			'sponsortalks' => $this->sponsortalkList->getObjects(),
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
		ContestMenu::getInstance()->setActiveMenuItem('wcf.contest.menu.link.sponsortalk');
		
		// check permission
		WCF::getUser()->checkPermission('user.contest.canViewContest');
		
		if (!MODULE_CONTEST) {
			throw new IllegalLinkException();
		}
		
		parent::show();
	}
}
?>
