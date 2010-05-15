<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');
require_once(WCF_DIR.'lib/data/contest/class/ContestClassEditor.class.php');

/**
 * Sorts the structure of class items.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestClassSortAction extends AbstractAction {
	/**
	 * New positions
	 *
	 * @var	array<mixed>
	 */
	public $positions = array();

	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		// get positions
		if (isset($_POST['classListPositions']) && is_array($_POST['classListPositions'])) $this->positions = ArrayUtil::toIntegerArray($_POST['classListPositions']);
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();

		// check permissions
		WCF::getUser()->checkPermission('admin.contest.canEditClass');

		// update postions
		$pos = $parent = array();
		foreach($this->positions as $classID => $row) {
			foreach($row as $parentClassID => $position) {
				$parent[$classID] = $parentClassID;
				$pos[$classID] = $position;
			}
		}
		ContestClassEditor::updatePositions($pos);
		ContestClassEditor::updateParents($parent);

		$this->executed();

		// forward to list page
		HeaderUtil::redirect('index.php?page=ContestClassList&classID='.$this->classID.'&successfullSorting=1&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>
