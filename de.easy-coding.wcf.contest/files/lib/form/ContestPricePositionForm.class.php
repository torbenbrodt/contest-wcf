<?php
// wcf imports
require_once(WCF_DIR.'lib/form/AbstractForm.class.php');
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');
require_once(WCF_DIR.'lib/data/contest/price/ContestPriceEditor.class.php');
require_once(WCF_DIR.'lib/util/ContestUtil.class.php');

/**
 * reordering for the items
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestPricePositionForm extends AbstractForm {
	/**
	 *
	 * @var array
	 */
	protected $pricePositionPositions = array();
	
	/**
	 * contest editor
	 *
	 * @var Contest
	 */
	public $contest = null;
	
	/**
	 * Creates a new ContestPriceAddForm object.
	 *
	 * @param	Contest	$contest
	 */
	public function __construct(Contest $contest) {
		$this->contest = $contest;
		parent::__construct();
	}
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get contest
		if (!$this->contest->isOwner()) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['pricePositionPositions'][$this->contest->contestID]) && is_array($_POST['pricePositionPositions'][$this->contest->contestID])) {
			$this->pricePositionPositions = $_POST['pricePositionPositions'][$this->contest->contestID];
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
