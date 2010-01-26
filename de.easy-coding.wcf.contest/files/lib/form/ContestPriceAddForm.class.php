<?php
// wcf imports
require_once(WCF_DIR.'lib/form/AbstractForm.class.php');
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');
require_once(WCF_DIR.'lib/data/contest/price/ContestPriceEditor.class.php');
require_once(WCF_DIR.'lib/util/ContestUtil.class.php');

/**
 * Shows the form for adding contest entry prices.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestPriceAddForm extends AbstractForm {
	// form parameters
	public $ownerID = 0;
	public $userID = 0;
	public $groupID = 0;
	public $subject = '';
	public $message = '';
	
	public $states = array();
	public $state = '';
	
	/**
	 * entry editor
	 *
	 * @var Contest
	 */
	public $entry = null;
	
	/**
	 * available groups
	 *
	 * @var array<Group>
	 */
	protected $availableGroups = array();
	
	/**
	 * Creates a new ContestPriceAddForm object.
	 *
	 * @param	Contest	$entry
	 */
	public function __construct(Contest $entry) {
		$this->entry = $entry;
		parent::__construct();
	}
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get entry
		if (!$this->entry->isPriceable()) {
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
		
		$this->states = ContestPriceEditor::getStates();

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
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		$sponsor = ContestSponsor::find($this->entry->contestID, $this->userID, $this->groupID);
		$state = 'invited';
		if($sponsor === null) {
			require_once(WCF_DIR.'lib/data/contest/price/ContestSponsorEditor.class.php');
			$sponsor = ContestSponsorEditor::create($this->entry->contestID, $this->userID, $this->groupID, $state);
		}
		$position = 0; // TODO: price, allow position
		
		// save price
		$price = ContestPriceEditor::create($this->entry->contestID, $sponsor->sponsorID, $this->subject, $this->message, $position);
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestPrice&contestID='.$this->entry->contestID.'&priceID='.$price->priceID.SID_ARG_2ND_NOT_ENCODED.'#price'.$price->priceID);
		exit;
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
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
