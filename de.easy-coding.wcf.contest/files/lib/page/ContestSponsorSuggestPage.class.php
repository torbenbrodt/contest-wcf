<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/page/UserSuggestPage.class.php');

/**
 * Outputs an XML document with a list of permissions objects (user or user groups).
 * file looks stupidly redundant, but it is planned to become very smart... listing the latest sponsors.
 * 
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSponsorSuggestPage extends UserSuggestPage {
	/**
	 * @see Page::show()
	 */
	public function show() {
		AbstractPage::show();
				
		header('Content-type: text/xml');
		echo "<?xml version=\"1.0\" encoding=\"".CHARSET."\"?>\n<suggestions>\n";
		$groupIDs = array(Group::GUESTS, Group::EVERYONE, Group::USERS);
		
		if (!empty($this->query)) {
			// get suggestions
			$sql = "(SELECT		username AS name, 'user' AS type
				FROM		wcf".WCF_N."_user
				WHERE		username LIKE '".escapeString($this->query)."%')
				UNION ALL
				(SELECT		groupName AS name, 'group' AS type
				FROM		wcf".WCF_N."_group
				WHERE		groupName LIKE '".escapeString($this->query)."%'
				AND		groupID NOT IN (".implode(",", $groupIDs)."))
				ORDER BY	name";
			$result = WCF::getDB()->sendQuery($sql, 10);
			while ($row = WCF::getDB()->fetchArray($result)) {
				echo "<".$row['type']."><![CDATA[".StringUtil::escapeCDATA($row['name'])."]]></".$row['type'].">\n";
			}
		}
		echo '</suggestions>';
		exit;
	}
}
?>
