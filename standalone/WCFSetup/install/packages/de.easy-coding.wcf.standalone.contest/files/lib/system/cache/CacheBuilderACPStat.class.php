<?php
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

/**
 * Caches the acp statistics.
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.standalone
 */
class CacheBuilderACPStat implements CacheBuilder {
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		$data = array();
		
		$fetchArray = array('user', 'contest', 'contest_solution', 'contest_class');
		
		foreach($fetchArray as $prop) {
			$alias = $prop.'s';

			$sql = "SELECT	COUNT(*) AS ".$alias."
				FROM	wcf".WCF_N."_".$prop;
			$row = WCF::getDB()->getFirstRow($sql);
			$data[$alias] = $row[$alias];
		}
		
		// attachments
		$sql = "SELECT	COUNT(*) AS attachments,
				IFNULL((SUM(attachmentSize) + SUM(thumbnailSize)), 0) AS attachmentsSize
			FROM	wcf".WCF_N."_attachment
			WHERE	packageID = ".PACKAGE_ID;
		$row = WCF::getDB()->getFirstRow($sql);
		$data['attachments'] = $row['attachments'];
		$data['attachmentsSize'] = $row['attachmentsSize'];
		
		// database entries and size
		$data['databaseSize'] = 0;
		$data['databaseEntries'] = 0;
		$sql = "SHOW TABLE STATUS";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$data['databaseSize'] += $row['Data_length'] + $row['Index_length'];
			$data['databaseEntries'] += $row['Rows'];
		}
		
		return $data;
	}
}
?>
