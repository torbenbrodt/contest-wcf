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
		
		$this->eventObj = $eventObj;

		// show coupon field
		$this->executeCoupon();

		// contest owner might give extra points
		$this->executeExtraPoints();
	}
	
	/**
	 *
	 */
	protected function showCouponList(array $coupons) {
		WCF::getTPL()->assign('contestCouponExisingCoupons', $coupons);
	}
	
	/**
	 *
	 */
	protected function showCouponForm() {
		$eventObj = $this->eventObj;

		// TODO: check for contest->enableCoupon
		if(isset($_POST['saveCoupon'])) {
			require_once(WCF_DIR.'lib/data/contest/coupon/ContestCoupon.class.php');
			
			try {
				$coupon = new ContestCoupon($eventObj->contest, $_POST['couponCode']);
				$coupon->giveToParticipant($_POST['participantID']);

			} catch(UserInputException $e) {
				// show user form, that error occurred
				WCF::getTPL()->assign('contestCouponException', $e);
			}
		}

		// possible participants for current user
		$possibleParticipants = array();
		foreach($eventObj->contest->getParticipants() as $participant) {
			if($participant->isOwner()) {
				$possibleParticipants[] = $participant;
			}
		}
		WCF::getTPL()->assign('contestCouponPossibleParticipants', $possibleParticipants);
	}

	/**
	 *
	 */
	protected function executeCoupon() {
		$eventObj = $this->eventObj;
		if(!$eventObj->contest) {
			return;
		}
	
		require_once(WCF_DIR.'lib/data/contest/coupon/participant/ContestCouponParticipantList.class.php');
	
		$list = new ContestCouponParticipantList($eventObj->contest);
		$list->setUser(WCF::getUser());
		$list->readObjects();
		$coupons = $list->getObjects();

		if(count($coupons)) {
			$this->showCouponList($coupons);
		} else {
			$this->showCouponForm();
		}
		
		WCF::getTPL()->append('additionalBoxes1', WCF::getTPL()->fetch('contestInteractionCouponSidebar'));
	}

	/**
	 * show form to give extra points
	 */
	protected function executeExtraPoints() {
		$eventObj = $this->eventObj;
		if($eventObj->contest && $eventObj->contest->isOwner()) {

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
