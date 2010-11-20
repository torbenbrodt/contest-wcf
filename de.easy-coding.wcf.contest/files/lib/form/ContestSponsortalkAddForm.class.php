<?php
// wcf imports
require_once(WCF_DIR.'lib/form/AbstractForm.class.php');
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');
require_once(WCF_DIR.'lib/data/contest/sponsortalk/ContestSponsortalkEditor.class.php');

/**
 * Shows the form for adding contest contest sponsortalks.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSponsortalkAddForm extends AbstractForm {
	// parameters
	public $message = '';
	
	/**
	 * contest editor
	 *
	 * @var Contest
	 */
	public $contest = null;
	
	/**
	 * Creates a new ContestSponsortalkAddForm object.
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
		if (!$this->contest->isSponsortalkable()) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		// get parameters
		if (isset($_POST['message'])) $this->message = StringUtil::trim($_POST['message']);
	}
	
	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();
		
		if (empty($this->message)) {
			throw new UserInputException('message');
		}
		
		if (StringUtil::length($this->message) > WCF::getUser()->getPermission('user.contest.maxSolutionLength')) {
			throw new UserInputException('message', 'tooLong');
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		// save sponsortalk
		$sponsortalk = ContestSponsortalkEditor::create($this->contest->contestID, $this->message, WCF::getUser()->userID, WCF::getUser()->username);
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestSponsortalk&contestID='.$this->contest->contestID.'&sponsortalkID='.$sponsortalk->sponsortalkID.SID_ARG_2ND_NOT_ENCODED.'#sponsortalk'.$sponsortalk->sponsortalkID);
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
			'message' => $this->message,
			'maxTextLength' => WCF::getUser()->getPermission('user.contest.maxSolutionLength')
		));
	}
}
?>
