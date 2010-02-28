<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/solution/rating/ContestSolutionRating.class.php');
require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');
require_once(WCF_DIR.'lib/data/message/bbcode/MessageParser.class.php');

/**
 * Represents a viewable contest entry rating.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ViewableContestSolutionRating extends ContestSolutionRating {
	/**
	 * owner object
	 *
	 * @var ContestOwner
	 */
	protected $owner = null;
	
	/**
	 * @see DatabaseObject::handleData()
	 */
	protected function handleData($data) {
		parent::handleData($data);
		$this->owner = new ContestOwner($data, $this->userID, $groupID = 0);
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
	 * Gets the solutions rating result for template output.
	 *
	 * @return	string		solution rating result for template output
	 */
	public function getRatingOutput() {
		$score = $this->score;
		$roundedScore = $score === false ? 0 : round($score, 0);
		
		return '<img src="'.StyleManager::getStyle()->getIconPath('contestRating'.$roundedScore.'.png').'" alt="" />';
	}
	
	/**
	 * Gets the solutions rating result for template output.
	 *
	 * @return	string		solution rating result for template output
	 */
	public function getJuryRatingOutput() {
		$score = $this->juryscore;
		$roundedScore = $score === false ? 0 : round($score, 0);
		
		return '<img src="'.StyleManager::getStyle()->getIconPath('contestRating'.$roundedScore.'.png').'" alt="" />';
	}
	
	/**
	 * Gets the solutions rating result for template output.
	 *
	 * @return	string		solution rating result for template output
	 */
	public function getMyRatingOutput() {
		$score = $this->myscore;
		$roundedScore = $score === false ? 0 : round($score, 0);
		
		$identifier = $this->ratingID ? $this->ratingID : rand(-1, -999);
		$identifier = 'contestRating'.$identifier;
		
		return '<img id="'.$identifier.'" src="'.StyleManager::getStyle()->getIconPath('contestRating'.$roundedScore.'.png').'" alt="" />
		<script type="text/javascript" src="'.RELATIVE_WCF_DIR.'js/ContestRating.class.js"></script>
		<script type="text/javascript">
		//<![CDATA[
		new ContestRating(\''.$identifier.'\', '.$roundedScore.');
		//]]>
		</script>';
	}
}
?>
