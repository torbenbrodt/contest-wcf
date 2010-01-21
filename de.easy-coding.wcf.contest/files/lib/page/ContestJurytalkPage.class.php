<?php
// wcf imports
require_once(WCF_DIR.'lib/page/MultipleLinkPage.class.php');
require_once(WCF_DIR.'lib/data/contest/jurytalk/ViewableContestJurytalk.class.php');
require_once(WCF_DIR.'lib/data/contest/jurytalk/ContestJurytalkList.class.php');
require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
require_once(WCF_DIR.'lib/page/util/menu/ContestMenu.class.php');

/**
 * Shows a detailed view of a user contest entry.
 * 
 * @author	Torben Brodt
 * @copyright	2009 TBR Jurytalks
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestJurytalkPage extends MultipleLinkPage {
	// system
	public $templateName = 'contestJurytalk';
	
	/**
	 * entry id
	 *
	 * @var	integer
	 */
	public $contestID = 0;
	
	/**
	 * entry object
	 * 
	 * @var	ContestJurytalk
	 */
	public $entry = null;
	
	/**
	 * list of jurytalks
	 *
	 * @var ContestJurytalkJurytalkList
	 */
	public $jurytalkList = null;
	
	/**
	 * jurytalk id
	 * 
	 * @var	integer
	 */
	public $jurytalkID = 0;
	
	/**
	 * jurytalk object
	 * 
	 * @var	ContestJurytalkJurytalk
	 */
	public $jurytalk = null;
	
	/**
	 * action
	 * 
	 * @var	string
	 */
	public $action = '';
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get entry
		if (isset($_REQUEST['contestID'])) $this->contestID = intval($_REQUEST['contestID']);
		$this->entry = new ViewableContestJurytalk($this->contestID);
		if (!$this->entry->contestID) {
			throw new IllegalLinkException();
		}
		
		// init jurytalk list
		$this->jurytalkList = new ContestJurytalkJurytalkList();
		$this->jurytalkList->sqlConditions .= 'contest_jurytalk.contestID = '.$this->contestID;
		$this->jurytalkList->sqlOrderBy = 'contest_jurytalk.time DESC';
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		// read objects
		$this->jurytalkList->sqlOffset = ($this->pageNo - 1) * $this->itemsPerPage;
		$this->jurytalkList->sqlLimit = $this->itemsPerPage;
		$this->jurytalkList->readObjects();
	}
	
	/**
	 * @see MultipleLinkPage::countItems()
	 */
	public function countItems() {
		parent::countItems();
		
		return $this->jurytalkList->countObjects();
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		// init form
		if ($this->entry->isJurytalkable()) {
			if ($this->action == 'edit') {
				require_once(WCF_DIR.'lib/form/ContestJurytalkJurytalkEditForm.class.php');
				new ContestJurytalkJurytalkEditForm($this->jurytalk);
			}
			else {
				require_once(WCF_DIR.'lib/form/ContestJurytalkJurytalkAddForm.class.php');
				new ContestJurytalkJurytalkAddForm($this->entry);
			}
		}
		
		WCF::getTPL()->assign(array(
			'entry' => $this->entry,
			'contestID' => $this->contestID,
			'userID' => $this->entry->userID,
			'jurytalks' => $this->jurytalkList->getObjects(),
			'templateName' => $this->templateName,
			'allowSpidersToIndexThisPage' => true,
			
			'contestmenu' => ContestMenu::getInstance(),
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		// set active header menu item
		PageMenu::setActiveMenuItem('wcf.header.menu.user.contest');
		
		// set active menu item
		ContestMenu::getInstance()->setActiveMenuItem('wcf.contest.menu.link.overview');
		
		// check permission
		WCF::getUser()->checkPermission('user.contest.canViewContest');
		
		if (!MODULE_CONTEST) {
			throw new IllegalLinkException();
		}
		
		parent::show();
	}
}
?>
