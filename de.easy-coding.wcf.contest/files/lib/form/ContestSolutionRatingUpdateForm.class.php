<?php
// wcf imports
require_once(WCF_DIR.'lib/form/AbstractSecureForm.class.php');
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');
require_once(WCF_DIR.'lib/data/contest/solution/rating/ContestSolutionRatingEditor.class.php');

/**
 * Shows the form for updateing contest optionIDs.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSolutionRatingUpdateForm extends AbstractSecureForm {
	/**
	 * insert new ratings
	 * @var array
	 */
	protected $optionIDs = array();
	
	/**
	 * contest solution updateor
	 *
	 * @var ContestSolution
	 */
	public $solutionObj = null;

	/**
	 * caller will call many forms, every form needs to know if it has been called or not
	 *
	 * @var boolean
	 */
	protected $doSave = false;
	
	/**
	 * Creates a new ContestSolutionRatingUpdateForm object.
	 *
	 * @param	ContestSolution	$contest
	 */
	public function __construct(ContestSolution $contest) {
		$this->solutionObj = $contest;
		parent::__construct();
	}
	
	/**
	 * @see Form::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get contest
		if (!$this->solutionObj->isRateable()) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		$this->doSave = isset($_POST[get_class($this)]);
		
		// insert new
		if (isset($_POST['optionIDs']) && is_array($_POST['optionIDs'])) {
			$this->optionIDs = ArrayUtil::toIntegerArray($_POST['optionIDs']);
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		// save rating
		ContestSolutionRatingEditor::updateRatings($this->solutionObj->solutionID, WCF::getUser()->userID, $this->optionIDs);
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestSolutionEntry&contestID='.$this->solutionObj->contestID.'&solutionID='.$this->solutionObj->solutionID);
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
			if($this->doSave) {
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
}
?>
