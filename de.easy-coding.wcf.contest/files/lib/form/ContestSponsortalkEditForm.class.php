<?php
// wcf imports
require_once(WCF_DIR.'lib/form/ContestSponsortalkAddForm.class.php');

/**
 * Shows the form for editing contest entry sponsortalks.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSponsortalkEditForm extends ContestSponsortalkAddForm {
	/**
	 * sponsortalk editor
	 *
	 * @var ContestSponsortalkEditor
	 */
	public $sponsortalkObj = null;
	
	/**
	 * Creates a new ContestSponsortalkEditForm object.
	 *
	 * @param	ContestSponsortalk		$sponsortalk
	 */
	public function __construct(ContestSponsortalk $sponsortalk) {
		$this->sponsortalkObj = $sponsortalk->getEditor();
		CaptchaForm::__construct();
	}
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		CaptchaForm::readParameters();
		
		// get sponsortalk
		if (!$this->sponsortalkObj->isEditable()) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		CaptchaForm::save();
		
		// save sponsortalk
		$this->sponsortalkObj->update($this->message);
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestSponsortalk&contestID='.$this->sponsortalkObj->contestID.'&sponsortalkID='.$this->sponsortalkObj->sponsortalkID.SID_ARG_2ND_NOT_ENCODED.'#sponsortalk'.$this->sponsortalkObj->sponsortalkID);
		exit;
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (!count($_POST)) {
			$this->sponsortalk = $this->sponsortalkObj->sponsortalk;
		}
	}
}
?>
