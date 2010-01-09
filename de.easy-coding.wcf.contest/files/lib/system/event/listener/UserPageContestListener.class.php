<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Shows the lastest contest entries.
 * 
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class UserPageContestListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (MODULE_CONTEST == 1 && PROFILE_SHOW_LAST_CONTEST_ENTRIES == 1 && WCF::getUser()->getPermission('user.contest.canViewContest') && $eventObj->frame->getUser()->getPermission('user.contest.canUseContest')) {
			// get entries
			require_once(WCF_DIR.'lib/data/contest/ContestEntryList.class.php');
			$entryList = new ContestEntryList();
			$entryList->sqlConditions .= 'contest.userID = '.$eventObj->frame->getUserID();
			$count = $entryList->countObjects();
			if ($count > 0) {
				$entryList->sqlLimit = 5;
				$entryList->readObjects();
				
				WCF::getTPL()->assign(array(
					'user' => $eventObj->frame->getUser(),
					'entries' => $entryList->getObjects(),
					'contestEntries' => $count
				));
				WCF::getTPL()->append('additionalContent3', WCF::getTPL()->fetch('userProfileContest'));
			}
		}
	}
}
?>