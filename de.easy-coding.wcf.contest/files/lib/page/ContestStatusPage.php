<?php
// wcf imports
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/data/contest/ViewableContest.class.php');

/**
 * Status page for better debugging
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestStatusPage extends AbstractPage {
	
	/**
	 * entry id
	 *
	 * @var	integer
	 */
	public $contestID = 0;
	
	/**
	 * entry object
	 * 
	 * @var	Contest
	 */
	public $entry = null;
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		
		if (!MODULE_CONTEST) {
			echo "Das Contest Modul ist komplett deaktiviert, ".
				"aktiviere es im Admin Control Panel unter System > Optionen > Module an/aus<br />";
		}

		if(WCF::getUser()->getPermission('user.contest.canViewContest') == false) {
			echo "Deine Benutzergruppe darf keine Contests nutzen, überprüfe die Benutzerrechte unter ".
				"Benutzer > Benutzergruppen auflisten > bearbeiten > Allgemeine Rechte > Wettbewerb<br />";
		}
		
		// get entry
		if (isset($_REQUEST['contestID'])) $this->contestID = intval($_REQUEST['contestID']);
		$this->entry = new ViewableContest($this->contestID);
		if (!$this->entry->contestID) {
			echo "Der angegebene Contest existiert nicht, überprüfe den aufgerufenen Link<br />";
		}
		
		if($this->entry->state != 'scheduled') {
			echo "Damit der Contest für andere Benutzer sichtbar ist, musst du den Status auf 'geplant' ändern.<br />";
		}

		if($this->entry->fromTime > TIME_NOW) {
			echo "Damit der Contest für andere Benutzer sichtbar ist, muss die Startzeit erreicht werden.<br />";
		}
		
		if ($this->entry->isOwner()) {
			echo "Du bist selbst der Besitzer, daher kannst du nicht am Contest teilnehmen.<br />";
		}
		
		if ($this->entry->state == 'closed' || !($this->entry->state == 'scheduled' && $this->entry->untilTime > TIME_NOW)) {
			echo "Der Contest ist beendet, daher kannst du nicht am Contest teilnehmen.<br />";
		}
		
		if ($this->entry->state == 'closed' || !($this->entry->state == 'scheduled' && $this->entry->untilTime > TIME_NOW)) {
			echo "Der Contest ist beendet, daher kannst du nicht am Contest teilnehmen.<br />";
		}
		
		foreach($this->entry->getJurys() as $jury) {
			if($jury->isOwner()) {
				echo "Du bist in der Jury und kannst deswegen nicht am Contest teilnehmen.<br />";
			}
		}

		// alreay participant
		$isParticipant = false;
		foreach($this->entry->getParticipants() as $participant) {
			if($participant->isOwner()) {
				$isParticipant = true;
				echo "Du bist bereits Teilnehmer am am Contest.<br />";
			}
		}
		
		if (!$isParticipant && !$this->entry->isParticipantable()) {
			echo "Du kannst nicht am Contest teilnehmen!<br />";
		}
		
		if (!$this->entry->isViewable()) {
			echo "Du kannst den Contest nicht sehen!<br />";
		}
		
		parent::show();
	}
}
?>
