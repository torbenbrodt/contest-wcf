<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/data/contest/sponsor/ContestSponsorEditor.class.php');

/**
 * Deletes a contest entry sponsor.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSponsorDeleteAction extends AbstractSecureAction {
	/**
	 * sponsor id
	 *
	 * @var integer
	 */
	public $sponsorID = 0;
	
	/**
	 * sponsor editor object
	 *
	 * @var ContestSponsorEditor
	 */
	public $sponsor = null;
	
	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['sponsorID'])) $this->sponsorID = intval($_REQUEST['sponsorID']);
		$this->sponsor = new ContestSponsorEditor($this->sponsorID);
		if (!$this->sponsor->sponsorID) {
			throw new IllegalLinkException();
		}
		if (!$this->sponsor->isDeletable()) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		
		// delete sponsor
		$this->sponsor->delete();
		$this->executed();
		
		// forward
		HeaderUtil::redirect('index.php?page=Contest&contestID='.$this->sponsor->contestID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>
