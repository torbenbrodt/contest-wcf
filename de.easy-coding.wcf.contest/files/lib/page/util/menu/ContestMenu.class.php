<?php
// wcf imports
require_once(WCF_DIR.'lib/page/util/menu/TreeMenu.class.php');

/**
 * Builds the contest menu.
 * 
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestMenu extends TreeMenu {
	protected static $instance = null;
	public $userID = 0;
	
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
		$link = str_replace('%s', $this->userID, $link);
		
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
