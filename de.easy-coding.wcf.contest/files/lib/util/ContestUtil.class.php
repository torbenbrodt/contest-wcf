<?php

/**
 * contest utilities
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestUtil {
	/**
	 * for singleton usage of assignVariablesBranding
	 * @var boolean
	 */
	protected static $BRANDED = false;

	/**
	 * returns the groups for which the current user is admin
	 */
	public static function readAvailableGroups($prefix = '') {
		$blacklisted = array(Group::GUESTS, Group::EVERYONE, Group::USERS);
		$sql = "SELECT		usergroup.*, (
						SELECT	COUNT(*)
						FROM	wcf".WCF_N."_user_to_groups
						WHERE	groupID = usergroup.groupID
					) AS members
			FROM 		wcf".WCF_N."_group usergroup
			WHERE		groupID IN (
						".implode(',', WCF::getUser()->getGroupIDs())."
					)
			AND		groupID NOT IN (".implode(',', $blacklisted).")
			ORDER BY 	groupName";
		$result = WCF::getDB()->sendQuery($sql);
		$availableGroups = array();
		while ($row = WCF::getDB()->fetchArray($result)) {
			$availableGroups[$prefix.$row['groupID']] = new Group(null, $row);
		}
		return $availableGroups;
	}
	
	/**
	 * returns the groups for which the current user is admin
	 */
	public static function assignVariablesBranding() {
		$showBranding = true;
		if(defined('CONTEST_N') || self::$BRANDED) {
			$showBranding = false;
		}
		
		if($showBranding) {
			self::$BRANDED = true;

			WCF::getTPL()->append('additionalFooterOptions', '<li><a class="externalURL" href="http://trac.easy-coding.de/trac/contest"><span>'.WCF::getLanguage()->get('wcf.contest.branding').'</span></a></li>');
		}
	}
}
?>
