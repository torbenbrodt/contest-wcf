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
		MessageForm::readParameters();
		
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
		$this->entry->update($this->subject, $this->text, $this->secretMessage, $this->state, $this->attachmentListEditor);
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=ContestPrice&contestID='.$this->entry->contestID.'&priceID='.$this->entry->priceID.SID_ARG_2ND_NOT_ENCODED.'#priceObj'.$this->entry->priceID);
		exit;
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (!count($_POST)) {
			$this->subject = $this->entry->subject;
			$this->text = $this->entry->message;
			$this->secretMessage = $this->entry->secretMessage;
			$this->state = $this->entry->state;
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
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		$max = min(WCF::getUser()->getPermission('user.contest.maxAttachmentCount'), 1);
		$extensions = WCF::getUser()->getPermission('user.contest.allowedAttachmentExtensions');
		$extensions = explode("\n", $extensions);
		$extensions = array_filter($extensions, create_function('$a', 'return in_array($a, array("jpeg", "jpg", "gif", "png"));'));
		$extensions = implode("\n", $extensions);
		require_once(WCF_DIR.'lib/data/attachment/MessageAttachmentListEditor.class.php');
		$this->attachmentListEditor = new MessageAttachmentListEditor(array($this->priceID), 'contestPriceEntry', WCF::getPackageID('de.easy-coding.wcf.contest'), WCF::getUser()->getPermission('user.contest.maxAttachmentSize'), $extensions, $max);
		
		parent::show();
	}
}
?>
