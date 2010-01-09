<?php
// wcf imports
require_once(WCF_DIR.'lib/form/ContestEntryAddForm.class.php');

/**
 * Shows the form for editing contest entries.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestEntryEditForm extends ContestEntryAddForm {
	/**
	 * entry id
	 *
	 * @var integer
	 */
	public $contestID = 0;
	
	/**
	 * entry editor object
	 *
	 * @var ContestEntryEditor
	 */
	public $entry = null;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		MessageForm::readParameters();
		
		if (isset($_REQUEST['contestID'])) $this->contestID = intval($_REQUEST['contestID']);
		$this->entry = new ContestEntryEditor($this->contestID);
		if (!$this->entry->contestID || !$this->entry->isEditable()) {
			throw new IllegalLinkException();
		}
		
		// get profile frame
		$this->frame = new UserProfileFrame($this, $this->entry->userID);
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		MessageForm::save();
		
		// save entry
		$this->entry->update($this->subject, $this->location, $this->text, $this->getOptions(), $this->classIDArray, $this->participantIDArray, $this->juryIDArray, $this->priceIDArray, $this->sponsorIDArray, $this->attachmentListEditor);
		
		// save tags
		if (MODULE_TAGGING) {
			$this->entry->updateTags(TaggingUtil::splitString($this->tags));
		}
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestEntry&userID='.$this->frame->getUserID().'&contestID='.$this->entry->contestID.SID_ARG_2ND_NOT_ENCODED.'#entry'.$this->entry->contestID);
		exit;
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		// default values
		if (!count($_POST)) {
			$this->subject = $this->entry->subject;
			$this->location = $this->entry->location;
			$this->text = $this->entry->message;
			$this->enableSmilies =  $this->entry->enableSmilies;
			$this->enableHtml = $this->entry->enableHtml;
			$this->enableBBCodes = $this->entry->enableBBCodes;
			$this->userID = $this->entry->userID;
			$this->groupID = $this->entry->groupID;
			
			if($this->groupID > 0) {
				$this->ownerID = $this->groupID;
			}
			
			// tags
			if (MODULE_TAGGING) {
				$this->tags = TaggingUtil::buildString($this->entry->getTags(array((count(Language::getAvailableContentLanguages()) > 0 ? WCF::getLanguage()->getLanguageID() : 0))));
			}
			
			$classes = $this->entry->getClasses();
			$jurys = $this->entry->getJurys();
			$participants = $this->entry->getParticipants();
			$prices = $this->entry->getPrices();
			$this->classIDArray = array_keys($classes);
			$this->juryIDArray = array_keys($jurys);
			$this->participantIDArray = array_keys($participants);
			$this->priceIDArray = array_keys($prices);
		}
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'action' => 'edit',
			'contestID' => $this->contestID
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		require_once(WCF_DIR.'lib/data/attachment/MessageAttachmentListEditor.class.php');
		$this->attachmentListEditor = new MessageAttachmentListEditor(array($this->contestID), 'contestEntry', WCF::getPackageID('de.easy-coding.wcf.contest'), WCF::getUser()->getPermission('user.contest.maxAttachmentSize'), WCF::getUser()->getPermission('user.contest.allowedAttachmentExtensions'), WCF::getUser()->getPermission('user.contest.maxAttachmentCount'));
		
		parent::show();
	}
}
?>
