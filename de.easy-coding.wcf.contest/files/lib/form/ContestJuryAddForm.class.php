<?php
// wcf imports
require_once(WCF_DIR.'lib/form/AbstractForm.class.php');
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');
require_once(WCF_DIR.'lib/data/contest/jury/ContestJuryEditor.class.php');
require_once(WCF_DIR.'lib/util/ContestUtil.class.php');

/**
 * Shows the form for adding contest contest jurys.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestJuryAddForm extends AbstractForm {
	// form parameters
	public $ownerID = 0;
	public $userID = 0;
	public $groupID = 0;
	
	public $states = array();
	public $state = 'applied';
	
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
	 * Creates a new ContestJuryAddForm object.
	 *
	 * @param	Contest	$contest
	 */
	public function __construct(Contest $contest) {
		$this->contest = $contest;
		parent::__construct();
	}
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get contest
		if (!$this->contest->isJuryable()) {
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
		
		$this->states = ContestJuryEditor::getStates();

		// owner
		$this->availableGroups = ContestUtil::readAvailableGroups();
	}
	
	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();
		
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
		
		// save jury
		$jury = ContestJuryEditor::create($this->contest->contestID, WCF::getUser()->userID, $this->groupID, $this->state);
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestJury&contestID='.$this->contest->contestID.'&juryID='.$jury->juryID.SID_ARG_2ND_NOT_ENCODED.'#jury'.$jury->juryID);
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
		));
	}
}
?>
