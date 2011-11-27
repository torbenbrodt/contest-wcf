<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/coupon/participant/ContestCouponParticipant.class.php');

/**
 * Provides functions to manage ..
 *
 * @author	Torben Brodt
 * @copyright	2011 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.interaction
 */
class ContestCouponParticipantEditor extends ContestCouponParticipant {

	/**
	 * Creates a new entry jurytalk.
	 *
	 * @param	integer		$couponID
	 * @param	integer		$participantID
	 * @param	integer		$time
	 * @return	ContestCouponParticipantEditor
	 */
	public static function create($couponID, $participantID, $time = TIME_NOW) {
		$sql = "INSERT INTO	wcf".WCF_N."_contest_coupon_participant
					(couponID, participantID, time)
			VALUES		(".intval($contestID).", ".intval($participantID).", ".$time.")";
		WCF::getDB()->sendQuery($sql);
		
		return new ContestCouponParticipantEditor(null, null, array(
			'couponID' => $couponID,
			'participantID' => $participantID,
		));
	}
}
?>
