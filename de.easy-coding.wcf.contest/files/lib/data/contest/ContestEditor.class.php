<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');

/**
 * Provides functions to manage contest entries.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestEditor extends Contest {
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
	 * @return	ContestEditor
	 */
	public static function create($userID, $groupID, $subject, $message, $options = array(), $state = 'private', $classIDArray = array(), $participants = array(), 
	    $jurys = array(), $prices = array(), $sponsors = array(), $attachmentList = null) {

		// get number of attachments
		$attachmentsAmount = ($attachmentList !== null ? count($attachmentList->getAttachments()) : 0);

		// save entry
		$sql = "INSERT INTO	wcf".WCF_N."_contest
					(userID, groupID, subject, message, time, attachments, enableSmilies, enableHtml,
					enableBBCodes, enableSolution, enableOpenSolution, enableParticipantCheck,
					enablePricechoice, priceExpireSeconds, enableSponsorCheck)
			VALUES		(".intval($userID).", ".intval($groupID).", '".escapeString($subject)."', '".escapeString($message)."', 
					".TIME_NOW.", ".$attachmentsAmount.",
					".(isset($options['enableSmilies']) ? $options['enableSmilies'] : 1).",
					".(isset($options['enableHtml']) ? $options['enableHtml'] : 0).",
					".(isset($options['enableBBCodes']) ? $options['enableBBCodes'] : 0).",
					".(isset($options['enableSolution']) ? $options['enableSolution'] : 0).",
					".(isset($options['enableOpenSolution']) ? $options['enableOpenSolution'] : 0).",
					".(isset($options['enableParticipantCheck']) ? $options['enableParticipantCheck'] : 0).",
					".(isset($options['enablePricechoice']) ? $options['enablePricechoice'] : 0).",
					".(isset($options['priceExpireSeconds']) ? $options['priceExpireSeconds'] : 0).",
					".(isset($options['enableSponsorCheck']) ? $options['enableSponsorCheck'] : 0).")";
		WCF::getDB()->sendQuery($sql);

		// get id
		$contestID = WCF::getDB()->getInsertID("wcf".WCF_N."_contest", 'contestID');

		// sent event
		require_once(WCF_DIR.'lib/data/contest/event/ContestEventEditor.class.php');
		require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
		ContestEventEditor::create($contestID, $userID, $groupID, __CLASS__, array(
			'contestID' => $contestID,
			'state' => $state,
			'owner' => ContestOwner::get($userID, $groupID)->getName()
		));

		// get new object
		$entry = new self($contestID);

		// update classes
		if (count($classIDArray) > 0) {
			$entry->setClasses($classIDArray);
		}

		// update participants
		$entry->setParticipants($participants);

		// update jurys
		$entry->setJurys($jurys);

		// update prices
		$sponsorID = 0;
		if (count($sponsors) > 0 || count($prices) > 0) {
			if(count($prices)) {
				$sponsorID = $entry->setSponsors($sponsors, $userID, $groupID);
			} else {
				$entry->setSponsors($sponsors);
				$sponsorID = 0;
			}
		}

		// update prices
		$entry->setPrices($prices, $sponsorID);

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

		require_once(WCF_DIR.'lib/data/contest/ViewableContest.class.php');
		$entry = new ViewableContest(null, $row);
		return $entry->getFormattedMessage();
	}

	/**
	 * Updates this entry.
	 *
	 * @param	integer				$userID
	 * @param	integer				$groupID
	 * @param	string				$subject
	 * @param	string				$message
	 * @param	string				$fromTime
	 * @param	string				$untilTime
	 * @param	string				$state
	 * @param	array				$options
	 * @param	integer				$classIDArray
	 * @param	MessageAttachmentListEditor	$attachmentList
	 */
	public function update($userID, $groupID, $subject, $message, $fromTime, $untilTime, $state, $options = array(), $classIDArray = array(), $attachmentList = null) {
		// get number of attachments
		$attachmentsAmount = ($attachmentList !== null ? count($attachmentList->getAttachments($this->contestID)) : 0);

		// update data
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	userID = ".intval($userID).",
				groupID = ".intval($groupID).",
				subject = '".escapeString($subject)."',
				message = '".escapeString($message)."',
				fromTime = ".intval($fromTime).",
				untilTime = ".intval($untilTime).",
				attachments = ".$attachmentsAmount.",
				enableSmilies = ".(isset($options['enableSmilies']) ? $options['enableSmilies'] : 1).",
				enableHtml = ".(isset($options['enableHtml']) ? $options['enableHtml'] : 0).",
				enableBBCodes = ".(isset($options['enableBBCodes']) ? $options['enableBBCodes'] : 1).",
				enableSolution = ".(isset($options['enableSolution']) ? $options['enableSolution'] : 0).",
				enableOpenSolution = ".(isset($options['enableOpenSolution']) ? $options['enableOpenSolution'] : 0).",
				enableParticipantCheck = ".(isset($options['enableParticipantCheck']) ? $options['enableParticipantCheck'] : 0).",
				enablePricechoice = ".(isset($options['enablePricechoice']) ? $options['enablePricechoice'] : 0).",
				priceExpireSeconds = ".(isset($options['priceExpireSeconds']) ? $options['priceExpireSeconds'] : 0).",
				enableSponsorCheck = ".(isset($options['enableSponsorCheck']) ? $options['enableSponsorCheck'] : 0)."
			WHERE	contestID = ".intval($this->contestID);
		WCF::getDB()->sendQuery($sql);

		// update attachments
		if ($attachmentList != null) {
			$attachmentList->findEmbeddedAttachments($message);
		}

		// update state
		$this->updateState($state);

		// update classes
		$this->setClasses($classIDArray);
	}

	/**
	 * Sets the classes of this entry.
	 *
	 * @param	integer		$classIDArray
	 */
	public function setClasses($classIDArray = array()) {
		$existing = array_keys($this->getClasses());

		$remove = array_diff($existing, $classIDArray);
		if(count($remove)) {
			$sql = "DELETE FROM	wcf".WCF_N."_contest_to_class
				WHERE		contestID = ".intval($this->contestID)."
				AND		classID IN (".implode(",", $remove).")";
			WCF::getDB()->sendQuery($sql);
		}

		$add = array_diff($classIDArray, $existing);
		if(count($add) > 0) {
			$sql = "INSERT INTO	wcf".WCF_N."_contest_to_class
						(contestID, classID)
				VALUES		(".$this->contestID.", ".implode("), (".$this->contestID.", ", $add).")";
			WCF::getDB()->sendQuery($sql);
		}

		// trick to make sql query easier
		$classIDArray[] = -1;
		$remove[] = -1;
		$add[] = -1;

		// update class counters
		$sql = "UPDATE		wcf".WCF_N."_contest_class
			SET		contests = contests + IF(
						classID IN (".implode(",", $remove)."),
						-1,
						IF(classID IN (".implode(",", $add)."), 1, 0)
					)
			WHERE		classID IN (".implode(",", array_merge($remove, $add)).")";
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Saves sponsors.
	 */
	public function setSponsors($sponsors = array(), $userID = 0, $groupID = 0) {
		$sponsorID = 0;

		require_once(WCF_DIR.'lib/data/contest/sponsor/ContestSponsorEditor.class.php');
		foreach ($sponsors as $sponsor) {
			$sponsorObj = ContestSponsorEditor::create(
				$this->contestID,
				$sponsor['type'] == 'user' ? intval($sponsor['id']) : 0,
				$sponsor['type'] == 'group' ? intval($sponsor['id']) : 0,
				'invited'
			);

			// did i create my own user? then remember sponsorID
			if($sponsorID == 0 && ($userID || $groupID)) {

				if(($userID > 0 && $sponsor['type'] == 'user' && $sponsor['id'] == $userID)
				    || ($groupID > 0 && $sponsor['type'] == 'group' && $sponsor['id'] == $groupID)) {
					$sponsorID = $sponsorObj->sponsorID;
				}
			}
		}

		if($sponsorID == 0 && ($userID || $groupID)) {
			$sponsorObj = ContestSponsorEditor::create(
				$this->contestID,
				$userID,
				$groupID,
				'accepted'
			);
			$sponsorID = $sponsorObj->sponsorID;
		}
		return $sponsorID;
	}

	/**
	 * Saves prices.
	 */
	public function setPrices($prices = array(), $sponsorID = 0) {
		require_once(WCF_DIR.'lib/data/contest/price/ContestPriceEditor.class.php');
		foreach ($prices as $price) {
			ContestPriceEditor::create(
				$this->contestID,
				intval($sponsorID),
				isset($price['subject']) ? $price['subject'] : '',
				isset($price['message']) ? $price['message'] : '',
				isset($position) ? $position++ : $position = 1
			);
		}
	}

	/**
	 * Saves participants.
	 */
	public function setParticipants($participants = array()) {
		require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipantEditor.class.php');
		foreach ($participants as $participant) {
			ContestParticipantEditor::create(
				$this->contestID,
				$participant['type'] == 'user' ? intval($participant['id']) : 0,
				$participant['type'] == 'group' ? intval($participant['id']) : 0,
				'invited'
			);
		}
	}

	/**
	 * Saves jurys.
	 */
	public function setJurys($jurys = array()) {
		require_once(WCF_DIR.'lib/data/contest/jury/ContestJuryEditor.class.php');
		foreach ($jurys as $jury) {
			ContestJuryEditor::create(
				$this->contestID,
				$jury['type'] == 'user' ? intval($jury['id']) : 0,
				$jury['type'] == 'group' ? intval($jury['id']) : 0,
				'invited'
			);
		}
	}

	/**
	 * Deletes this entry.
	 */
	public function delete() {
		// delete solutions
		$sql = "DELETE FROM	wcf".WCF_N."_contest_solution
			WHERE		contestID = ".intval($this->contestID);
		WCF::getDB()->sendQuery($sql);

		// delete entry
		$sql = "DELETE FROM	wcf".WCF_N."_contest
			WHERE		contestID = ".intval($this->contestID);
		WCF::getDB()->sendQuery($sql);

		// delete entry to class and update class counters
		$this->setClasses(array());

		// delete entry to participant
		$sql = "DELETE FROM	wcf".WCF_N."_contest_participant
			WHERE		contestID = ".intval($this->contestID);
		WCF::getDB()->sendQuery($sql);

		// delete entry to jury
		$sql = "DELETE FROM	wcf".WCF_N."_contest_jury
			WHERE		contestID = ".intval($this->contestID);
		WCF::getDB()->sendQuery($sql);

		// delete entry to price
		$sql = "DELETE FROM	wcf".WCF_N."_contest_price
			WHERE		contestID = ".intval($this->contestID);
		WCF::getDB()->sendQuery($sql);

		// delete entry to sponsor
		$sql = "DELETE FROM	wcf".WCF_N."_contest_sponsor
			WHERE		contestID = ".intval($this->contestID);
		WCF::getDB()->sendQuery($sql);

		// delete events
		$sql = "DELETE FROM	wcf".WCF_N."_contest_event
			WHERE		contestID = ".intval($this->contestID);
		WCF::getDB()->sendQuery($sql);

		// delete tags
		if (MODULE_TAGGING) {
			require_once(WCF_DIR.'lib/data/tag/TagEngine.class.php');
			$taggable = TagEngine::getInstance()->getTaggable('de.easy-coding.wcf.contest.entry');

			$sql = "DELETE FROM	wcf".WCF_N."_tag_to_object
				WHERE 		taggableID = ".$taggable->getTaggableID()."
						AND objectID = ".intval($this->contestID);
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
		require_once(WCF_DIR.'lib/data/contest/TaggedContest.class.php');

		// save tags
		$tagged = new TaggedContest(null, array(
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

	/**
	 * 'private', 'applied', 'accepted', 'declined', 'scheduled', 'closed'
	 */
	public static function getStates($current = '', $flag = 0, $isClosable = false) {
		require_once(WCF_DIR.'lib/data/contest/state/ContestState.class.php');

		$arr = array($current);
		switch($current) {
			case 'private':
				if($flag & (ContestState::FLAG_USER | ContestState::FLAG_CREW)) {
					$arr[] = 'applied';
				}
			break;
			case 'accepted':
			case 'declined':
			case 'applied':
				if($flag & ContestState::FLAG_CREW) {
					$arr[] = 'accepted';
					$arr[] = 'declined';
					$arr[] = 'applied';
					$arr[] = 'scheduled';
				}
			case 'scheduled':
			case 'closed':
				if($flag & ContestState::FLAG_CREW) {
					$arr[] = 'scheduled';
					$arr[] = 'accepted';
				}
			break;
			default:
				$arr = array();
				$arr[] = 'private';
			break;
		}

		if($isClosable && !in_array('closed', $arr)) {
			$arr[] = 'closed';
		}

		$canSchedule = WCF::getUser()->getPermission('user.contest.canScheduleOwnContest');
		if($flag & ContestState::FLAG_USER && $canSchedule) {
			if(in_array($current, array('private', 'accepted', 'applied'))) {
				$arr[] = 'scheduled';
			}
		}

		return ContestState::translateArray($arr);
	}
	
	/**
	 * when winners are not allowed to take the prices on their own, this is done automatically
	 */
	public function updatePricechoices() {
		require_once(WCF_DIR.'lib/data/contest/solution/ContestSolution.class.php');

		// check if there are already solutions with prices
		$solutionIDs = array();
		foreach(ContestSolution::getWinners($this->contestID) as $solution) {
			if($solution->hasPrice()) {
				return;
			}
			$solutionIDs[] = $solution->solutionID;
		}
		
		require_once(WCF_DIR.'lib/data/contest/price/ContestPriceList.class.php');
		$priceList = new ContestPriceList();
		$priceList->sqlConditions .= 'contest_price.state = "accepted" AND contest_price.contestID = '.intval($this->contestID);
		$priceList->sqlLimit = count($solutionIDs);
		$priceList->readObjects();
		
		$i = 0;
		foreach($priceList->getObjects() as $price) {
			$price->getEditor()->pick($solutionIDs[$i], $i + 1);
			$i++;
		}
	}

	/**
	 * if priceExpireSeconds is set, the solution will have a maximum time to choose a price
	 * if no price is chosen in this period, the next winner can take a price
	 */
	public function updatePickTimes() {
		require_once(WCF_DIR.'lib/data/contest/solution/ContestSolution.class.php');

		// get first + latest pick
		$firstPick = $lastPick = 0;

		// update cache
		ContestSolution::resetWinners();
		foreach(ContestSolution::getWinners($this->contestID) as $solution) {
			if($solution->hasPrice()) {
				$lastPick = max($lastPick, $solution->pickTime);
			} else {
				$firstPick = min($firstPick, $solution->pickTime);
			}
		}
		$timestamp = 0;

		// first run? then start from the current date
		if($firstPick == 0) {
			$firstPick = TIME_NOW;
		}

		// no price was picked yet, so start from the beginning
		if($lastPick == 0) {
			$timestamp = $firstPick;
		}
		
		// there was a picked price, so use the last timestamp
		else {
			$timestamp = $lastPick;
		}

		foreach(ContestSolution::getWinners($this->contestID) as $solution) {
			if($solution->hasPrice()) {
				continue;
			}

			// no change, skip database update
			$save = $this->priceExpireSeconds == 0 ? 0 : $timestamp;
			
			// user will have xx hours from now on
			$timestamp += $this->priceExpireSeconds;

			if($solution->pickTime == $save) {
				continue;
			}

			// database update
			$solution->getEditor()->updatePickTime($save);
		}

		// TODO: send event to the next winner
		if(false) {
			require_once(WCF_DIR.'lib/data/contest/event/ContestEventEditor.class.php');
			$owner = $this->getOwner();
			ContestEventEditor::create($contestID, $owner->userID, $owner->groupID, 'ContestPriceExpire', array(
				'priceID' => $priceID,
				'owner' => $owner->getName()
			));
		}
	}

	/**
	 * updates state
	 *
	 * @param	$state		string
	 */
	public function updateState($state) {
		// update data
		$sql = "UPDATE	wcf".WCF_N."_contest
			SET	state = '".escapeString($state)."'
			WHERE	contestID = ".intval($this->contestID);
		WCF::getDB()->sendQuery($sql);

		// if state is changed to closed, then update timestamps, when winners have to pick prices
		if($state == 'closed') {
			$this->updatePickTimes();
			
			// winners cannot choose prices on their own, so give prices now
			if(!$this->enablePricechoice) {
				$this->updatePricechoices();
			}
		}

		// TODO: send event to participants that contest is finished
		if(false) {
			require_once(WCF_DIR.'lib/data/contest/event/ContestEventEditor.class.php');
			$owner = $this->getOwner();
			ContestEventEditor::create($contestID, $owner->userID, $owner->groupID, 'ContestState', array(
				'priceID' => $priceID,
				'owner' => $owner->getName()
			));
		}
	}
}
?>
