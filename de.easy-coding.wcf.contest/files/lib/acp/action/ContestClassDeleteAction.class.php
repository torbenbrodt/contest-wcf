<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');
require_once(WCF_DIR.'lib/data/contest/class/ContestClassEditor.class.php');

/**
 * Rule item delete action.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestClassDeleteAction extends AbstractAction {
	/**
	 * class item id
	 *
	 * @var integer
	 */
	public $classID = 0;

	/**
	 * class item object
	 *
	 * @var ContestClassEditor
	 */
	public $contestClass = null;

	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		if (isset($_REQUEST['classID'])) $this->contestClassID = intval($_REQUEST['classID']);
		$this->contestClass = new ContestClassEditor($this->contestClassID);
		if (!$this->contestClass->classID) {
			throw new IllegalLinkException();
		}
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();

		// check permissions
		WCF::getUser()->checkPermission('admin.contest.canDeleteClass');

		// delete
		$this->contestClass->delete();
		
		$this->executed();

		// forward to list page
		header('Location: index.php?page=ContestClassList&deletedclassID='.$this->contestClassID.'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>
