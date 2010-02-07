<?php
// wcf imports
require_once(WCF_DIR.'lib/form/AbstractForm.class.php');
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');
require_once(WCF_DIR.'lib/data/contest/participant/ContestParticipantEditor.class.php');
require_once(WCF_DIR.'lib/util/ContestUtil.class.php');

/**
 * Shows the form for inviting contest participants.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestParticipantInviteForm extends AbstractForm {
	/**
	 *
	 * @var array<ContestParticipant>
	 */
	protected $participants = array();
	
	/**
	 * contest editor
	 *
	 * @var Contest
	 */
	public $contest = null;
	
	/**
	 * Creates a new ContestParticipantAddForm object.
	 *
	 * @param	Contest	$contest
	 */
	public function __construct(Contest $contest) {
		$this->contest = $contest;
		parent::__construct();
	}
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get contest
		if (!$this->contest->isOwner()) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['participant']) && is_array($_POST['participant'])) $this->participants = $_POST['participant'];
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		// save participant
		$inserts = '';
		foreach ($this->participants as $participant) {
			if (!empty($inserts)) $inserts .= ',';
			$inserts .= '	('.$this->contest->contestID.',
					'.($participant['type'] == 'user' ? intval($participant['id']) : 0).',
					'.($participant['type'] == 'group' ? intval($participant['id']) : 0).')';
		}
	
		if (!empty($inserts)) {
			$sql = "INSERT IGNORE INTO
						wcf".WCF_N."_contest_participant
						(contestID, userID, groupID)
				VALUES		".$inserts;
			WCF::getDB()->sendQuery($sql);
		}
		
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestParticipant&contestID='.$this->contest->contestID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'participants' => $this->participants,
		));
	}
}
?>
