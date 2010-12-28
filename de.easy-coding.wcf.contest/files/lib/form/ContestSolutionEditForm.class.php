<?php
// wcf imports
require_once(WCF_DIR.'lib/form/ContestSolutionAddForm.class.php');

/**
 * Shows the form for editing contest entry solutions.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSolutionEditForm extends ContestSolutionAddForm {
	public $action = 'edit';
	
	/**
	 * solution editor
	 *
	 * @var ContestSolutionEditor
	 */
	public $entry = null;
	
	/**
	 * solution id
	 *
	 * @var integer
	 */
	public $solutionID = 0;
	
	/**
	 * @see Form::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get entry
		if (isset($_REQUEST['contestID'])) $this->contestID = intval($_REQUEST['contestID']);
		$this->contest = new ViewableContest($this->contestID);
		if (!$this->contest->contestID) {
			throw new IllegalLinkException();
		}

		if (isset($_REQUEST['solutionID'])) $this->solutionID = intval($_REQUEST['solutionID']);
		$this->entry = new ContestSolutionEditor($this->solutionID);
		if (!$this->entry->solutionID || !$this->entry->isOwner()) {
			throw new IllegalLinkException();
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		MessageForm::save();
		
		// save solution
		$this->entry->update($this->text, $this->state, $this->getOptions(), $this->attachmentListEditor);
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestSolutionEntry&contestID='.$this->entry->contestID.'&solutionID='.$this->entry->solutionID.SID_ARG_2ND_NOT_ENCODED.'#solution'.$this->entry->solutionID);
		exit;
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		// default values
		if (!count($_POST)) {
			$this->text = $this->entry->message;
			$this->enableSmilies =  $this->entry->enableSmilies;
			$this->enableHtml = $this->entry->enableHtml;
			$this->enableBBCodes = $this->entry->enableBBCodes;
			$this->enableParticipantCheck = $this->entry->enableParticipantCheck;
			$this->enableSponsorCheck = $this->entry->enableSponsorCheck;
			$this->userID = $this->entry->userID;
			$this->groupID = $this->entry->groupID;
			$this->state = $this->entry->state;
			
			if($this->groupID > 0) {
				$this->ownerID = $this->groupID;
			}
		}
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'solutionID' => $this->solutionID
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		require_once(WCF_DIR.'lib/data/attachment/MessageAttachmentListEditor.class.php');
		$this->attachmentListEditor = new MessageAttachmentListEditor(array($this->solutionID), 'contestSolutionEntry', WCF::getPackageID('de.easy-coding.wcf.contest'), WCF::getUser()->getPermission('user.contest.maxAttachmentSize'), WCF::getUser()->getPermission('user.contest.allowedAttachmentExtensions'), WCF::getUser()->getPermission('user.contest.maxAttachmentCount'));
		
		parent::show();
	}
}
?>
