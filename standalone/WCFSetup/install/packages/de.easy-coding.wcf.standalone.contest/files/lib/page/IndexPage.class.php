<?php
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');

/**
 * index page with contest overview
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.standalone
 */
class IndexPage extends AbstractPage {
	/**
	 * @see Page::show()
	 */
	public function show() {
		require_once(WCF_DIR.'lib/page/ContestOverviewPage.class.php');
		new ContestOverviewPage();
		exit;
	}
}
?>
