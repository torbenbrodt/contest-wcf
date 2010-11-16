<?php
require_once(WCF_DIR.'lib/system/language/LanguageEditor.class.php');

/**
 * easy wrapper around language editor
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest
 */
class ContestLanguageEditor extends LanguageEditor {

	/**
	 * Updates the language items of a language category.
	 * 
	 * @param	array		$items
	 * @param	integer		$categoryID
	 * @param	integer		$packageID
	 * @param 	array		$useCustom
	 */
	public function easyUpdateItems($categoryString, $items, $packageID = null) {
		$packageID = $packageID === null ? WCF::getPackageID('de.easy-coding.wcf.contest') : $packageID;
		$language = new LanguageEditor($this->languageID);

		$xmlString = '<?xml version="1.0" encoding="'.CHARSET.'"?>
		<language languagecode="'.$this->getLanguageCode().'">
			<category name="'.$categoryString.'">';
			
		foreach($items as $key => $val) {
			if($val === null) {
				continue;
			}
			$xmlString .= '<item name="'.$key.'"><![CDATA['.$val.']]></item>';
		}
		$xmlString .= '
			</category>
		</language>';

		$xml = new XML();
		$xml->loadString($xmlString);

		$language->updateFromXML($xml, $packageID);
	}
}
?>
