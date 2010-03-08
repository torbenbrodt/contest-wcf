<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Shows the lastest contest entries.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class GroupPageContestListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (MODULE_CONTEST == 1 && PROFILE_SHOW_LAST_CONTEST_ENTRIES == 1 && WCF::getUser()->getPermission('user.contest.canViewContest')) {
			// get entries
			require_once(WCF_DIR.'lib/data/contest/ContestList.class.php');
			$entryList = new ContestList();
			$entryList->sqlConditions .= 'contest.groupID = '.$eventObj->groupID;

			$count = $entryList->countObjects();
			if ($count > 0) {
				$entryList->sqlLimit = 5;
				$entryList->readObjects();
				
				WCF::getTPL()->assign(array(
					'group' => $eventObj->group,
					'entries' => $entryList->getObjects(),
					'contestEntries' => $count
				));
				WCF::getTPL()->append('additionalContent1', WCF::getTPL()->fetch('groupProfileContest'));
			}
		}
	}
}
?>
