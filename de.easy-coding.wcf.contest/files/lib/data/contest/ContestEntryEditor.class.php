<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/ContestEntry.class.php');
require_once(WCF_DIR.'lib/data/image/Thumbnail.class.php');

/**
 * Provides functions to manage contest entries.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestEntryEditor extends ContestEntry {
	/**
	 * Creates a new contest entry.
	 * 
	 * @param	integer				$userID
	 * @param	integer				$groupID
	 * @param	string				$subject
	 * @param	string				$message
	 * @param	array				$options
	 * @param	integer				$classIDArray
	 * @param	integer				$participants
	 * @param	integer				$jurys
	 * @param	integer				$prices
	 * @param	integer				$sponsors
	 * @param	MessageAttachmentListEditor	$attachmentList
	 * @return	ContestEntryEditor
	 */
	public static function create($userID, $groupID, $subject, $message, $options = array(), $classIDArray = array(), $participants = array(), $jurys = array(), $prices = array(), $sponsors = array(), $attachmentList = null) {
	
		// get number of attachments
		$attachmentsAmount = ($attachmentList !== null ? count($attachmentList->getAttachments()) : 0);
		
		// save entry
		$sql = "INSERT INTO	wcf".WCF_N."_contest
					(userID, groupID, subject, message, time, attachments, enableSmilies, enableHtml, enableBBCodes)
			VALUES		(".intval($userID).", ".intval($groupID).", '".escapeString($subject)."', '".escapeString($message)."', ".TIME_NOW.", ".$attachmentsAmount.",
					".(isset($options['enableSmilies']) ? $options['enableSmilies'] : 1).",
					".(isset($options['enableHtml']) ? $options['enableHtml'] : 0).",
					".(isset($options['enableBBCodes']) ? $options['enableBBCodes'] : 1).")";
		WCF::getDB()->sendQuery($sql);
		
		// get id
		$contestID = WCF::getDB()->getInsertID("wcf".WCF_N."_contest", 'contestID');
		
		// get new object
		$entry = new self($contestID);
		
		// update classes
		if (count($classIDArray) > 0) {
			$entry->setClasses($classIDArray);
		}
		
		// update participants
		if (count($participants) > 0) {
			$entry->setParticipants($participants);
		}
		
		// update jurys
		if (count($jurys) > 0) {
			$entry->setJurys($jurys);
		}
		
		// update prices
		if (count($sponsors) > 0 || count($prices) > 0) {
			if(count($prices)) {
				$sponsorID = $entry->setSponsors($sponsors, $userID, $groupID);
			} else {
				$entry->setSponsors($sponsors);
				$sponsorID = 0;
			}
		}
		
		// update prices
		if (count($prices) > 0) {
			$entry->setPrices($prices, $sponsorID);
		}
		
		// update attachments
		if ($attachmentList !== null) {
			$attachmentList->updateContainerID($contestID);
			$attachmentList->findEmbeddedAttachments($message);
		}
		
		// return object
		return $entry;
	}
	
	/**
	 * Creates a preview of a contest entry.
	 *
	 * @param 	string		$message
	 * @param 	boolean		$enableSmilies
	 * @param 	boolean		$enableHtml
	 * @param 	boolean		$enableBBCodes
	 * @return	string
	 */
	public static function createPreview($message, $enableSmilies = 1, $enableHtml = 0, $enableBBCodes = 1) {
		$row = array(
			'contestID' => 0,
			'message' => $message,
			'enableSmilies' => $enableSmilies,
			'enableHtml' => $enableHtml,
			'enableBBCodes' => $enableBBCodes,
			'messagePreview' => true
		);

		require_once(WCF_DIR.'lib/data/contest/ViewableContestEntry.class.php');
		$entry = new ViewableContestEntry(null, $row);
		return $entry->getFormattedMessage();
	}
	
	/**
	 * Updates this entry.
	 * 
	 * @param	integer				$userID
	 * @param	integer				$groupID
	 * @param	string				$subject
	 * @param	string				$message
	 * @param	array				$options 
	 * @param	integer				$classIDArray
	 * @param	integer				$participants
	 * @param	integer				$jurys
	 * @param	integer				$prices
	 * @param	integer				$sponsors
	 * @param	MessageAttachmentListEditor	$attachmentList 
	 */
	public function update($userID, $groupID, $subject, $message, $options = array(), $classIDArray = array(), $participants = array(), $jurys = array(), $prices = array(), $sponsors = array(), $attachmentList = null) {
		// get number of attachments
		$attachmentsAmount = ($attachmentList !== null ? count($attachmentList->getAttachments($this->contestID)) : 0);
		
		// update data
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	userID = '".intval($userID)."',
				groupID = '".intval($groupID)."',
				subject = '".escapeString($subject)."',
				message = '".escapeString($message)."',
				attachments = ".$attachmentsAmount.",
				enableSmilies = ".(isset($options['enableSmilies']) ? $options['enableSmilies'] : 1).",
				enableHtml = ".(isset($options['enableHtml']) ? $options['enableHtml'] : 0).",
				enableBBCodes = ".(isset($options['enableBBCodes']) ? $options['enableBBCodes'] : 1)."
			WHERE	contestID = ".$this->contestID;
		WCF::getDB()->sendQuery($sql);
		
		// update attachments
		if ($attachmentList != null) {
			$attachmentList->findEmbeddedAttachments($message);
		}
		
		// update classes
		$this->setClasses($classIDArray);
		
		// update participants
		$entry->setParticipants($participants);
		
		// update jurys
		$this->setJurys($jurys);
		
		// update sponsors
		if(count($prices)) {
			$sponsorID = $entry->setSponsors($sponsors, $userID, $groupID);
		} else {
			$entry->setSponsors($sponsors);
			$sponsorID = 0;
		}
		
		if($sponsorID) {
		
			// update prices
			$this->setPrices($prices, $sponsorID);
		}
	}
	
	/**
	 * Sets the classes of this entry.
	 * 
	 * @param	integer		$classIDArray 
	 */
	public function setClasses($classIDArray = array()) {
		$sql = "DELETE FROM	wcf".WCF_N."_contest_to_class
			WHERE		contestID = ".$this->contestID;
		WCF::getDB()->sendQuery($sql);
		
		if (count($classIDArray) > 0) {
			$sql = "INSERT INTO	wcf".WCF_N."_contest_to_class
						(contestID, classID)
				VALUES		(".$this->contestID.", ".implode("), (".$this->contestID.", ", $classIDArray).")";
			WCF::getDB()->sendQuery($sql);
		}
	}
	
	/**
	 * Saves sponsors.
	 */
	public function setSponsors($sponsors = array(), $userID = 0, $groupID = 0) {
		$sql = "DELETE FROM	wcf".WCF_N."_contest_sponsor
			WHERE		contestID = ".$this->contestID;
		WCF::getDB()->sendQuery($sql);
		
		$foundUserID = $foundGroupID = false;

		// existing?
		if($userID || $groupID)  {
			foreach ($sponsors as $sponsor) {
				$foundUserID = $userID > 0 && $sponsor['type'] == 'user' && $sponsor['id'] == $userID;
				$foundGroupID = $groupID > 0 && $sponsor['type'] == 'group' && $sponsor['id'] == $groupID;
			}
			
			if($userID && $foundUserID == false) {
				$sponsors[] = array(
					'type' => 'user',
					'id' => $userID
				);
			} else if($groupID && $foundGroupID == false) {
				$sponsors[] = array(
					'type' => 'group',
					'id' => $groupID
				);
			}
		}

		// create inserts
		foreach ($sponsors as $sponsor) {
			$sql = "INSERT INTO	wcf".WCF_N.'_contest_sponsor
						(contestID, userID, groupID)
				VALUES		('.$this->contestID.',
						'.($sponsor['type'] == 'user' ? intval($sponsor['id']) : 0).',
						'.($sponsor['type'] == 'group' ? intval($sponsor['id']) : 0).')';
			WCF::getDB()->sendQuery($sql);
		}
		
		$sponsorID = 0;
		if($userID || $groupID)  {
			$sponsorID = WCF::getDB()->getInsertID("wcf".WCF_N."_contest_sponsor", 'sponsorID');
		}
		return $sponsorID;
	}
	
	/**
	 * Saves prices.
	 */
	public function setPrices($prices = array(), $sponsorID = 0) {
		$sql = "DELETE FROM	wcf".WCF_N."_contest_price
			WHERE		contestID = ".$this->contestID;
		WCF::getDB()->sendQuery($sql);

		// create inserts
		$inserts = '';
		foreach ($prices as $price) {
			if (!empty($inserts)) $inserts .= ',';
			$inserts .= '	('.$this->contestID.', 
					'.intval($sponsorID).',
					"'.(isset($price['subject']) ? escapeString($price['subject']) : '').'",
					"'.(isset($price['message']) ? escapeString($price['message']) : '').'")';
		}
	
		if (!empty($inserts)) {
			$sql = "INSERT INTO	wcf".WCF_N."_contest_price
						(contestID, sponsorID, subject, message)
				VALUES		".$inserts;
			WCF::getDB()->sendQuery($sql);
		}
	}
	
	/**
	 * Saves participants.
	 */
	public function setParticipants($participants = array()) {
		$sql = "DELETE FROM	wcf".WCF_N."_contest_participant
			WHERE		contestID = ".$this->contestID;
		WCF::getDB()->sendQuery($sql);

		// create inserts
		$inserts = '';
		foreach ($participants as $participant) {
			if (!empty($inserts)) $inserts .= ',';
			$inserts .= '	('.$this->contestID.',
					'.($participant['type'] == 'user' ? intval($participant['id']) : 0).',
					'.($participant['type'] == 'group' ? intval($participant['id']) : 0).')';
		}
	
		if (!empty($inserts)) {
			$sql = "INSERT INTO	wcf".WCF_N."_contest_participant
						(contestID, userID, groupID)
				VALUES		".$inserts;
			WCF::getDB()->sendQuery($sql);
		}
	}
	
	/**
	 * Saves jurys.
	 */
	public function setJurys($jurys = array()) {
		$sql = "DELETE FROM	wcf".WCF_N."_contest_jury
			WHERE		contestID = ".$this->contestID;
		WCF::getDB()->sendQuery($sql);

		// create inserts
		$inserts = '';
		foreach ($jurys as $jury) {
			if (!empty($inserts)) $inserts .= ',';
			$inserts .= '	('.$this->contestID.',
					'.($jury['type'] == 'user' ? intval($jury['id']) : 0).',
					'.($jury['type'] == 'group' ? intval($jury['id']) : 0).')';
		}
	
		if (!empty($inserts)) {
			$sql = "INSERT INTO	wcf".WCF_N."_contest_jury
						(contestID, userID, groupID)
				VALUES		".$inserts;
			WCF::getDB()->sendQuery($sql);
		}
	}
	
	/**
	 * Deletes this entry.
	 */
	public function delete() {
		// delete solutions
		$sql = "DELETE FROM	wcf".WCF_N."_contest_solution
			WHERE		contestID = ".$this->contestID;
		WCF::getDB()->sendQuery($sql);
		
		// delete entry
		$sql = "DELETE FROM	wcf".WCF_N."_contest
			WHERE		contestID = ".$this->contestID;
		WCF::getDB()->sendQuery($sql);
		
		// delete entry to class
		$sql = "DELETE FROM	wcf".WCF_N."_contest_to_class
			WHERE		contestID = ".$this->contestID;
		WCF::getDB()->sendQuery($sql);
		
		// delete entry to participant
		$sql = "DELETE FROM	wcf".WCF_N."_contest_participant
			WHERE		contestID = ".$this->contestID;
		WCF::getDB()->sendQuery($sql);
		
		// delete entry to jury
		$sql = "DELETE FROM	wcf".WCF_N."_contest_jury
			WHERE		contestID = ".$this->contestID;
		WCF::getDB()->sendQuery($sql);
		
		// delete entry to price
		$sql = "DELETE FROM	wcf".WCF_N."_contest_price
			WHERE		contestID = ".$this->contestID;
		WCF::getDB()->sendQuery($sql);
		
		// delete entry to sponsor
		$sql = "DELETE FROM	wcf".WCF_N."_contest_sponsor
			WHERE		contestID = ".$this->contestID;
		WCF::getDB()->sendQuery($sql);
		
		// delete tags
		if (MODULE_TAGGING) {
			require_once(WCF_DIR.'lib/data/tag/TagEngine.class.php');
			$taggable = TagEngine::getInstance()->getTaggable('de.easy-coding.wcf.contest.entry');
			
			$sql = "DELETE FROM	wcf".WCF_N."_tag_to_object
				WHERE 		taggableID = ".$taggable->getTaggableID()."
						AND objectID = ".$this->contestID;
			WCF::getDB()->registerShutdownUpdate($sql);
		}
		
		// delete attachments
		if ($this->attachments > 0) {
			require_once(WCF_DIR.'lib/data/attachment/MessageAttachmentListEditor.class.php');
			$attachmentList = new MessageAttachmentListEditor($this->contestID, 'contestEntry');
			$attachmentList->deleteAll();
		}
	}
	
	/**
	 * Updates the tags of this entry.
	 * 
	 * @param	array<string>		$tagArray
	 */
	public function updateTags($tagArray) {
		// include files
		require_once(WCF_DIR.'lib/data/tag/TagEngine.class.php');
		require_once(WCF_DIR.'lib/data/contest/TaggedContestEntry.class.php');
		
		// save tags
		$tagged = new TaggedContestEntry(null, array(
			'contestID' => $this->contestID,
			'taggable' => TagEngine::getInstance()->getTaggable('de.easy-coding.wcf.contest.entry')
		));

		$languageID = 0;
		if (count(Language::getAvailableContentLanguages()) > 0) {
			$languageID = WCF::getLanguage()->getLanguageID();
		}
		
		// delete old tags
		TagEngine::getInstance()->deleteObjectTags($tagged, array($languageID));
		
		// save new tags
		if (count($tagArray) > 0) {
			TagEngine::getInstance()->addTags($tagArray, $tagged, $languageID);
		}
	}
	
	public static function getStates() {
		return array(
			'private',
			'waiting',
			'reviewed',
			'scheduled'
		);
	}
}
?>
