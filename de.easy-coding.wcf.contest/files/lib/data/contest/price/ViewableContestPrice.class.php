<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/price/ContestPrice.class.php');
require_once(WCF_DIR.'lib/data/contest/sponsor/ContestSponsor.class.php');
require_once(WCF_DIR.'lib/data/message/bbcode/MessageParser.class.php');

/**
 * Represents a viewable contest entry price.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
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
	 * Creates a new ViewableContest object.
	 *
	 * @param	integer		$priceID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($priceID, $row = null) {
		if ($priceID !== null) {
			$sql = "SELECT		user_table.username, 
						contest_sponsor.userID, 
						contest_sponsor.groupID, 
						group_table.groupName,
						avatar_table.*, 
						contest_price.*
				FROM 		wcf".WCF_N."_contest_price contest_price
				LEFT JOIN	wcf".WCF_N."_contest_sponsor contest_sponsor
				ON		(contest_sponsor.sponsorID = contest_price.sponsorID)
				LEFT JOIN	wcf".WCF_N."_user user_table
				ON		(user_table.userID = contest_sponsor.userID)
				LEFT JOIN	wcf".WCF_N."_avatar avatar_table
				ON		(avatar_table.avatarID = user_table.avatarID)
				LEFT JOIN	wcf".WCF_N."_group group_table
				ON		(group_table.groupID = contest_sponsor.groupID)
				WHERE 		contest_price.priceID = ".intval($priceID);
			$row = WCF::getDB()->getFirstRow($sql);
		}
		DatabaseObject::__construct($row);
	}
	
	/**
	 * Returns the title of this price.
	 * 
	 * @return	string
	 */
	public function __toString() {
		return $this->subject;
	}
	
	/**
	 * @see DatabaseObject::handleData()
	 */
	protected function handleData($data) {
		parent::handleData($data);
		$this->owner = new ContestOwner($data, $this->userID, $this->groupID);
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
}
?>
