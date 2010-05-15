<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');
require_once(WCF_DIR.'lib/data/contest/ratingoption/ContestRatingoptionEditor.class.php');

/**
 * Sorts the structure of ratingoption items.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestRatingoptionSortAction extends AbstractAction {
	/**
	 * New positions
	 *
	 * @var	array<mixed>
	 */
	public $positions = array();
	
	/**
	 * Ruleset ID
	 *
	 * @var integer
	 */
	public $optionID = 0;

	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		// get positions
		if (isset($_POST['ratingoptionListPositions']) && is_array($_POST['ratingoptionListPositions'])) $this->positions = ArrayUtil::toIntegerArray($_POST['ratingoptionListPositions']);
		if (isset($_POST['optionID'])) $this->optionID = intval($_POST['optionID']);
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();

		// check permissions
		WCF::getUser()->checkPermission('admin.ratingoption.canEditItem');

		// update postions
		foreach ($this->positions as $contestRatingoptionID => $data) {
			foreach ($data as $parentContestRatingoptionID => $position) {
				$parentContestRatingoption = '';
				if ($parentContestRatingoptionID != 0) {
					$parentRuleObject = new ContestRatingoption($parentContestRatingoptionID);
					$parentContestRatingoption = $parentRuleObject->contestRatingoption;
				}

				ContestRatingoptionEditor::updateShowOrder(intval($contestRatingoptionID), $parentContestRatingoption, $position);
			}
		}

		$this->executed();

		// forward to list page
		HeaderUtil::redirect('index.php?page=ContestRatingoptionList&optionID='.$this->optionID.'&successfullSorting=1&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>
