<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');
require_once(WCF_DIR.'lib/data/contest/interaction/ContestInteraction.class.php');

/**
 * close an interactive contest
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @package	de.easy-coding.wcf.contest.interaction
 */
class ContestInteractionCloseAction extends AbstractSecureAction {

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
		$interaction = new ContestInteraction($this->contest);
		$interaction->close();

		// redirect
		HeaderUtil::redirect('index.php?page=Contest'.
                        '&contestID='.$this->contestID.
                        SID_ARG_2ND_NOT_ENCODED
                );

		$this->executed();
	}
}
?>
