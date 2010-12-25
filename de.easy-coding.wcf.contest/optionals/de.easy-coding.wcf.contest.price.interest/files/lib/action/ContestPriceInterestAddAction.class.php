<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/data/contest/price/ContestPrice.class.php');
require_once(WCF_DIR.'lib/data/contest/price/interest/ContestPriceInterestEditor.class.php');

/**
 * close an interactive contest
 * 
 * @author	Torben Brodtrequire_once(WCF_DIR.'lib/data/contest/price/ContestPrice.class.php');
 * @copyright	2010 easy-coding.de
 * @package	de.easy-coding.wcf.contest.price.interest
 */
class ContestPriceInterestAction extends AbstractSecureAction {

	/**
	 * The id of the contest
	 *
	 * @var integer
	 */
	public $priceID = 0;

	/**
	 * The id of the interest to be deleted
	 *
	 * @var integer
	 */
	public $interestID = 0;

	/**
	 * contest price instanceContestInteraction
	 *
	 * @var ContestPrice
	 */
	public $price = null;

	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();require_once(WCF_DIR.'lib/data/contest/price/ContestPrice.class.php');

		if (!MODULE_CONTEST) {
			throw new IllegalLinkException();
		}

		$this->priceID = intval($_REQUEST['priceID']);
		$this->participantID = intval($_REQUEST['participantID']);
		if(isset($_REQUEST['interestID'])) $this->interestID = $_REQUEST['interestID'];
		
		if(isset($_REQUEST['contestAction'])) $this->contestAction = $_REQUEST['contestAction'];

		$this->contest = new Contest($this->priceID);
		if (!$this->contest->isViewable()) {
			throw new IllegalLinkException();
		}
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();

		switch($this->contestAction) {
			case 'add':
				$participantID = 0;
				$interest = ContestPriceInterestEditor::add($this->priceID, $participantID);
			break;
			case 'delete':
				$interest = new ContestPriceInterestEditor($this->interestID);
				$interest->delete();
			break;
		}

		// redirect
		HeaderUtil::redirect('index.php?page=ContestPrice'.
                        '&priceID='.$this->priceID.
                        SID_ARG_2ND_NOT_ENCODED
                );

		$this->executed();
	}
}
?>
