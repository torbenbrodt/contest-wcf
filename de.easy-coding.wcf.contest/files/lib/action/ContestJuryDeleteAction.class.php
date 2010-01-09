<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/data/contest/jury/ContestJuryEditor.class.php');

/**
 * Deletes a contest entry jury.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestJuryDeleteAction extends AbstractSecureAction {
	/**
	 * jury id
	 *
	 * @var integer
	 */
	public $juryID = 0;
	
	/**
	 * jury editor object
	 *
	 * @var ContestJuryEditor
	 */
	public $jury = null;
	
	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['juryID'])) $this->juryID = intval($_REQUEST['juryID']);
		$this->jury = new ContestJuryEditor($this->juryID);
		if (!$this->jury->juryID) {
			throw new IllegalLinkException();
		}
		if (!$this->jury->isDeletable()) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		
		// delete jury
		$this->jury->delete();
		$this->executed();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestEntry&contestID='.$this->jury->contestID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>
