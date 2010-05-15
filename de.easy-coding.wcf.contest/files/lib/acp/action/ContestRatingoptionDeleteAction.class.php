<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');
require_once(WCF_DIR.'lib/data/contest/ratingoption/ContestRatingoptionEditor.class.php');

/**
 * Rule item delete action.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestRatingoptionDeleteAction extends AbstractAction {
	/**
	 * class item id
	 *
	 * @var integer
	 */
	public $optionID = 0;

	/**
	 * class item object
	 *
	 * @var ContestRatingoptionEditor
	 */
	public $contestRatingoption = null;

	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		if (isset($_REQUEST['optionID'])) $this->contestRatingoptionID = intval($_REQUEST['optionID']);
		$this->contestRatingoption = new ContestRatingoptionEditor($this->contestRatingoptionID);
		if (!$this->contestRatingoption->optionID) {
			throw new IllegalLinkException();
		}
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();

		// check permissions
		WCF::getUser()->checkPermission('admin.contest.canDeleteRatingoption');

		// delete
		$this->contestRatingoption->delete();
		
		$this->executed();

		// forward to list page
		header('Location: index.php?page=ContestRatingoptionList&deletedoptionID='.$this->contestRatingoptionID.'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>
