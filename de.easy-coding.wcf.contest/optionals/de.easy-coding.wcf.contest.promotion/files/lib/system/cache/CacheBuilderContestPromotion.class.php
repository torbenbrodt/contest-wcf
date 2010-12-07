<?php
// wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

/**
 * Caches the active promotion
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.promotion
 */
class CacheBuilderContestPromotion implements CacheBuilder {

	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		list($cache, $packageID, $languageID) = explode('-', $cacheResource['cache']);

		// get all menu items and filter menu items with low priority
		$sql = "SELECT		contest_promotion.*
			FROM		wcf".WCF_N."_contest_promotion contest_promotion
			INNER JOIN	wcf".WCF_N."_contest contest USING(contestID)
			WHERE		contest.state = 'scheduled'
			AND		contest.fromTime < ".TIME_NOW."
			AND		contest.untilTime > ".TIME_NOW."
			AND		contest_promotion.languageID = ".intval($languageID);
		$result = WCF::getDB()->sendQuery($sql);
	
		$notifications = array();
		while ($row = WCF::getDB()->fetchArray($result)) {
			$notifications[$row['contestID']] = $row;
		}
		
		return $notifications;
	}
}
?>
