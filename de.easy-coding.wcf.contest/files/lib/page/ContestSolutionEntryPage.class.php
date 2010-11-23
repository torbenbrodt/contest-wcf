<?php
// wcf imports
require_once(WCF_DIR.'lib/page/MultipleLinkPage.class.php');
require_once(WCF_DIR.'lib/data/contest/ViewableContest.class.php');
require_once(WCF_DIR.'lib/data/contest/solution/comment/ContestSolutionCommentList.class.php');
require_once(WCF_DIR.'lib/data/contest/solution/rating/ContestSolutionRatingSummaryList.class.php');
require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
require_once(WCF_DIR.'lib/page/util/menu/ContestMenu.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestSidebar.class.php');

/**
 * show/edit solution entries
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSolutionEntryPage extends MultipleLinkPage {
	// system
	public $templateName = 'contestSolutionEntry';
	
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
	 * entry object
	 * 
	 * @var	ContestSolution
	 */
	public $solutionObj = null;
	
	/**
	 * solution id
	 * 
	 * @var	integer
	 */
	public $solutionID = 0;
	
	/**
	 * solution object
	 * 
	 * @var	ContestSolution
	 */
	public $solution = null;
	
	/**
	 * list of comments
	 *
	 * @var ContestSolutionCommentList
	 */
	public $commentList = null;
	
	/**
	 * list of ratings
	 *
	 * @var ContestSolutionRatingSummaryList
	 */
	public $ratingList = null;
	
	/**
	 * action
	 * 
	 * @var	string
	 */
	public $action = '';
	
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
	 * @see Form::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get entry
		if (isset($_REQUEST['contestID'])) $this->contestID = intval($_REQUEST['contestID']);
		$this->entry = new ViewableContest($this->contestID);
		if (!$this->entry->contestID) {
			throw new IllegalLinkException();
		}
		
		// get entry
		if (isset($_REQUEST['solutionID'])) $this->solutionID = intval($_REQUEST['solutionID']);
		$this->solutionObj = new ViewableContestSolution($this->solutionID);
		if (!$this->solutionObj->solutionID) {
			throw new IllegalLinkException();
		}
		
		// init comment list
		$this->commentList = new ContestSolutionCommentList();
		$this->commentList->sqlConditions .= 'contest_solution_comment.solutionID = '.$this->solutionID;
		$this->commentList->sqlOrderBy = 'contest_solution_comment.time DESC';
		
		// init rating list
		$this->ratingList = new ContestSolutionRatingSummaryList();
		$this->ratingList->sqlConditions .= 'contest_solution_rating.solutionID = '.$this->solutionID;
	}
	
	/**
	 * @see Form::readData()
	 */
	public function readData() {
		parent::readData();
		
		// read comments
		$this->commentList->sqlOffset = ($this->pageNo - 1) * $this->itemsPerPage;
		$this->commentList->sqlLimit = $this->itemsPerPage;
		$this->commentList->readObjects();
		
		// read ratings
		$this->ratingList->readObjects();
		
		// read attachments
		if (MODULE_ATTACHMENT == 1 && $this->solutionObj->attachments > 0) {
			require_once(WCF_DIR.'lib/data/attachment/MessageAttachmentList.class.php');
			$this->attachmentList = new MessageAttachmentList($this->solutionObj->solutionID, 'contestSolutionEntry', '', WCF::getPackageID('de.easy-coding.wcf.contest'));
			$this->attachmentList->readObjects();
			$this->attachments = $this->attachmentList->getSortedAttachments(WCF::getUser()->getPermission('user.blog.canViewAttachmentPreview'));
			
			// set embedded attachments
			if (WCF::getUser()->getPermission('user.blog.canViewAttachmentPreview')) {
				require_once(WCF_DIR.'lib/data/message/bbcode/AttachmentBBCode.class.php');
				AttachmentBBCode::setAttachments($this->attachments);
			}
			
			// remove embedded attachments from list
			if (count($this->attachments) > 0) {
				MessageAttachmentList::removeEmbeddedAttachments($this->attachments);
			}
		}
		
		// init sidebar
		$this->sidebar = new ContestSidebar($this->entry);
	}
	
	/**
	 * @see MultipleLinkForm::countItems()
	 */
	public function countItems() {
		parent::countItems();
		
		return $this->commentList->countObjects();
	}
	
	/**
	 * @see Form::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		// display branding
		require_once(WCF_DIR.'lib/util/ContestUtil.class.php');
		ContestUtil::assignVariablesBranding();
		
		// save ratings
		if($this->solutionObj->isRateable()) {
			require_once(WCF_DIR.'lib/form/ContestSolutionRatingUpdateForm.class.php');
			new ContestSolutionRatingUpdateForm($this->solutionObj);
		}
		
		// init form
		if ($this->action == 'edit') {
			require_once(WCF_DIR.'lib/form/ContestSolutionCommentEditForm.class.php');
			new ContestSolutionCommentEditForm($this->solutionObj);
		}
		else if ($this->entry->isCommentable()) {
			require_once(WCF_DIR.'lib/form/ContestSolutionCommentAddForm.class.php');
			new ContestSolutionCommentAddForm($this->solutionObj);
		}
		
		if(!$this->entry->enableOpenSolutions && (
			$this->entry->state != 'scheduled' || !($this->entry->fromTime < TIME_NOW && TIME_NOW < $this->entry->untilTime)
		)) {
			WCF::getTPL()->append('userMessages', '<p class="info">'.WCF::getLanguage()->get('wcf.contest.enableOpenSolutions.info').'</p>');
		}
		
		if($this->entry->enableParticipantCheck) {
			WCF::getTPL()->append('userMessages', '<p class="info">'.WCF::getLanguage()->get('wcf.contest.enableParticipantCheck.info').'</p>');
		}

		$this->sidebar->assignVariables();
		WCF::getTPL()->assign(array(
			'entry' => $this->entry,
			'solutionObj' => $this->solutionObj,
			'contestID' => $this->contestID,
			'solutionID' => $this->solutionID,
			'userID' => $this->entry->userID,
			'comments' => $this->commentList->getObjects(),
			'ratings' => $this->ratingList->getObjects(),
			'templateName' => $this->templateName,
			'allowSpidersToIndexThisForm' => true,
			'attachments' => $this->attachments,
			
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
		ContestMenu::getInstance()->setActiveMenuItem('wcf.contest.menu.link.solution');
		
		// check permission
		WCF::getUser()->checkPermission('user.contest.canViewContest');
		
		if (!MODULE_CONTEST) {
			throw new IllegalLinkException();
		}
		
		parent::show();
	}
}
?>
