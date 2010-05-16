<?php
// wcf imports
require_once(WCF_DIR.'lib/page/SortablePage.class.php');
require_once(WCF_DIR.'lib/data/contest/class/ContestClassList.class.php');
require_once(WCF_DIR.'lib/data/contest/ratingoption/ContestRatingoptionList.class.php');

/**
 * Shows a list of all ratingoption items.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestRatingoptionListPage extends SortablePage {
	// system
	public $templateName = 'contestRatingoptionList';
	public $defaultSortField = 'position';
	public $deletedOptionID = 0;
	
	/**
	 *
	 */
	protected $defaultRatingoptions = 0;
	
	/**
	 * ratingoption list object
	 * 
	 * @var	ContestRatingoptionList
	 */
	public $ratingoptionList = null;
	
	/**
	 * ratingoption category id
	 * 
	 * @var	integer
	 */
	public $classID = 0;
	
	/**
	 * list of ratingoption categories.
	 * 
	 * @var	array<ContestClass>
	 */
	public $classes = array();
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['deletedOptionID'])) $this->deletedOptionID = intval($_REQUEST['deletedOptionID']);
		if (isset($_REQUEST['classID'])) $this->classID = intval($_REQUEST['classID']);
		$this->ratingoptionList = new ContestRatingoptionList();
		$this->ratingoptionList->sqlConditions = 'contest_ratingoption.classID = '.$this->classID;
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		// get ratingoptions
		$this->ratingoptionList->sqlOffset = ($this->pageNo - 1) * $this->itemsPerPage;
		$this->ratingoptionList->sqlLimit = $this->itemsPerPage;
		$this->ratingoptionList->sqlOrderBy = 'contest_ratingoption.'.$this->sortField." ".$this->sortOrder;
		$this->ratingoptionList->readObjects();
		
		// get classes
		$this->classList = new ContestClassList();
		$this->classList->sqlJoins = "LEFT JOIN	wcf".WCF_N."_contest_ratingoption contest_ratingoption USING(classID)";
		$this->classList->sqlGroupBy = 'contest_class.classID';
		$this->classList->readObjects();
		
		// get categories
		$this->classes = $this->classList->getObjects();
		
		// defaultRatingoptions
		$sql = "SELECT	COUNT(*) AS count
			FROM	wcf".WCF_N."_contest_ratingoption
			WHERE	classID	= 0";
		$row = WCF::getDB()->getFirstRow($sql);
		$this->defaultRatingoptions = $row['count'];
	}
	
	/**
	 * @see SortablePage::validateSortField()
	 */
	public function validateSortField() {
		parent::validateSortField();
		
		switch ($this->sortField) {
			case 'optionID':
			case 'position': break;
			default: $this->sortField = $this->defaultSortField;
		}
	}
	
	/**
	 * @see MultipleLinkPage::countItems()
	 */
	public function countItems() {
		parent::countItems();
		
		return $this->ratingoptionList->countObjects();
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'ratingoptions' => $this->ratingoptionList->getObjects(),
			'defaultRatingoptions' => $this->defaultRatingoptions,
			'deletedOptionID' => $this->deletedOptionID,
			'classID' => $this->classID,
			'classes' => $this->classes,
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.contest.ratingoption');
		
		// check permission
		WCF::getUser()->checkPermission(array('admin.contest.canEditRatingoption', 'admin.contest.canDeleteRatingoption'));
		
		parent::show();
	}
}
?>
