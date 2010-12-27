<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/form/ContestInteractionRulesetAddForm.class.php');

/**
 * Shows the server edit form.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @package	de.easy-coding.wcf.contest.interaction
 */
class ContestInteractionRulesetEditForm extends ContestInteractionRulesetAddForm {
	public $action = 'edit';
	
	public $entry;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['rulesetID'])) $this->rulesetID = intval($_REQUEST['rulesetID']);
		$this->entry = new ContestInteractionRulesetEditor($this->rulesetID);
		if (!$this->entry->rulesetID) {
			throw new IllegalLinkException();
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		AbstractForm::save();
		
		// save server
		$this->entry->update($this->kind, $this->rulesetTable, $this->rulesetColumn, $this->rulesetColumnTime);
		$this->saved();
		
		// show success message
		WCF::getTPL()->assign('success', true);
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (!count($_POST)) {
			$this->kind = $this->entry->kind;
			$this->rulesetTable = $this->entry->rulesetTable;
			$this->rulesetColumn = $this->entry->rulesetColumn;
			$this->rulesetColumnTime = $this->entry->rulesetColumnTime;
		}
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
			
		WCF::getTPL()->assign(array(
			'rulesetID' => $this->rulesetID,
		));
	}
}
?>
