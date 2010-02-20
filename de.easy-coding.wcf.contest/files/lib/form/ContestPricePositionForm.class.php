<?php
// wcf imports
require_once(WCF_DIR.'lib/form/AbstractContestForm.class.php');
require_once(WCF_DIR.'lib/data/contest/price/ContestPriceEditor.class.php');

/**
 * reordering for the items
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestPricePositionForm extends AbstractContestForm {
	
	/**
	 *
	 * @var array
	 */
	protected $pricePositionPositions = array();
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['pricePositionPositions']) && is_array($_POST['pricePositionPositions'])) {
			$this->pricePositionPositions = $_POST['pricePositionPositions'];
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		// save price
		ContestPriceEditor::updatePositions($this->pricePositionPositions);
		
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestPrice&contestID='.$this->contest->contestID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'pricePositionPositions' => $this->pricePositionPositions,
		));
	}
}
?>
