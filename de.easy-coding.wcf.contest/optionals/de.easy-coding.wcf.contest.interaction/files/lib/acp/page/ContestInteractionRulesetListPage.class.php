<?php
// wcf imports
require_once(WCF_DIR.'lib/page/SortablePage.class.php');
require_once(WCF_DIR.'lib/system/event/EventHandler.class.php');

/**
 * Shows a list of all user rulesets.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @package	de.easy-coding.wcf.contest.interaction
 */
class ContestInteractionRulesetListPage extends SortablePage {
	public $templateName = 'contestInteractionRulesetList';
	public $deletedrulesets = 0;
	public $rulesets = array();
	public $defaultSortField = 'rulesetTable';
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// detect group deletion
		if (isset($_REQUEST['deletedrulesets'])) {
			$this->deletedrulesets = intval($_REQUEST['deletedrulesets']);
		}
	}
	
	/**
	 * @see SortablePage::validateSortField()
	 */
	public function validateSortField() {
		parent::validateSortField();

		switch ($this->sortField) {
			case 'kind':
			case 'rulesetTable':
			case 'rulesetColumn':
			case 'rulesetColumnTime': break;
			default: $this->sortField = $this->defaultSortField;
		}
	}
	
	/**
	 * @see MultipleLinkPage::countItems()
	 */
	public function countItems() {
		parent::countItems();
		
		$sql = "SELECT	COUNT(*) AS count
			FROM	wcf".WCF_N."_contest_interaction_ruleset";
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function readData() {
		parent::readData();
		
		if ($this->items) {
			$sql = "SELECT		contest_interaction_ruleset.*
				FROM		wcf".WCF_N."_contest_interaction_ruleset contest_interaction_ruleset
				ORDER BY	".$this->sortField." ".$this->sortOrder;
			$result = WCF::getDB()->sendQuery($sql, $this->itemsPerPage, ($this->pageNo - 1) * $this->itemsPerPage);
			while ($row = WCF::getDB()->fetchArray($result)) {
				$row['deletable'] = WCF::getUser()->getPermission('admin.user.canEditGroup');
				$row['editable'] = WCF::getUser()->getPermission('admin.user.canEditGroup');
				
				$this->rulesets[] = $row;
			}
		}
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'rulesets' => $this->rulesets,
			'deletedrulesets' => $this->deletedrulesets
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.group.view');
		
		// check permission
		WCF::getUser()->checkPermission(array('admin.user.canEditGroup', 'admin.user.canDeleteGroup'));
		 // TODO: update permission
		
		parent::show();
	}
}
?>
