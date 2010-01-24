<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/comment/ContestComment.class.php');

/**
 * Provides functions to manage entry comments.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Comments
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestCommentEditor extends ContestComment {
	/**
	 * Creates a new entry comment.
	 *
	 * @param	integer		$contestID
	 * @param	string		$comment
	 * @param	integer		$userID
	 * @param	string		$username
	 * @param	integer		$time
	 * @return	ContestCommentEditor
	 */
	public static function create($contestID, $comment, $userID, $username, $time = TIME_NOW) {
		$sql = "INSERT INTO	wcf".WCF_N."_contest_comment
					(contestID, userID, username, comment, time)
			VALUES		(".$contestID.", ".$userID.", '".escapeString($username)."', '".escapeString($comment)."', ".$time.")";
		WCF::getDB()->sendQuery($sql);
		
		// get id
		$commentID = WCF::getDB()->getInsertID("wcf".WCF_N."_contest_comment", 'commentID');
		
		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	comments = comments + 1
			WHERE	contestID = ".$contestID;
		WCF::getDB()->sendQuery($sql);
		
		return new ContestCommentEditor($commentID);
	}
	
	/**
	 * Updates this entry comment.
	 *
	 * @param	string		$comment
	 */
	public function update($comment) {
		$sql = "UPDATE	wcf".WCF_N."_contest_comment
			SET	comment = '".escapeString($comment)."'
			WHERE	commentID = ".$this->commentID;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Deletes this entry comment.
	 */
	public function delete() {
		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	comments = comments - 1
			WHERE	contestID = ".$this->contestID;
		WCF::getDB()->sendQuery($sql);
		
		// delete comment
		$sql = "DELETE FROM	wcf".WCF_N."_contest_comment
			WHERE		commentID = ".$this->commentID;
		WCF::getDB()->sendQuery($sql);
	}
}
?>
