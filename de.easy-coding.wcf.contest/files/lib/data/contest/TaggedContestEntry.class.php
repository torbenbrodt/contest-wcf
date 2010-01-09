<?php
// wcf imports
require_once(WCF_DIR.'lib/data/tag/Tagged.class.php');
require_once(WCF_DIR.'lib/data/user/User.class.php');
require_once(WCF_DIR.'lib/data/contest/ContestEntry.class.php');

/**
 * An implementation of Tagged to support the tagging of contest entries.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class TaggedContestEntry extends ContestEntry implements Tagged {
	/**
	 * user object
	 * 
	 * @var	User
	 */
	protected $user = null;

	/**
	 * @see ViewableThread::handleData()
	 */
	protected function handleData($data) {
		parent::handleData($data);
		
		// get user
		$this->user = new User(null, array('userID' => $this->userID, 'username' => $this->username));
	}

	/**
	 * @see Tagged::getTitle()
	 */
	public function getTitle() {
		return $this->subject;
	}

	/**
	 * @see Tagged::getObjectID()
	 */
	public function getObjectID() {
		return $this->contestID;
	}

	/**
	 * @see Tagged::getTaggable()
	 */
	public function getTaggable() {
		return $this->taggable;
	}
	
	/**
	 * @see Tagged::getDescription()
	 */
	public function getDescription() {
		return $this->getExcerpt();
	}

	/**
	 * @see Tagged::getSmallSymbol()
	 */
	public function getSmallSymbol() {
		return StyleManager::getStyle()->getIconPath('contestS.png');
	}

	/**
	 * @see Tagged::getMediumSymbol()
	 */
	public function getMediumSymbol() {
		return StyleManager::getStyle()->getIconPath('contestM.png');
	}

	/**
	 * @see Tagged::getLargeSymbol()
	 */
	public function getLargeSymbol() {
		return StyleManager::getStyle()->getIconPath('contestL.png');
	}

	/**
	 * @see Tagged::getUser()
	 */
	public function getUser() {
		return $this->user;
	}
	
	/**
	 * @see Tagged::getDate()
	 */
	public function getDate() {
		return $this->time;
	}
	
	/**
	 * @see Tagged::getDate()
	 */
	public function getURL() {
		return 'index.php?page=ContestEntry&contestID='.$this->contestID;
	}
}
?>