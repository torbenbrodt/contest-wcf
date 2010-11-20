<?php
// wcf imports
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/data/contest/class/ContestClass.class.php');

/**
 * Shows a list of all class items.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestClassListPage extends AbstractPage {
	/**
	 * Template name
	 *
	 * @var	string
	 */
	public $templateName = 'contestClassList';
	
	/**
	 * Delte class item id
	 *
	 * @var	integer
	 */
	public $deletedClassID = 0;
	
	/**
	 * If the list was sorted successfully
	 *
	 * @var boolean
	 */
	public $successfullSorting = false;

	/**
	 * class item list
	 *
	 * @var array<array>
	 */
	public $contestClassList = array();

	/**
	 * structured class item list
	 *
	 * @var array<array>
	 */
	public $contestClasses = array();

	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		if (isset($_REQUEST['successfullSorting'])) $this->successfullSorting = true;
		if (isset($_REQUEST['deletedClassID'])) $this->deletedClassID = intval($_REQUEST['deletedClassID']);

	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();

		$this->readContestClasss();
		$this->makeContestClassList();
	}

	/**
	 * Gets a list of all class items.
	 */
	protected function readContestClasss() {
		$sql = "SELECT		contest_class.*
			FROM		wcf".WCF_N."_contest_class contest_class
			ORDER BY	parentClassID, position ";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->contestClasses[$row['parentClassID']][] = new ContestClass(null, $row);
		}
	}

	/**
	 * Renders one level of the help item structure.
	 *
	 * @param	integer		$parentHelpItemID
	 * @param	integer		$depth
	 * @param	integer		$openParents
	 */
	protected function makeContestClassList($parentClassID = 0, $depth = 1, $openParents = 0) {
		if (!isset($this->contestClasses[$parentClassID])) return;

		$i = 0; 
		$children = count($this->contestClasses[$parentClassID]);
		foreach ($this->contestClasses[$parentClassID] as $contestClass) {
			$childrenOpenParents = $openParents + 1;
			$hasChildren = isset($this->contestClasses[$contestClass->classID]);
			$last = $i == count($this->contestClasses[$parentClassID]) - 1;
			if ($hasChildren && !$last) $childrenOpenParents = 1;
			$this->contestClassList[] = array('depth' => $depth, 'hasChildren' => $hasChildren, 'openParents' => ((!$hasChildren && $last) ? ($openParents) : (0)), 'contestClass' => $contestClass, 'position' => $i + 1, 'maxPosition' => $children);

			// make next level of the list
			$this->makeContestClassList($contestClass->classID, $depth + 1, $childrenOpenParents);
			$i++;
		}
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		// display branding
		require_once(WCF_DIR.'lib/util/ContestUtil.class.php');
		ContestUtil::assignVariablesBranding();

		WCF::getTPL()->assign(array(
			'contestClasses' => $this->contestClassList,
			'deletedClassID' => $this->deletedClassID,
			'successfullSorting' => $this->successfullSorting,
		));
	}

	/**
	 * @see Page::show()
	 */
	public function show() {
		// check user permission
		WCF::getUser()->checkPermission(array('admin.contest.canEditClass', 'admin.contest.canDeleteClass'));
		
		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.contest.class');

		parent::show();
	}
}
?>
