<?php
// wcf imports
require_once(WCF_DIR.'lib/data/page/location/UserLocation.class.php');

/**
 * ContestLocation is an implementation of Location for the user contest page.
 *
 * @author	Torben Brodt
 * @copyright	2009 TBR Solutions
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestLocation extends UserLocation {
	/**
	 * @see Location::get()
	 */
	public function get($location, $requestURI, $requestMethod, $match) {
		if ($this->users == null) {
			$this->readUsers();
		}
		
		$userID = $match[1];
		if (!isset($this->users[$userID])) {
			return '';
		}
		
		return WCF::getLanguage()->get($location['locationName'], array('$user' => '<a href="index.php?page=Contest&amp;userID='.$userID.SID_ARG_2ND.'">'.StringUtil::encodeHTML($this->users[$userID]).'</a>'));
	}
}
?>
