<?php

/**
 * Tool support for contest promotions.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.promotion
 */
class ContestPromotionUtil {

	/**
	 * hide promotion for active user
	 */
	public static function updateList($contestID) {
		if(WCF::getUser()->userID) {
			$str = WCF::getUser()->contestPromotionDisabled;
			$str = trim($str.','.$contestID, ',');

			$editor = WCF::getUser()->getEditor();
			$editor->updateFields(array(
				'contestPromotionDisabled' => $str
			));
		} else {
			$cacheName = 'contest-promotion';
			$str = WCF::getSession()->getVar($cacheName);
			$str = trim($str.','.$contestID, ',');

			WCF::getSession()->register($cacheName, $str);
		}
	}

	/**
	 * hide promotion for active user
	 */
	protected static function getPromotions() {
		$languageID = WCF::getLanguage()->getLanguageID();
		$cacheName = 'contest.promotion-'.PACKAGE_ID.'-'.$languageID;

		WCF::getCache()->addResource($cacheName, WCF_DIR.'cache/'.$cacheName.'.php',
			WCF_DIR.'lib/system/cache/CacheBuilderContestPromotion.class.php', 0, 3600);

		return (array)WCF::getCache()->get($cacheName);
	}

	/**
	 * hide promotion for active user
	 */
	public static function getList() {
		$notifications = self::getPromotions();

		if(!$notifications) {
			return array();
		}

		// hide known promotions
		$cacheName = 'contest-promotion';
		$str = WCF::getSession()->getVar($cacheName);
		$str .= ','.WCF::getUser()->contestPromotionDisabled;
		$known = array_filter(explode(',', trim($str)));
		foreach($known as $contestID) {
			unset($notifications[$contestID]);
		}
		
		return $notifications;
	}
}
?>
