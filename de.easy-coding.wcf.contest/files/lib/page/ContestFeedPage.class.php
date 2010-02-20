<?php
// wcf imports
require_once(WCF_DIR.'lib/page/AbstractFeedPage.class.php');
require_once(WCF_DIR.'lib/data/contest/ViewableContest.class.php');
require_once(WCF_DIR.'lib/data/contest/eventmix/ContestEventMixList.class.php');
require_once(WCF_DIR.'lib/system/session/UserSession.class.php');

/**
 * Prints a list of contest entries as a rss or an atom feed.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestFeedPage extends AbstractFeedPage {
	
	/**
	 * entry id
	 *
	 * @var	integer
	 */
	public $contestID = 0;
	
	/**
	 * entry object
	 * 
	 * @var	Contest
	 */
	public $entry = null;
	
	/**
	 * list of eventmix entries
	 *
	 * @var ContestEventMixList
	 */
	public $eventmixList = null;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get entry
		if (isset($_REQUEST['contestID'])) $this->contestID = intval($_REQUEST['contestID']);
		$this->entry = new ViewableContest($this->contestID);
		if (!$this->entry->contestID) {
			throw new IllegalLinkException();
		}
		
		// get entries
		$this->eventmixList = new ContestEventMixList();
		
		// fetch data
		$this->eventmixList->sqlConditions .= 'contestID = '.$this->contestID;
		$this->eventmixList->sqlOrderBy = 'contest_eventmix.time DESC';
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		$this->eventmixList->sqlLimit = $this->limit;
		$this->eventmixList->readObjects();
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'entries' => $this->eventmixList->getObjects()
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
