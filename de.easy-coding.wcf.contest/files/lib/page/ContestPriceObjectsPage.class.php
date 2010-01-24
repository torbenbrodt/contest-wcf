<?php
// wcf imports
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/data/contest/price/ContestPriceEditor.class.php');

/**
 * Validates price and outputs json, does NOT add the entry to the database
 * 
 * @author	Torben Brodt
 * @copyright 2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestPriceObjectsPage extends AbstractPage {
	/**
	 * query
	 * 
	 * @var	string
	 */
	public $subject = '';

	/**
	 * query
	 * 
	 * @var	string
	 */
	public $message = '';

	/**
	 * id
	 * 
	 * @var	string
	 */
	public $id = '';
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['query']) && isset($_REQUEST['text'])) {
			$querySubject = $_REQUEST['query'];
			$queryText = $_REQUEST['text'];
			if (CHARSET != 'UTF-8') {
				$querySubject = StringUtil::convertEncoding('UTF-8', CHARSET, $querySubject);
				$queryText = StringUtil::convertEncoding('UTF-8', CHARSET, $queryText);
			}
			
			$this->subject = $querySubject;
			$this->message = $queryText;
		}
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		parent::show();
		
		header('Content-Type: application/json');
		echo json_encode(array(
			array(
				'name' => $this->subject,
				'type' => 'contestPrice',
				'message' => $this->message,
				'subject' => $this->subject,
				'id' => rand(1,1000),
			)
		));
		exit;
	}
}
?>
