<?php
// wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

/**
 * Caches the contest menu items tree.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class CacheBuilderContestMenu implements CacheBuilder {
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		$data = array();

		// get all menu items and filter menu items with low priority
		$sql = "SELECT		menuItem, menuItemID 
			FROM		wcf".WCF_N."_contest_menu_item menu_item";
		$result = WCF::getDB()->sendQuery($sql);
		$itemIDs = array();
		while ($row = WCF::getDB()->fetchArray($result)) {
			$itemIDs[$row['menuItem']] = $row['menuItemID'];
		}
		
		if (count($itemIDs) > 0) {
			// get needed menu items and build item tree
			$sql = "SELECT		*
				FROM		wcf".WCF_N."_contest_menu_item menu_item
				WHERE		menuItemID IN (".implode(',', $itemIDs).")
				ORDER BY	showOrder";
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				if (!isset($data[$row['parentMenuItem']])) {
					$data[$row['parentMenuItem']] = array();
				}
				
				$data[$row['parentMenuItem']][] = $row;
			}
		}
		
		return $data;
	}
}
?>
