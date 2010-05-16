<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/event/AbstractContestNotificationObject.class.php');

/**
 * An implementation of NotificationObject to support the usage of an user contest entry jury as a notification object.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.notification
 */
class ContestJuryNotificationObject extends AbstractContestNotificationObject {
	protected $className = 'ContestJury';
	protected $primarykey = 'juryID';
		
	/**
	 * @see AbstractContestNotificationObject::getRecipients()
	 */
	public function getRecipients() {
		$ids = array();
		switch($this->state) {
			// tell contest owner that s.o. did apply
			case 'applied':
				require_once(WCF_DIR.'lib/data/contest/Contest.class.php');
				$contest = new Contest($this->contestID);
				$ids = array_merge($ids, $contest->getOwner()->getUserIDs());
			break;
			case 'invited':
				$ids = array_merge($ids, $this->getInstance()->getOwner()->getUserIDs());
			break;
			// tell recipient that s.o. did moderator interaction
			case 'accepted':
				$ids = array_merge($ids, $this->getInstance()->getOwner()->getUserIDs());
				
				// maybe the user applied himself, then tell the owners
				require_once(WCF_DIR.'lib/data/contest/Contest.class.php');
				$contest = new Contest($this->contestID);
				if($contest->enableJuryCheck == false) {
					$ids = array_merge($ids, $contest->getOwner()->getUserIDs());
				}
			break;
		}
		return array_unique($ids);
	}

	/**
	 * @see NotificationObject::getURL()
	 */
	public function getURL() {
		return 'index.php?page=Contest&contestID='.$this->contestID.'&juryID='.$this->juryID.'#jury'.$this->juryID;
	}
}
?>
