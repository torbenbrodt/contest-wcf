<?php

/**
 * states
 *
 * Most common usage:
 * @code
 * ContestState::get('scheduled')->renderButton();
 * @endcode
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestState {
	/**
	 * see get states
	 */
	const FLAG_USER = 1;
	const FLAG_CONTESTOWNER = 2;
	const FLAG_CREW = 4;
	
	/**
	 * current config
	 */
	protected $config = array();
	
	/**
	 * singleton for using ContestState::get(...)
	 *
	 * @var array<ContestState>
	 */
	public static $instances = array();

	/**
	 * Creates a new object.
	 *
	 * @param	string		$state
	 */
	protected function __construct($state) {
		$this->state = $state;
		$this->buildConfig();
	}
	
	/**
	 * includes eventlister to beautify all states
	 */
	protected function buildConfig() {
		$c = array();
		$c['padding'] = '0px 5px';
		
		switch($this->state) {
			/* green */
			case 'accepted':
			case 'scheduled':
			case 'sent':
				$c['border-color'] = '#99cc99';
				$c['background-color'] = '#ddffdd';
				$c['color'] = '#55cc55';
			break;
			
			/* red */
			case 'declined':
			case 'unknown':
				$c['border-color'] = '#ffaaaa';
				$c['background-color'] = '#ffdddd';
				$c['color'] = '#ff5555';
			break;
			
			/* yellow */
			case 'private':
			case 'applied':
			case 'closed':
			case 'invited':
				$c['border-color'] = '#ffdd00';
				$c['background-color'] = '#ffffaa';
				$c['color'] = '#ccaa00';
			break;
			
			/* grey */
			default:
				$c['border-color'] = '#afafaf';
				$c['background-color'] = '#a0a0a0';
				$c['color'] = '#fff';
			break;
		}
		$this->config = $c;
		
		// call buildMenu event
		EventHandler::fireAction($this, 'buildConfig');
	}
	
	/**
	 * static method to construct
	 *
	 * @param	string		$state
	 */
	public static function get($state) {
		if(!isset(self::$instances[$state])) {
			self::$instances[$state] = new self($state);
		}
		return self::$instances[$state];
	}
	
	/**
	 * common method to generate link to user- or group profile page
	 */
	public function renderButton() {
		$style = '';
		foreach($this->config as $key => $val) {
			$style .= $key.':'.$val.';';
		}
		$style = rtrim($style, ';');
		return '<div style="'.$style.'" class="messageInner">'.WCF::getLanguage()->get('wcf.contest.state.'.$this->state).'</div>';
	}
	
	/**
	 * magic string conversion function
	 */
	public function __toString() {
		return "".$this->state;
	}
	
	/**
	 *
	 */
	public static function translateArray(array $arr) {
		$arr = array_unique($arr);
		$arr = count($arr) ? array_combine($arr, $arr) : $arr;
		foreach($arr as $key => $val) {
			$arr[$key] = WCF::getLanguage()->get('wcf.contest.state.'.$val);
		}
		return $arr;
	}
}
?>
