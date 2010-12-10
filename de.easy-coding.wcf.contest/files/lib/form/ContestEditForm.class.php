<?php
// wcf imports
require_once(WCF_DIR.'lib/form/MessageForm.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestEditor.class.php');
require_once(WCF_DIR.'lib/data/contest/class/ContestClassTree.class.php');
require_once(WCF_DIR.'lib/data/contest/jury/ContestJury.class.php');
require_once(WCF_DIR.'lib/data/contest/sponsor/ContestSponsor.class.php');
require_once(WCF_DIR.'lib/data/contest/price/ContestPrice.class.php');
require_once(WCF_DIR.'lib/data/contest/date/ContestDate.class.php');
require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
require_once(WCF_DIR.'lib/util/ContestUtil.class.php');
require_once(WCF_DIR.'lib/page/util/InlineCalendar.class.php');
require_once(WCF_DIR.'lib/data/contest/state/ContestState.class.php');
require_once(WCF_DIR.'lib/data/contest/crew/ContestCrew.class.php');

/**
 * Shows the form for adding new contest entries.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestEditForm extends MessageForm {
	// system
	public $templateName = 'contestEntryEdit';
	public $showPoll = false;
	public $showSignatureSetting = false;
	public $tags = '';
	public $preview, $send;
	
	// form parameters
	public $ownerID = 0;
	public $userID = 0;
	public $groupID = 0;
	public $state = '';
	public $fromTime = 0;
	public $untilTime = 0;
	public $isFullDay = 0;
	
	// options
	public $enableOpenSolutions = 1;
	public $enableParticipantCheck = 0;
	public $enableSponsorCheck = 0;
	
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
	 * list of contest classes
	 * 
	 * @var	ContestClassTree
	 */
	public $classList = null;
	
	/**
	 * list of available groups
	 * 
	 * @var	array<Group>
	 */
	public $availableGroups = array();
	
	/**
	 *
	 */
	public $states = array();
	
	/**
	 * start
	 */
	public $fromDay = 0;
	public $fromMonth = 0;
	public $fromYear = 0;
	public $fromHour = 0;
	public $fromMinute = 0;
	
	/**
	 * end
	 */
	public $untilDay = 0;
	public $untilMonth = 0;
	public $untilYear = 0;
	public $untilHour = 0;
	public $untilMinute = 0;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// check permissions
		if (MODULE_CONTEST != 1 || !WCF::getUser()->userID) {
			throw new IllegalLinkException();
		}

		if (isset($_REQUEST['contestID'])) $this->contestID = intval($_REQUEST['contestID']);
		$this->entry = new ContestEditor($this->contestID);
		if (!$this->entry->contestID || !$this->entry->isEditable()) {
			throw new IllegalLinkException();
		}
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		// default values
		if (!count($_POST)) {
			$this->subject = $this->entry->subject;
			$this->text = $this->entry->message;
			$this->enableSmilies =  $this->entry->enableSmilies;
			$this->enableHtml = $this->entry->enableHtml;
			$this->enableBBCodes = $this->entry->enableBBCodes;
			$this->enableOpenSolutions = $this->entry->enableOpenSolutions;
			$this->enableParticipantCheck = $this->entry->enableParticipantCheck;
			$this->enableSponsorCheck = $this->entry->enableSponsorCheck;
			$this->userID = $this->entry->userID;
			$this->groupID = $this->entry->groupID;
			$this->state = $this->entry->state;
			$this->isFullDay = $this->entry->isFullDay;
			$this->fromTime = $this->entry->fromTime;
			$this->untilTime = $this->entry->untilTime;
			$this->classIDArray = array_keys($this->entry->getClasses());
			
			if($this->groupID > 0) {
				$this->ownerID = $this->groupID;
			}
			
			// tags
			if (MODULE_TAGGING) {
				$this->tags = TaggingUtil::buildString($this->entry->getTags(array((count(Language::getAvailableContentLanguages()) > 0 ? WCF::getLanguage()->getLanguageID() : 0))));
			}
		}

		$from = $this->fromTime == 0 ? time() : $this->fromTime;
		$until = $this->untilTime == 0 ? time() : $this->untilTime;
		
		$this->eventDate = new ContestDate(array(
			'isFullDay' => $this->isFullDay,
			'fromDay' => date('d', $from),
			'fromMonth' => date('m', $from),
			'fromYear' => date('Y', $from),
			'fromHour' => date('h', $from),
			'fromMinute' => date('i', $from),
			'untilDay' => date('d', $until),
			'untilMonth' => date('m', $until),
			'untilYear' => date('Y', $until),
			'untilHour' => date('h', $until),
			'untilMinute' => date('i', $until),
		));

		// get classes
		$this->classList = new ContestClassTree();
		$this->classList->readObjects();
		
		$this->availableClasses = ContestClass::getClasses();
		$this->states = $this->getStates();
		$this->availableGroups = ContestUtil::readAvailableGroups();
	}
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		$this->enableOpenSolutions = intval(isset($_POST['enableOpenSolutions']));
		$this->enableParticipantCheck = intval(isset($_POST['enableParticipantCheck']));
		$this->enableSponsorCheck = intval(isset($_POST['enableSponsorCheck']));
		
		$this->isFullDay = isset($_POST['isFullDay']);
		
		if (isset($_POST['tags'])) $this->tags = StringUtil::trim($_POST['tags']);
		if (isset($_POST['preview'])) $this->preview = (boolean) $_POST['preview'];
		if (isset($_POST['send'])) $this->send = (boolean) $_POST['send'];
		if (isset($_POST['state'])) $this->state = StringUtil::trim($_POST['state']);
		if (isset($_POST['classIDArray']) && is_array($_POST['classIDArray'])) $this->classIDArray = $_POST['classIDArray'];
		if (isset($_POST['ownerID'])) $this->ownerID = intval($_POST['ownerID']);
		
		if (isset($_POST['fromDay'])) $this->fromDay = intval($_POST['fromDay']);
		if (isset($_POST['fromMonth'])) $this->fromMonth = intval($_POST['fromMonth']);
		if (isset($_POST['fromYear'])) $this->fromYear = intval($_POST['fromYear']);
		if (isset($_POST['fromHour']) && !$this->isFullDay) $this->fromHour = intval($_POST['fromHour']);
		if (isset($_POST['fromMinute']) && !$this->isFullDay) $this->fromMinute = intval($_POST['fromMinute']);
		
		// starttime
		$this->fromTime = mktime(
			$this->fromHour, 
			$this->fromMinute, 
			0, // second 
			$this->fromMonth, 
			$this->fromDay, 
			$this->fromYear
		);

		if (isset($_POST['untilDay'])) $this->untilDay = intval($_POST['untilDay']);
		if (isset($_POST['untilMonth'])) $this->untilMonth = intval($_POST['untilMonth']);
		if (isset($_POST['untilYear'])) $this->untilYear = intval($_POST['untilYear']);
		if (isset($_POST['untilHour']) && !$this->isFullDay) $this->untilHour = intval($_POST['untilHour']);
		if (isset($_POST['untilMinute']) && !$this->isFullDay) $this->untilMinute = intval($_POST['untilMinute']);
		
		// endtime
		$this->untilTime = mktime(
			$this->untilHour, 
			$this->untilMinute, 
			0, // second
			$this->untilMonth, 
			$this->untilDay, 
			$this->untilYear
		);
		
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
			$this->availableGroups = ContestUtil::readAvailableGroups();
		
			// validate group ids
			if(!array_key_exists($this->ownerID, $this->availableGroups)) {
				throw new UserInputException('ownerID'); 
			}
		}
		
		if(!array_key_exists($this->state, $this->getStates())) {
			throw new UserInputException('state');
		}
	}
	
	/**
	 * returns available states
	 */
	protected function getStates() {
		$flags = ($this->entry->isOwner() ? ContestState::FLAG_USER : 0)
			| ($this->entry->isOwner() ? ContestState::FLAG_CONTESTOWNER : 0)
			| (ContestCrew::isMember() ? ContestState::FLAG_CREW : 0);
		return ContestEditor::getStates($this->state, $flags, $this->entry->isClosable());
	}
	
	/**
	 * return the options
	 */
	protected function getOptions() {
		$options = parent::getOptions();
		$options['enableOpenSolutions'] = $this->enableOpenSolutions;
		$options['enableParticipantCheck'] = $this->enableParticipantCheck;
		$options['enableSponsorCheck'] = $this->enableSponsorCheck;
		return $options;
	}
	
	/**
	 * @see Form::submit()
	 */
	public function submit() {
		// call submit event
		EventHandler::fireAction($this, 'submit');
		
		$this->readFormParameters();
		
		try {
			// attachment handling
			if ($this->showAttachments) {
				$this->attachmentListEditor->handleRequest();
			}

			// preview
			if ($this->preview) {
				require_once(WCF_DIR.'lib/data/message/bbcode/AttachmentBBCode.class.php');
				AttachmentBBCode::setAttachments($this->attachmentListEditor->getSortedAttachments());
				WCF::getTPL()->assign('preview', ContestSolutionEditor::createPreview($this->subject, $this->text, $this->enableSmilies, $this->enableHtml, $this->enableBBCodes));
			}

			// send message or save as draft
			if ($this->send) {
				$this->validate();
				
				// no errors
				$this->save();
			}
		}
		catch (UserInputException $e) {
			$this->errorField = $e->getField();
			$this->errorType = $e->getType();
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		// save entry
		$this->entry->update($this->userID, $this->groupID, $this->subject, $this->text, $this->fromTime, $this->untilTime, $this->state, $this->getOptions(), 
			$this->classIDArray, $this->attachmentListEditor);
		$this->saved();
		
		// save tags
		if (MODULE_TAGGING) {
			$tagArray = TaggingUtil::splitString($this->tags);
			if (count($tagArray)) $this->entry->updateTags($tagArray);
		}
		
		// forward
		HeaderUtil::redirect('index.php?page=Contest&contestID='.$this->entry->contestID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		// display branding
		require_once(WCF_DIR.'lib/util/ContestUtil.class.php');
		ContestUtil::assignVariablesBranding();

		InlineCalendar::assignVariables();		
		WCF::getTPL()->assign(array(
			'action' => 'edit',
			'userID' => WCF::getUser()->userID,
			'tags' => $this->tags,
			'insertQuotes' => (!count($_POST) && empty($this->text) ? 1 : 0),
			'availableClasses' => $this->classList->getObjects(),
			'availableGroups' => $this->availableGroups,
			'contestID' => $this->contestID,
			'ownerID' => $this->ownerID,
			'classIDArray' => $this->classIDArray,
			'states' => $this->states,
			'state' => $this->state,
			'eventDate' => $this->eventDate,
			'enableOpenSolutions' => $this->enableOpenSolutions,
			'enableParticipantCheck' => $this->enableParticipantCheck,
			'enableSponsorCheck' => $this->enableSponsorCheck,
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
		if ($this->attachmentListEditor === null) {
			require_once(WCF_DIR.'lib/data/attachment/MessageAttachmentListEditor.class.php');
			$this->attachmentListEditor = new MessageAttachmentListEditor(array($this->contestID), 'contestEntry', WCF::getPackageID('de.easy-coding.wcf.contest'), WCF::getUser()->getPermission('user.contest.maxAttachmentSize'), WCF::getUser()->getPermission('user.contest.allowedAttachmentExtensions'), WCF::getUser()->getPermission('user.contest.maxAttachmentCount'));
		}
		
		parent::show();
	}
}
?>
