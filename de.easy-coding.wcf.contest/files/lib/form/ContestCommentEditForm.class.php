<?php
// wcf imports
require_once(WCF_DIR.'lib/form/ContestCommentAddForm.class.php');

/**
 * Shows the form for editing contest entry solutions.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestCommentEditForm extends ContestCommentAddForm {
	/**
	 * solution editor
	 *
	 * @var ContestCommentEditor
	 */
	public $entry = null;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		CaptchaForm::readParameters();
		
		if (isset($_REQUEST['id'])) $this->commentID = intval($_REQUEST['id']);
		$this->entry = new ContestCommentEditor($this->commentID);
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
		$this->entry->update($this->solution);
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=Contest&contestID='.$this->entry->contestID.'&solutionID='.$this->entry->solutionID.SID_ARG_2ND_NOT_ENCODED.'#solution'.$this->entry->solutionID);
		exit;
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (!count($_POST)) {
			$this->solution = $this->entry->solution;
		}
	}
}
?>
