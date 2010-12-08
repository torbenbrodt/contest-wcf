<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');
require_once(WCF_DIR.'lib/util/ContestPromotionUtil.class.php');

/**
 * This action disables notifications
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @package	de.easy-coding.wcf.contest.promotion
 */
class ContestPromotionAction extends AbstractSecureAction {

	/**
	 * The id of the contest
	 *
	 * @var integer
	 */
	public $contestID = 0;

	/**
	 * contest instance
	 *
	 * @var Contest
	 */
	public $contest = null;

	/**
	 * @var string
	 */
	public $contestAction = '';

	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		if (!MODULE_CONTEST) {
			throw new IllegalLinkException();
		}

		$this->contestID = intval($_REQUEST['contestID']);
		
		if(isset($_REQUEST['contestAction'])) $this->contestAction = $_REQUEST['contestAction'];

		$this->contest = new Contest($this->contestID);
		if (!$this->contest->isViewable()) {
			throw new IllegalLinkException();
		}
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();

		// remember
		ContestPromotionUtil::updateList($this->contestID);

		$this->executed();
		
		switch($this->contestAction) {
			case 'participate':
				if(WCF::getUser()->userID == 0) {
					// forward
                                        HeaderUtil::redirect('index.php?page=Contest'.
                                                '&contestID='.$this->contestID.
                                                SID_ARG_2ND_NOT_ENCODED
                                        );

				} else {

					$state = $this->contest->enableParticipantCheck ? 'applied' : 'accepted';
	
					// add participant
	
					require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipantEditor.class.php');
					$participant = ContestParticipantEditor::create($this->contestID, WCF::getUser()->userID, 0, $state);
			
					// forward
					HeaderUtil::redirect('index.php?page=ContestParticipant'.
						'&contestID='.$this->contestID.
						'&participantID='.$participant->participantID.
						SID_ARG_2ND_NOT_ENCODED.'#participant'.$participant->participantID
					);
				}
			break;
		}
	}
}
?>
