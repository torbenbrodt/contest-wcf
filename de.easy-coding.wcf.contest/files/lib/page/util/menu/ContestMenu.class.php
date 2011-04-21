<?php
// wcf imports
require_once(WCF_DIR.'lib/page/util/menu/TreeMenu.class.php');

/**
 * Builds the contest menu.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestMenu extends TreeMenu {
	protected static $instance = null;
	protected $contest = null;
	
	/**
	 * validations
	 */
	protected $validations = array(
		'wcf.contest.menu.link.jurytalk' => 'isJurytalkable',
		'wcf.contest.menu.link.sponsortalk' => 'isSponsortalkable',
		'wcf.contest.menu.link.solution' => 'isEnabledSolution',
		'wcf.contest.menu.link.jury' => 'isEnabledJury',
	);
	
	/**
	 * Returns an instance of the ContestMenu class.
	 * 
	 * @return	ContestMenu
	 */
	public static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	/**
	 * sets contest
	 */
	public function setContest(Contest $contest = null) {
		$this->contest = $contest;
	}
	
	/**
	 * Checks the permissions of the menu items.
	 * Removes item without permission.
	 * 
	 * @param	string		$parentMenuItem
	 */
	protected function checkPermissions($parentMenuItem = '') {
		parent::checkPermissions($parentMenuItem);
		
		if(isset($this->validations[$parentMenuItem])) {
			$validation = $this->validations[$parentMenuItem];
			if($this->contest->$validation() == false) {
				$this->removeByMenuItem($parentMenuItem);
			}
		}
	}
	
	/**
	 * returns icon from active menu item
	 * 
	 * @param	string		$size
	 */
	public function getIcon($size = 'L') {
		$parentMenuItem = $this->getActiveMenuItem();
		foreach ($this->menuItems[''] as $key => $val) {
			if($val['menuItem'] == $parentMenuItem) {
				return $val['menuItemIcon'.$size];
			}
		}
		return '';
	}
	
	/**
	 *
	 */
	protected function removeByMenuItem($parentMenuItem) {
		foreach ($this->menuItems[''] as $key => $val) {
			if($val['menuItem'] == $parentMenuItem) {
				unset($this->menuItems[''][$key]);
				return true;
			}
		}
		return false;
	}
	
	/**
	 * @see TreeMenu::loadCache()
	 */
	protected function loadCache() {
		parent::loadCache();
		
		WCF::getCache()->addResource('contestMenu', WCF_DIR.'cache/cache.contestMenu.php', WCF_DIR.'lib/system/cache/CacheBuilderContestMenu.class.php');
		$this->menuItems = WCF::getCache()->get('contestMenu');
	}
	
	/**
	 * @see TreeMenu::parseMenuItemLink()
	 */
	protected function parseMenuItemLink($link, $path) {
		if (preg_match('~\.php$~', $link)) {
			$link .= SID_ARG_1ST; 
		}
		else {
			$link .= SID_ARG_2ND_NOT_ENCODED;
		}
		
		// insert user id
		$link = str_replace('%s', $this->contest->contestID, $link);
		
		return $link;
	}
	
	/**
	 * @see TreeMenu::parseMenuItemIcon()
	 */
	protected function parseMenuItemIcon($icon, $path) {
		return StyleManager::getStyle()->getIconPath($icon);
	}
}
?>
