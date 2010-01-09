<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/message/bbcode/URLBBCode.class.php');
require_once(WCF_DIR.'lib/data/message/bbcode/URLParser.class.php');

/**
 * Parses URLs to user contest classes and entries.
 * 
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class URLParserContestURLListener implements EventListener {
	protected $classes = array();
	protected $entries = array();
	protected $classURLPattern = 'index\.php\?page=Contest&classID=([0-9]+)';
	protected $entryURLPattern = 'index\.php\?page=ContestEntry&contestID=([0-9]+)';
	
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (!MODULE_CONTEST || empty(URLParser::$text)) return;
		
		// reset data
		$this->classes = $this->entries = array();
		$classIDArray = $contestIDArray = array();
		
		// get page urls
		$pageURLs = URLBBCode::getPageURLs();
		$pageURLs = '(?:'.implode('|', array_map('preg_quote', $pageURLs)).')';
		
		// build search pattern
		$classIDPattern = "!\[url\](".$pageURLs."/?".$this->classURLPattern.".*?)\[/url\]!i";
		$contestIDPattern = "!\[url\](".$pageURLs."/?".$this->entryURLPattern.".*?)\[/url\]!i";
		
		// find class ids
		if (preg_match_all($classIDPattern, URLParser::$text, $matches)) {
			$classIDArray = $matches[2];
		}
		
		// find entry ids
		if (preg_match_all($contestIDPattern, URLParser::$text, $matches)) {
			$contestIDArray = $matches[2];
		}
		
		// get classes
		if (count($classIDArray) > 0) {
			// remove duplicates
			$classIDArray = array_unique($classIDArray);
				
			$sql = "SELECT	classID, title
				FROM 	wcf".WCF_N."_contest_class
				WHERE 	classID IN (".implode(",", $classIDArray).")";
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				$this->classes[$row['classID']] = $row['title'];
			}
			
			if (count($this->classes) > 0) {
				// insert classes
				URLParser::$text = preg_replace_callback($classIDPattern, array($this, 'buildClassURLTagCallback'), URLParser::$text);
			}
		}
		
		// get entries
		if (count($contestIDArray) > 0) {
			// remove duplicates
			$contestIDArray = array_unique($contestIDArray);
				
			$sql = "SELECT	contestID, subject
				FROM 	wcf".WCF_N."_contest
				WHERE 	contestID IN (".implode(",", $contestIDArray).")";
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				$this->entries[$row['contestID']] = $row['subject'];
			}
			
			if (count($this->entries) > 0) {
				// insert classes
				URLParser::$text = preg_replace_callback($contestIDPattern, array($this, 'buildEntryURLTagCallback'), URLParser::$text);
			}
		}
	}
	
	/**
	 * Builds the url bbcode tag.
	 * 
	 * @param	array		$matches
	 * @return	string
	 */
	private function buildClassURLTagCallback($matches) {
		$url = $matches[1];
		$classID = $matches[2];
		
		if ($classID != 0 && isset($this->classes[$classID])) {
			return '[url=\''.$url.'\']'.$this->classes[$classID].'[/url]';
		}
		
		return '[url]'.$url.'[/url]';
	}
	
	/**
	 * Builds the url bbcode tag.
	 * 
	 * @param	array		$matches
	 * @return	string
	 */
	private function buildEntryURLTagCallback($matches) {
		$url = $matches[1];
		$contestID = $matches[2];
		
		if ($contestID != 0 && isset($this->entries[$contestID])) {
			return '[url=\''.$url.'\']'.$this->entries[$contestID].'[/url]';
		}
		
		return '[url]'.$url.'[/url]';
	}
}
?>