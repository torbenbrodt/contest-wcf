<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/twitter/TwitterRetweetMessage.class.php');

/**
 * Displays retweets.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.retweet
 */
class ContestRetweetListener implements EventListener {
	/**
	 * @var array<TwitterRetweetMessage>
	 */
	protected $retweets = array();

	/**
	 * @return integer
	 */
	protected function getAdapterID($objectType, $packageID) {
		// get cache
		WCF::getCache()->addResource(
			'trackback-adapter',
			WCF_DIR.'cache/cache.trackback-adapter.php',
			WCF_DIR.'lib/system/cache/CacheBuilderTrackbackAdapter.class.php'
		);
		$cache = WCF::getCache()->get('trackback-adapter');

		// try to find a matching trackback adapter
		if (isset($cache[$objectType])) {
			foreach ($cache[$objectType] as $adapter) {
				if ($adapter['packageID'] == $packageID) {
					return $adapter['adapterID'];
				}
			}
		}
		return 0;
	}

	/**
	 * get retweets
	 */
	public function readData($eventObj) {

		$objectPackageID = WCF::getPackageID('de.easy-coding.wcf.contest.socialBookmarks');
		$adapterID = $this->getAdapterID('contestEntry', $objectPackageID);

		if(!$adapterID) {
			return;
		}
	
		$sql = "SELECT		*
			FROM    	wcf".WCF_N."_twitter_message_retweet retweet
			INNER JOIN	wcf".WCF_N."_twitter_message message ON retweet.messageID = message.messageID
			INNER JOIN	wcf".WCF_N."_twitter_account ta ON message.accountID = ta.accountID
			WHERE   	retweet.adapterID = ".$adapterID."
			AND		retweet.objectID = ".intval($eventObj->contestID)."
			ORDER BY	retweet.retweetID DESC";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->retweets[] = new TwitterRetweetMessage(null, $row);
		}
	}
	
	/**
	 * @EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {

		// read the retweets
		$this->readData($eventObj);

		if(count($this->retweets)) {
			WCF::getTPL()->assign('retweets', $this->retweets);
			WCF::getTPL()->append('additionalContent1', WCF::getTPL()->fetch('retweetBox'));
		}
	}
}
?>
