<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a contest entry comment.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Comments
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestEntryComment extends DatabaseObject {
	/**
	 * Creates a new ContestEntryComment object.
	 *
	 * @param	integer		$commentID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($commentID, $row = null) {
		if ($commentID !== null) {
			$sql = "SELECT	*
				FROM 	wcf".WCF_N."_contest_comment
				WHERE 	commentID = ".$commentID;
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}
	
	/**
	 * Returns true, if the active user can edit this comment.
	 * 
	 * @return	boolean
	 */
	public function isEditable() {
		if (($this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canEditComment')) || ($this->userID && $this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canEditOwnComment')) || WCF::getUser()->getPermission('mod.contest.canEditComment')) {
			return true;
		}
		return false;
	}
	
	/**
	 * Returns true, if the active user can delete this comment.
	 * 
	 * @return	boolean
	 */
	public function isDeletable() {
		if (($this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canDeleteComment')) || ($this->userID && $this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.contest.canDeleteOwnComment')) || WCF::getUser()->getPermission('mod.contest.canDeleteComment')) {
			return true;
		}
		return false;
	}
	
	/**
	 * Returns an editor object for this comment.
	 *
	 * @return	ContestEntryCommentEditor
	 */
	public function getEditor() {
		require_once(WCF_DIR.'lib/data/contest/comment/ContestEntryCommentEditor.class.php');
		return new ContestEntryCommentEditor(null, $this->data);
	}
}
?>
