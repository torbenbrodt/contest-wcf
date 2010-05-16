<?php
// wcf imports
require_once(WCF_DIR.'lib/form/MessageForm.class.php');
require_once(WCF_DIR.'lib/page/MultipleLinkPage.class.php');
require_once(WCF_DIR.'lib/data/contest/solution/ContestSolutionEditor.class.php');
require_once(WCF_DIR.'lib/data/contest/ViewableContest.class.php');
require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
require_once(WCF_DIR.'lib/page/util/menu/ContestMenu.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestSidebar.class.php');
require_once(WCF_DIR.'lib/data/contest/state/ContestState.class.php');
require_once(WCF_DIR.'lib/data/contest/crew/ContestCrew.class.php');

/**
 * Shows the form for adding contest contest solutions.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSolutionAddForm extends MessageForm {
	// system
	public $useCaptcha = 0;
	public $templateName = 'contestSolutionAdd';
	public $showPoll = false;
	public $showSignatureSetting = false;
	public $action = 'add';
	public $preview, $send;

	// parameters
	public $ownerID = 0;
	public $contestID = 0;
	public $userID = 0;
	public $groupID = 0;
	
	public $states = array();
	public $state = '';
	
	/**
	 * attachment list editor
	 * 
	 * @var	AttachmentListEditor
	 */
	public $attachmentListEditor = null;
	
	/**
	 * contest editor
	 *
	 * @var Contest
	 */
	public $contest = null;
	
	/**
	 * available groups
	 *
	 * @var array<Group>
	 */
	protected $availableGroups = array();
	
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
		$this->contest = new ViewableContest($this->contestID);
		if (!$this->contest->contestID) {
			throw new IllegalLinkException();
		}
		
		// get contest
		if (!$this->contest->isSolutionable()) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		// get parameters
		if (isset($_POST['preview'])) $this->preview = (boolean) $_POST['preview'];
		if (isset($_POST['send'])) $this->send = (boolean) $_POST['send'];
		if (isset($_POST['ownerID'])) $this->ownerID = intval($_POST['ownerID']);
		if (isset($_POST['state'])) $this->state = $_POST['state'];
		
		if ($this->ownerID == 0) {
			$this->userID = WCF::getUser()->userID;
		} else {
			$this->groupID = $this->ownerID;
		}
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		$this->states = $this->getStates();
		$this->availableGroups = ContestUtil::readAvailableGroups();
		
		// init sidebar
		$this->sidebar = new ContestSidebar($this, $this->contest);
	}
	
	/**
	 * no validation required
	 */
	protected function validateSubject() {
	}
	
	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();
		
		if (empty($this->text)) {
			throw new UserInputException('text');
		}
		
		if (StringUtil::length($this->text) > WCF::getUser()->getPermission('user.contest.maxSolutionLength')) {
			throw new UserInputException('text', 'tooLong');
		}
		
		if($this->ownerID != 0) {
			$this->availableGroups = ContestUtil::readAvailableGroups();
		
			// validate group ids
			if(!array_key_exists($this->ownerID, $this->availableGroups)) {
				throw new UserInputException('ownerID'); 
			}
		}
		
		if(!array_key_exists($this->state, $this->getStates())) {
			throw new UserInputException('state');
		}
	}
	
	/**
	 * returns available states
	 */
	protected function getStates() {
		$flags = (!isset($this->entry) || $this->entry->isOwner() ? ContestState::FLAG_USER : 0)
			+ ($this->contest->isOwner() ? ContestState::FLAG_CONTESTOWNER : 0)
			+ (ContestCrew::isMember() ? ContestState::FLAG_CREW : 0);

		return ContestSolutionEditor::getStates(isset($this->entry) ? $this->entry->state : '', $flags);
	}
	
	/**
	 * @see Form::submit()
	 */
	public function submit() {
		// call submit event
		EventHandler::fireAction($this, 'submit');
		
		$this->readFormParameters();
		
		try {
			// attachment handling
			if ($this->showAttachments) {
				$this->attachmentListEditor->handleRequest();
			}

			// preview
			if ($this->preview) {
				require_once(WCF_DIR.'lib/data/message/bbcode/AttachmentBBCode.class.php');
				AttachmentBBCode::setAttachments($this->attachmentListEditor->getSortedAttachments());
				WCF::getTPL()->assign('preview', ContestSolutionEditor::createPreview($this->subject, $this->text, $this->enableSmilies, $this->enableHtml, $this->enableBBCodes));
			}

			// send message or save as draft
			if ($this->send) {
				$this->validate();
				
				// no errors
				$this->save();
			}
		}
		catch (UserInputException $e) {
			$this->errorField = $e->getField();
			$this->errorType = $e->getType();
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		$participant = ContestParticipant::find($this->contest->contestID, $this->userID, $this->groupID);
		
		$state = 'applied';
		if($participant === null) {
			require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipantEditor.class.php');
			$participant = ContestParticipantEditor::create($this->contest->contestID, $this->userID, $this->groupID, $state);
		}
		
		// save solution
		$solution = ContestSolutionEditor::create($this->contest->contestID, $participant->participantID, $this->text, $this->state, $this->getOptions(), $this->attachmentListEditor);
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestSolutionEntry&contestID='.$this->contest->contestID.'&solutionID='.$solution->solutionID.SID_ARG_2ND_NOT_ENCODED.'#solution'.$solution->solutionID);
		exit;
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		$this->sidebar->assignVariables();
		WCF::getTPL()->assign(array(
			'states' => $this->states,
			'state' => $this->state,
			'availableGroups' => $this->availableGroups,
			'ownerID' => $this->ownerID,
			'entry' => $this->contest,
			'contestID' => $this->contestID,
			'userID' => $this->contest->userID,
			'templateName' => $this->templateName,
			'allowSpidersToIndexThisForm' => true,
			
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
		ContestMenu::getInstance()->setContest($this->contest);
		ContestMenu::getInstance()->setActiveMenuItem('wcf.contest.menu.link.solution');
		
		// check permission
		WCF::getUser()->checkPermission('user.contest.canViewContest');
		
		if (!MODULE_CONTEST) {
			throw new IllegalLinkException();
		}
		
		// check upload permission
		if (MODULE_ATTACHMENT != 1 || !WCF::getUser()->getPermission('user.contest.canUploadAttachment')) {
			$this->showAttachments = false;
		}
		
		// get attachments editor
		if ($this->attachmentListEditor === null) {
			require_once(WCF_DIR.'lib/data/attachment/MessageAttachmentListEditor.class.php');
			$this->attachmentListEditor = new MessageAttachmentListEditor(array(), 'contestSolutionEntry', WCF::getPackageID('de.easy-coding.wcf.contest'), WCF::getUser()->getPermission('user.contest.maxAttachmentSize'), WCF::getUser()->getPermission('user.contest.allowedAttachmentExtensions'), WCF::getUser()->getPermission('user.contest.maxAttachmentCount'));
		}
		
		parent::show();
	}
}
?>
