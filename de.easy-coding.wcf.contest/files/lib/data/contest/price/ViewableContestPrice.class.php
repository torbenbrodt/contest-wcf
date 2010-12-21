<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/price/ContestPrice.class.php');
require_once(WCF_DIR.'lib/data/contest/sponsor/ContestSponsor.class.php');
require_once(WCF_DIR.'lib/data/message/bbcode/MessageParser.class.php');

/**
 * Represents a viewable contest entry price.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ViewableContestPrice extends ContestPrice {
	/**
	 * owner object
	 *
	 * @var ContestOwner
	 */
	protected $owner = null;

	/**
	 * winner object
	 *
	 * @var ContestOwner
	 */
	protected $winner = null;

	/**
	 * Creates a new ViewableContest object.
	 *
	 * @param	integer		$priceID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($priceID, $row = null) {
		if ($priceID !== null) {
			$sql = "SELECT		
						avatar_table.*, 
						contest_price.*,
						user_table.username, 
						contest_sponsor.userID, 
						contest_sponsor.groupID, 
						group_table.groupName,
						user_table_winner.userID AS winner_userID,
						user_table_winner.username AS winner_username,
						group_table_winner.groupID AS winner_groupID,
						group_table_winner.groupName AS winner_groupName,
						avatar_table_winner.avatarID AS winner_avatarID,
						avatar_table_winner.avatarCategoryID AS winner_avatarCategoryID,
						avatar_table_winner.avatarName AS winner_avatarName,
						avatar_table_winner.avatarExtension AS winner_avatarExtension,
						avatar_table_winner.width AS winner_width,
						avatar_table_winner.height  AS winner_height
				FROM 		wcf".WCF_N."_contest_price contest_price
				LEFT JOIN	wcf".WCF_N."_contest_sponsor contest_sponsor
				ON		(contest_sponsor.sponsorID = contest_price.sponsorID)
				LEFT JOIN	wcf".WCF_N."_user user_table
				ON		(user_table.userID = contest_sponsor.userID)
				LEFT JOIN	wcf".WCF_N."_avatar avatar_table
				ON		(avatar_table.avatarID = user_table.avatarID)
				LEFT JOIN	wcf".WCF_N."_group group_table
				ON		(group_table.groupID = contest_sponsor.groupID)
				
				LEFT JOIN	wcf".WCF_N."_contest_solution contest_solution
				ON		(contest_solution.solutionID = contest_price.solutionID)
				LEFT JOIN	wcf".WCF_N."_contest_participant contest_participant
				ON		(contest_participant.participantID = contest_solution.participantID)
				LEFT JOIN	wcf".WCF_N."_user user_table_winner
				ON		(user_table_winner.userID = contest_participant.userID)
				LEFT JOIN	wcf".WCF_N."_avatar avatar_table_winner
				ON		(avatar_table_winner.avatarID = user_table_winner.avatarID)
				LEFT JOIN	wcf".WCF_N."_group group_table_winner
				ON		(group_table_winner.groupID = contest_participant.groupID)
				
				WHERE 		contest_price.priceID = ".intval($priceID)."
				AND		(".ContestPrice::getStateConditions().")";
			$row = WCF::getDB()->getFirstRow($sql);
		}
		DatabaseObject::__construct($row);
	}
	
	/**
	 * @see DatabaseObject::handleData()
	 */
	protected function handleData($data) {
		
		$winnerData = $ownerData = array();
		foreach($data as $key => $val) {
			if(strpos($key, 'winner_') === 0) {
				$winnerData[substr($key, strlen('winner_'))] = $val;
			} else {
				$ownerData[$key] = $val;
			}
		}
	
		$this->winner = new ContestOwner($winnerData, $winnerData['userID'], $winnerData['groupID']);
		$this->owner = new ContestOwner($ownerData, $ownerData['userID'], $ownerData['groupID']);

		parent::handleData($data);
	}
	
	/**
	 * Returns the title of this price.
	 * 
	 * @return	string
	 */
	public function __toString() {
		return "".$this->subject;
	}
	
	/**
	 * Returns the formatted price.
	 * 
	 * @return	string
	 */
	public function getFormattedMessage() {
		$enableSmilies = 1; 
		$enableHtml = 0; 
		$enableBBCodes = 1;
	
		MessageParser::getInstance()->setOutputType('text/html');
		return MessageParser::getInstance()->parse($this->message, $enableSmilies, $enableHtml, $enableBBCodes);
	}
	
	/**
	 * Returns the owner object.
	 * 
	 * @return	ContestOwner
	 */
	public function getOwner() {
		return $this->owner;
	}
	
	/**
	 * Returns the winner/participant object.
	 * 
	 * @return	ContestOwner|null
	 */
	public function getWinner() {
		return $this->winner;
	}
	
	/**
	 * Returns a state object.
	 * 
	 * @return	ContestState
	 */
	public function getState() {
		require_once(WCF_DIR.'lib/data/contest/state/ContestState.class.php');
		return ContestState::get($this->state);
	}
}
?>
