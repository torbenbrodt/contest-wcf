<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestEntryEditor.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestEntryList.class.php');

/**
 * Deletes a contest entry.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestDeleteAction extends AbstractSecureAction {
	/**
	 * entry id
	 *
	 * @var integer
	 */
	public $contestID = 0;
	
	/**
	 * entry editor object
	 *
	 * @var ContestEntryEditor
	 */
	public $entry = null;
	
	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['contestID'])) $this->contestID = intval($_REQUEST['contestID']);
		$this->entry = new ContestEntryEditor($this->contestID);
		if (!$this->entry->contestID) {
			throw new IllegalLinkException();
		}
		if (!$this->entry->isDeletable()) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		
		// delete entry
		$this->entry->delete();
		$this->executed();
		
		// forward
		$entryList = new ContestEntryList();
		$entryList->sqlConditions .= 'contest.userID = '.$this->entry->userID;
		if (empty($_REQUEST['ajax'])) {
			if ($entryList->countObjects() > 0 || WCF::getUser()->userID == $this->entry->userID) {
				HeaderUtil::redirect('index.php?page=Contest&userID='.$this->entry->userID.SID_ARG_2ND_NOT_ENCODED);
			}
			else {
				HeaderUtil::redirect('index.php?page=User&userID='.$this->entry->userID.SID_ARG_2ND_NOT_ENCODED);
			}
		}
		exit;
	}
}
?>
