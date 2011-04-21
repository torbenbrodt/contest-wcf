<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventHandler.class.php');
require_once(WCF_DIR.'lib/data/contest/class/ContestClassTree.class.php');
require_once(WCF_DIR.'lib/data/contest/jury/ContestJuryList.class.php');
require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipantList.class.php');
require_once(WCF_DIR.'lib/data/contest/sponsor/ContestSponsorList.class.php');
require_once(WCF_DIR.'lib/data/contest/price/ContestPriceList.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestList.class.php');
require_once(WCF_DIR.'lib/data/contest/solution/ContestSolutionList.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestTagList.class.php');

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
	 * @var	ContestClassTree
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
	 * @param	ContestJury	$contest
	 */
	public function __construct(Contest $contest = null, $disabledModules = array()) {
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
		
		$userID = WCF::getUser()->userID;
		$key = __CLASS__.'.'.($this->contest ? $this->contest->contestID : 0).'.'.$userID;
		$cacheResource = array(
			'file' => $key,
			'cache' => $key,
			'minLifetime' => 0,
			'maxLifetime' => 15 * 60
		);
		
		// only write cache for guest user
		if($userID > 0 || ($val = WCF::getCache()->getCacheSource()->get($cacheResource)) === null) {
			$val = $this->_init();
			if($userID == 0) {
				WCF::getCache()->getCacheSource()->set($cacheResource, $val);
			}
		}
		
		// activate
		foreach($val as $key => $list) {
			$this->$key = $list;
		}
		
		// deactivate
		foreach($this->disabledModules as $diss) {
			$this->$diss = null;
		}
		
		// advertising
		$this->advertiseParticipant = $this->contest && !in_array('advertiseParticipant', $this->disabledModules) 
			&& $this->contest->isParticipantable(false);
		$this->advertiseSponsor = $this->contest && !in_array('advertiseSponsor', $this->disabledModules) 
			&& $this->contest->isSponsorable(false);
		$this->advertiseJury = false;
	}
	
	/**
	 * returns all sidebar data in format, which can be cached
	 */
	protected function _init() {
		// get classes
		$classList = new ContestClassTree();
		$classList->readObjects();
		
		// get jurys
		if(!$this->contest || $this->contest->isEnabledJury()) {
			$juryList = new ContestJuryList();
			if($this->contest !== null) {
				$juryList->sqlConditions .= 'contest_jury.contestID = '.$this->contest->contestID.' AND contest_jury.state = "accepted" ';
			} else {
				$juryList->sqlJoins .= " INNER JOIN wcf".WCF_N."_contest contest ON contest.contestID = contest_jury.contestID ";
				$juryList->sqlConditions .= 'contest.state IN ("scheduled", "closed") AND contest_jury.state = "accepted" ';
			}
			$juryList->sqlOrderBy = 'juryID DESC';
			$juryList->readObjects();
		} else {
			$juryList = null;
		}
		
		// get participants
		$participantList = new ContestParticipantList();
		if($this->contest !== null) {
			$participantList->sqlConditions .= 'contest_participant.contestID = '.$this->contest->contestID.' AND contest_participant.state = "accepted" ';
		} else {
			$participantList->sqlJoins .= " INNER JOIN wcf".WCF_N."_contest contest ON contest.contestID = contest_participant.contestID ";
			$participantList->sqlConditions .= 'contest.state IN ("scheduled", "closed") AND contest_participant.state = "accepted" ';
		}
		$participantList->sqlLimit = 10;
		$participantList->sqlOrderBy = 'participantID DESC';
		$participantList->readObjects();
		
		// get sponsors
		$sponsorList = new ContestSponsorList();
		if($this->contest !== null) {
			$sponsorList->sqlConditions .= 'contest_sponsor.contestID = '.$this->contest->contestID.' AND contest_sponsor.state = "accepted" ';
		} else {
			$sponsorList->sqlJoins .= " INNER JOIN wcf".WCF_N."_contest contest ON contest.contestID = contest_sponsor.contestID ";
			$sponsorList->sqlConditions .= 'contest.state IN ("scheduled", "closed") AND contest_sponsor.state = "accepted" ';
		}
		$sponsorList->sqlOrderBy = 'sponsorID DESC';
		$sponsorList->readObjects();
		
		// get prices
		$priceList = new ContestPriceList();
		if($this->contest !== null) {
			$priceList->sqlConditions .= 'contest_price.contestID = '.$this->contest->contestID.' AND contest_price.state != "declined" ';
		} else {
			$priceList->sqlJoins .= " INNER JOIN wcf".WCF_N."_contest contest ON contest.contestID = contest_price.contestID ";
			$priceList->sqlConditions .= 'contest.state IN ("scheduled", "closed") AND contest_price.state != "declined" ';
		}
		$priceList->sqlOrderBy = 'position ASC';
		$priceList->readObjects();
		
		// get tag cloud
		$tagList = null;
		if (MODULE_TAGGING) {
			$tagList = new ContestTagList($this->contest, WCF::getSession()->getVisibleLanguageIDArray());
			$tagList->readObjects();
		}

		// get latest entries
		$latestEntryList = new ContestList();
		if($this->contest !== null) {
			$latestEntryList->sqlConditions .= 'contest.contestID != '.$this->contest->contestID;
		}
		$latestEntryList->sqlLimit = 10;
		$latestEntryList->readObjects();

		// get latest solutions
		if(!$this->contest || $this->contest->enableSolution) {
			$latestSolutionList = new ContestSolutionList();
			if($this->contest !== null) {
				$latestSolutionList->sqlConditions .= 'contest_solution.contestID = '.$this->contest->contestID;
			}
			$latestSolutionList->sqlOrderBy = 'solutionID DESC';
			$latestSolutionList->sqlLimit = 5;
			$latestSolutionList->readObjects();
		} else {
			$latestSolutionList = null;
		}
		
		return array(
			'classList' => $classList,
			'juryList' => $juryList,
			'participantList' => $participantList,
			'sponsorList' => $sponsorList,
			'priceList' => $priceList,
			'tagList' => $tagList,
			'latestEntryList' => $latestEntryList,
			'latestSolutionList' => $latestSolutionList,
		);
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
			'canAddContest' => WCF::getUser()->getPermission('user.contest.canAddContest'),
			'availableClasses' => $this->classList ? $this->classList->getObjects() : array(),
			'availableJurys' => $this->juryList ? $this->juryList->getObjects() : array(),
			'availableParticipants' => $this->participantList ? $this->participantList->getObjects() : array(),
			'availableSponsors' => $this->sponsorList ? $this->sponsorList->getObjects() : array(),
			'availablePrices' => $this->priceList ? $this->priceList->getObjects() : array(),
			'availableTags' => (MODULE_TAGGING ? $this->tagList->getObjects() : array()),
			'latestEntries' => $this->latestEntryList ? $this->latestEntryList->getObjects() : array(),
			'latestSolutions' => $this->latestSolutionList ? $this->latestSolutionList->getObjects() : array(),
			'advertiseParticipant' => $this->advertiseParticipant,
			'advertiseSponsor' => $this->advertiseSponsor,
			'advertiseJury' => $this->advertiseJury,
		));
	}
}
?>
