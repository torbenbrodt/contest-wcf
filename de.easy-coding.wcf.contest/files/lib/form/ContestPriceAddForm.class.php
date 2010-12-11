<?php
// wcf imports
require_once(WCF_DIR.'lib/form/AbstractSecureForm.class.php');
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
class ContestPriceAddForm extends AbstractSecureForm {
	// form parameters
	public $ownerID = 0;
	public $userID = 0;
	public $groupID = 0;
	public $subject = '';
	public $message = '';
	
	public $states = array();
	public $state = '';
	
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
		if (isset($_POST['message'])) $this->message = StringUtil::trim($_POST['message']);
		
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
		
		if (empty($this->message)) {
			throw new UserInputException('message');
		}
		
		if (StringUtil::length($this->message) > WCF::getUser()->getPermission('user.contest.maxSolutionLength')) {
			throw new UserInputException('message', 'tooLong');
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
		$price = ContestPriceEditor::create($this->contest->contestID, $sponsor->sponsorID, $this->subject, $this->message);
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestPrice&contestID='.$this->contest->contestID.'&priceID='.$price->priceID.SID_ARG_2ND_NOT_ENCODED.'#price'.$price->priceID);
		exit;
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
			'subject' => $this->subject,
			'message' => $this->message,
			'maxTextLength' => WCF::getUser()->getPermission('user.contest.maxSolutionLength')
		));
	}
}
?>
