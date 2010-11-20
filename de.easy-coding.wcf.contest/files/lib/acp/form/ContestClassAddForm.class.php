<?php
// wcf imports
require_once(WCF_DIR.'lib/data/contest/class/ContestClassEditor.class.php');
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');
require_once(WCF_DIR.'lib/data/contest/class/ContestClass.class.php');

/**
 * Shows the class item add form.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestClassAddForm extends ACPForm {
	/**
	 * Template name
	 *
	 * @var	string
	 */
	public $templateName = 'contestClassAdd';
	
	/**
	 * Active menu item
	 *
	 * @var	string
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.contest.class';
	
	/**
	 * Permission
	 *
	 * @var	string
	 */
	public $neededPermissions = 'admin.contest.canAddClass';

	/**
	 * Rule item editor object
	 *
	 * @var	ContestClassEditor
	 */
	public $contestClass = null;

	/**
	 * Topic
	 *
	 * @var	string
	 */
	public $topic = '';
	
	/**
	 * sort order
	 *
	 * @var	integer
	 */
	public $position = 0;
	
	/**
	 * parent class
	 *
	 * @var	integer
	 */
	public $parentClassID = 0;
	
	/**
	 * Text
	 *
	 * @var	string
	 */
	public $text = '';
	
	/**
	 * Language id
	 *
	 * @var	integer
	 */
	public $languageID = 0;

	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['classID'])) {
			$this->contestClass = new ContestClass(intval($_REQUEST['classID']));
		}
	}
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();

		if (isset($_POST['topic'])) $this->topic = StringUtil::trim($_POST['topic']);
		if (isset($_POST['text'])) $this->text = StringUtil::trim($_POST['text']);
		if (isset($_POST['languageID'])) $this->languageID = intval($_POST['languageID']);
	}

	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();

		//  validate topic
		$this->validateTopic();
	}

	/**
	 * Validates the given topic.
	 */
	public function validateTopic() {
		if (empty($this->topic)) {
			throw new UserInputException('topic');
		}
	}

	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();

		// save
		$this->contestClass = ContestClassEditor::create($this->topic, $this->text, 
			$this->parentClassID, $this->position, WCF::getLanguage()->getLanguageID());
		$this->saved();

		// reset values
		$this->topic = $this->text = $this->parentClassID = '';
		$this->languageID = $this->position = 0;

		// show success message
		WCF::getTPL()->assign('success', true);
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
			'topic' => $this->topic,
			'text' => $this->text,
			'contestClass' => $this->contestClass,
			'languageID' => $this->languageID,
			'class' => $this->contestClass,
			'action' => 'add'
		));
	}
}
?>
