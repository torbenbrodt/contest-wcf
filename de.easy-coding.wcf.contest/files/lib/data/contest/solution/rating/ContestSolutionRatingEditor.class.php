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
	 * @param	integer		$optionID
	 * @param	integer		$score
	 * @param	integer		$userID
	 * @param	integer		$time
	 * @return	ContestSolutionRatingEditor
	 */
	public static function create($solutionID, $optionID, $score, $userID, $time = TIME_NOW) {
		$sql = "INSERT INTO	wcf".WCF_N."_contest_solution_rating
					(solutionID, userID, optionID, score, time)
			VALUES		(".intval($solutionID).", ".intval($userID).", ".intval($optionID).", ".intval($score).", ".$time.")";
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
		ContestEventEditor::create($solutionID, $userID, $groupID = 0, __CLASS__, array(
			'ratingID' => $ratingID,
			'owner' => ContestOwner::get($userID, $groupID)->getName()
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
			SET	rating = ".intval($rating)."
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
	
	/**
	 * updates scores
	 * @param	array		$data
	 */
	public static function updateRatings($solutionID, $userID, $data) {
		foreach($data as $optionID => $score) {
			$sql = "INSERT INTO	wcf".WCF_N."_contest_solution_rating
						(solutionID, userID, optionID, score, time)
				VALUES		(".intval($solutionID).", ".intval($userID).", ".intval($optionID).", ".intval($score).", ".TIME_NOW.")
				ON DUPLICATE KEY UPDATE
						score = ".intval($score).",
						time = ".TIME_NOW;
			WCF::getDB()->sendQuery($sql);
		}
	}
}
?>
