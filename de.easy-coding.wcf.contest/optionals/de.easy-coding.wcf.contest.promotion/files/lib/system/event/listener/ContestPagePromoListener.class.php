<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * show button to edit promotion settings
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @package	de.easy-coding.wcf.contest.promotion
 */
class ContestPagePromoListener implements EventListener {

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		// only show button to contest owner with special permissions
		if($eventObj->isOwner() == false ||  WCF::getUser()->getPermission('mod.contest.canAddPromotion') == false) {
			return;
		}

		// link
		$link = 'index.php?page=ContestPromotion&amp;contestID='.$eventObj->contestID.SID_ARG_2ND_NOT_ENCODED;

		// button
		WCF::getTPL()->append('additionalSmallButtons', '<li>'.
			'<a href="'.$link.'" title="'.WCF::getLanguage()->get('wcf.contest.promotion.description').'">'.
				'<img src="'.RELATIVE_WCF_DIR.'icon/contestPromotionS.png" alt="" /> '.
				'<span>'.WCF::getLanguage()->get('wcf.contest.promotion').'</span>'.
			'</a>'.
		'</li>');
	}
}
?>
