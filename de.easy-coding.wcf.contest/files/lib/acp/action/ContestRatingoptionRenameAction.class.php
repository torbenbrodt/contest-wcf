<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');
require_once(WCF_DIR.'lib/data/contest/ratingoption/ContestRatingoptionEditor.class.php');

/**
 * Renames a ratingoption item.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestRatingoptionRenameAction extends AbstractAction {
	/**
	 * Rule Item ID
	 *
	 * @var	integer
	 */
	public $contestRatingoptionID = 0;
	
	/**
	 * title
	 *
	 * @var	string
	 */
	public $title = '';

	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		if (isset($_REQUEST['contestRatingoptionID'])) $this->contestRatingoptionID = intval($_REQUEST['contestRatingoptionID']);
		if (isset($_POST['title'])) {
			$this->title = $_POST['title'];
			if (CHARSET != 'UTF-8') $this->title = StringUtil::convertEncoding('UTF-8', CHARSET, $this->title);
		}
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();

		// check permission
		WCF::getUser()->checkPermission('admin.ratingoption.canEditItem');

		// get ratingoption item
		$contestRatingoption = new ContestRatingoptionEditor($this->contestRatingoptionID);
		if (!$contestRatingoption->contestRatingoptionID) {
			throw new IllegalLinkException();
		}

		// change language variable
		require_once(WCF_DIR.'lib/system/language/LanguageEditor.class.php');
		$language = new LanguageEditor(WCF::getLanguage()->getLanguageID());
		$language->updateItems(array('wcf.ratingoptions.item.' . $contestRatingoption->contestRatingoption => $this->title), 0, PACKAGE_ID, array('wcf.ratingoptions.item.' . $contestRatingoption->contestRatingoption => 1));

		$this->executed();
	}
}
?>
