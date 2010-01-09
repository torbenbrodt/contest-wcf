<?php
// wcf imports
require_once(WCF_DIR.'lib/page/MultipleLinkPage.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestSidebar.class.php');

/**
 * Shows the overview page of a user contest.
 * 
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestPage extends MultipleLinkPage {
	// system
	public $templateName = 'contest';
	
	/**
	 * list of contest entries
	 *
	 * @var ContestEntryList
	 */
	public $entryList = null;
	
	/**
	 * contest sidebar
	 * 
	 * @var	ContestSidebar
	 */
	public $sidebar = null;
	
	/**
	 * tag id
	 *
	 * @var integer
	 */
	public $tagID = 0;
	
	/**
	 * tag object
	 *
	 * @var Tag
	 */
	public $tag = null;
	
	/**
	 * class id
	 *
	 * @var integer
	 */
	public $classID = 0;
	
	/**
	 * class object
	 *
	 * @var ContestClass
	 */
	public $class = null;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get tag
		if (isset($_REQUEST['tagID'])) $this->tagID = intval($_REQUEST['tagID']);
		
		// get class
		if (isset($_REQUEST['classID'])) $this->classID = intval($_REQUEST['classID']);
		
		// init entry list
		if (MODULE_TAGGING && $this->tagID) {
			require_once(WCF_DIR.'lib/data/tag/TagEngine.class.php');
			$this->tag = TagEngine::getInstance()->getTagByID($this->tagID);
			if ($this->tag === null) {
				throw new IllegalLinkException();
			}
			require_once(WCF_DIR.'lib/data/contest/TaggedContestEntryList.class.php');
			$this->entryList = new TaggedContestEntryList($this->tagID);
		}
		else if ($this->classID) {
			require_once(WCF_DIR.'lib/data/contest/class/ContestClass.class.php');
			$this->class = new ContestClass($this->classID);
			if (!$this->class->classID || $this->class->userID != WCF::getUser()->userID) {
				throw new IllegalLinkException();
			}
			require_once(WCF_DIR.'lib/data/contest/ContestClassEntryList.class.php');
			$this->entryList = new ContestClassEntryList($this->classID);
		}
		else {
			require_once(WCF_DIR.'lib/data/contest/ViewableContestEntryList.class.php');
			$this->entryList = new ViewableContestEntryList();
		}
		$this->entryList->sqlConditions .= 'contest.userID = '.WCF::getUser()->userID;
	}
	
	/**
	 * @see MultipleLinkPage::countItems()
	 */
	public function countItems() {
		parent::countItems();
		
		return $this->entryList->countObjects();
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		// read entries
		$this->entryList->sqlLimit = 20;
		$this->entryList->readObjects();
		
		// init sidebar
		$this->sidebar = new ContestSidebar($this, WCF::getUser()->userID);
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		$this->sidebar->assignVariables();
		WCF::getTPL()->assign(array(
			'userID' => WCF::getUser()->userID,
			'entries' => $this->entryList->getObjects(),
			'classes' => $this->entryList->getClasses(),
			'tags' => $this->entryList->getTags(),
			'tagID' => $this->tagID,
			'tag' => $this->tag,
			'classID' => $this->classID,
			'class' => $this->class,
			'allowSpidersToIndexThisPage' => true
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		// set active menu item
		UserProfileMenu::getInstance()->setActiveMenuItem('wcf.user.profile.menu.link.contest');
		
		// check permission
		WCF::getUser()->checkPermission('user.contest.canViewContest');
		
		if (!MODULE_CONTEST || !WCF::getUser()->getUser()->getPermission('user.contest.canUseContest')) {
			throw new IllegalLinkException();
		}
		
		parent::show();
	}
}
?>
