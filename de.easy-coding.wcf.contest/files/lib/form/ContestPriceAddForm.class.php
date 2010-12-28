<?php
// wcf imports
require_once(WCF_DIR.'lib/form/MessageForm.class.php');
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');
require_once(WCF_DIR.'lib/data/contest/price/ContestPriceEditor.class.php');
require_once(WCF_DIR.'lib/util/ContestUtil.class.php');
require_once(WCF_DIR.'lib/data/contest/state/ContestState.class.php');
require_once(WCF_DIR.'lib/data/contest/crew/ContestCrew.class.php');

/**
 * Shows the form for adding contest contest prices.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestPriceAddForm extends MessageForm {
	// form parameters
	public $ownerID = 0;
	public $userID = 0;
	public $groupID = 0;
	public $secretMessage = '';

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
	 * Creates a new ContestPriceAddForm object.
	 *
	 * @param	Contest	$contest
	 */
	public function __construct(Contest $contest) {
		$this->contest = $contest;
		parent::__construct();
	}

	/**
	 * @see Form::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		// get contest
		if (!$this->contest->isPriceable()) {
			throw new PermissionDeniedException();
		}
	}

	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();

		// get parameters
		if (isset($_POST['ownerID'])) $this->ownerID = intval($_POST['ownerID']);
		if (isset($_POST['state'])) $this->state = $_POST['state'];
		if (isset($_POST['subject'])) $this->subject = StringUtil::trim($_POST['subject']);
		if (isset($_POST['text'])) $this->text = StringUtil::trim($_POST['text']);
		if (isset($_POST['secretMessage'])) $this->secretMessage = StringUtil::trim($_POST['secretMessage']);

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

		// owner
		$this->availableGroups = ContestUtil::readAvailableGroups();
	}

	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();

		if (empty($this->subject)) {
			throw new UserInputException('subject');
		}

		if (empty($this->text)) {
			throw new UserInputException('text');
		}

		if (StringUtil::length($this->text) > WCF::getUser()->getPermission('user.contest.maxSolutionLength')) {
			throw new UserInputException('text', 'tooLong');
		}

		if (StringUtil::length($this->secretMessage) > WCF::getUser()->getPermission('user.contest.maxSolutionLength')) {
			throw new UserInputException('secretMessage', 'tooLong');
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

		return ContestPriceEditor::getStates(isset($this->entry) ? $this->entry->state : '', $flags);
	}

	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();

		$sponsor = ContestSponsor::find($this->contest->contestID, $this->userID, $this->groupID);

		if($sponsor === null) {
			require_once(WCF_DIR.'lib/data/contest/sponsor/ContestSponsorEditor.class.php');
			$state = $this->contest->enableSponsorCheck ? 'applied' : 'accepted';
			$sponsor = ContestSponsorEditor::create($this->contest->contestID, $this->userID, $this->groupID, $state);
		}

		// save price
		$price = ContestPriceEditor::create($this->contest->contestID, $sponsor->sponsorID, $this->subject, $this->text, $this->secretMessage, $this->attachmentListEditor);
		$this->saved();

		// forward
		HeaderUtil::redirect('index.php?page=ContestPrice&contestID='.$this->contest->contestID.'&priceID='.$price->priceID.SID_ARG_2ND_NOT_ENCODED.'#priceObj'.$price->priceID);
		exit;
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

			$this->validate();

			// no errors
			$this->save();
		}
		catch (UserInputException $e) {
			$this->errorField = $e->getField();
			$this->errorType = $e->getType();
		}
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		// display branding
		require_once(WCF_DIR.'lib/util/ContestUtil.class.php');
		ContestUtil::assignVariablesBranding();

		WCF::getTPL()->assign(array(
			'states' => $this->states,
			'state' => $this->state,
			'availableGroups' => $this->availableGroups,
			'ownerID' => $this->ownerID,
			'secretMessage' => $this->secretMessage,
			'maxTextLength' => WCF::getUser()->getPermission('user.contest.maxSolutionLength')
		));
	}

	/**
	 * @see Page::show()
	 */
	public function show() {

		// check upload permission
		if (MODULE_ATTACHMENT != 1 || !WCF::getUser()->getPermission('user.contest.canUploadAttachment')) {
			$this->showAttachments = false;
		}

		// get attachments editor
		if ($this->attachmentListEditor === null) {
			$max = min(WCF::getUser()->getPermission('user.contest.maxAttachmentCount'), 1);
			$extensions = WCF::getUser()->getPermission('user.contest.allowedAttachmentExtensions');
			$extensions = explode("\n", $extensions);
			$extensions = array_filter($extensions, create_function('$a', 'return in_array($a, array("jpeg", "jpg", "gif", "png"));'));
			$extensions = implode("\n", $extensions);
			require_once(WCF_DIR.'lib/data/attachment/MessageAttachmentListEditor.class.php');
			$this->attachmentListEditor = new MessageAttachmentListEditor(array(), 'contestPriceEntry', WCF::getPackageID('de.easy-coding.wcf.contest'), WCF::getUser()->getPermission('user.contest.maxAttachmentSize'), $extensions, $max);
		}

		parent::show();
	}
}
?>
