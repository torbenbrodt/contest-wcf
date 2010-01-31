<?php
/**
 * @author	Marcel Werk
 * @copyright	2001-2009 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @category 	Community Framework
 */
// make group names to language variables
$sql = "UPDATE	wcf".WCF_N."_group
	SET	groupName = '".WCF::getLanguage()->get('wcf.acp.group.everyone')."'
	WHERE	groupName = 'Everyone'";
WCF::getDB()->sendQuery($sql);

$sql = "UPDATE	wcf".WCF_N."_group
	SET	groupName = '".WCF::getLanguage()->get('wcf.acp.group.guests')."'
	WHERE	groupName = 'Guests'";
WCF::getDB()->sendQuery($sql);

$sql = "UPDATE	wcf".WCF_N."_group
	SET	groupName = '".WCF::getLanguage()->get('wcf.acp.group.users')."'
	WHERE	groupName = 'Users'";
WCF::getDB()->sendQuery($sql);

$sql = "UPDATE	wcf".WCF_N."_group
	SET	groupName = '".WCF::getLanguage()->get('wcf.acp.group.administrators')."'
	WHERE	groupName = 'Administrators'";
WCF::getDB()->sendQuery($sql);

$sql = "UPDATE	wcf".WCF_N."_group
	SET	groupName = '".WCF::getLanguage()->get('wcf.acp.group.moderators')."'
	WHERE	groupName = 'Moderators'";
WCF::getDB()->sendQuery($sql);

$sql = "UPDATE	wcf".WCF_N."_group
	SET	groupName = '".WCF::getLanguage()->get('wcf.acp.group.superModerators')."'
	WHERE	groupName = 'Super Moderators'";
WCF::getDB()->sendQuery($sql);


// change the priority of the PIP's to "1"
$sql = "UPDATE	wcf".WCF_N."_package_installation_plugin
	SET	priority = 1";
WCF::getDB()->sendQuery($sql);


// change group options from admin group to true
$sql = "UPDATE	wcf".WCF_N."_group_option_value
	SET	optionValue = 1
	WHERE	groupID = 4
		AND optionValue = '0'";
WCF::getDB()->sendQuery($sql);

// update accessible groups
$sql = "UPDATE	wcf".WCF_N."_group_option_value
	SET	optionValue = '1,2,3,4,5,6'
	WHERE	groupID = 4
		AND optionValue = ''";
WCF::getDB()->sendQuery($sql);

// reset sessions
require_once(WCF_DIR.'lib/system/session/Session.class.php');
Session::resetSessions();

// change the packageID from the acp-templates to the wcf-packageID (in database)
$sql = "UPDATE	wcf".WCF_N."_acp_template
	SET	packageID = ".PACKAGE_ID;
WCF::getDB()->sendQuery($sql);

// change the packageID from all installed files to the wcf-packageID (in database)
$sql = "UPDATE	wcf".WCF_N."_package_installation_file_log
	SET	packageID = ".PACKAGE_ID;
WCF::getDB()->sendQuery($sql);

// change the packageID from all installed tables to the wcf-packageID (in database)
$sql = "UPDATE	wcf".WCF_N."_package_installation_sql_log
	SET	packageID = ".PACKAGE_ID;
WCF::getDB()->sendQuery($sql);
?>