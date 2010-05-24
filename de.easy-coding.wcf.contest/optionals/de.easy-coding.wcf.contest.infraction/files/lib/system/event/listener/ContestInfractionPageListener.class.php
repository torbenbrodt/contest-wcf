<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * show the buttons for user infraction
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.infraction
 */
class ContestInfractionPageListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		// if module is disabled or user does not have permissions then stop
		if(MODULE_USER_INFRACTION == false || WCF::getUser()->getPermission('admin.user.infraction.canWarnUser') == false) {
			return;
		}

		switch($className) {
			case 'ContestPage':
				WCF::getTPL()->assign('entry', $eventObj->entry);
				WCF::getTPL()->append('additionalSmallButtons', WCF::getTPL()->fetch('contestInfractionSmall'));
			break;
		}
	}
}
?>
