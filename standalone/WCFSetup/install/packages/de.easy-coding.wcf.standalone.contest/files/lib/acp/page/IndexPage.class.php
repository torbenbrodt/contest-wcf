<?php
// wcf imports
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/acp/package/PackageInstallationQueue.class.php');
require_once(WCF_DIR.'lib/data/feed/FeedReaderSource.class.php');

/**
 * Shows the welcome page in wbb admin control panel.
 * 
 * @author 	Marcel Werk
 * @copyright	2001-2009 WoltLab GmbH
 * @license	WoltLab Burning Board License <http://www.woltlab.com/products/burning_board/license.php>
 * @package	com.woltlab.wbb
 * @subpackage	acp.page
 * @category 	Burning Board
 */
class IndexPage extends AbstractPage {
	// system
	public $templateName = 'index';
	
	// data
	public $os = '', $webserver = '', $sqlVersion = '', $sqlType = '', $load = '';
	public $stat = array();
	public $news = array();
	public $updates = array();
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		$this->os = PHP_OS;
		if (isset($_SERVER['SERVER_SOFTWARE'])) $this->webserver = $_SERVER['SERVER_SOFTWARE'];
		$this->sqlVersion = WCF::getDB()->getVersion();
		$this->sqlType = WCF::getDB()->getDBType();
		$this->readLoad();
		$this->readStat();
		
		// updates
		if (WCF::getUser()->getPermission('admin.system.package.canUpdatePackage')) {
			require_once(WCF_DIR.'lib/acp/package/update/PackageUpdate.class.php');
			$this->updates = PackageUpdate::getAvailableUpdates();
		}
			
		// news
		$this->news = FeedReaderSource::getEntries(5);
		foreach ($this->news as $key => $news) {
			$this->news[$key]['description'] = preg_replace('/href="(.*?)"/e', '\'href="'.RELATIVE_WCF_DIR.'acp/dereferrer.php?url=\'.rawurlencode(\'$1\').\'" class="externalURL"\'', $news['description']);
		}
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'os' => $this->os,
			'webserver' => $this->webserver,
			'sqlVersion' => $this->sqlVersion,
			'sqlType' => $this->sqlType,
			'load' => $this->load,
			'news' => $this->news,
			'updates' => $this->updates,
			'dbName' => WCF::getDB()->getDatabaseName(),
			'cacheSource' => get_class(WCF::getCache()->getCacheSource())
		));
		WCF::getTPL()->assign($this->stat);
	}
	
	/**
	 * Gets a list of simple statistics.
	 */
	protected function readStat() {
		WCF::getCache()->addResource('acpstat', WBB_DIR.'cache/cache.acpstat.php', WBB_DIR.'lib/system/cache/CacheBuilderACPStat.class.php', 0, 3600 * 12);
		$this->stat = WCF::getCache()->get('acpstat');
		
		// users online
		$sql = "SELECT	COUNT(*) AS usersOnline
			FROM	wcf".WCF_N."_session
			WHERE	packageID = ".PACKAGE_ID."
				AND lastActivityTime > ".(TIME_NOW - USER_ONLINE_TIMEOUT);
		$row = WCF::getDB()->getFirstRow($sql);
		$this->stat['usersOnline'] = $row['usersOnline'];
	}
	
	/**
	 * Gets the current server load.
	 */
	protected function readLoad() {
		if ($uptime = @exec("uptime")) {
			if (preg_match("/averages?: ([0-9\.]+,?[\s]+[0-9\.]+,?[\s]+[0-9\.]+)/", $uptime, $match)) {
				$this->load = $match[1];
			}
		}
	}
}
?>
