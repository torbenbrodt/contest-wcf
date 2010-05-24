<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/event/AbstractContestNotificationObject.class.php');

/**
 * An implementation of NotificationObject to support the usage of an user contest entry solution as a notification object.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.notification
 */
class ContestSolutionNotificationObject extends AbstractContestNotificationObject {
	protected $className = 'ContestSolution';
	protected $primarykey = 'solutionID';
		
	/**
	 * @see ContestNotificationInterface::getRecipients()
	 */
	public function getRecipients() {
		$ids = array();
		switch($this->state) {
			// tell contest jury that a solution was commited
			case 'applied':
				require_once(WCF_DIR.'lib/data/contest/Contest.class.php');
				$contest = Contest::getInstance($this->contestID);
				foreach($contest->getJurys() as $jury) {
					$ids = array_merge($ids, $jury->getOwner()->getUserIDs());
				}
			break;
			
			// tell solution member, that moderator did interaction
			case 'accepted':
			case 'declined':
				$ids = array_merge($ids, $this->getInstance()->getOwner()->getUserIDs());
			break;
		}
		return array_unique($ids);
	}

	/**
	 * @see NotificationObject::getTitle()
	 */
	public function getTitle() {
	}

	/**
	 * @see NotificationObject::getURL()
	 */
	public function getURL() {
		return 'index.php?page=Contest&contestID='.$this->contestID.'&solutionID='.$this->solutionID.'#solution'.$this->solutionID;
	}

	/**
	 * @see ViewableContestSolution::getFormattedSolution()
	 */
	public function getFormattedMessage($outputType = 'text/html') {
		require_once(WCF_DIR.'lib/data/message/bbcode/SimpleMessageParser.class.php');
		return SimpleMessageParser::getInstance()->parse($this->solution);
	}

}
?>
