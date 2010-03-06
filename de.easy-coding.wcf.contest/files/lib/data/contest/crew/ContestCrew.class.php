<?php

/**
 * the supermod is the crew... the only person who can apply contests
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestCrew {

	/**
	 * is this user member of the contest crew?
	 *
	 * @return	boolean
	 */
	public static function isMember() {
		return WCF::getUser()->getPermission('mod.contest.isSuperMod');
	}
}
?>
