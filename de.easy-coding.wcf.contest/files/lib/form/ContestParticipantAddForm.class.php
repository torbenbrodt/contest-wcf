<?php
// wcf imports
require_once(WCF_DIR.'lib/form/AbstractForm.class.php');
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');
require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipantEditor.class.php');

/**
 * Shows the form for adding contest entry participants.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestParticipantAddForm extends AbstractForm {
	// form parameters
	public $ownerID = 0;
	public $userID = 0;
	public $groupID = 0;
	
	public $states = array();
	public $state = '';
	
	/**
	 * entry editor
	 *
	 * @var Contest
	 */
	public $entry = null;
	
	/**
	 * available groups
	 *
	 * @var array<Group>
	 */
	protected $availableGroups = array();
	
	/**
	 * Creates a new ContestParticipantAddForm object.
	 *
	 * @param	Contest	$entry
	 */
	public function __construct(Contest $entry) {
		$this->entry = $entry;
		parent::__construct();
	}
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get entry
		if (!$this->entry->isParticipantable()) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		// get parameters
		if (isset($_POST['ownerID'])) $this->ownerID = intval($_POST['ownerID']);
		if (isset($_POST['state'])) $this->state = $_POST['state'];
		
		if ($this->ownerID == 0) {
			$this->userID = WCF::getUser()->userID;
		} else {
			$this->groupID = $this->ownerID;
		}
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		$this->states = ContestParticipantEditor::getStates();

		// owner
		$this->readAvailableGroups();
	}
	
	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();
		
		if($this->ownerID != 0) {
			$this->readAvailableGroups();
		
			// validate group ids
			if(!array_key_exists($this->ownerID, $this->availableGroups)) {
				throw new UserInputException('ownerID'); 
			}
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		// save participant
		$participant = ContestParticipantEditor::create($this->entry->contestID, WCF::getUser()->userID, $this->groupID, $this->state);
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestParticipant&contestID='.$this->entry->contestID.'&participantID='.$participant->participantID.SID_ARG_2ND_NOT_ENCODED.'#participant'.$participant->participantID);
		exit;
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'states' => $this->states,
			'state' => $this->state,
			'availableGroups' => $this->availableGroups,
			'ownerID' => $this->ownerID,
		));
	}
	
	/**
	 * returns the groups for which the user is admin
	 */
	protected function readAvailableGroups() {
		$sql = "SELECT		usergroup.*, (
						SELECT	COUNT(*)
						FROM	wcf".WCF_N."_user_to_groups
						WHERE	groupID = usergroup.groupID
					) AS members
			FROM 		wcf".WCF_N."_group usergroup
			WHERE		groupID IN (
						SELECT	groupID
						FROM	wcf".WCF_N."_group_leader
						WHERE	leaderUserID = ".WCF::getUser()->userID."
							OR leaderGroupID IN (".implode(',', WCF::getUser()->getGroupIDs()).")
					)
			ORDER BY 	groupName";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->availableGroups[$row['groupID']] = new Group(null, $row);
		}
	}
}
?>
