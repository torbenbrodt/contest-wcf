<?php
// wcf imports
require_once(WCF_DIR.'lib/page/MultipleLinkPage.class.php');
require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestSidebar.class.php');

/**
 * Shows an overview of all user contests.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestOverviewPage extends MultipleLinkPage {
	// system
	public $templateName = 'contestOverview';
	public $itemsPerPage = 10;
	
	/**
	 * decription for filters to be shown
	 *
	 * @var string
	 */
	protected $description = array();
	
	/**
	 * list of contest entrys
	 *
	 * @var ContestList
	 */
	public $entryList = null;
	
	/**
	 * tag list object
	 *
	 * @var TagList
	 */
	public $tagList = null;
	
	/**
	 * 
	 * @var ContestJuryTodoList
	 */
	public $todoList = null;
	
	/**
	 * list of tags
	 * 
	 * @var	array
	 */
	public $tags = array();
	
	/**
	 * tag id
	 *
	 * @var integer
	 */
	public $tagID = 0;
	
	/**
	 * juryID
	 *
	 * @var integer
	 */
	public $juryID = 0;
	
	/**
	 * participantID
	 *
	 * @var integer
	 */
	public $participantID = 0;
	
	/**
	 * classID
	 *
	 * @var integer
	 */
	public $classID = 0;
	
	/**
	 * tag object
	 *
	 * @var Tag
	 */
	public $tag = null;
	
	/**
	 * taggable object
	 *
	 * @var Taggable
	 */
	public $taggable = null;
	
	/**
	 * contest sidebar
	 * 
	 * @var	ContestSidebar
	 */
	public $sidebar = null;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get tag
		if (isset($_REQUEST['tagID'])) $this->tagID = intval($_REQUEST['tagID']);
		
		// get juryID
		if (isset($_REQUEST['juryID'])) $this->juryID = intval($_REQUEST['juryID']);
		
		// get participantID
		if (isset($_REQUEST['participantID'])) $this->participantID = intval($_REQUEST['participantID']);
		
		// get classID
		if (isset($_REQUEST['classID'])) $this->classID = intval($_REQUEST['classID']);
		
		// init entry list
		if (MODULE_TAGGING && $this->tagID) {
			require_once(WCF_DIR.'lib/data/tag/TagEngine.class.php');
			$this->tag = TagEngine::getInstance()->getTagByID($this->tagID);
			if ($this->tag === null) {
				throw new IllegalLinkException();
			}
			require_once(WCF_DIR.'lib/data/contest/TaggedContestOverviewList.class.php');
			$this->entryList = new TaggedContestOverviewList($this->tagID);
		} else if($this->juryID) {
			require_once(WCF_DIR.'lib/data/contest/ContestOverviewList.class.php');
			$this->entryList = new ContestOverviewList();
			$this->entryList->sqlConditions .= 'contest_jury.juryID = '.intval($this->juryID);
			$this->entryList->sqlJoins .= " LEFT JOIN wcf".WCF_N."_contest_jury contest_jury ON (contest_jury.contestID = contest.contestID) ";
		
		} else if($this->participantID) {
			require_once(WCF_DIR.'lib/data/contest/ContestOverviewList.class.php');
			$this->entryList = new ContestOverviewList();
			$this->entryList->sqlConditions .= 'contest_participant.participantID = '.intval($this->participantID);
			$this->entryList->sqlJoins .= " LEFT JOIN wcf".WCF_N."_contest_participant contest_participant ON (contest_participant.contestID = contest.contestID) ";
		
		} else if($this->classID) {
			require_once(WCF_DIR.'lib/data/contest/ContestOverviewList.class.php');
			$this->entryList = new ContestOverviewList();
			$this->entryList->sqlConditions .= 'contest_to_class.classID = '.intval($this->classID);
			$this->entryList->sqlJoins .= " LEFT JOIN wcf".WCF_N."_contest_to_class contest_to_class ON (contest_to_class.contestID = contest.contestID) ";
		}
		else {
			require_once(WCF_DIR.'lib/data/contest/ContestOverviewList.class.php');
			$this->entryList = new ContestOverviewList();
		}
		
		// init tag list
		if (MODULE_TAGGING) {
			require_once(WCF_DIR.'lib/data/tag/TagList.class.php');
			$this->tagList = new TagList(array('de.easy-coding.wcf.contest.entry'), WCF::getSession()->getVisibleLanguageIDArray());
		}
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		if($this->classID) {
			$class = new ContestClass($this->classID);
			$val = WCF::getLanguage()->get($class->description);
			if(!empty($val)) {
				$this->description[] = $val;
			}
		}
		
		// read entries
		$this->entryList->sqlLimit = $this->itemsPerPage;
		$this->entryList->sqlOffset = ($this->pageNo - 1) * $this->itemsPerPage;
		$this->entryList->readObjects();
		
		// init todo list
		require_once(WCF_DIR.'lib/data/contest/crew/todo/ContestCrewTodoList.class.php');
		$this->todoList = new ContestCrewTodoList();
		$this->todoList->readObjects();
		
		// init sidebar
		$this->sidebar = new ContestSidebar($this);

		// read tags
		if (MODULE_TAGGING) {
			$this->tagList->readObjects();
			$this->tags = $this->tagList->getObjects();
		}
	}
	
	/**
	 * @see MultipleLinkPage::countItems()
	 */
	public function countItems() {
		parent::countItems();
		
		return $this->entryList->countObjects();
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		$this->sidebar->assignVariables();
		
		WCF::getTPL()->assign(array(
			'entries' => $this->entryList->getObjects(),
			'description' => $this->description,
			'classes' => $this->entryList->getClasses(),
			'todos' => $this->todoList ? $this->todoList->getObjects() : array(),
			'tags' => $this->entryList->getTags(),
			'availableTags' => $this->tags,
			'tagID' => $this->tagID,
			'juryID' => $this->juryID,
			'participantID' => $this->participantID,
			'classID' => $this->classID,
			'tag' => $this->tag,
			'taggableID' => ($this->taggable !== null ? $this->taggable->getTaggableID() : 0),
			'allowSpidersToIndexThisPage' => true
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		// set active header menu item
		PageMenu::setActiveMenuItem('wcf.header.menu.user.contest');
		
		// check permission
		WCF::getUser()->checkPermission('user.contest.canViewContest');
		
		if (!MODULE_CONTEST) {
			throw new IllegalLinkException();
		}
		
		parent::show();
	}
}
?>
