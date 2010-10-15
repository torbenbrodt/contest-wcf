<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Show info, that you are a beta user, show easy-coding facts, ...
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.easycoding
 */
class EasyCodingUserLoginListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		WCF::getTPL()->append('userMessages', '<p class="warning">Das Contest Plugin ist noch im Beta Stadium. '.
			'Alle Eingaben dienen zum gegenwärtigen Zeitpunkt nur zur Evaluierung, es werden keine Preise vergeben.</p>');
			
		WCF::getTPL()->append('additionalBoxes2', WCF::getTPL()->fetch('contestSidebarContestAdd'));
	}
}
?>
