<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Show info, that you are a beta user.
 * 
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestBetaPageListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (MODULE_CONTEST == 1) {
			WCF::getTPL()->append('userMessages', '<p class="info">Das Contest Plugin ist noch im Beta Stadium. Du kannst diese Seite nur sehen, weil du der <a href="index.php?page=Project&id=contest">Projektgruppe</a> beigetren bist. Mehr Informationen findest du im <a href="http://trac.easy-coding.de/trac/contest">Trac</a>.</p>');
		}
	}
}
?>
