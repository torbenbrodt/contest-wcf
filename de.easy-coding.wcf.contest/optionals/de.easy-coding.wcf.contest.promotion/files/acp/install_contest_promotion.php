<?php
/**
 *
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.promotion
 */

// mod options
$sql = "UPDATE 	wcf".WCF_N."_group_option_value
	SET	optionValue = 1
	WHERE	groupID IN (4,5,6)
		AND optionID IN (
			SELECT	optionID
			FROM	wcf".WCF_N."_group_option
			WHERE	optionName = 'mod.contest.canAddPromotion'
				AND packageID IN (
					SELECT	dependency
					FROM	wcf".WCF_N."_package_dependency
					WHERE	packageID = ".intval($this->installation->getPackageID())."
				)
		)
		AND optionValue = '0'";
WCF::getDB()->sendQuery($sql);

// try to delete this file
@unlink(__FILE__);
?>
