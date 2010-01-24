<?php
// wcf imports
require_once(WCF_DIR.'lib/page/MultipleLinkPage.class.php');
require_once(WCF_DIR.'lib/data/contest/ViewableContest.class.php');
require_once(WCF_DIR.'lib/data/contest/comment/ContestCommentList.class.php');
require_once(WCF_DIR.'lib/data/contest/price/ContestPriceList.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestSidebar.class.php');
require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
require_once(WCF_DIR.'lib/page/util/menu/ContestMenu.class.php');

/**
 * Shows a detailed view of a user contest entry.
 * 
 * @author	Torben Brodt
 * @copyright	2009 TBR Comments
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestPage extends MultipleLinkPage {
	// system
	public $templateName = 'contestEntry';
	
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
	 * list of comments
	 *
	 * @var ContestCommentList
	 */
	public $commentList = null;
	
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
	 * list of comments
	 *
	 * @var ContestCommentList
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
			$sql = "SELECT	COUNT(*) AS comments
				FROM 	wcf".WCF_N."_contest_comment
				WHERE 	contestID = ".$this->contestID."
					AND time < ".$this->comment->time;
			$result = WCF::getDB()->getFirstRow($sql);
			$this->pageNo = intval(ceil($result['comments'] / $this->itemsPerPage));
		}
		
		// init comment list
		$this->commentList = new ContestCommentList();
		$this->commentList->sqlConditions .= 'contest_comment.contestID = '.$this->contestID;
		$this->commentList->sqlOrderBy = 'contest_comment.time DESC';
		
		// price
		if (isset($_REQUEST['priceID'])) $this->priceID = intval($_REQUEST['priceID']);
		if ($this->priceID != 0) {
			$this->price = new ContestPrice($this->priceID);
			if (!$this->price->priceID || $this->price->contestID != $this->contestID) {
				throw new IllegalLinkException();
			}
			
			// check permissions
			if ($this->action == 'edit' && !$this->price->isEditable()) {
				throw new PermissionDeniedException();
			}
						
			// get page number
			$sql = "SELECT	COUNT(*) AS prices
				FROM 	wcf".WCF_N."_contest_price
				WHERE 	contestID = ".$this->contestID."
					AND time < ".$this->price->time;
			$result = WCF::getDB()->getFirstRow($sql);
			$this->pageNo = intval(ceil($result['prices'] / $this->itemsPerPage));
		}
		
		// init price list
		$this->priceList = new ContestPriceList();
		$this->priceList->sqlConditions .= 'contest_price.contestID = '.$this->contestID;
		$this->priceList->sqlOrderBy = 'contest_price.time DESC';
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		// read objects
		$this->commentList->sqlOffset = ($this->pageNo - 1) * $this->itemsPerPage;
		$this->commentList->sqlLimit = $this->itemsPerPage;
		$this->commentList->readObjects();
		
		// read objects
		$this->priceList->sqlOffset = ($this->pageNo - 1) * $this->itemsPerPage;
		$this->priceList->sqlLimit = $this->itemsPerPage;
		$this->priceList->readObjects();

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
		
		// init sidebar
		$this->sidebar = new ContestSidebar($this, $this->entry->userID);
	}
	
	/**
	 * @see MultipleLinkPage::countItems()
	 */
	public function countItems() {
		parent::countItems();
		
		return $this->commentList->countObjects();
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		// init form
		if ($this->entry->isCommentable()) {
			if ($this->action == 'edit') {
				require_once(WCF_DIR.'lib/form/ContestCommentEditForm.class.php');
				new ContestCommentEditForm($this->comment);
			}
			else {
				require_once(WCF_DIR.'lib/form/ContestCommentAddForm.class.php');
				new ContestCommentAddForm($this->entry);
			}
		}
		
		$this->sidebar->assignVariables();
		WCF::getTPL()->assign(array(
			'entry' => $this->entry,
			'contestID' => $this->contestID,
			'userID' => $this->entry->userID,
			'user' => $this->entry->getUser(),
			'tags' => (MODULE_TAGGING ? $this->entry->getTags(WCF::getSession()->getVisibleLanguageIDArray()) : array()),
			'comments' => $this->commentList->getObjects(),
			'classes' => $this->entry->getClasses(),
			'jurys' => $this->entry->getJurys(),
			'participants' => $this->entry->getParticipants(),
			'prices' => $this->priceList->getObjects(),
			'attachments' => $this->attachments,
			'location' => $this->entry->location,
			'action' => $this->action,
			'commentID' => $this->commentID,
			'priceID' => $this->priceID,
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
		ContestMenu::getInstance()->contestID = $this->contestID;
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
