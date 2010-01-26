<?php
// wcf imports
require_once(WCF_DIR.'lib/data/user/group/Group.class.php');

/**
 * GroupProfile extends Group by functions for displaying a group profile.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestGroupProfile extends Group {
	/**
	 * displayable avatar object.
	 *
	 * @var DisplayableAvatar
	 */
	protected $avatar = null;
	
	/**
	 * Creates a new GroupProfile object.
	 * 
	 * @see Group::__construct()
	 */
	public function __construct($groupID = null, $row = null, $groupname = null, $email = null, $sqlSelects = '', $sqlJoins = '') {
		$this->avatarID = 0;
		
		parent::__construct($groupID, $row, $groupname, $email);
	}
	
	/**
	 * @see Group::handleData()
	 */
	protected function handleData($data) {
		parent::handleData($data);
		if (MODULE_AVATAR == 1 && !$this->disableAvatar && (!WCF::getUser()->groupID || WCF::getUser()->showAvatar) && ($this->groupID == WCF::getUser()->groupID || WCF::getUser()->getPermission('group.profile.avatar.canViewAvatar'))) {
			if (MODULE_GRAVATAR == 1 && $this->gravatar) {
				require_once(WCF_DIR.'lib/data/group/avatar/Gravatar.class.php');
				$this->avatar = new Gravatar($this->gravatar);
			}
			else if ($this->avatarID) {
				require_once(WCF_DIR.'lib/data/group/avatar/Avatar.class.php');
				$this->avatar = new Avatar(null, $data);
			}
		}
	}
	
	/**
	 * Returns the avatar of this group.
	 * 
	 * @return	DisplayableAvatar
	 */
	public function getAvatar() {
		return $this->avatar;
	}
}
?>
