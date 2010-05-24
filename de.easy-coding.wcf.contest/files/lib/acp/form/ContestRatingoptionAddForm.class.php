<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');
require_once(WCF_DIR.'lib/data/contest/class/ContestClass.class.php');
require_once(WCF_DIR.'lib/data/contest/ratingoption/ContestRatingoptionEditor.class.php');
require_once(WCF_DIR.'lib/data/contest/ratingoption/ContestRatingoption.class.php');

/**
 * Shows the class item add form.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestRatingoptionAddForm extends ACPForm {
	/**
	 * Template name
	 *
	 * @var	string
	 */
	public $templateName = 'contestRatingoptionAdd';
	
	/**
	 * Active menu item
	 *
	 * @var	string
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.contest.ratingoption';
	
	/**
	 * Permission
	 *
	 * @var	string
	 */
	public $neededPermissions = 'admin.contest.canAddRatingoption';

	/**
	 * Rule item editor object
	 *
	 * @var	ContestRatingoptionEditor
	 */
	public $contestRatingoption = null;

	/**
	 * Topic
	 *
	 * @var	string
	 */
	public $topic = '';
	
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
	
	/**
	 * sort order
	 *
	 * @var	integer
	 */
	public $position = 0;
	
	/**
	 * ratingoption category id
	 * 
	 * @var	integer
	 */
	public $classID = 0;
	
	/**
	 * list of ratingoption categories.
	 * 
	 * @var	array<ContestClass>
	 */
	public $classes = array();

	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['optionID'])) {
			$this->contestRatingoption = new ContestRatingoption(intval($_REQUEST['optionID']));
		}
		if (isset($_REQUEST['classID'])) $this->classID = intval($_REQUEST['classID']);
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		// get categories
		$this->classes = ContestClass::getClasses();
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
		$this->contestRatingoption = ContestRatingoptionEditor::create($this->topic, $this->text, 
			$this->classID, $this->position, WCF::getLanguage()->getLanguageID());
		$this->saved();

		// reset values
		$this->topic = $this->text = $this->classID = '';
		$this->languageID = $this->position = 0;

		// show success message
		WCF::getTPL()->assign('success', true);
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
			'topic' => $this->topic,
			'text' => $this->text,
			'contestRatingoption' => $this->contestRatingoption,
			'languageID' => $this->languageID,
			'class' => $this->contestRatingoption,
			'action' => 'add',
			'classes' => $this->classes,
			'classID' => $this->classID,
		));
	}
}
?>
