<?php
// wcf imports
require_once(WCF_DIR.'lib/form/ContestJurytalkAddForm.class.php');

/**
 * Shows the form for editing contest entry jurytalks.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestJurytalkEditForm extends ContestJurytalkAddForm {
	
	/**
	 * entry editor object
	 *
	 * @var ContestJuryEditor
	 */
	public $entry = null;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		AbstractForm::readParameters();
		
		if (isset($_REQUEST['jurytalkID'])) $this->jurytalkID = intval($_REQUEST['jurytalkID']);
		$this->entry = new ContestJurytalkEditor($this->jurytalkID);
		if (!$this->entry->jurytalkID || !$this->entry->isEditable()) {
			throw new IllegalLinkException();
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		AbstractForm::save();
		
		// save jurytalk
		$this->entry->update($this->message);
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestJurytalk&contestID='.$this->entry->contestID.'&jurytalkID='.$this->entry->jurytalkID.SID_ARG_2ND_NOT_ENCODED.'#jurytalk'.$this->entry->jurytalkID);
		exit;
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (!count($_POST)) {
			$this->jurytalk = $this->entry->jurytalk;
			$this->message = $this->entry->message;
		}
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'action' => 'edit',
			'message' => $this->message,
			'jurytalkID' => $this->jurytalkID
		));
	}
}
?>
