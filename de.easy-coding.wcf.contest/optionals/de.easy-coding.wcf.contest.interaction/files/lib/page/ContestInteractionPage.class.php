<?php
// wcf imports
require_once(WCF_DIR.'lib/page/MultipleLinkPage.class.php');
require_once(WCF_DIR.'lib/data/contest/ViewableContest.class.php');
require_once(WCF_DIR.'lib/data/contest/jury/ViewableContestJury.class.php');
require_once(WCF_DIR.'lib/data/contest/interaction/ContestInteractionList.class.php');

/**
 * show xmas ranking
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @package	de.easy-coding.wcf.contest.interaction
 */
class ContestInteractionPage extends MultipleLinkPage {
	/**
	 * contest interaction
	 *
	 * @var string
	 */
	public $templateName = 'contestInteraction';
	
	/**
	 * The number of items shown per page.
	 * 
	 * @var integer
	 */
	public $itemsPerPage = 16;

	/**
	 * entry id
	 *
	 * @var	integer
	 */
	public $contestID = 0;

	/**
	 * entry object
	 * 
	 * @var	Contest
	 */
	public $entry = null;

	/**
	 * list of top scorer
	 *
	 * @var ContestInteractionList
	 */
	public $interactionList = null;

	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		// get entry
		if (isset($_REQUEST['contestID'])) $this->contestID = intval($_REQUEST['contestID']);
		$this->entry = new ViewableContest($this->contestID);
		if (!$this->entry->isViewable()) {
			throw new IllegalLinkException();
		}

		// validation
		if($this->entry->enableInteraction == 0) {
			throw new Exception('invalid contest type');
		}

		// init price list
		$this->interactionList = new ContestInteractionList($this->entry);
	}
	
	/**
	 * @see MultipleLinkForm::countItems()
	 */
	public function countItems() {
		parent::countItems();
		
		return $this->interactionList->countObjects();
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();

		$this->interactionList->sqlOffset = ($this->pageNo - 1) * $this->itemsPerPage;
		$this->interactionList->sqlLimit = $this->itemsPerPage;
		$this->interactionList->readObjects();
	}
	
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'contestID' => $this->contestID,
			'users' => $this->interactionList->getObjects()
		));
	}

	/**
	 * @see Page::show()
	 */
	public function show() {
		// check permission
		WCF::getUser()->checkPermission('user.contest.canViewContest');

		if (!MODULE_CONTEST) {
			throw new IllegalLinkException();
		}

		parent::show();
	}
}
