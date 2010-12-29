<?php
// wcf imports
require_once(WCF_DIR.'lib/page/MultipleLinkPage.class.php');
require_once(WCF_DIR.'lib/data/contest/ViewableContest.class.php');
require_once(WCF_DIR.'lib/data/contest/sponsor/ContestSponsorList.class.php');
require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
require_once(WCF_DIR.'lib/page/util/menu/ContestMenu.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestSidebar.class.php');

/**
 * show/edit sponsor entries
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSponsorPage extends MultipleLinkPage {
	// system
	public $templateName = 'contestSponsor';

	// form
	public $ownerID = 0;

	/**
	 * is sponsor?
	 * 
	 * @var	boolean
	 */
	public $isSponsor = false;

	/**
	 * entry id
	 *
	 * @var	integer
	 */
	public $contestID = 0;

	/**
	 * entry object
	 * 
	 * @var	ContestSponsor
	 */
	public $entry = null;

	/**
	 * list of sponsors
	 *
	 * @var ContestSponsorList
	 */
	public $sponsorList = null;

	/**
	 * 
	 * @var ContestJuryTodoList
	 */
	public $todoList = null;

	/**
	 * action
	 * 
	 * @var	string
	 */
	public $action = '';

	/**
	 * contest sidebar
	 * 
	 * @var	ContestSidebar
	 */
	public $sidebar = null;

	/**
	 * @see Form::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		// get entry
		if (isset($_REQUEST['contestID'])) $this->contestID = intval($_REQUEST['contestID']);
		$this->entry = new ViewableContest($this->contestID);
		if (!$this->entry->contestID) {
			throw new IllegalLinkException();
		}

		// init sponsor list
		$this->sponsorList = new ContestSponsorList();
		$this->sponsorList->sqlConditions .= 'contest_sponsor.contestID = '.intval($this->contestID);
		$this->sponsorList->sqlOrderBy = 'contest_sponsor.time DESC';
	}

	/**
	 * @see Form::readData()
	 */
	public function readData() {
		parent::readData();

		// read objects
		$this->sponsorList->sqlOffset = ($this->pageNo - 1) * $this->itemsPerPage;
		$this->sponsorList->sqlLimit = $this->itemsPerPage;
		$this->sponsorList->readObjects();

		$this->isSponsor = $this->entry->isSponsor();

		// init sidebar
		$this->sidebar = new ContestSidebar($this->entry, array(
			'sponsorList',
			'advertiseSponsor'
		));
	}

	/**
	 * @see MultipleLinkForm::countItems()
	 */
	public function countItems() {
		parent::countItems();

		return $this->sponsorList->countObjects();
	}

	/**
	 * @see Form::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		// display branding
		require_once(WCF_DIR.'lib/util/ContestUtil.class.php');
		ContestUtil::assignVariablesBranding();
		
		// display branding
		require_once(WCF_DIR.'lib/util/ContestUtil.class.php');
		ContestUtil::assignVariablesBranding();

		// save invitations
		if($this->entry->isOwner()) {
			require_once(WCF_DIR.'lib/form/ContestSponsorInviteForm.class.php');
			new ContestSponsorInviteForm($this->entry);
		}

		// init form
		if ($this->action == 'edit') {
			require_once(WCF_DIR.'lib/form/ContestSponsorEditForm.class.php');
			new ContestSponsorEditForm($this->entry);
		}
		else if($this->entry->isSponsorable()) {
			require_once(WCF_DIR.'lib/form/ContestSponsorAddForm.class.php');
			new ContestSponsorAddForm($this->entry);
		}

		if($this->entry->enableSponsorCheck && !$this->entry->isSponsor()) {
			WCF::getTPL()->append('additionalContentBecomeSponsor', 
				'<p class="info">'.WCF::getLanguage()->get('wcf.contest.enableSponsorCheck.info').'</p>');
		}

		if($this->entry->isEnabledJury() && $this->entry->state == 'closed') {
			WCF::getTPL()->append('userMessages', '<p class="info">'.WCF::getLanguage()->get('wcf.contest.jury.closed.info').'</p>');

			// init todo list
			require_once(WCF_DIR.'lib/data/contest/sponsor/todo/ContestSponsorTodoList.class.php');
			$this->todoList = new ContestSponsorTodoList();
			$this->todoList->sqlConditions .= 'contest_sponsor.contestID = '.$this->contestID;
			$this->todoList->readObjects();
		}

		$this->sidebar->assignVariables();
		WCF::getTPL()->assign(array(
			'entry' => $this->entry,
			'isSponsor' => $this->isSponsor,
			'contestID' => $this->contestID,
			'userID' => $this->entry->userID,
			'sponsors' => $this->sponsorList->getObjects(),
			'todos' => $this->todoList ? $this->todoList->getObjects() : array(),
			'templateName' => $this->templateName,
			'allowSpidersToIndexThisPage' => true,

			'contestmenu' => ContestMenu::getInstance(),
		));
	}

	/**
	 * @see Form::show()
	 */
	public function show() {
		// set active header menu item
		PageMenu::setActiveMenuItem('wcf.header.menu.user.contest');

		// set active menu item
		ContestMenu::getInstance()->setContest($this->entry);
		ContestMenu::getInstance()->setActiveMenuItem('wcf.contest.menu.link.sponsor');

		// check permission
		WCF::getUser()->checkPermission('user.contest.canViewContest');

		if (!MODULE_CONTEST) {
			throw new IllegalLinkException();
		}

		parent::show();
	}
}
?>
