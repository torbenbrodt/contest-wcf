<?php
// wcf imports
require_once(WCF_DIR.'lib/form/MessageForm.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestEditor.class.php');
require_once(WCF_DIR.'lib/data/contest/class/ContestClass.class.php');
require_once(WCF_DIR.'lib/data/contest/jury/ContestJury.class.php');
require_once(WCF_DIR.'lib/data/contest/sponsor/ContestSponsor.class.php');
require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipant.class.php');
require_once(WCF_DIR.'lib/data/contest/price/ContestPrice.class.php');
require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');

/**
 * Shows the form for adding new contest entries.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestAddForm extends MessageForm {
	// system
	public $templateName = 'contestEntryAdd';
	public $showPoll = false;
	public $showSignatureSetting = false;
	public $tags = '';
	
	// form parameters
	public $ownerID = 0;
	public $userID = 0;
	public $groupID = 0;
	
	/**
	 * attachment list editor
	 * 
	 * @var	AttachmentListEditor
	 */
	public $attachmentListEditor = null;
	
	/**
	 * list of class ids
	 * 
	 * @var	array<integer>
	 */
	public $classIDArray = array();
	
	/**
	 * list of available classes
	 * 
	 * @var	array<ContestClass>
	 */
	public $availableClasses = array();
	
	/**
	 * list of available groups
	 * 
	 * @var	array<Group>
	 */
	public $availableGroups = array();
	
	/**
	 * list of available participant permisions
	 * 
	 * @var	array
	 */
	public $participants = array();
	
	/**
	 * list of available prices
	 * 
	 * @var	array
	 */
	public $prices = array();
	
	/**
	 * list of available sponsor permisions
	 * 
	 * @var	array
	 */
	public $sponsors = array();
	
	/**
	 * list of available jury permisions
	 * 
	 * @var	array
	 */
	public $jurys = array();
	
	/**
	 * send jurytalks?
	 * @var boolean
	 */
	public $jurytalk_trigger = false;
	
	/**
	 * send sponsortalks?
	 * @var boolean
	 */
	public $sponsortalk_trigger = false;
	
	/**
	 * send first comment?
	 * @var boolean
	 */
	public $comment_trigger = false;
	
	/**
	 * talks
	 */
	public $sponsortalk_message = '';
	public $jurytalk_message = '';
	public $comment_message = '';
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// check permissions
		if (MODULE_CONTEST != 1 || !WCF::getUser()->userID) {
			throw new IllegalLinkException();
		}
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		$this->availableClasses = ContestClass::getClasses();
		$this->readAvailableGroups();
	}
	
	/**
	 * returns the groups for which the user is admin
	 */
	protected function readAvailableGroups() {
		$sql = "SELECT		usergroup.*, (
						SELECT	COUNT(*)
						FROM	wcf".WCF_N."_user_to_groups
						WHERE	groupID = usergroup.groupID
					) AS members
			FROM 		wcf".WCF_N."_group usergroup
			WHERE		groupID IN (
						SELECT	groupID
						FROM	wcf".WCF_N."_group_leader
						WHERE	leaderUserID = ".WCF::getUser()->userID."
							OR leaderGroupID IN (".implode(',', WCF::getUser()->getGroupIDs()).")
					)
			ORDER BY 	groupName";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->availableGroups[$row['groupID']] = new Group(null, $row);
		}
	}
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();

		if (isset($_POST['tags'])) $this->tags = StringUtil::trim($_POST['tags']);
		if (isset($_POST['send'])) $this->send = (boolean) $_POST['send'];
		if (isset($_POST['classIDArray']) && is_array($_POST['classIDArray'])) $this->classIDArray = $_POST['classIDArray'];
		if (isset($_POST['sponsor']) && is_array($_POST['sponsor'])) $this->sponsors = $_POST['sponsor'];
		if (isset($_POST['jury']) && is_array($_POST['jury'])) $this->jurys = $_POST['jury'];
		if (isset($_POST['participant']) && is_array($_POST['participant'])) $this->participants = $_POST['participant'];
		if (isset($_POST['price']) && is_array($_POST['price'])) $this->prices = $_POST['price'];
		if (isset($_POST['ownerID'])) $this->ownerID = intval($_POST['ownerID']);
		
		// sponsortalk
		$this->jurytalk_trigger = isset($_POST['jurytalk_trigger']);
		if($this->jurytalk_trigger && isset($_POST['jurytalkAddText'])) $this->jurytalk_message = $_POST['jurytalkAddText'];
		
		// jurytalk
		$this->sponsortalk_trigger = isset($_POST['sponsortalk_trigger']);
		if($this->jurytalk_trigger && isset($_POST['sponsortalkAddText'])) $this->sponsortalk_message = $_POST['sponsortalkAddText'];
		
		// comment
		$this->comment_trigger = isset($_POST['comment_trigger']);
		if($this->comment_trigger && isset($_POST['commentAddText'])) $this->comment_message = $_POST['commentAddText'];
		
		if ($this->ownerID == 0) {
			$this->userID = WCF::getUser()->userID;
		} else {
			$this->groupID = $this->ownerID;
		}
	}
	
	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();
		
		$this->availableClasses = ContestClass::getClasses();
		
		// validate class ids
		foreach ($this->classIDArray as $key => $classID) {
			if (!isset($this->availableClasses[$classID])) unset($this->classIDArray[$key]);
		}
		
		if(count($this->classIDArray) == 0) {
			throw new UserInputException('classes'); 
		}
		
		if($this->ownerID != 0) {
			$this->readAvailableGroups();
		
			// validate group ids
			if(!array_key_exists($this->ownerID, $this->availableGroups)) {
				throw new UserInputException('ownerID'); 
			}
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		// save entry
		$entry = ContestEditor::create($this->userID, $this->groupID, $this->subject, $this->text, $this->getOptions(), 
			$this->classIDArray, $this->participants, $this->jurys, $this->prices, $this->sponsors, $this->attachmentListEditor);
		$this->saved();

		$contestID = $entry->contestID;
		
		if($this->sponsortalk_trigger) {
			require_once(WCF_DIR.'lib/data/contest/sponsortalk/ContestSponsortalkEditor.class.php');
			$sponsortalk = ContestSponsortalkEditor::create($contestID, $this->sponsortalk_message, 
				WCF::getUser()->userID, WCF::getUser()->username);
		}
		
		if($this->jurytalk_trigger) {
			require_once(WCF_DIR.'lib/data/contest/jurytalk/ContestJurytalkEditor.class.php');
			$jurytalk = ContestJurytalkEditor::create($contestID, $this->jurytalk_message, 
				WCF::getUser()->userID, WCF::getUser()->username);
		}
		
		if($this->comment_trigger) {
			require_once(WCF_DIR.'lib/data/contest/comment/ContestCommentEditor.class.php');
			$comment = ContestCommentEditor::create($contestID, $this->comment_message, 
				WCF::getUser()->userID, WCF::getUser()->username);
		}
		
		// save tags
		if (MODULE_TAGGING) {
			$tagArray = TaggingUtil::splitString($this->tags);
			if (count($tagArray)) $entry->updateTags($tagArray);
		}
		
		// forward
		HeaderUtil::redirect('index.php?page=Contest&contestID='.$entry->contestID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'action' => 'add',
			'userID' => WCF::getUser()->userID,
			'tags' => $this->tags,
			'insertQuotes' => (!count($_POST) && empty($this->text) ? 1 : 0),
			'availableClasses' => $this->availableClasses,
			'availableGroups' => $this->availableGroups,
			'ownerID' => $this->ownerID,
			'classIDArray' => $this->classIDArray,
			'sponsors' => $this->sponsors,
			'participants' => $this->participants,
			'jurys' => $this->jurys,
			'prices' => $this->prices,
			'jurytalk_trigger' => $this->jurytalk_trigger,
			'sponsortalk_trigger' => $this->sponsortalk_trigger,
			'comment_trigger' => $this->comment_trigger,
			
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		// set active header menu item
		PageMenu::setActiveMenuItem('wcf.header.menu.user.contest');
		
		// check permission
		WCF::getUser()->checkPermission('user.contest.canUseContest');
		
		if (!MODULE_CONTEST) {
			throw new IllegalLinkException();
		}
		
		// check upload permission
		if (MODULE_ATTACHMENT != 1 || !WCF::getUser()->getPermission('user.contest.canUploadAttachment')) {
			$this->showAttachments = false;
		}
		
		// get attachments editor
		if ($this->attachmentListEditor == null) {
			require_once(WCF_DIR.'lib/data/attachment/MessageAttachmentListEditor.class.php');
			$this->attachmentListEditor = new MessageAttachmentListEditor(array(), 'contestEntry', WCF::getPackageID('de.easy-coding.wcf.contest'), WCF::getUser()->getPermission('user.contest.maxAttachmentSize'), WCF::getUser()->getPermission('user.contest.allowedAttachmentExtensions'), WCF::getUser()->getPermission('user.contest.maxAttachmentCount'));
		}
		
		parent::show();
	}
}
?>
