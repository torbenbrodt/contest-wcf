<?php
// wcf imports
require_once(WCF_DIR.'lib/form/CaptchaForm.class.php');
require_once(WCF_DIR.'lib/data/contest/Contest.class.php');
require_once(WCF_DIR.'lib/data/contest/comment/ContestCommentEditor.class.php');

/**
 * Shows the form for adding contest contest comments.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestCommentAddForm extends CaptchaForm {
	// parameters
	public $comment = '';
	public $username = '';
	
	/**
	 * contest editor
	 *
	 * @var Contest
	 */
	public $contest = null;
	
	/**
	 * Creates a new ContestCommentAddForm object.
	 *
	 * @param	Contest	$contest
	 */
	public function __construct(Contest $contest) {
		$this->contest = $contest;
		parent::__construct();
	}
	
	/**
	 * @see Form::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get contest
		if (!$this->contest->isCommentable()) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		// get parameters
		if (isset($_POST['comment'])) $this->comment = StringUtil::trim($_POST['comment']);
		if (isset($_POST['username'])) $this->username = StringUtil::trim($_POST['username']);
	}
	
	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();
		
		if (empty($this->comment)) {
			throw new UserInputException('comment');
		}
		
		if (StringUtil::length($this->comment) > WCF::getUser()->getPermission('user.contest.maxSolutionLength')) {
			throw new UserInputException('comment', 'tooLong');
		}
		
		// username
		$this->validateUsername();
	}
	
	/**
	 * Validates the username.
	 */
	protected function validateUsername() {
		// only for guests
		if (WCF::getUser()->userID == 0) {
			// username
			if (empty($this->username)) {
				throw new UserInputException('username');
			}
			if (!UserUtil::isValidUsername($this->username)) {
				throw new UserInputException('username', 'notValid');
			}
			if (!UserUtil::isAvailableUsername($this->username)) {
				throw new UserInputException('username', 'notAvailable');
			}
			
			WCF::getSession()->setUsername($this->username);
		}
		else {
			$this->username = WCF::getUser()->username;
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		// save comment
		$comment = ContestCommentEditor::create($this->contest->contestID, $this->comment, WCF::getUser()->userID, $this->username);
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=Contest&contestID='.$this->contest->contestID.'&commentID='.$comment->commentID.SID_ARG_2ND_NOT_ENCODED.'#comment'.$comment->commentID);
		exit;
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
			'comment' => $this->comment,
			'username' => $this->username,
			'maxTextLength' => WCF::getUser()->getPermission('user.contest.maxSolutionLength')
		));
	}
}
?>
