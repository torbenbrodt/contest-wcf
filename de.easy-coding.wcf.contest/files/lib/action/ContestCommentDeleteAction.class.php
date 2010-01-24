<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/data/contest/comment/ContestCommentEditor.class.php');

/**
 * Deletes a contest entry comment.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Comments
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestCommentDeleteAction extends AbstractSecureAction {
	/**
	 * comment id
	 *
	 * @var integer
	 */
	public $commentID = 0;
	
	/**
	 * comment editor object
	 *
	 * @var ContestCommentEditor
	 */
	public $comment = null;
	
	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['commentID'])) $this->commentID = intval($_REQUEST['commentID']);
		$this->comment = new ContestCommentEditor($this->commentID);
		if (!$this->comment->commentID) {
			throw new IllegalLinkException();
		}
		if (!$this->comment->isDeletable()) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		
		// delete comment
		$this->comment->delete();
		$this->executed();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestComment&contestID='.$this->comment->contestID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>
