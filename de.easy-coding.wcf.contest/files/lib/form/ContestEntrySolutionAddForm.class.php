<?php
// wcf imports
require_once(WCF_DIR.'lib/form/CaptchaForm.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestEntry.class.php');
require_once(WCF_DIR.'lib/data/contest/solution/ContestEntrySolutionEditor.class.php');

/**
 * Shows the form for adding contest entry solutions.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestEntrySolutionAddForm extends CaptchaForm {
	// parameters
	public $solution = '';
	public $username = '';
	
	/**
	 * entry editor
	 *
	 * @var ContestEntry
	 */
	public $entry = null;
	
	/**
	 * Creates a new ContestEntrySolutionAddForm object.
	 *
	 * @param	ContestEntry	$entry
	 */
	public function __construct(ContestEntry $entry) {
		$this->entry = $entry;
		parent::__construct();
	}
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get entry
		if (!$this->entry->isSolutionable()) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		// get parameters
		if (isset($_POST['solution'])) $this->solution = StringUtil::trim($_POST['solution']);
		if (isset($_POST['username'])) $this->username = StringUtil::trim($_POST['username']);
	}
	
	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();
		
		if (empty($this->solution)) {
			throw new UserInputException('solution');
		}
		
		if (StringUtil::length($this->solution) > WCF::getUser()->getPermission('user.contest.maxSolutionLength')) {
			throw new UserInputException('solution', 'tooLong');
		}
		
		// username
		$this->validateUsername();
	}
	
	/**
	 * Validates the username.
	 */
	protected function validateUsername() {
		// only for guests
		if (WCF::getUser()->userID == 0) {
			// username
			if (empty($this->username)) {
				throw new UserInputException('username');
			}
			if (!UserUtil::isValidUsername($this->username)) {
				throw new UserInputException('username', 'notValid');
			}
			if (!UserUtil::isAvailableUsername($this->username)) {
				throw new UserInputException('username', 'notAvailable');
			}
			
			WCF::getSession()->setUsername($this->username);
		}
		else {
			$this->username = WCF::getUser()->username;
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		// save solution
		$solution = ContestEntrySolutionEditor::create($this->entry->contestID, $this->entry->userID, $this->solution, WCF::getUser()->userID, $this->username);
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestEntry&contestID='.$this->entry->contestID.'&solutionID='.$solution->solutionID.SID_ARG_2ND_NOT_ENCODED.'#solution'.$solution->solutionID);
		exit;
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'solution' => $this->solution,
			'username' => $this->username,
			'maxTextLength' => WCF::getUser()->getPermission('user.contest.maxSolutionLength')
		));
	}
}
?>
