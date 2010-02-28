<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/data/contest/price/ContestPriceEditor.class.php');

/**
 * Picks a contest entry price.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestPricePickAction extends AbstractSecureAction {
	/**
	 * price id
	 *
	 * @var integer
	 */
	public $priceID = 0;
	
	/**
	 * price editor object
	 *
	 * @var ContestPriceEditor
	 */
	public $price = null;
	
	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['priceID'])) $this->priceID = intval($_REQUEST['priceID']);
		$this->price = new ContestPriceEditor($this->priceID);
		if (!$this->price->priceID) {
			throw new IllegalLinkException();
		}
		if (!$this->price->isPickable()) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		
		// pick price
		$this->price->pick();
		$this->executed();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestPrice&contestID='.$this->price->contestID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>
