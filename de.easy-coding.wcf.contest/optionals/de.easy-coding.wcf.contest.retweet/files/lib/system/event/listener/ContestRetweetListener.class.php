<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/listener/TwitterRetweetDisplayListener.class.php');
require_once(WCF_DIR.'lib/data/twitter/TwitterRetweetMessage.class.php');

/**
 * Displays retweets of a contest.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.retweet
 */
class ContestRetweetListener extends TwitterRetweetDisplayListener {
	
	/**
	 * name of package like com.woltlab.wcf.user.blog
	 * @var string
	 */
	protected $packageName = 'de.easy-coding.wcf.contest.socialBookmarks';
	
	/**
	 * description of adapter like userBlogEntry
	 * @var string
	 */
	protected $objectType = 'contestEntry';
	
	/**
	 * where to append the template
	 * @var string
	 */
	protected $templateHook = 'additionalContent1';
	
	/**
	 * what is the primary key?
	 * @var string
	 */
	protected $primarykey = 'contestID';
}
?>
