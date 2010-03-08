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
	 * returns the groups for which the current user is admin
	 */
	public static function readAvailableGroups() {
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
			$availableGroups[$row['groupID']] = new Group(null, $row);
		}
		return $availableGroups;
	}
}
?>
