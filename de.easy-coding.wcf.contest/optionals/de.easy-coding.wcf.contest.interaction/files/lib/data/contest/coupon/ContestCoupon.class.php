<?php

/**
 * contest coupon
 *
 * @author	Torben Brodt
 * @copyright	2011 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.interaction
 */
class ContestCoupon extends DatabaseObject {

	/**
	 * construct with contest instance
	 *
	 * @param	$contest	Contest
	 */
	public function __construct(Contest $contest = null, $couponCode = null, $row = array()) {
		if($contest) {
			$this->contest = $contest;
		}

		if(empty($row)) {
			$sql = "SELECT		*
				FROM 		wcf".WCF_N."_contest_coupon
				WHERE 		contestID IN (0, ".intval($contest->contestID).")
				AND		couponCode = '".escapeString($couponCode)."'";
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}
	
	/**
	 * validate
	 */
	protected function validate() {
		if(!$this->couponID) {
			throw new UserInputException('coupon', 'Der Coupon Code ist ungültig.');
		}
		
		if(!$this->contest) {
			throw new SystemException('invalid contest given');
		}
		
		if(($this->fromTime && $this->fromTime > TIME_NOW) || ($this->untilTime && TIME_NOW > $this->untilTime)) {
			throw new UserInputException('coupon', 'Der Coupon Code ist nicht mehr gültig.');
		}
		
		// TODO: check if participant did already use a code
	}
	
	/**
	 * 
	 */
	public function giveToParticipant($participantID) {
		$this->validate();
		
		// user is not a participant yet, need to create entry
		if($participantID == 0) {
			$state = $this->contest->enableParticipantCheck ? 'applied' : 'accepted';

			// add participant
			require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipantEditor.class.php');
			$participant = ContestParticipantEditor::create($this->contest->contestID, WCF::getUser()->userID, 0, $state);
			
			$participantID = $participant->participantID;
		}

		require_once(WCF_DIR.'lib/data/contest/coupon/participant/ContestCouponParticipantEditor.class.php');				
		ContestCouponParticipantEditor::create($this->couponID, $participantID);
	}
}
?>
