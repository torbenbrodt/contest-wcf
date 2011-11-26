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

		// show coupon field
		$this->executeCoupon();

		// contest owner might give extra points
		$this->executeExtraPoints();
	}

	/**
	 *
	 */
	protected function executeCoupon() {
		// TODO: check for contest->enableCoupon
		if(isset($_POST['saveCoupon'])) {
			require_once(WCF_DIR.'lib/data/contest/coupon/ContestCoupon.class.php');
			
			try {
				$coupon = new ContestCoupon($eventObj->contest, $_POST['couponCode']);
				$coupon->giveToParticipant($_POST['participantID']);
			} catch(Exception $e) {
				// show user form, that error occurred
			}
		}

		// possible participants for current user
		$possibleParticipants = array();
		foreach($this->getParticipants() as $participant) {
			if($participant->isOwner()) {
				$possibleParticipants[] = $participant;
			}
		}
		WCF::getTPL()->assign('contestCouponPossibleParticipants', $possibleParticipants);
		WCF::getTPL()->append('additionalBoxes1', WCF::getTPL()->fetch('contestInteractionCouponSidebar'));
	}

	/**
	 * show form to give extra points
	 */
	protected function executeExtraPoints() {
		if($eventObj->contest->isOwner()) {

			// save
			if(isset($_POST['saveExtraPoints'])) {
				require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipant.class.php');
				$contestID = intval($eventObj->contest->contestID);
				$participantID = intval($_POST['participantID']);
				
				$participant = new ContestParticipant($participantID);
				if(!$participant->participantID || $participant->contestID != $eventObj->contest->contestID) {
					// TODO: use user exception and display error
					throw new SystemException('participant does not exist in this contest');
				}

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
