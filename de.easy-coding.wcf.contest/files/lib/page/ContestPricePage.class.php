<?php
// wcf imports
require_once(WCF_DIR.'lib/page/MultipleLinkPage.class.php');
require_once(WCF_DIR.'lib/data/contest/ViewableContest.class.php');
require_once(WCF_DIR.'lib/data/contest/price/ContestPriceList.class.php');
require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
require_once(WCF_DIR.'lib/page/util/menu/ContestMenu.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestSidebar.class.php');

/**
 * show/edit price entries
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestPricePage extends MultipleLinkPage {
	// system
	public $templateName = 'contestPrice';
	
	/**
	 * entry id
	 *
	 * @var	integer
	 */
	public $contestID = 0;
	
	/**
	 * success action
	 *
	 * @var	string
	 */
	public $success = '';
	
	/**
	 * is sponsor?
	 * 
	 * @var	boolean
	 */
	public $isSponsor = false;
	
	/**
	 * entry object
	 * 
	 * @var	ContestPrice
	 */
	public $entry = null;
	
	/**
	 * list of prices
	 *
	 * @var ContestPriceList
	 */
	public $priceList = null;
	
	/**
	 * 
	 * @var ContestJuryTodoList
	 */
	public $todoList = null;
	
	/**
	 * price id
	 * 
	 * @var	integer
	 */
	public $priceID = 0;
	
	/**
	 * price object
	 * 
	 * @var	ContestPrice
	 */
	public $price = null;
	
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
		
		// any success action?
		if (isset($_REQUEST['success'])) $this->success = $_REQUEST['success'];
		
		// get entry
		if (isset($_REQUEST['contestID'])) $this->contestID = intval($_REQUEST['contestID']);
		$this->entry = new ViewableContest($this->contestID);
		if (!$this->entry->contestID) {
			throw new IllegalLinkException();
		}
		
		// init price list
		$this->priceList = new ContestPriceList();
		$this->priceList->sqlConditions .= 'contest_price.contestID = '.intval($this->contestID);
	}
	
	/**
	 * @see Form::readData()
	 */
	public function readData() {
		parent::readData();
		
		// read objects
		$this->priceList->sqlOffset = ($this->pageNo - 1) * $this->itemsPerPage;
		$this->priceList->sqlLimit = $this->itemsPerPage;
		$this->priceList->readObjects();
		
		$this->isSponsor = $this->entry->isSponsor();
		
		// init sidebar
		$this->sidebar = new ContestSidebar($this->entry, array(
			'priceList',
			'advertiseSponsor'
		));
	}
	
	/**
	 * @see MultipleLinkForm::countItems()
	 */
	public function countItems() {
		parent::countItems();
		
		return $this->priceList->countObjects();
	}
	
	/**
	 * @see Form::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		if($this->success) {
			$l = 'wcf.contest.price.'.StringUtil::encodeHTML($this->success).'.success';
			WCF::getTPL()->append('userMessages', '<p class="success">'.WCF::getLanguage()->get($l).'</p>');
		}
		
		// display branding
		require_once(WCF_DIR.'lib/util/ContestUtil.class.php');
		ContestUtil::assignVariablesBranding();
		
		// save price position
		if($this->entry->isOwner()) {
			require_once(WCF_DIR.'lib/form/ContestPricePositionForm.class.php');
			new ContestPricePositionForm($this->entry);
		}
		
		// init form
		if ($this->action == 'edit') {
			require_once(WCF_DIR.'lib/form/ContestPriceEditForm.class.php');
			new ContestPriceEditForm($this->entry);
		}
		else if($this->entry->isPriceable()) {
			require_once(WCF_DIR.'lib/form/ContestPriceAddForm.class.php');
			new ContestPriceAddForm($this->entry);
		}

		// become sponsor
		if($this->entry->enableSponsorCheck && !$this->entry->isSponsor() && $this->entry->isSponsorable(false)) {
			WCF::getTPL()->append('additionalContentBecomeSponsor', 
				'<p class="info">'.WCF::getLanguage()->get('wcf.contest.enableSponsorCheck.info').'</p>');
		}
		
		// if contest is finished, show todo list
		// who is able to pick the prices
		$isWinner = false;
		if($this->entry->state == 'closed') {
			// need winners
			require_once(WCF_DIR.'lib/data/contest/solution/ContestSolutionList.class.php');
			$solutionList = new ContestSolutionList();
			$solutionList->sqlConditions .= 'contest_solution.contestID = '.intval($this->contestID);
			$solutionList->sqlLimit = $this->countItems();
			$solutionList->readObjects();

			$winners = array();
			foreach($solutionList->getObjects() as $solution) {
				$winners[] = $solution->participantID;
				$isWinner = $isWinner || $solution->isOwner();
			}
			
			if(count($winners)) {
				// init todo list
				require_once(WCF_DIR.'lib/data/contest/price/todo/ContestPriceTodoList.class.php');
				$this->todoList = new ContestPriceTodoList();
				$this->todoList->sqlConditions .= '
					contest_solution.participantID IN ('.implode(',', $winners).')
					AND contest_solution.contestID = '.intval($this->contestID);
				$this->todoList->sqlOrderBy = 'FIND_IN_SET(contest_solution.participantID, \''.implode(',', $winners).'\')';
				$this->todoList->sqlLimit = $this->countItems();
				$this->todoList->readObjects();
			}
		}

		// which price is pickable be the current user NOW?
		$solution = null;
		$didPick = true;
		if($isWinner) {
			foreach($this->priceList->getObjects() as $price) {
				if($price->isPickable()) {
					$solution = $price->pickableByWinner();
					break;
				} else if($price->isOwner()) {
					$didPick = true;
				}
			}
		} else {
			if($this->entry->state == 'scheduled' && $this->entry->untilTime > TIME_NOW) {
				WCF::getTPL()->append('userMessages', '<p class="info">'.WCF::getLanguage()->get('wcf.contest.price.closed.info').'</p>');
			}
			
			// after contest is finished, winners have to choose prices
			else if($this->entry->enablePricechoice) {
				WCF::getTPL()->append('userMessages', '<p class="info">'.WCF::getLanguage()->get('wcf.contest.price.pick.info').'</p>');
			}
		}

		$this->sidebar->assignVariables();		
		WCF::getTPL()->assign(array(
			'entry' => $this->entry,
			'isSponsor' => $this->isSponsor,
			'contestID' => $this->contestID,
			'userID' => $this->entry->userID,
			'solution' => $solution,
			'isWinner' => $isWinner,
			'didPick' => $didPick,
			'prices' => $this->priceList->getObjects(),
			'todos' => $this->todoList ? $this->todoList->getObjects() : array(),
			'templateName' => $this->templateName,
			'allowSpidersToIndexThisPage' => true,
			
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
		ContestMenu::getInstance()->setActiveMenuItem('wcf.contest.menu.link.price');
		
		// check permission
		WCF::getUser()->checkPermission('user.contest.canViewContest');
		
		if (!MODULE_CONTEST) {
			throw new IllegalLinkException();
		}
		
		parent::show();
	}
}
?>
