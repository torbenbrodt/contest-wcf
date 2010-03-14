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
 * @copyright	2010 easy-coding.de
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
	 * use disableModule to add items to blacklist
	 *
	 * @var array
	 */
	protected $disabledModules = array();
	
	/**
	 * contest
	 *
	 * @var ContestJury
	 */
	public $contest = null;
	
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
	 * list of latest solutions
	 * 
	 * @var	ContestSolutionList
	 */	
	public $latestSolutionList = array();
	
	/**
	 * list of latest entries
	 * 
	 * @var	ContestList
	 */
	public $latestEntryList = array();
	
	/**
	 * Creates a new ContestSidebar.
	 *
	 * @param	object		$container
	 * @param	ContestJury	$contest
	 */
	public function __construct($container = null, Contest $contest = null, $disabledModules = array()) {
		$this->container = $container;
		$this->contest = $contest;
		
		// merge disabled modules
		$this->disabledModules = array_merge($this->disabledModules, $disabledModules);
		
		// init sidebar
		$this->init();
	}
	
	/**
	 * Initializes the sidebar.
	 */
	public function init() {
		// call init event
		EventHandler::fireAction($this, 'init');
		
		// advertising
		$this->advertiseParticipant = $this->contest && $this->contest->participants < 5 && $this->contest->isParticipantable(false);
		$this->advertiseSponsor = $this->contest && $this->contest->sponsors < 2 && $this->contest->isSponsorable(false);
		$this->advertiseJury = false;
		
		// get classes
		$this->classList = new ContestClassList();
		$this->classList->readObjects();
		
		// get jurys
		$this->juryList = new ContestJuryList();
		if($this->contest !== null) {
			$this->juryList->sqlConditions .= 'contest_jury.contestID = '.$this->contest->contestID;
		} else {
			$this->juryList->sqlJoins .= " INNER JOIN wcf".WCF_N."_contest contest ON contest.contestID = contest_jury.contestID ";
			$this->juryList->sqlConditions .= 'contest.state = "scheduled"';
		}
		$this->juryList->sqlOrderBy = 'juryID DESC';
		if(!in_array('juryList', $this->disabledModules)) {
			$this->juryList->readObjects();
		}
		
		// get participants
		$this->participantList = new ContestParticipantList();
		if($this->contest !== null) {
			$this->participantList->sqlConditions .= 'contest_participant.contestID = '.$this->contest->contestID;
		} else {
			$this->participantList->sqlJoins .= " INNER JOIN wcf".WCF_N."_contest contest ON contest.contestID = contest_participant.contestID ";
			$this->participantList->sqlConditions .= 'contest.state = "scheduled"';
		}
		$this->participantList->sqlOrderBy = 'participantID DESC';
		if(!in_array('participantList', $this->disabledModules)) {
			$this->participantList->readObjects();
		}
		
		// get sponsors
		$this->sponsorList = new ContestSponsorList();
		if($this->contest !== null) {
			$this->sponsorList->sqlConditions .= 'contest_sponsor.contestID = '.$this->contest->contestID;
		} else {
			$this->sponsorList->sqlJoins .= " INNER JOIN wcf".WCF_N."_contest contest ON contest.contestID = contest_sponsor.contestID ";
			$this->sponsorList->sqlConditions .= 'contest.state = "scheduled"';
		}
		$this->sponsorList->sqlOrderBy = 'sponsorID DESC';
		if(!in_array('sponsorList', $this->disabledModules)) {
			$this->sponsorList->readObjects();
		}
		
		// get prices
		$this->priceList = new ContestPriceList();
		if($this->contest !== null) {
			$this->priceList->sqlConditions .= 'contest_price.contestID = '.$this->contest->contestID;
		} else {
			$this->priceList->sqlJoins .= " INNER JOIN wcf".WCF_N."_contest contest ON contest.contestID = contest_price.contestID ";
			$this->priceList->sqlConditions .= 'contest.state = "scheduled"';
		}
		$this->priceList->sqlOrderBy = 'priceID DESC';
		if(!in_array('priceList', $this->disabledModules)) {
			$this->priceList->readObjects();
		}
		
		// get tag cloud
		if (MODULE_TAGGING) {
			require_once(WCF_DIR.'lib/data/contest/ContestTagList.class.php');
			$this->tagList = new ContestTagList($this->contest, WCF::getSession()->getVisibleLanguageIDArray());
			$this->tagList->readObjects();
		}

		// get latest entries
		$this->latestEntryList = new ContestList();
		$this->latestEntryList->sqlLimit = 10;
		$this->latestEntryList->readObjects();

		// get latest solutions
		$this->latestSolutionList = new ContestSolutionList();
		if($this->contest !== null) {
			$this->latestSolutionList->sqlConditions .= 'contest_solution.contestID = '.$this->contest->contestID;
		}
		$this->latestSolutionList->sqlOrderBy = 'solutionID DESC';
		$this->latestSolutionList->sqlLimit = 5;
		$this->latestSolutionList->readObjects();
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
			'latestEntries' => $this->latestEntryList->getObjects(),
			'latestSolutions' => $this->latestSolutionList->getObjects(),
			'advertiseParticipant' => $this->advertiseParticipant,
			'advertiseSponsor' => $this->advertiseSponsor,
			'advertiseJury' => $this->advertiseJury,
		));
	}
}
?>
