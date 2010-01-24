<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipantEditor.class.php');

/**
 * Deletes a contest entry participant.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Participants
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestParticipantDeleteAction extends AbstractSecureAction {
	/**
	 * participant id
	 *
	 * @var integer
	 */
	public $participantID = 0;
	
	/**
	 * participant editor object
	 *
	 * @var ContestParticipantEditor
	 */
	public $participant = null;
	
	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['participantID'])) $this->participantID = intval($_REQUEST['participantID']);
		$this->participant = new ContestParticipantEditor($this->participantID);
		if (!$this->participant->participantID) {
			throw new IllegalLinkException();
		}
		if (!$this->participant->isDeletable()) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		
		// delete participant
		$this->participant->delete();
		$this->executed();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestParticipant&contestID='.$this->participant->contestID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>
