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
	const FLAG_OPENSOLUTION = 256;

	/**
	 * Creates a new entry solution.
	 *
	 * @param	integer				$contestID
	 * @param	integer				$participantID
	 * @param	string				$message
	 * @param	string				$state
	 * @param	array				$options
	 * @param	MessageAttachmentListEditor	$attachmentList
	 * @return	ContestSolutionEditor
	 */
	public static function create($contestID, $participantID, $message, $state = 'private', $options = array(), $attachmentList = null) {

		// get number of attachments
		$attachmentsAmount = ($attachmentList !== null ? count($attachmentList->getAttachments()) : 0);

		$sql = "INSERT INTO	wcf".WCF_N."_contest_solution
					(contestID, participantID, message, time, 
					state, attachments, enableSmilies, enableHtml, enableBBCodes)
			VALUES		(".intval($contestID).", ".intval($participantID).", '".escapeString($message)."', ".TIME_NOW.", 
					'".escapeString($state)."', ".$attachmentsAmount.",
					".(isset($options['enableSmilies']) ? $options['enableSmilies'] : 1).",
					".(isset($options['enableHtml']) ? $options['enableHtml'] : 0).",
					".(isset($options['enableBBCodes']) ? $options['enableBBCodes'] : 0).")";
		WCF::getDB()->sendQuery($sql);

		// get id
		$solutionID = WCF::getDB()->getInsertID("wcf".WCF_N."_contest_solution", 'solutionID');

		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	solutions = solutions + 1
			WHERE	contestID = ".intval($contestID);
		WCF::getDB()->sendQuery($sql);

		// sent event
		require_once(WCF_DIR.'lib/data/contest/event/ContestEventEditor.class.php');
		require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
		require_once(WCF_DIR.'lib/data/contest/participant/ViewableContestParticipant.class.php');
		$participant = new ViewableContestParticipant($participantID);
		ContestEventEditor::create($contestID, $participant->userID, $participant->groupID, __CLASS__, array(
			'solutionID' => $solutionID,
			'owner' => $participant->getOwner()->getName()
		));

		// update attachments
		if ($attachmentList !== null) {
			$attachmentList->updateContainerID($solutionID);
			$attachmentList->findEmbeddedAttachments($message);
		}

		return new ContestSolutionEditor($solutionID);
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
	 * @param	string				$message
	 * @param	string				$state
	 * @param	array				$options
	 * @param	MessageAttachmentListEditor	$attachmentList
	 */
	public function update($message, $state, $options = array(), $attachmentList = null) {
		// get number of attachments
		$attachmentsAmount = ($attachmentList !== null ? count($attachmentList->getAttachments($this->solutionID)) : 0);

		$sql = "UPDATE	wcf".WCF_N."_contest_solution
			SET	message = '".escapeString($message)."',
				state = '".escapeString($state)."',
				attachments = ".$attachmentsAmount.",
				enableSmilies = ".(isset($options['enableSmilies']) ? $options['enableSmilies'] : 1).",
				enableHtml = ".(isset($options['enableHtml']) ? $options['enableHtml'] : 0).",
				enableBBCodes = ".(isset($options['enableBBCodes']) ? $options['enableBBCodes'] : 1)."
			WHERE	solutionID = ".intval($this->solutionID);
		WCF::getDB()->sendQuery($sql);

		// update attachments
		if ($attachmentList != null) {
			$attachmentList->findEmbeddedAttachments($message);
		}
	}

	/**
	 * Updates this entry solution.
	 *
	 * @param	integer				$timestamp
	 */
	public function updatePickTime($timestamp) {
		$sql = "UPDATE	wcf".WCF_N."_contest_solution
			SET	pickTime = ".intval($timestamp)."
			WHERE	solutionID = ".intval($this->solutionID);
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Sends notification
	 */
	public function sendPickNotification() {
		// use notification api
		if(false) {
			require_once(WCF_DIR.'lib/data/contest/event/ContestEventEditor.class.php');
			$owner = $this->getOwner();
			ContestEventEditor::create($contestID, $owner->userID, $owner->groupID, 'ContestPriceExpire', array(
				'priceID' => $priceID,
				'owner' => $owner->getName()
			));
		}

		// use mail if participant is single user
		// TODO: remove after notification api is implemented
		// TODO: missing translation
		if($this->getOwner()->userID) {
			require_once(WCF_DIR.'lib/data/mail/Mail.class.php');
			$mail = new Mail(
				$this->getOwner()->email,
				'easy-coding Gewinnspiel - du hast gewonnen',
	'Hallo '.$this->getOwner()->getName().',
du gehörst zu den glücklichen Gewinnern beim easy-coding Gewinnspiel.
Bitte suche dir innerhalb von 24h auf folgender Seite einen Preis aus: '.PAGE_URL.'/index.php?page=ContestPrice&contestID='.$this->contestID.'

Vielen Dank für die Teilnahme beim Gewinnspiel,

Torben Brodt');
			$mail->addBCC(MAIL_ADMIN_ADDRESS);
			$mail->send();
		}
	}

	/**
	 * Deletes this entry solution.
	 */
	public function delete() {
		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	solutions = solutions - 1
			WHERE	contestID = ".intval($this->contestID);
		WCF::getDB()->sendQuery($sql);

		// delete solution
		$sql = "DELETE FROM	wcf".WCF_N."_contest_solution
			WHERE		solutionID = ".intval($this->solutionID);
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * 'private', 'applied', 'accepted', 'declined'
	 */
	public static function getStates($current = '', $flag = 0) {
		require_once(WCF_DIR.'lib/data/contest/state/ContestState.class.php');

		$arr = array($current);
		switch($current) {
			case 'private':
				if($flag & (ContestState::FLAG_USER | ContestState::FLAG_CREW)) {
					$arr[] = $flag & self::FLAG_OPENSOLUTION ? 'accepted' : 'applied';
				}
			break;
			case 'accepted':
			case 'declined':
			case 'applied':
				if($flag & (ContestState::FLAG_CONTESTOWNER | ContestState::FLAG_CREW)) {
					$arr[] = 'accepted';
					$arr[] = 'declined';
				}
			break;
			default:
				$arr = array(); // reset array
				$arr[] = $flag & self::FLAG_OPENSOLUTION ? 'accepted' : 'applied';
				$arr[] = 'private';
			break;
		}
		return ContestState::translateArray($arr);
	}
}
?>
