<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/data/contest/jurytalk/ContestJurytalkEditor.class.php');

/**
 * Deletes a contest entry jurytalk.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestJurytalkDeleteAction extends AbstractSecureAction {
	/**
	 * jurytalk id
	 *
	 * @var integer
	 */
	public $jurytalkID = 0;
	
	/**
	 * jurytalk editor object
	 *
	 * @var ContestJurytalkEditor
	 */
	public $jurytalk = null;
	
	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['jurytalkID'])) $this->jurytalkID = intval($_REQUEST['jurytalkID']);
		$this->jurytalk = new ContestJurytalkEditor($this->jurytalkID);
		if (!$this->jurytalk->jurytalkID) {
			throw new IllegalLinkException();
		}
		if (!$this->jurytalk->isDeletable()) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		
		// delete jurytalk
		$this->jurytalk->delete();
		$this->executed();
		
		// forward
		HeaderUtil::redirect('index.php?page=Contest&contestID='.$this->jurytalk->contestID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>
