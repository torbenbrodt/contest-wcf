<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');
require_once(WCF_DIR.'lib/acp/package/update/ContestInteractionRulesetEditor.class.php');

/**
 * Shows the server add form.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @package	de.easy-coding.wcf.contest.interaction
 */
class ContestInteractionRulesetAddForm extends ACPForm {
	public $templateName = 'contestInteractionRulesetAdd';
	public $activeMenuItem = 'wcf.acp.menu.link.contest.interaction';
	public $neededPermissions = 'admin.system.package.canEditServer'; // TODO: update permission
	public $action = 'add';
	public $rulesetID = 0;
	
	public $kind = '';
	public $rulesetTable = '';
	public $rulesetColumn = '';
	public $rulesetColumnTime = '';
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['kind'])) $this->kind = StringUtil::trim($_POST['kind']);
		if (isset($_POST['rulesetTable'])) $this->rulesetTable = StringUtil::trim($_POST['rulesetTable']);
		if (isset($_POST['rulesetColumn'])) $this->rulesetColumn = StringUtil::trim($_POST['rulesetColumn']);
		if (isset($_POST['rulesetColumnTime'])) $this->rulesetColumnTime = StringUtil::trim($_POST['rulesetColumnTime']);
	}
	
	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();
		
		if (empty($this->kind)) {
			throw new UserInputException('kind');
		}
		
		if (empty($this->rulesetTable)) {
			throw new UserInputException('rulesetTable');
		}
		
		if (empty($this->rulesetColumn)) {
			throw new UserInputException('rulesetColumn');
		}
		
		if (empty($this->rulesetColumnTime)) {
			throw new UserInputException('rulesetColumnTime');
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		// save server
		$this->rulesetID = ContestInteractionRulesetEditor::create($this->server, $this->htUsername, $this->htPassword);
		$this->saved();
		
		// reset values
		$this->kind = $this->rulesetTable = $this->rulesetColumn = $this->rulesetColumnTime = '';
		
		// show success message
		WCF::getTPL()->assign('success', true);
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
	
		WCF::getTPL()->assign(array(
			'kind' => $this->kind,
			'rulesetTable' => $this->rulesetTable,
			'rulesetColumn' => $this->rulesetColumn,
			'rulesetColumnTime' => $this->rulesetColumnTime,
			'action' => $this->action
		));
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function show() {
		// check master password
		WCFACP::checkMasterPassword();
		
		parent::show();
	}
}
?>
