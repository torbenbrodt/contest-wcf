<?php
// wcf imports
require_once(WCF_DIR.'lib/form/ContestJurytalkAddForm.class.php');

/**
 * Shows the form for editing contest entry jurytalks.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestJurytalkEditForm extends ContestJurytalkAddForm {
	/**
	 * jurytalk editor
	 *
	 * @var ContestJurytalkEditor
	 */
	public $jurytalkObj = null;
	
	/**
	 * Creates a new ContestJurytalkEditForm object.
	 *
	 * @param	ContestJurytalk		$jurytalk
	 */
	public function __construct(ContestJurytalk $jurytalk) {
		$this->jurytalkObj = $jurytalk->getEditor();
		AbstractForm::__construct();
	}
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		AbstractForm::readParameters();
		
		// get jurytalk
		if (!$this->jurytalkObj->isEditable()) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		AbstractForm::save();
		
		// save jurytalk
		$this->jurytalkObj->update($this->message);
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestJurytalk&contestID='.$this->jurytalkObj->contestID.'&jurytalkID='.$this->jurytalkObj->jurytalkID.SID_ARG_2ND_NOT_ENCODED.'#jurytalk'.$this->jurytalkObj->jurytalkID);
		exit;
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (!count($_POST)) {
			$this->jurytalk = $this->jurytalkObj->jurytalk;
		}
	}
}
?>
