<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventHandler.class.php');
require_once(WCF_DIR.'lib/data/contest/class/ContestClassList.class.php');
require_once(WCF_DIR.'lib/data/contest/jury/ContestJuryList.class.php');
require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipantList.class.php');
require_once(WCF_DIR.'lib/data/contest/sponsor/ContestSponsorList.class.php');
require_once(WCF_DIR.'lib/data/contest/price/ContestPriceList.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestList.class.php');
require_once(WCF_DIR.'lib/data/contest/solution/ContestSolutionList.class.php');

/**
 * Manages the user contest sidebar.
 * 
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSidebar {
	/**
	 * sidebar container.
	 * 
	 * @var	object 
	 */
	public $container = null;
	
	/**
	 * user id
	 *
	 * @var integer
	 */
	public $userID = 0;
	
	/**
	 * list of contest classes
	 * 
	 * @var	ContestClassList
	 */
	public $classList = null;
	
	/**
	 * list of contest jurys
	 * 
	 * @var	ContestJuryList
	 */
	public $juryList = null;
	
	/**
	 * list of contest participants
	 * 
	 * @var	ContestParticipantList
	 */
	public $participantList = null;
	
	/**
	 * list of contest sponsors
	 * 
	 * @var	ContestSponsorList
	 */
	public $sponsorList = null;
	
	/**
	 * list of contest prices
	 * 
	 * @var	ContestPriceList
	 */
	public $priceList = null;
	
	/**
	 * tag list object
	 *
	 * @var TagList
	 */
	public $tagList = null;
	
	/**
	 * list of lastest solutions
	 * 
	 * @var	ContestSolutionList
	 */	
	public $lastestSolutionList = array();
	
	/**
	 * list of lastest entries
	 * 
	 * @var	ContestList
	 */
	public $lastestEntryList = array();
	
	/**
	 * Creates a new ContestSidebar.
	 *
	 * @param	object		$container
	 * @param	integer		$userID
	 */
	public function __construct($container = null, $userID = 0) {
		$this->container = $container;
		$this->userID = $userID;
		
		// init sidebar
		$this->init();
	}
	
	/**
	 * Initializes the sidebar.
	 */
	public function init() {
		// call init event
		EventHandler::fireAction($this, 'init');
		
		// get classes
		$this->classList = new ContestClassList();
		$this->classList->readObjects();
		
		// get jurys
		$this->juryList = new ContestJuryList();
		$this->juryList->readObjects();
		
		// get participants
		$this->participantList = new ContestParticipantList();
		$this->participantList->readObjects();
		
		// get sponsors
		$this->sponsorList = new ContestSponsorList();
		$this->sponsorList->readObjects();
		
		// get prices
		$this->priceList = new ContestPriceList();
		$this->priceList->readObjects();
		
		// get tag cloud
		if (MODULE_TAGGING) {
			require_once(WCF_DIR.'lib/data/contest/ContestTagList.class.php');
			$this->tagList = new ContestTagList($this->userID, WCF::getSession()->getVisibleLanguageIDArray());
			$this->tagList->readObjects();
		}

		// get lastest entries
		$this->lastestEntryList = new ContestList();
		$this->lastestEntryList->sqlConditions .= 'contest.userID = '.$this->userID;
		$this->lastestEntryList->sqlLimit = 10;
		$this->lastestEntryList->readObjects();

		// get lastest solutions
		$this->lastestSolutionList = new ContestSolutionList();
		$this->lastestSolutionList->sqlConditions .= 'contest_solution.userID = '.$this->userID;
		$this->lastestSolutionList->sqlOrderBy = 'time DESC';
		$this->lastestSolutionList->sqlLimit = 5;
		$this->lastestSolutionList->readObjects();
	}
	
	/**
	 * Assigns variables to the template engine.
	 */
	public function assignVariables() {
		// call assignVariables event
		EventHandler::fireAction($this, 'assignVariables');
		
		// assign variables
		WCF::getTPL()->assign(array(
			'isRegistered' => WCF::getUser()->userID > 0,
			'availableClasses' => $this->classList->getObjects(),
			'availableJurys' => $this->juryList->getObjects(),
			'availableParticipants' => $this->participantList->getObjects(),
			'availableSponsors' => $this->sponsorList->getObjects(),
			'availablePrices' => $this->priceList->getObjects(),
			'availableTags' => (MODULE_TAGGING ? $this->tagList->getObjects() : array()),
			'lastestEntries' => $this->lastestEntryList->getObjects(),
			'lastestSolutions' => $this->lastestSolutionList->getObjects()
		));
	}
}
?>
