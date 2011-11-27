<?php

/**
 * contest coupon
 *
 * @author	Torben Brodt
 * @copyright	2011 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.interaction
 */
class ContestCoupon {

	/**
	 * construct with contest instance
	 *
	 * @param	$contest	Contest
	 */
	public function __construct(Contest $contest = null, $couponCode = null, $row = array()) {

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
			throw new SystemException('wrong coupon code');
		}
		
		if($this->fromTime && $this->fromTime > TIME_NOW) || ($this->untilTime && TIME_NOW > $this->untilTime)) {
			throw new SystemException('coupon code is not valid any longer');
		}
		
		// TODO: check if participant did already use a code
	}
	
	/**
	 * 
	 */
	public function giveToParticipant($participantID) {
		$this->validate();
		
		require_once(WCF_DIR.'lib/data/contest/coupon/participant/ContestCouponParticipantEditor.class.php');				
		ContestCouponParticipantEditor::create($couponID, $participantID);
	}
}
?>
