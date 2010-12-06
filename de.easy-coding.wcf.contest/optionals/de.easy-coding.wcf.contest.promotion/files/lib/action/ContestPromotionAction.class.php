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
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		if (!MODULE_CONTEST) {
			throw new IllegalLinkException();
		}

		$this->contestID = intval($_REQUEST['contestID']);

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

		ContetPromotionUtil::updateList($this->contestID);

		$this->executed();

		HeaderUtil::redirect();
		exit;
	}
}
?>
