<?php
// wcf imports
require_once(WCF_DIR.'lib/form/ContestJuryAddForm.class.php');

/**
 * Shows the form for editing a jury entry.
 * maybe user just got an invitation, we have to display jurytalk
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestJuryEditForm extends ContestJuryAddForm {
	/**
	 * entry id
	 *
	 * @var integer
	 */
	public $juryID = 0;
	
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
		MessageForm::readParameters();
		
		if (isset($_REQUEST['juryID'])) $this->juryID = intval($_REQUEST['juryID']);
		$this->entry = new ContestJuryEditor($this->juryID);
		if (!$this->entry->juryID || !$this->entry->isEditable()) {
			throw new IllegalLinkException();
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		AbstractForm::save();
		
		// save entry
		$this->entry->update($this->contestID, $this->userID, $this->groupID, $this->state);
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestJury&juryID='.$this->entry->juryID.SID_ARG_2ND_NOT_ENCODED.'#entry'.$this->entry->juryID);
		exit;
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		// default values
		if (!count($_POST)) {
			$this->contestID = $this->entry->contestID;
			$this->userID = $this->entry->userID;
			$this->groupID = $this->entry->groupID;
			$this->state =  $this->entry->state;
		}
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'action' => 'edit',
			'juryID' => $this->juryID
		));
	}
}
?>
