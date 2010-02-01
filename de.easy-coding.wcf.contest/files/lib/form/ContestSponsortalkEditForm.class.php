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
	 * entry editor object
	 *
	 * @var ContestSponsorEditor
	 */
	public $entry = null;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		AbstractForm::readParameters();
		
		if (isset($_REQUEST['sponsortalkID'])) $this->sponsortalkID = intval($_REQUEST['sponsortalkID']);
		$this->entry = new ContestSponsortalkEditor($this->sponsortalkID);
		if (!$this->entry->sponsortalkID || !$this->entry->isEditable()) {
			throw new IllegalLinkException();
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		AbstractForm::save();
		
		// save sponsortalk
		$this->entry->update($this->message);
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestSponsortalk&contestID='.$this->entry->contestID.'&sponsortalkID='.$this->entry->sponsortalkID.SID_ARG_2ND_NOT_ENCODED.'#sponsortalk'.$this->entry->sponsortalkID);
		exit;
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (!count($_POST)) {
			$this->sponsortalk = $this->entry->sponsortalk;
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
			'sponsortalkID' => $this->sponsortalkID
		));
	}
}
?>
