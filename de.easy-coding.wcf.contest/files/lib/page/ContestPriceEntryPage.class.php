<?php
// wcf imports
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/data/contest/price/ContestPrice.class.php');

/**
 * price entry page just redirects to price page
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestPriceEntryPage extends AbstractPage {
	
	/**
	 * @see AbstractPage::show()
	 */
	public function show() {
		// check permission
		WCF::getUser()->checkPermission('user.contest.canViewContest');
		
		if (!MODULE_CONTEST) {
			throw new IllegalLinkException();
		}

		$priceID = intval($_GET['priceID']);
		$price = new ContestPrice($priceID);
		if($price->priceID) {
			HeaderUtil::redirect('index.php?page=ContestPrice'.
				'&contestID='.$price->contestID.
				'#priceObj'.$price->priceID.
				SID_ARG_2ND_NOT_ENCODED
			);
			exit;
		} else {
			throw new IllegalLinkException();
		}
	}
}
?>
