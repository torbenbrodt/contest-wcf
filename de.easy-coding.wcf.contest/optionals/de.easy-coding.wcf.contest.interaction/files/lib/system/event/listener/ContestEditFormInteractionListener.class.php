<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * editor for rulesets.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.interaction
 */
class ContestEditFormInteractionListener implements EventListener {
	protected $enableInteraction = false;
	protected $rulesetList = null;

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		$this->eventObj = $eventObj;
		$this->className = $className;
		
		$this->$eventName();
	}
	
	/**
	 *
	 */
	public function readFormParameters() {
		$this->enableInteraction = isset($_POST['enableInteraction']);
	}
	
	/**
	 *
	 */
	public function readData() {
		if(count($_POST)) {
			$this->enableInteraction = $this->eventObj->entry->enableInteraction;
		}
		
		if($this->enableInteraction) {
			require_once(WCF_DIR.'lib/data/contest/interaction/ruleset/ContestInteractionRulesetList.class.php');
			$this->rulesetList = new ContestInteractionRulesetList();
			$this->rulesetList->sqlConditions .= ' contestID = '.intval($this->eventObj->entry->contestID);
			$this->rulesetList->readObjects();
		}
	}
	
	/**
	 *
	 */
	public function save() {
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	enableInteraction = ".intval($this->enableInteraction)."
			WHERE	contestID = ".intval($this->eventObj->entry->contestID);
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 *
	 */
	public function assignVariables() {
		WCF::getTPL()->assign(array(
			'enableInteraction' => $this->enableInteraction,
			'interactionRulesetList' => $this->rulesetList ? $this->rulesetList->getObjects() : array(),
		));
		
		WCF::getTPL()->append('additionalFields2', WCF::getTPL()->fetch('contestEntryAddStepInteraction'));
	}
}
?>
