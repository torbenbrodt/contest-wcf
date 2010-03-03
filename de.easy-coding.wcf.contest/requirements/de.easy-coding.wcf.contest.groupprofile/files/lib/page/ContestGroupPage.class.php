<?php
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestSidebar.class.php');

/**
 * Group Page
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestGroupPage extends AbstractPage {
	public $groupID = 0;
	public $sqlSelects = '';
	public $sqlJoins = '';
	public $group;
	public $informationFields = array();
	public $categories = array();
	public $userlist = array();
	public $templateName = 'contestGroupProfile';
	
	/**
	 * contest sidebar
	 * 
	 * @var	ContestSidebar
	 */
	public $sidebar = null;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['groupID'])) $this->groupID = intval($_REQUEST['groupID']);
		require_once(WCF_DIR.'lib/data/user/group/ContestGroupProfile.class.php');
		$this->group = new ContestGroupProfile($this->groupID);
		
		$groupIDs = Group::getGroupIdsByType(array(Group::GUESTS, Group::EVERYONE, Group::USERS));
		if (in_array($this->group->groupID, $groupIDs)) {
			require_once(WCF_DIR.'lib/system/exception/IllegalLinkException.class.php');
			throw new IllegalLinkException();
		}
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		// get members
		require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');
		$sql = "SELECT 		user_option.userOption".User::getUserOptionID('invisible').", user.userID, user.username, user.lastActivityTime
			FROM		wcf".WCF_N."_user_to_groups ug
			NATURAL JOIN	wcf".WCF_N."_user user
			LEFT JOIN	wcf".WCF_N."_user_option_value user_option
			ON		user.userID = user_option.userID
			WHERE		ug.groupID = ".$this->groupID;
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->userlist[] = new UserProfile(null, $row);
		}
		
		// init sidebar
		$this->sidebar = new ContestSidebar($this);
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		$this->sidebar->assignVariables();
		
		WCF::getTPL()->assign(array(
			'group' => $this->group,
			'categories' => $this->categories,
			'informationFields' => $this->informationFields,
			'userlist' => $this->userlist,
			'allowSpidersToIndexThisPage' => true
		));
	}
}
?>
