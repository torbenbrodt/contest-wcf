<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a contest entry interaction ruleset.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestInteractionRuleset extends DatabaseObject {
	/**
	 * Creates a new ContestInteractionRuleset object.
	 *
	 * @param	integer		$rulesetID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($rulesetID, $row = null) {
		if ($rulesetID !== null) {
			$sql = "SELECT		*
				FROM 		wcf".WCF_N."_contest_interaction_ruleset
				WHERE 		rulesetID = ".intval($rulesetID);
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}
	
	/**
	 * Returns an editor object for this rating.
	 *
	 * @return	ContestInteractionRulesetEditor
	 */
	public function getEditor() {
		require_once(WCF_DIR.'lib/data/contest/interaction/ruleset/ContestInteractionRulesetEditor.class.php');
		return new ContestInteractionRulesetEditor(null, $this->data);
	}
}
?>
