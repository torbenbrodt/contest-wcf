<?php
// wcf imports
require_once(WCF_DIR.'lib/form/AbstractContestForm.class.php');
require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipantEditor.class.php');

/**
 * Shows the form for inviting contest participants.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestParticipantInviteForm extends AbstractContestForm {
	
	/**
	 *
	 * @var array<ContestParticipant>
	 */
	protected $participants = array();
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['participant']) && is_array($_POST['participant'])) $this->participants = $_POST['participant'];
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		// save participant
		$inserts = '';
		foreach ($this->participants as $participant) {
			$userID = $groupID = 0;
			switch($participant['type']) {
				case 'user':
					$userID = intval($participant['id']);
				break;
				case 'group':
					$groupID = intval($participant['id']);
				break;
			}
			ContestParticipantEditor::create($this->contest->contestID, $userID, $groupID, 'invited');
		}
		
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestParticipant&contestID='.$this->contest->contestID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'participants' => $this->participants,
		));
	}
}
?>
