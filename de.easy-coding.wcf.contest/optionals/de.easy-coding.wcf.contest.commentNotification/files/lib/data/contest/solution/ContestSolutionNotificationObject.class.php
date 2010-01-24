<?php
// wcf imports
require_once(WCF_DIR.'lib/data/user/notification/object/NotificationObject.class.php');
require_once(WCF_DIR.'lib/data/contest/solution/ViewableContestSolution.class.php');

/**
 * An implementation of NotificationObject to support the usage of an user contest entry solution as a notification object.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.commentNotification
 */
class ContestSolutionNotificationObject extends ViewableContestSolution implements NotificationObject {

	/**
	 * @see ViewableContestSolution:__construct
	 */
	public function __construct($solutionID, $row = null) {
		// construct from old data if possible
		if (is_object($row)) {
			$row = $row->data;
		}
		parent::__construct($solutionID, $row);
	}
		
	/**
	 * @see NotificationObject::getObjectID()
	 */
	public function getObjectID() {
		return $this->solutionID;
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
	 * @see NotificationObject::getIcon()
	 */
	public function getIcon() {
		return 'contest';
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
