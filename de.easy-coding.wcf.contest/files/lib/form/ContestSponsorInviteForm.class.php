<?php
// wcf imports
require_once(WCF_DIR.'lib/form/AbstractContestForm.class.php');
require_once(WCF_DIR.'lib/data/contest/sponsor/ContestSponsorEditor.class.php');

/**
 * Shows the form for inviting contest sponsors.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSponsorInviteForm extends AbstractContestForm {
	
	/**
	 *
	 * @var array<ContestSponsor>
	 */
	protected $sponsors = array();
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();

		if (isset($_POST['sponsor']) && is_array($_POST['sponsor'])) $this->sponsors = $_POST['sponsor'];
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		// save sponsor
		$inserts = '';
		foreach ($this->sponsors as $sponsor) {
			$userID = $groupID = 0;
			switch($sponsor['type']) {
				case 'user':
					$userID = intval($sponsor['id']);
				break;
				case 'group':
					$groupID = intval($sponsor['id']);
				break;
			}
			ContestSponsorEditor::create($this->contest->contestID, $userID, $groupID, 'invited');
		}
		
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestSponsor&contestID='.$this->contest->contestID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'sponsors' => $this->sponsors,
		));
	}
}
?>
