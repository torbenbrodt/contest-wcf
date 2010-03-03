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
	public $solutionObj = null;
	
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
		
		// get contest
		if (!$this->contest->isSolutionable()) {
			throw new PermissionDeniedException();
		}

		if (isset($_REQUEST['solutionID'])) $this->solutionID = intval($_REQUEST['solutionID']);
		$this->solutionObj = new ContestSolutionEditor($this->solutionID);
		if (!$this->solutionObj->solutionID || !$this->solutionObj->isOwner()) {
			throw new IllegalLinkException();
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		MessageForm::save();
		
		// save solution
		$this->solutionObj->update($this->text, $this->state, $this->getOptions(), $this->attachmentListEditor);
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestSolutionEntry&contestID='.$this->solutionObj->contestID.'&solutionID='.$this->solutionObj->solutionID.SID_ARG_2ND_NOT_ENCODED.'#solution'.$this->solutionObj->solutionID);
		exit;
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		// default values
		if (!count($_POST)) {
			$this->text = $this->solutionObj->message;
			$this->enableSmilies =  $this->solutionObj->enableSmilies;
			$this->enableHtml = $this->solutionObj->enableHtml;
			$this->enableBBCodes = $this->solutionObj->enableBBCodes;
			$this->enableParticipantCheck = $this->solutionObj->enableParticipantCheck;
			$this->enableSponsorCheck = $this->solutionObj->enableSponsorCheck;
			$this->userID = $this->solutionObj->userID;
			$this->groupID = $this->solutionObj->groupID;
			$this->state = $this->solutionObj->state;
			
			if($this->groupID > 0) {
				$this->ownerID = $this->groupID;
			}
		}
		
		$this->states = ContestSolutionEditor::getStates($this->state, $this->contest->isOwner());
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
		$this->attachmentListEditor = new MessageAttachmentListEditor(array($this->solutionID), 'contestSolutionEntry', WCF::getPackageID('de.easy-coding.wcf.contest'), WCF::getUser()->getPermission('user.blog.maxAttachmentSize'), WCF::getUser()->getPermission('user.contest.allowedAttachmentExtensions'), WCF::getUser()->getPermission('user.contest.maxAttachmentCount'));
		
		parent::show();
	}
}
?>
