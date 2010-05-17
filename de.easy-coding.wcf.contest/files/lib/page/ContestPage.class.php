<?php
// wcf imports
require_once(WCF_DIR.'lib/page/MultipleLinkPage.class.php');
require_once(WCF_DIR.'lib/data/contest/ViewableContest.class.php');
require_once(WCF_DIR.'lib/data/contest/eventmix/ContestEventMixList.class.php');
require_once(WCF_DIR.'lib/data/contest/comment/ContestComment.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestSidebar.class.php');
require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
require_once(WCF_DIR.'lib/page/util/menu/ContestMenu.class.php');

/**
 * Shows a detailed view of a user contest entry.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestPage extends MultipleLinkPage {
	// system
	public $templateName = 'contestEntry';
	
	public $itemsOnLandingpage = 5;
	
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
	 * list of eventmixs
	 *
	 * @var ContestEventMixList
	 */
	public $eventmixList = null;
	
	/**
	 * 
	 * @var ContestJuryTodoList
	 */
	public $todoList = null;
	
	/**
	 * comment id
	 * 
	 * @var	integer
	 */
	public $commentID = 0;
	
	/**
	 * comment object
	 * 
	 * @var	ContestComment
	 */
	public $comment = null;
	
	/**
	 * action
	 * 
	 * @var	string
	 */
	public $action = '';
	
	/**
	 * previous entry
	 * 
	 * @var	Contest
	 */
	public $previousEntry = null;
	
	/**
	 * next entry
	 * 
	 * @var	Contest
	 */
	public $nextEntry = null;
	
	/**
	 * attachment list object
	 * 
	 * @var	MessageAttachmentList
	 */
	public $attachmentList = null;
	
	/**
	 * list of attachments
	 * 
	 * @var	array<Attachment>
	 */
	public $attachments = array();
	
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
		
		// get entry
		if (isset($_REQUEST['contestID'])) $this->contestID = intval($_REQUEST['contestID']);
		$this->entry = new ViewableContest($this->contestID);
		if (!$this->entry->contestID) {
			throw new IllegalLinkException();
		}
		
		if (isset($_REQUEST['errorField'])) $this->errorField = $_REQUEST['errorField'];
		if (isset($_REQUEST['action'])) $this->action = $_REQUEST['action'];
		if (isset($_REQUEST['commentID'])) $this->commentID = intval($_REQUEST['commentID']);
		if ($this->commentID != 0) {
			$this->comment = new ContestComment($this->commentID);
			if (!$this->comment->commentID || $this->comment->contestID != $this->contestID) {
				throw new IllegalLinkException();
			}
			
			// check permissions
			if ($this->action == 'edit' && !$this->comment->isEditable()) {
				throw new PermissionDeniedException();
			}
			
			// get page number
			$pagennumber = new ContestEventMixList();
			$pagennumber->sqlConditions .= 'contestID = '.$this->contestID;
			$pagennumber->sqlConditions .= ' AND time < '.$this->comment->time;
			$count = $pagennumber->countObjects();
			$this->pageNo = $count > $this->itemsOnLandingpage ? 1 : 0;
			$this->pageNo += ceil(($count - $this->itemsOnLandingpage) / $this->itemsPerPage);
		}
		
		// init eventmix list
		$this->eventmixList = new ContestEventMixList();
		$this->eventmixList->sqlConditions .= 'contestID = '.$this->contestID;
		$this->eventmixList->sqlOrderBy = 'contest_eventmix.time DESC';
	}
	
	public function calculateNumberOfPages() {
		// call calculateNumberOfPages event
		EventHandler::fireAction($this, 'calculateNumberOfPages');
		
		// calculate number of pages
		$this->items = $this->countItems();
		$this->pages = $this->items > $this->itemsOnLandingpage ? 1 : 0;
		$this->pages += ceil(($this->items - $this->itemsOnLandingpage) / $this->itemsPerPage);
		
		// correct active page number
		if ($this->pageNo > $this->pages) $this->pageNo = $this->pages;
		if ($this->pageNo < 1) $this->pageNo = 1;
		
		// calculate start and end index
		$this->startIndex = ($this->pageNo - 1) * $this->itemsPerPage;
		$this->endIndex = $this->startIndex + $this->itemsPerPage;
		$this->startIndex++;
		if ($this->endIndex > $this->items) $this->endIndex = $this->items;
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		// show 5 entries on first page, but 20 on the following pages
		$this->eventmixList->sqlLimit = $this->pageNo <= 1 ? $this->itemsOnLandingpage : $this->itemsPerPage;
		$this->eventmixList->sqlOffset = $this->pageNo <= 1 ? 0 : (($this->pageNo - 2) * $this->itemsPerPage) + $this->itemsOnLandingpage;
		
		// fire sql query
		$this->eventmixList->readObjects();

		// get previous entry
		$sql = "SELECT		*
			FROM		wcf".WCF_N."_contest
			WHERE		userID = ".$this->entry->userID."
					AND (
						time > ".$this->entry->time."
						OR (time = ".$this->entry->time." AND contestID < ".$this->entry->contestID.")
					)
			ORDER BY	time ASC, contestID DESC";
		$this->previousEntry = new Contest(null, WCF::getDB()->getFirstRow($sql));
		if (!$this->previousEntry->contestID) $this->previousEntry = null;
		
		// get next entry
		$sql = "SELECT		*
			FROM		wcf".WCF_N."_contest
			WHERE		userID = ".$this->entry->userID."
					AND (
						time < ".$this->entry->time."
						OR (time = ".$this->entry->time." AND contestID > ".$this->entry->contestID.")
					)
			ORDER BY	time DESC, contestID ASC";
		$this->nextEntry = new Contest(null, WCF::getDB()->getFirstRow($sql));
		if (!$this->nextEntry->contestID) $this->nextEntry = null;
		
		// read attachments
		if (MODULE_ATTACHMENT == 1 && $this->entry->attachments > 0) {
			require_once(WCF_DIR.'lib/data/attachment/MessageAttachmentList.class.php');
			$this->attachmentList = new MessageAttachmentList($this->contestID, 'contestEntry', '', WCF::getPackageID('de.easy-coding.wcf.contest'));
			$this->attachmentList->readObjects();
			$this->attachments = $this->attachmentList->getSortedAttachments(WCF::getUser()->getPermission('user.contest.canViewAttachmentPreview'));
			
			// set embedded attachments
			if (WCF::getUser()->getPermission('user.contest.canViewAttachmentPreview')) {
				require_once(WCF_DIR.'lib/data/message/bbcode/AttachmentBBCode.class.php');
				AttachmentBBCode::setAttachments($this->attachments);
			}
			
			// remove embedded attachments from list
			if (count($this->attachments) > 0) {
				MessageAttachmentList::removeEmbeddedAttachments($this->attachments);
			}
		}
		
		if($this->entry->isOwner()) {
			// init todo list
			require_once(WCF_DIR.'lib/data/contest/owner/todo/ContestOwnerTodoList.class.php');
			$this->todoList = new ContestOwnerTodoList();
			$this->todoList->sqlConditions .= 'contest.contestID = '.$this->contestID;
			$this->todoList->readObjects();
		}

		// init sidebar
		$this->sidebar = new ContestSidebar($this->entry);
	}
	
	/**
	 * @see MultipleLinkPage::countItems()
	 */
	public function countItems() {
		parent::countItems();
		return $this->eventmixList->countObjects();
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		// init form
		if ($this->action == 'edit') {
			require_once(WCF_DIR.'lib/form/ContestCommentEditForm.class.php');
			new ContestCommentEditForm($this->entry);
		}
		else if ($this->entry->isCommentable()) {
			require_once(WCF_DIR.'lib/form/ContestCommentAddForm.class.php');
			new ContestCommentAddForm($this->entry);
		}
		
		$this->sidebar->assignVariables();
		WCF::getTPL()->assign(array(
			'entry' => $this->entry,
			'contestID' => $this->contestID,
			'tags' => (MODULE_TAGGING ? $this->entry->getTags(WCF::getSession()->getVisibleLanguageIDArray()) : array()),
			'events' => $this->eventmixList->getObjects(),
			'todos' => $this->todoList ? $this->todoList->getObjects() : array(),
			'classes' => $this->entry->getClasses(),
			'jurys' => $this->entry->getJurys(),
			'participants' => $this->entry->getParticipants(),
			'attachments' => $this->attachments,
			'location' => $this->entry->location,
			'action' => $this->action,
			'commentID' => $this->commentID,
			'previousEntry' => $this->previousEntry,
			'nextEntry' => $this->nextEntry,
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
		ContestMenu::getInstance()->setContest($this->entry);
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
