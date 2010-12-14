<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/interaction/ruleset/ContestInteractionRuleset.class.php');

/**
 * Provides functions to manage entry rulesets.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestInteractionRulesetEditor extends ContestInteractionRuleset {
	/**
	 * Creates a new entry ruleset.
	 *
	 * @param	string		$kind
	 * @param	string		$rulesetTable
	 * @param	string		$rulesetColumn
	 * @return	ContestInteractionRulesetEditor
	 */
	public static function create($kind, $rulesetTable, $rulesetColumn) {
		$sql = "INSERT INTO	wcf".WCF_N."_contest_interaction_ruleset
					(kind, rulesetTable, rulesetColumn)
			VALUES		('".escapeString($kind)."', '".escapeString($rulesetTable)."', '".escapeString($rulesetColumn)."')";
		WCF::getDB()->sendQuery($sql);
		
		// get id
		$rulesetID = WCF::getDB()->getInsertID("wcf".WCF_N."_contest_interaction_ruleset", 'rulesetID');
		
		return new ContestInteractionRulesetEditor($rulesetID);
	}
	
	/**
	 * Updates this entry ruleset.
	 *
	 * @param	string		$kind
	 * @param	string		$rulesetTable
	 * @param	string		$rulesetColumn
	 */
	public function update($kind, $rulesetTable, $rulesetColumn) {
		$sql = "UPDATE	wcf".WCF_N."_contest_interaction_ruleset
			SET	kind = '".escapeString($kind)."',
				rulesetTable = '".escapeString($rulesetTable)."',
				rulesetColumn = '".escapeString($rulesetColumn)."'
			WHERE	rulesetID = ".intval($this->rulesetID);
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Deletes this entry ruleset.
	 */
	public function delete() {
		
		// delete ruleset
		$sql = "DELETE FROM	wcf".WCF_N."_contest_interaction_ruleset
			WHERE		rulesetID = ".intval($this->rulesetID);
		WCF::getDB()->sendQuery($sql);
	}
}
?>
