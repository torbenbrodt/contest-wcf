<?php
// wcf imports
require_once(WCF_DIR.'lib/form/ContestPriceAddForm.class.php');

/**
 * Shows the form for editing contest entry prices.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestPriceEditForm extends ContestPriceAddForm {
	/**
	 * price editor
	 *
	 * @var ContestPriceEditor
	 */
	public $entry = null;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		AbstractForm::readParameters();
		
		if (isset($_REQUEST['priceID'])) $this->priceID = intval($_REQUEST['priceID']);
		$this->entry = new ContestPriceEditor($this->priceID);
		if (!$this->entry->priceID || !$this->entry->isEditable()) {
			throw new IllegalLinkException();
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		AbstractForm::save();
		
		// save price
		$this->entry->update($this->subject, $this->message);
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestPrice&contestID='.$this->entry->contestID.'&priceID='.$this->entry->priceID.SID_ARG_2ND_NOT_ENCODED.'#price'.$this->entry->priceID);
		exit;
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (!count($_POST)) {
			$this->subject = $this->entry->subject;
			$this->message = $this->entry->message;
		}
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'action' => 'edit',
			'priceID' => $this->priceID
		));
	}
}
?>
