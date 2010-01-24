<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/data/contest/sponsortalk/ContestSponsortalkEditor.class.php');

/**
 * Deletes a contest entry sponsortalk.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSponsortalkDeleteAction extends AbstractSecureAction {
	/**
	 * sponsortalk id
	 *
	 * @var integer
	 */
	public $sponsortalkID = 0;
	
	/**
	 * sponsortalk editor object
	 *
	 * @var ContestSponsortalkEditor
	 */
	public $sponsortalk = null;
	
	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['sponsortalkID'])) $this->sponsortalkID = intval($_REQUEST['sponsortalkID']);
		$this->sponsortalk = new ContestSponsortalkEditor($this->sponsortalkID);
		if (!$this->sponsortalk->sponsortalkID) {
			throw new IllegalLinkException();
		}
		if (!$this->sponsortalk->isDeletable()) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		
		// delete sponsortalk
		$this->sponsortalk->delete();
		$this->executed();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestSponsortalk&contestID='.$this->sponsortalk->contestID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>
