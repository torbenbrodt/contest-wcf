<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/ViewableContest.class.php');

/**
 * Represents a viewable contest entry in a rss or an atom feed.
 * 
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestFeedEntry extends ViewableContest {
	/**
	 * @see ViewableContest::getFormattedMessage()
	 */
	public function getFormattedMessage() {
		// replace relative urls
		$text = preg_replace('~(?<=href="|src=")(?![a-z0-9]+://)~i', PAGE_URL.'/', parent::getFormattedMessage());
		
		return StringUtil::escapeCDATA($text);
	}
}
?>