<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/util/ContestPromotionUtil.class.php');

/**
 * contest promotion notification listener
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @package	de.easy-coding.wcf.contest.promotion
 */
class ContestPromoListener implements EventListener {
	private static $executed = false;

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if(self::$executed) {
			return;
		}
		self::$executed = true;

		$notifications = ContestPromotionUtil::getList();
		if(count($notifications)) {
			WCF::getTPL()->assign('contestPromotionNotifications', $notifications);
			WCF::getTPL()->append('userMessages',  WCF::getTPL()->fetch('contestPromotionNotification'));
		}
	}
}
?>
