<?php
// wcf imports
require_once(WCF_DIR.'lib/form/ContestPriceAddForm.class.php');

/**
 * Shows the form for editing contest entry prices.
 *
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestPriceEditForm extends ContestPriceAddForm {
	/**
	 * price editor
	 *
	 * @var ContestPriceEditor
	 */
	public $priceObj = null;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		AbstractForm::readParameters();
		
		if (isset($_REQUEST['priceID'])) $this->priceID = intval($_REQUEST['priceID']);
		$this->priceObj = new ContestPriceEditor($this->priceID);
		if (!$this->entry->priceID || !$this->entry->isEditable()) {
			throw new IllegalLinkException();
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		// save price
		$this->priceObj->update($this->subject, $this->message);
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestPrice&contestID='.$this->priceObj->contestID.'&priceID='.$this->priceObj->priceID.SID_ARG_2ND_NOT_ENCODED.'#price'.$this->priceObj->priceID);
		exit;
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (!count($_POST)) {
			$this->price = $this->priceObj->price;
		}
	}
}
?>
