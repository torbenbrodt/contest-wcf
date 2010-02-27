<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/solution/ContestSolution.class.php');

/**
 * Provides functions to manage entry solutions.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSolutionEditor extends ContestSolution {

	/**
	 * Creates a new entry solution.
	 *
	 * @param	integer				$contestID
	 * @param	string				$message
	 * @param	integer				$userID
	 * @param	integer				$groupID
	 * @param	string				$state
	 * @param	integer				$time
	 * @param	array				$options
	 * @param	MessageAttachmentListEditor	$attachmentList
	 * @return	ContestSolutionEditor
	 */
	public static function create($contestID, $message, $userID, $groupID, $state = '', $time = TIME_NOW, $options = array(), $attachmentList = null) {
	
		// get number of attachments
		$attachmentsAmount = ($attachmentList !== null ? count($attachmentList->getAttachments()) : 0);
		
		$sql = "INSERT INTO	wcf".WCF_N."_contest_solution
					(contestID, userID, groupID, message, time,
					attachments, enableSmilies, enableHtml, enableBBCodes)
			VALUES		(".intval($contestID).", ".intval($userID).", ".intval($groupID).", '".escapeString($message)."', ".$time.",
					".$attachmentsAmount.", ".(isset($options['enableSmilies']) ? $options['enableSmilies'] : 1).",
					".(isset($options['enableHtml']) ? $options['enableHtml'] : 0).",
					".(isset($options['enableBBCodes']) ? $options['enableBBCodes'] : 0).")";
		WCF::getDB()->sendQuery($sql);
	
		// get number of attachments
		$attachmentsAmount = ($attachmentList !== null ? count($attachmentList->getAttachments()) : 0);
		
		// get id
		$solutionID = WCF::getDB()->getInsertID("wcf".WCF_N."_contest_solution", 'solutionID');
		
		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	solutions = solutions + 1
			WHERE	contestID = ".$contestID;
		WCF::getDB()->sendQuery($sql);
		
		// sent event
		require_once(WCF_DIR.'lib/data/contest/event/ContestEventEditor.class.php');
		require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
		$eventName = ContestEvent::getEventName(__METHOD__);
		ContestEventEditor::create($contestID, $userID, $groupID, $eventName, array(
			'solutionID' => $solutionID,
			'owner' => ContestOwner::get($userID, $groupID)->getName()
		));
		
		$entry = new ContestSolutionEditor($solutionID);
		
		// update attachments
		if ($attachmentList !== null) {
			$attachmentList->updateContainerID($solutionID);
			$attachmentList->findEmbeddedAttachments($message);
		}
		
		return $entry;
	}
	
	/**
	 * Creates the preview of a post with the given data.
	 * 
	 * @param	string		$subject
	 * @param	string		$text
	 * 
	 * @return	string		the preview of a solution 
	 */
	public static function createPreview($subject, $message, $enableSmilies = 1, $enableHtml = 0, $enableBBCodes = 1) {
		$row = array(
			'postID' => 0,
			'subject' => $subject,
			'message' => $message,
			'enableSmilies' => $enableSmilies,
			'enableHtml' => $enableHtml,
			'enableBBCodes' => $enableBBCodes,
			'messagePreview' => true
		);

		require_once(WCF_DIR.'lib/data/contest/solution/ViewableContestSolution.class.php');
		$solution = new ViewableContestSolution(null, $row);
		return $solution->getFormattedMessage();
	}
	
	/**
	 * Updates this entry solution.
	 *
	 * @param	integer				$userID
	 * @param	integer				$groupID
	 * @param	string				$message
	 * @param	string				$state
	 * @param	array				$options
	 * @param	MessageAttachmentListEditor	$attachmentList
	 */
	public function update($userID, $groupID, $message, $state, $options = array(), $attachmentList = null) {
		// get number of attachments
		$attachmentsAmount = ($attachmentList !== null ? count($attachmentList->getAttachments($this->solutionID)) : 0);
		
		$sql = "UPDATE	wcf".WCF_N."_contest_solution
			SET	userID = ".intval($userID).",
				groupID = ".intval($groupID).",
				message = '".escapeString($message)."',
				state = '".escapeString($state)."',
				attachments = ".$attachmentsAmount.",
				enableSmilies = ".(isset($options['enableSmilies']) ? $options['enableSmilies'] : 1).",
				enableHtml = ".(isset($options['enableHtml']) ? $options['enableHtml'] : 0).",
				enableBBCodes = ".(isset($options['enableBBCodes']) ? $options['enableBBCodes'] : 1)."
			WHERE	solutionID = ".$this->solutionID;
		WCF::getDB()->sendQuery($sql);
		
		// update attachments
		if ($attachmentList != null) {
			$attachmentList->findEmbeddedAttachments($message);
		}
	}
	
	/**
	 * Deletes this entry solution.
	 */
	public function delete() {
		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	solutions = solutions - 1
			WHERE	contestID = ".$this->contestID;
		WCF::getDB()->sendQuery($sql);
		
		// delete solution
		$sql = "DELETE FROM	wcf".WCF_N."_contest_solution
			WHERE		solutionID = ".$this->solutionID;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 *
	 */
	public static function getStates($current = '', $isUser = false) {
		switch($current) {
			case 'invited':
				if($isUser) {
					$arr = array(
						'accepted',
						'declined'
					);
				} else {
					$arr = array(
						$current
					);
				}
			break;
			case 'accepted':
			case 'declined':
			case 'applied':
				if($isUser) {
					$arr = array(
						$current
					);
				} else {
					$arr = array(
						'accepted',
						'declined'
					);
				}
			break;
			default:
				$arr = array(
					'private',
					'applied',
				);
			break;
		}
		return count($arr) ? array_combine($arr, $arr) : $arr;
	}
}
?>
