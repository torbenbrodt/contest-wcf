<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/data/contest/solution/ContestSolutionEditor.class.php');

/**
 * Deletes a contest entry solution.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSolutionDeleteAction extends AbstractSecureAction {
	/**
	 * solution id
	 *
	 * @var integer
	 */
	public $solutionID = 0;
	
	/**
	 * solution editor object
	 *
	 * @var ContestSolutionEditor
	 */
	public $solution = null;
	
	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['solutionID'])) $this->solutionID = intval($_REQUEST['solutionID']);
		$this->solution = new ContestSolutionEditor($this->solutionID);
		if (!$this->solution->solutionID) {
			throw new IllegalLinkException();
		}
		if (!$this->solution->isDeletable()) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		
		// delete solution
		$this->solution->delete();
		$this->executed();
		
		// forward
		HeaderUtil::redirect('index.php?page=Contest&contestID='.$this->solution->contestID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>
