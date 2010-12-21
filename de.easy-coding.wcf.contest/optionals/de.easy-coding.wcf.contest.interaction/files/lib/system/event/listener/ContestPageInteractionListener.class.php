<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * show tickets.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.interaction
 */
class ContestPageInteractionListener implements EventListener {

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
	
		if($eventObj->entry->enableInteraction == 0) {
			return;
		}
	
		// show button to finish contest
		if($eventObj->entry->isOwner() && $eventObj->entry->state == 'scheduled' && $eventObj->entry->untilTime < time()) {
			WCF::getTPL()->append('userMessages', '<p class="info">hello admin, want to finish the interactive contest? <a href="index.php?action=ContestInteractionClose&amp;contestID='.$eventObj->entry->contestID.'&amp;t='.SECURITY_TOKEN.SID_ARG_2ND.'">YES</a></p>');
		}

		WCF::getTPL()->assign(array(
			'contestID' => $eventObj->entry->contestID
		));
		WCF::getTPL()->append('additionalMessageContent', WCF::getTPL()->fetch('contestInteractionScript'));
	}
}
?>
