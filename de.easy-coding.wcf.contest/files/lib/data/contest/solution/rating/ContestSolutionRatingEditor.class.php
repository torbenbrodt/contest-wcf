<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/solution/rating/ContestSolutionRating.class.php');

/**
 * Provides functions to manage entry ratings.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSolutionRatingEditor extends ContestSolutionRating {
	/**
	 * Creates a new entry rating.
	 *
	 * @param	integer		$solutionID
	 * @param	string		$rating
	 * @param	integer		$juryID
	 * @param	string		$username
	 * @param	integer		$time
	 * @return	ContestSolutionRatingEditor
	 */
	public static function create($solutionID, $rating, $juryID, $username, $time = TIME_NOW) {
		$sql = "INSERT INTO	wcf".WCF_N."_contest_solution_rating
					(solutionID, juryID, username, rating, time)
			VALUES		(".intval($solutionID).", ".intval($juryID).", '".escapeString($username)."', '".escapeString($rating)."', ".$time.")";
		WCF::getDB()->sendQuery($sql);
		
		// get id
		$ratingID = WCF::getDB()->getInsertID("wcf".WCF_N."_contest_solution_rating", 'ratingID');
		
		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest_solution
			SET	ratings = ratings + 1
			WHERE	solutionID = ".$solutionID;
		WCF::getDB()->sendQuery($sql);
		
		// sent event
		require_once(WCF_DIR.'lib/data/contest/event/ContestEventEditor.class.php');
		require_once(WCF_DIR.'lib/data/contest/owner/ContestOwner.class.php');
		$eventName = ContestEvent::getEventName(__METHOD__);
		ContestEventEditor::create($solutionID, $juryID, $groupID = 0, $eventName, array(
			'ratingID' => $ratingID,
			'owner' => ContestOwner::get($juryID, $groupID)->getName()
		));
		
		return new ContestSolutionRatingEditor($ratingID);
	}
	
	/**
	 * Updates this entry rating.
	 *
	 * @param	string		$rating
	 */
	public function update($rating) {
		$sql = "UPDATE	wcf".WCF_N."_contest_solution_rating
			SET	rating = '".escapeString($rating)."'
			WHERE	ratingID = ".$this->ratingID;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Deletes this entry rating.
	 */
	public function delete() {
		// update entry
		$sql = "UPDATE	wcf".WCF_N."_contest_solution
			SET	ratings = ratings - 1
			WHERE	solutionID = ".$this->solutionID;
		WCF::getDB()->sendQuery($sql);
		
		// delete rating
		$sql = "DELETE FROM	wcf".WCF_N."_contest_solution_rating
			WHERE		ratingID = ".$this->ratingID;
		WCF::getDB()->sendQuery($sql);
	}
}
?>
