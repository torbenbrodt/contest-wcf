<?php
// wcf imports
require_once(WCF_DIR.'lib/form/ContestParticipantAddForm.class.php');

/**
 * Shows the form for editing a participant entry.
 * maybe user just got an invitation, we have to display participanttalk
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestParticipantEditForm extends ContestParticipantAddForm {
	/**
	 * entry id
	 *
	 * @var integer
	 */
	public $participantID = 0;
	
	/**
	 * entry editor object
	 *
	 * @var ContestParticipantEditor
	 */
	public $entry = null;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['participantID'])) $this->participantID = intval($_REQUEST['participantID']);
		$this->entry = new ContestParticipantEditor($this->participantID);
		if (!$this->entry->participantID || !$this->entry->isEditable()) {
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
		HeaderUtil::redirect('index.php?page=ContestParticipant&participantID='.$this->entry->participantID.SID_ARG_2ND_NOT_ENCODED.'#entry'.$this->entry->participantID);
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
			'participantID' => $this->participantID
		));
	}
}
?>
