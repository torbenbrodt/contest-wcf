<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * show tickets.
 * 
 * @author	Torben Brodt
 * @copyright	2011 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.interaction
 */
class ContestSidebarInteractionListener implements EventListener {

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
	
		if(!$eventObj->contest || $eventObj->contest->enableInteraction == 0) {
			return;
		}

		// show form to give extra points
		if($eventObj->contest->isOwner()) {

			// save
			if(isset($_POST['saveExtraPoints'])) {
				$contestID = intval($eventObj->contest->contestID);
				$participantID = intval($_POST['participantID']);
				$score = intval($_POST['score']);

				$sql = "INSERT INTO	wcf".WCF_N."_contest_interaction_extra
							(contestID, participantID, score)
					VALUES		(".$contestID.", ".$participantID.", ".$score.")";
				WCF::getDB()->sendQuery($sql);
			}
		
			WCF::getTPL()->append('additionalBoxes1', WCF::getTPL()->fetch('contestInteractionExtraSidebar'));
		}
	}
}
?>
