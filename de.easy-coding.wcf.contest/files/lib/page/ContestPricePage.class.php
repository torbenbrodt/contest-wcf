<?php
// wcf imports
require_once(WCF_DIR.'lib/page/MultipleLinkPage.class.php');
require_once(WCF_DIR.'lib/data/contest/ViewableContestEntry.class.php');
require_once(WCF_DIR.'lib/data/contest/price/ContestPriceList.class.php');
require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
require_once(WCF_DIR.'lib/page/util/menu/ContestMenu.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestSidebar.class.php');

/**
 * show/edit price entries
 * 
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestPricePage extends MultipleLinkPage {
	// system
	public $templateName = 'contestPrice';
	
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
	 * available groups
	 *
	 * @var array<Group>
	 */
	protected $availableGroups = array();
	
	/**
	 * @see Form::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get entry
		if (isset($_REQUEST['contestID'])) $this->contestID = intval($_REQUEST['contestID']);
		$this->entry = new ViewableContestEntry($this->contestID);
		if (!$this->entry->contestID) {
			throw new IllegalLinkException();
		}
		
		// init price list
		$this->priceList = new ContestPriceList();
		$this->priceList->sqlConditions .= 'contest_price.contestID = '.$this->contestID;
		$this->priceList->sqlOrderBy = 'contest_price.time DESC';
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
		
		// init sidebar
		$this->sidebar = new ContestSidebar($this, $this->entry->userID);

		// owner
		$this->readAvailableGroups();
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
		
		// init form
		if ($this->entry->isPriceable()) {
			if ($this->action == 'edit') {
				require_once(WCF_DIR.'lib/form/ContestPriceEditForm.class.php');
				new ContestPriceEditForm($this->entry);
			}
			else {
				require_once(WCF_DIR.'lib/form/ContestPriceAddForm.class.php');
				new ContestPriceAddForm($this->entry);
			}
		}

		$this->sidebar->assignVariables();		
		WCF::getTPL()->assign(array(
			'entry' => $this->entry,
			'contestID' => $this->contestID,
			'userID' => $this->entry->userID,
			'prices' => $this->priceList->getObjects(),
			'availableGroups' => $this->availableGroups,
			'ownerID' => $this->ownerID,
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
		ContestMenu::getInstance()->contestID = $this->contestID;
		ContestMenu::getInstance()->setActiveMenuItem('wcf.contest.menu.link.price');
		
		// check permission
		WCF::getUser()->checkPermission('user.contest.canViewContest');
		
		if (!MODULE_CONTEST) {
			throw new IllegalLinkException();
		}
		
		parent::show();
	}
	
	/**
	 * returns the groups for which the user is admin
	 */
	protected function readAvailableGroups() {
		$sql = "SELECT		usergroup.*, (
						SELECT	COUNT(*)
						FROM	wcf".WCF_N."_user_to_groups
						WHERE	groupID = usergroup.groupID
					) AS members
			FROM 		wcf".WCF_N."_group usergroup
			WHERE		groupID IN (
						SELECT	groupID
						FROM	wcf".WCF_N."_group_leader
						WHERE	leaderUserID = ".WCF::getUser()->userID."
							OR leaderGroupID IN (".implode(',', WCF::getUser()->getGroupIDs()).")
					)
			ORDER BY 	groupName";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->availableGroups[$row['groupID']] = new Group(null, $row);
		}
	}
}
?>
