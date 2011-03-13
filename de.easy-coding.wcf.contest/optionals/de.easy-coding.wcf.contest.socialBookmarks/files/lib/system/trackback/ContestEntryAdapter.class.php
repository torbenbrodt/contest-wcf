<?php
// wcf imports
require_once(WCF_DIR.'lib/system/trackback/TrackbackAdapter.class.php');

/**
 * Stores trackback data in database.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.socialBookmarks
 */
class ContestEntryAdapter implements TrackbackAdapter {
	/**
	 * @see	TrackbackAdapter::receive()
	 */
	public function receive($objectID, $applicationName, $url, $title, $excerpt) {
		$sql = "INSERT INTO	wcf".WCF_N."_contest_trackback
					(entryID, time, applicationName,
					url, title, excerpt)
			VALUES		(".intval($objectID).",
					".TIME_NOW.",
					'".escapeString($applicationName)."',
					'".escapeString($url)."',
					'".escapeString($title)."',
					'".escapeString($excerpt)."')";
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * @see	TrackbackAdapter::receive()
	 */
	public function ping($objectID, $trackbackURL) {
		require_once(WCF_DIR.'lib/data/contest/ViewableContest.class.php');
		$entry = new ViewableContest($objectID);
		if (!$entry->contestID) {
			throw new IllegalLinkException();
		}

		$trackback = new Trackback('easy coding contest', $entry->getOwner()->getName());
		$trackback->ping($trackbackURL, PAGE_URL.'/index.php?page=Contest&contestID='.$objectID, $entry->subject, $entry->getExcerpt());
	}
}
?>
