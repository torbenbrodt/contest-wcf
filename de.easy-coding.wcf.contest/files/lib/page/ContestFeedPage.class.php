<?php
// wcf imports
require_once(WCF_DIR.'lib/page/AbstractFeedPage.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestFeedEntryList.class.php');
require_once(WCF_DIR.'lib/system/session/UserSession.class.php');

/**
 * Prints a list of contest entries as a rss or an atom feed.
 * 
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestFeedPage extends AbstractFeedPage {
	/**
	 * list of contest entries
	 *
	 * @var ContestList
	 */
	public $entryList = null;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get entries
		$this->entryList = new ContestFeedEntryList();
		
		// fetch data
		$this->entryList->sqlConditions .= ' AND contest.time > '.($this->hours ? (TIME_NOW - $this->hours * 3600) : (TIME_NOW - 30 * 86400));
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		$this->entryList->sqlLimit = $this->limit;
		$this->entryList->readObjects();
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'entries' => $this->entryList->getObjects()
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		// check permission
		WCF::getUser()->checkPermission('user.contest.canViewContest');
		
		if (!MODULE_CONTEST) {
			throw new IllegalLinkException();
		}
		
		parent::show();
		
		// send content
		WCF::getTPL()->display(($this->format == 'atom' ? 'contestFeedAtom' : 'contestFeedRss2'), false);
	}
}
?>
