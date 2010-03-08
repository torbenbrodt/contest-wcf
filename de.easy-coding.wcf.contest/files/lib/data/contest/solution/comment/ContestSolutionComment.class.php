<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');

/**
 * Represents a contest entry comment.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSolutionComment extends DatabaseObject {
	/**
	 * Creates a new ContestSolutionComment object.
	 *
	 * @param	integer		$commentID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($commentID, $row = null) {
		if ($commentID !== null) {
			$sql = "SELECT	*
				FROM 	wcf".WCF_N."_contest_solution_comment
				WHERE 	commentID = ".$commentID;
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}
	
	/**
	 * Returns an editor object for this comment.
	 *
	 * @return	ContestSolutionCommentEditor
	 */
	public function getEditor() {
		require_once(WCF_DIR.'lib/data/contest/solution/comment/ContestSolutionCommentEditor.class.php');
		return new ContestSolutionCommentEditor(null, $this->data);
	}

	/**
	 * Returns true, if the active user is member
	 * 
	 * @return	boolean
	 */
	public function isOwner() {
		return ContestOwner::get($this->userID, $this->groupID)->isCurrentUser();
	}
	
	/**
	 * Returns true, if the active user can edit this entry.
	 * 
	 * @return	boolean
	 */
	public function isEditable() {
		return $this->isOwner();
	}
	
	/**
	 * Returns true, if the active user can delete this entry.
	 * 
	 * @return	boolean
	 */
	public function isDeletable() {
		return $this->isOwner();
	}
}
?>
