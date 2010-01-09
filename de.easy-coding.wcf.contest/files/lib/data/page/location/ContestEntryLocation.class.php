<?php
// wcf imports
require_once(WCF_DIR.'lib/data/page/location/Location.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestEntry.class.php');

/**
 * ContestEntryLocation is an implementation of Location for the user contest page.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestEntryLocation implements Location {
	/**
	 * list of contest entry ids
	 * 
	 * @var	array<integer>
	 */
	public $cachedEntryIDArray = array();
	
	/**
	 * list of contest entries
	 * 
	 * @var	array<ContestEntry>
	 */
	public $entries = null;
	
	/**
	 * @see Location::cache()
	 */
	public function cache($location, $requestURI, $requestMethod, $match) {
		$this->cachedEntryIDArray[] = $match[1];
	}
	
	/**
	 * @see Location::get()
	 */
	public function get($location, $requestURI, $requestMethod, $match) {
		if ($this->entries == null) {
			$this->readEntries();
		}
		
		$contestID = $match[1];
		if (!isset($this->entries[$contestID])) {
			return '';
		}
		
		return WCF::getLanguage()->get($location['locationName'], array(
			'$entry' => '<a href="index.php?page=ContestEntry&amp;contestID='.$contestID.SID_ARG_2ND.'">'.StringUtil::encodeHTML($this->entries[$contestID]->subject).'</a>',
			'$user' => '<a href="index.php?page=Contest&amp;userID='.$this->entries[$contestID]->userID.SID_ARG_2ND.'">'.StringUtil::encodeHTML($this->entries[$contestID]->username).'</a>'
		));
	}
	
	/**
	 * Gets entries.
	 */
	protected function readEntries() {
		$this->entries = array();
		
		if (!count($this->cachedEntryIDArray)) {
			return;
		}
		
		$sql = "SELECT		contest.*, user_table.username
			FROM		wcf".WCF_N."_contest contest
			LEFT JOIn	wcf".WCF_N."_user user_table
			ON		(user_table.userID = contest.userID)
			WHERE		contest.contestID IN (".implode(',', $this->cachedEntryIDArray).")";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->entries[$row['contestID']] = new ContestEntry(null, $row);
		}
	}
}
?>
