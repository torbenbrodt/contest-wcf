<?php
require_once(WCF_DIR.'lib/data/contest/ViewableContest.class.php');
require_once(WCF_DIR.'lib/data/message/util/SearchResultTextParser.class.php');

/**
 * This class extends the viewable contest entry by functions for a search result output.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestSearchResult extends ViewableContest {
	/**
	 * @see ViewableContest::handleData();
	 */
	protected function handleData($data) {
		$data['messagePreview'] = true;
		parent::handleData($data);
	}

	/**
	 * @see ViewableContest::getFormattedMessage()
	 */
	public function getFormattedMessage() {
		return SearchResultTextParser::parse(parent::getFormattedMessage());
	}
}
?>