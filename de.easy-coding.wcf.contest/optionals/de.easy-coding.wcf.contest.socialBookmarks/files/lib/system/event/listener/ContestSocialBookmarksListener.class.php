<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/socialBookmark/SocialBookmarks.class.php');
require_once(WCF_DIR.'lib/data/trackback/Trackback.class.php');

/**
 * Displays a global announcement.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.socialBookmarks
 */
class ContestSocialBookmarksListener implements EventListener {

	/**
	 * trackback object
	 * 
	 * @var Trackback
	 */
	public $trackback = null;

	/**
	 * trackbacks for this entry
	 * 
	 * @var array
	 */
	public $trackbacks = array();

	/**
	 *
	 */
	public function readTrackbacks($eventObj) {
		// init trackback
		$this->trackback = new Trackback('easy coding contest', $eventObj->entry->getOwner()->getName());

		// get trackbacks
		$sql = "SELECT  *
			FROM    wcf".WCF_N."_contest_trackback
			WHERE   entryID = ".intval($eventObj->contestID);
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->trackbacks[] = $row;
		}
	}
	
	/**
	 * @EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {

		// read the trackbacks
		$this->readTrackbacks($eventObj);
		
		// absolute url
		$permalink = PAGE_URL.'/index.php?page=Contest&contestID='.$eventObj->contestID;

		$socialBookmarks = null;
                if (MODULE_SOCIAL_BOOKMARK) {
			$socialBookmarks = SocialBookmarks::getInstance()->getSocialBookmarks($permalink, $eventObj->entry->subject);
                }

		// assign data to template
		WCF::getTPL()->assign(array(
			'trackback' => $this->trackback,
			'trackbacks' => $this->trackbacks,
                        'socialBookmarks' => $socialBookmarks
		));
		$objectPackageID = WCF::getPackageID('de.easy-coding.wcf.contest.socialBookmarks');

		// get rdf code
		if($this->trackback) {
			$tmp = $this->trackback->getRdfAutoDiscover($eventObj->entry->subject, $permalink, $eventObj->contestID, 'contestEntry', $objectPackageID);
			WCF::getTPL()->append('additionalContent3', $tmp);
		}

		WCF::getTPL()->append('additionalContent3', WCF::getTPL()->fetch('contestTrackbacks'));
		WCF::getTPL()->append('additionalButtonBar', WCF::getTPL()->fetch('contestSocialBookmarks'));
	}
}
?>
