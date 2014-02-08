<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');

/**
 * @author	Torben Brodt
 * @copyright	2011 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.interaction
 */
class ContestCouponParticipant extends DatabaseObject {

	/**
	 * Creates a new object.
	 *
	 * @param	integer		$id
	 * @param 	array<mixed>	$row
	 */
	public function __construct($id, $row = null) {
		if ($id !== null) {
			throw new SystemException('not implemented');
		}
		parent::__construct($row);
	}
	
	/**
	 * Returns an editor object for this jurytalk.
	 *
	 * @return	ContestCouponParticipantEditor
	 */
	public function getEditor() {
		require_once(WCF_DIR.'lib/data/contest/coupon/participant/ContestCouponParticipantEditor.class.php');
		return new ContestCouponParticipantEditor(null, $this->data);
	}
}
?>
