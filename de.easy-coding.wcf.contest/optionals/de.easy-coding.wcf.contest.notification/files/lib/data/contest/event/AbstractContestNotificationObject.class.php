<?php
// wcf imports
require_once(WCF_DIR.'lib/data/user/notification/object/NotificationObject.class.php');
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * 
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
abstract class AbstractContestNotificationObject extends DatabaseObject implements NotificationObject {
	/**
	 * eager loading of instance
	 */
	protected $instance = null;
	
	/**
	 * @var string
	 */
	protected $className = '';
	
	/**
	 * @var string
	 */
	protected $primarykey = '';

	/**
	 * eager loading of instance
	 */
	public final function getInstance() {
		return $this->instance !== null ? $this->instance : $this->instance = new $this->className($this->getObjectID());
	}
		
	/**
	 * @see NotificationObject::getObjectID()
	 */
	public final function getObjectID() {
		return $this->{$this->primarykey};
	}

	/**
	 * 
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * @see NotificationObject::getIcon()
	 */
	public function getIcon() {
		return 'contest';
	}
	
	/**
	 * overwrite this method to get an objectIDScope
	 */
	public function getObjects() {
		return array();
	}
	
	/**
	 * overwrite this
	 */
	public abstract function getRecipients();
}
?>
