<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Removes the contest tab.
 * 
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class UserProfileFrameContestTabListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if ($eventName == 'init') {
			$eventObj->sqlSelects .= "(SELECT COUNT(*) FROM wcf".WCF_N."_contest WHERE userID = user.userID) AS contestEntries,";
		}
		else if ($eventName == 'assignVariables') {
			if (!WCF::getUser()->getPermission('user.contest.canViewContest') || !$eventObj->getUser()->getPermission('user.contest.canUseContest') || (!$eventObj->getUser()->contestEntries && (WCF::getUser()->userID != $eventObj->userID || !WCF::getUser()->getPermission('user.contest.canUseContest')))) {
				// remove contest overview tab
				foreach (UserProfileMenu::getInstance()->menuItems as $parentMenuItem => $items) {
					foreach ($items as $key => $item) {
						if ($item['menuItem'] == 'wcf.user.profile.menu.link.contest') {
							unset(UserProfileMenu::getInstance()->menuItems[$parentMenuItem][$key]);
						}
					}
				}
			}
		}
	}
}
?>