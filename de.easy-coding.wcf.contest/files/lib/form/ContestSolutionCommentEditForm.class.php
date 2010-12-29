<?php
// wcf imports
require_once(WCF_DIR.'lib/form/ContestSolutionCommentAddForm.class.php');

/**
 * Shows the form for editing contest entry solutions.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSolutionCommentEditForm extends ContestSolutionCommentAddForm {
	/**
	 * entry id
	 *
	 * @var integer
	 */
	public $commentID = 0;
	
	/**
	 * solution editor
	 *
	 * @var ContestSolutionCommentEditor
	 */
	public $entry = null;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		CaptchaForm::readParameters();
		
		if (isset($_REQUEST['commentID'])) $this->commentID = intval($_REQUEST['commentID']);
		$this->entry = new ContestSolutionCommentEditor($this->commentID);
		if (!$this->entry->commentID || !$this->entry->isEditable()) {
			throw new IllegalLinkException();
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		CaptchaForm::save();
		
		// save solution
		$this->entry->update($this->comment);
		$this->saved();

		// get solution/contest
		require_once(WCF_DIR.'lib/data/contest/solution/ContestSolution.class.php');
		$solution = new ContestSolution($this->entry->solutionID);

		// forward
		HeaderUtil::redirect('index.php?page=ContestSolutionEntry'.
			'&contestID='.$solution->contestID.
			'&solutionID='.$solution->solutionID.
			SID_ARG_2ND_NOT_ENCODED.
			'#comment'.$this->entry->commentID
		);
		exit;
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (!count($_POST)) {
			$this->comment = $this->entry->comment;
		}
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'action' => 'edit',
			'commentID' => $this->commentID
		));
	}
}
?>
