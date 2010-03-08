<?php
// wcf imports
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');

/**
 * Outputs an json document with a list of permissions objects (user or user groups).
 * file looks stupidly redundant, but it is planned to become very smart... listing the latest participants.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestParticipantObjectsPage extends AbstractPage {
	/**
	 * query
	 * 
	 * @var	array
	 */
	public $query = array();
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['query'])) {
			$queryString = $_REQUEST['query'];
			if (CHARSET != 'UTF-8') {
				$queryString = StringUtil::convertEncoding('UTF-8', CHARSET, $queryString);
			}
			
			$this->query = ArrayUtil::trim(explode(',', $queryString));
		}
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		parent::show();
				
		header('Content-Type: application/json');
		$objects = array();
		$groupIDs = array(Group::GUESTS, Group::EVERYONE, Group::USERS);
		
		if (count($this->query)) {
			// get users
			$names = implode("','", array_map('escapeString', $this->query));
			$sql = "(SELECT		username AS name, userID AS id, 'user' AS type 
				FROM		wcf".WCF_N."_user
				WHERE		username IN ('".$names."'))
				UNION
				(SELECT		groupName AS name, groupID AS id, 'group' AS type 
				FROM		wcf".WCF_N."_group
				WHERE		groupName IN ('".$names."')
				AND		groupID NOT IN (".implode(",", $groupIDs)."))
				ORDER BY 	name";
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				$objects[] = $row;
			}
		}
		
		echo json_encode($objects);
		exit;
	}
}
?>
