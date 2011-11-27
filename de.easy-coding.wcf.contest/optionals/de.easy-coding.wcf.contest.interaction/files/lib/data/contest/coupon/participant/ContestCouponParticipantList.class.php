<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/contest/coupon/participant/ContestCouponParticipant.class.php');

/**
 * List of coupons which a single user makes use from.
 * 
 * @author	Torben Brodt
 * @copyright	2011 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.interaction
 */
class ContestCouponParticipantList extends DatabaseObjectList {

	/**
	 * list of coupons
	 * 
	 * @var array<ContestCouponParticipant>
	 */
	public $coupons = array();

	/**
	 * list of participant ids
	 * 
	 * @var array<int>
	 */
	public $participantIDs = array();

	/**
	 * construct new list
	 *
	 * @param	Contest		$contest
	 */
	public function __construct(Contest $contest) {
		$this->contest = $contest;
	}

	/**
	 *
	 */
	public function setUser($user) {
		$this->participantIDs = array();

		if($user->userID) {
			foreach($this->contest->getParticipants() as $participant) {
				if($participant->getOwner()->isUser($user)) {
					$this->participantIDs[] = $participant->participantID;
				}
			}
		}
	}

	/**
	 * @see DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		if(empty($this->participantIDs)) return 0;

		$sql = 'SELECT		COUNT(contest_coupon.couponID) AS count
			FROM		wcf'.WCF_N.'_contest_coupon_participant contest_coupon_participant
			INNER JOIN	wcf'.WCF_N.'_contest_coupon contest_coupon
			ON		contest_coupon_participant.couponID = contest_coupon.couponID
			WHERE		contestID = '.intval($this->contest->contestID).'
			AND		participantID IN ('.implode(',', $this->participantIDs).')
			'.(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '');
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}

	/**
	 * @see DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		if(empty($this->participantIDs)) return;

		$sql = 'SELECT		*
			FROM		wcf'.WCF_N.'_contest_coupon_participant contest_coupon_participant
			INNER JOIN	wcf'.WCF_N.'_contest_coupon contest_coupon
			ON		contest_coupon_participant.couponID = contest_coupon.couponID
			WHERE		contestID = '.intval($this->contest->contestID).'
			AND		participantID IN ('.implode(',', $this->participantIDs).')
			'.(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '')."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->coupons[] = new ContestCouponParticipant(null, $row);
		}
	}

	/**
	 * @see DatabaseObjectList::getObjects()
	 */
	public function getObjects() {
		return $this->coupons;
	}
}
?>
