/* +++ tables +++ */
DROP TABLE IF EXISTS wcf1_acp_menu_item;
CREATE TABLE wcf1_acp_menu_item (
	menuItemID int(10) unsigned NOT NULL auto_increment,
	packageID int(10) unsigned NOT NULL default 0,
	menuItem varchar(255) NOT NULL default '',
	parentMenuItem varchar(255) NOT NULL default '',
	menuItemLink varchar(255) NOT NULL default '',
	menuItemIcon varchar(255) NOT NULL default '', 
	showOrder int(10) unsigned NOT NULL default 0,
	permissions text NULL,
	options TEXT NULL,
	PRIMARY KEY (menuItemID),
	UNIQUE KEY (menuItem, packageID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_acp_session;
CREATE TABLE wcf1_acp_session (
	sessionID char(40) NOT NULL default '',
	packageID int(10) unsigned NOT NULL default 0,
	userID int(10) unsigned NOT NULL default 0,
	ipAddress varchar(39) NOT NULL default '',
	userAgent varchar(255) NOT NULL default '',
	lastActivityTime int(10) unsigned NOT NULL default 0,
	requestURI varchar(255) NOT NULL default '',
	requestMethod varchar(4) NOT NULL default '',
	username varchar(255) NOT NULL default '',
	PRIMARY KEY (sessionID,packageID)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_acp_session_data;
CREATE TABLE wcf1_acp_session_data (
	sessionID char(40) NOT NULL default '',
	userData mediumtext NULL,
	sessionVariables mediumtext NULL,
	PRIMARY KEY (sessionID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_acp_session_log;
CREATE TABLE wcf1_acp_session_log (
	sessionLogID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	sessionID CHAR(40) NOT NULL DEFAULT '',
	userID INT(10) NOT NULL DEFAULT 0,
	ipAddress VARCHAR(39) NOT NULL DEFAULT '',
	hostname VARCHAR(255) NOT NULL DEFAULT '',
	userAgent VARCHAR(255) NOT NULL DEFAULT '',
	time INT(10) NOT NULL DEFAULT 0,
	lastActivityTime INT(10) NOT NULL DEFAULT 0,
	KEY (sessionID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_acp_session_access_log;
CREATE TABLE wcf1_acp_session_access_log (
	sessionAccessLogID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	sessionLogID INT(10) NOT NULL DEFAULT 0,
	packageID INT(10) NOT NULL DEFAULT 0,
	ipAddress VARCHAR(39) NOT NULL DEFAULT '',
	time INT(10) NOT NULL DEFAULT 0,
	requestURI VARCHAR(255) NOT NULL DEFAULT '',
	requestMethod VARCHAR(4) NOT NULL DEFAULT '',
	className VARCHAR(255) NOT NULL DEFAULT '',
	KEY (sessionLogID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_acp_template;
CREATE TABLE wcf1_acp_template (
	templateID int(10) unsigned NOT NULL auto_increment,
	packageID int(10) unsigned NOT NULL default 0,
	templateName varchar(255) NOT NULL default '',
	PRIMARY KEY (templateID),
	UNIQUE KEY (packageID, templateName)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_acp_template_patch;
CREATE TABLE wcf1_acp_template_patch (
	patchID int(10) unsigned NOT NULL auto_increment,
	packageID int(10) unsigned NOT NULL default 0,
	templateID int(10) unsigned NOT NULL default 0,
	success tinyint(1) unsigned NOT NULL default 0,
	fuzzFactor int(10) unsigned NOT NULL default 0,
	patch LONGTEXT,
	PRIMARY KEY  (patchID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_cache_resource;
CREATE TABLE wcf1_cache_resource (
	cacheResource VARCHAR(255) NOT NULL PRIMARY KEY
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_event_listener;
CREATE TABLE wcf1_event_listener (
	listenerID int(10) unsigned NOT NULL auto_increment,
	packageID int(10) unsigned NOT NULL default '0',
	environment enum('user','admin') NOT NULL default 'user',
	eventClassName varchar(80) NOT NULL default '',
	eventName varchar(50) NOT NULL default '',
	listenerClassFile varchar(200) NOT NULL default '',
	inherit tinyint(1) unsigned NOT NULL default 0,
	niceValue TINYINT(3) NOT NULL DEFAULT 0,
	PRIMARY KEY  (listenerID),
	UNIQUE KEY packageID (packageID,environment,eventClassName,eventName,listenerClassFile)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_group;
CREATE TABLE wcf1_group (
	groupID int(10) unsigned NOT NULL auto_increment,
	groupName varchar(255) NOT NULL default '',
	groupType tinyint(1) unsigned NOT NULL default 0, 
	PRIMARY KEY (groupID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_group_option_category;
CREATE TABLE wcf1_group_option_category (
	categoryID int(10) unsigned NOT NULL auto_increment,
	packageID int(10) unsigned NOT NULL default 0,
	categoryName varchar(255) NOT NULL default '',
	parentCategoryName varchar(255) NOT NULL default '',
	showOrder int(10) unsigned NOT NULL default 0,
	permissions TEXT NULL,
	options TEXT NULL,
	PRIMARY KEY (categoryID),
	UNIQUE KEY (categoryName, packageID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_group_option;
CREATE TABLE wcf1_group_option  (
	optionID int(10) unsigned NOT NULL auto_increment,
	packageID int(10) unsigned NOT NULL default 0,
	optionName varchar(255) NOT NULL default '',
	categoryName varchar(255) NOT NULL default '',
	optionType varchar(255) NOT NULL default '',
	defaultValue mediumtext NULL,
	validationPattern text NULL,
	enableOptions mediumtext NULL,
	showOrder int(10) unsigned NOT NULL default 0,
	permissions TEXT NULL,
	options TEXT NULL,
	additionalData MEDIUMTEXT NULL,
	PRIMARY KEY (optionID),
	UNIQUE KEY (optionName, packageID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_group_option_value;
CREATE TABLE wcf1_group_option_value  (
	groupID int(10) unsigned NOT NULL default 0,
	optionID int(10) unsigned NOT NULL default 0,
	optionValue mediumtext NOT NULL,
	PRIMARY KEY (groupID, optionID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_language;
CREATE TABLE wcf1_language (
	languageID int(10) unsigned NOT NULL auto_increment,
	languageCode varchar(20) NOT NULL default '',
	languageEncoding varchar(20) NOT NULL default '',
	isDefault tinyint(1) unsigned NOT NULL default 0,
	hasContent tinyint(1) unsigned NOT NULL default 0,
	PRIMARY KEY (languageID),
	UNIQUE KEY (languageCode)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_language_to_packages;
CREATE TABLE wcf1_language_to_packages (
	languageID int(10) unsigned NOT NULL default 0,
	packageID int(10) unsigned NOT NULL default 0,
	PRIMARY KEY (languageID,packageID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_language_category;
CREATE TABLE wcf1_language_category (
	languageCategoryID int(10) unsigned NOT NULL auto_increment,
	languageCategory varchar(255) NOT NULL default '',
	PRIMARY KEY (languageCategoryID),
	UNIQUE KEY (languageCategory)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_language_item;
CREATE TABLE wcf1_language_item (
	languageItemID int(10) unsigned NOT NULL auto_increment,
	languageID int(10) unsigned NOT NULL default 0,
	languageItem varchar(255) NOT NULL default '',
	languageItemValue mediumtext NOT NULL,
	languageHasCustomValue tinyint(1) NOT NULL default 0,
	languageCustomItemValue mediumtext NULL,
	languageUseCustomValue tinyint(1) NOT NULL default 0,
	languageCategoryID int(10) unsigned NOT NULL default 0,
	packageID int(10) unsigned NOT NULL default 0,
	PRIMARY KEY (languageItemID),
	UNIQUE KEY (languageItem, packageID, languageID),
	KEY (languageHasCustomValue)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_option_category;
CREATE TABLE wcf1_option_category (
	categoryID int(10) unsigned NOT NULL auto_increment,
	packageID int(10) unsigned NOT NULL default 0,
	categoryName varchar(255) NOT NULL default '',
	parentCategoryName  varchar(255) NOT NULL default '',
	showOrder int(10) unsigned NOT NULL default 0,
	permissions TEXT NULL,
	options TEXT NULL,
	PRIMARY KEY (categoryID),
	UNIQUE KEY (categoryName, packageID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_option;
CREATE TABLE wcf1_option (
	optionID int(10) unsigned NOT NULL auto_increment,
	packageID int(10) unsigned NOT NULL default 0,
	optionName varchar(255) NOT NULL default '',
	categoryName varchar(255) NOT NULL default '',
	optionType varchar(255) NOT NULL default '',
	optionValue mediumtext NULL,
	validationPattern text NULL,
	selectOptions mediumtext NULL,
	enableOptions mediumtext NULL,
	showOrder int(10) unsigned NOT NULL default 0,
	hidden tinyint(1) unsigned NOT NULL default 0,
	permissions TEXT NULL,
	options TEXT NULL,
	additionalData MEDIUMTEXT NULL,
	PRIMARY KEY (optionID),
	UNIQUE KEY (optionName, packageID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_package_dependency;
CREATE TABLE wcf1_package_dependency (
	packageID int(10) unsigned NOT NULL default 0,
	dependency int(10) unsigned NOT NULL default 0,
	priority int(10) unsigned NOT NULL default 0,
	PRIMARY KEY (packageID, dependency),
	KEY (dependency)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_package;
CREATE TABLE wcf1_package (
	packageID int(10) unsigned NOT NULL auto_increment,
	package varchar(255) NOT NULL default '',
	packageDir varchar(255) NOT NULL default '',
	packageName varchar(255) NOT NULL default '',
	instanceName varchar(255) NOT NULL default '',
	instanceNo int(10) unsigned NOT NULL default 1,
	packageDescription varchar(255) NOT NULL default '',
	packageVersion varchar(255) NOT NULL default '',
	packageDate int(10) unsigned NOT NULL default 0,
	installDate INT(10) NOT NULL DEFAULT 0,
	updateDate INT(10) NOT NULL DEFAULT 0,
	packageURL varchar(255) NOT NULL default '',
	parentPackageID int(10) unsigned NOT NULL default 0,
	isUnique tinyint(1) unsigned NOT NULL default 0,
	standalone tinyint(1) unsigned NOT NULL default 0,
	author varchar(255) NOT NULL default '',
	authorURL varchar(255) NOT NULL default '',
	PRIMARY KEY (packageID),
	KEY (package)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_package_installation_queue;
CREATE TABLE wcf1_package_installation_queue (
	queueID int(10) unsigned NOT NULL auto_increment,
	parentQueueID int(10) unsigned NOT NULL default 0,
	processNo int(10) unsigned NOT NULL default 0,
	userID int(10) unsigned NOT NULL default 0,
	package varchar(255) NOT NULL default '',
	packageID int(10) unsigned NOT NULL default 0,
	archive varchar(255) NOT NULL default '',
	action ENUM('install', 'update', 'uninstall', 'rollback') NOT NULL default 'install',
	cancelable tinyint(1) unsigned NOT NULL default 1,
	done tinyint(1) unsigned NOT NULL default 0,
	confirmInstallation tinyint(1) unsigned NOT NULL default 0,
	packageType ENUM('default', 'requirement', 'optional') NOT NULL default 'default',
	installationType ENUM('install', 'setup', 'other') NOT NULL default 'other',
	PRIMARY KEY (queueID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_package_installation_file_log;
CREATE TABLE wcf1_package_installation_file_log (
	packageID int(10) unsigned NOT NULL default 0,
	filename varchar(255) NOT NULL default '',
	PRIMARY KEY (packageID, filename)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_package_installation_plugin;
CREATE TABLE wcf1_package_installation_plugin (
	pluginName varchar(255) NOT NULL default '',
	packageID int(10) unsigned NOT NULL default 0,
	priority tinyint(1) unsigned NOT NULL default 0, 
	PRIMARY KEY (pluginName)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_package_installation_sql_log;
CREATE TABLE wcf1_package_installation_sql_log ( 
	packageID int(10) unsigned NOT NULL default 0, 
	sqlTable varchar(100) NOT NULL default '', 
	sqlColumn varchar(100) NOT NULL default '', 
	sqlIndex varchar(100) NOT NULL default '', 
	PRIMARY KEY (packageID, sqlTable, sqlColumn, sqlIndex) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_package_requirement;
CREATE TABLE wcf1_package_requirement (
	packageID int(10) unsigned NOT NULL default 0,
	requirement int(10) unsigned NOT NULL default 0,
	PRIMARY KEY (packageID, requirement)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_package_exclusion;
CREATE TABLE wcf1_package_exclusion (
	packageID INT(10) NOT NULL DEFAULT 0,
	excludedPackage VARCHAR(255) NOT NULL DEFAULT '',
	excludedPackageVersion VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY (packageID, excludedPackage)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_package_requirement_map;
CREATE TABLE wcf1_package_requirement_map (
	packageID int(10) unsigned NOT NULL default 0,
	requirement int(10) unsigned NOT NULL default 0,
	level int(10) unsigned NOT NULL default 0,
	PRIMARY KEY (packageID, requirement),
	KEY (requirement)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_package_update_server;
CREATE TABLE wcf1_package_update_server (
	packageUpdateServerID int(10) unsigned NOT NULL auto_increment,
	server varchar(255) NOT NULL default '',
	status varchar(10) NOT NULL default '',
	statusUpdate tinyint(1) unsigned NOT NULL default 1,
	errorText text NULL,
	updatesFile tinyint(1) unsigned NOT NULL default 0,
	timestamp int(10) unsigned NOT NULL default 0,
	htUsername varchar(50) NOT NULL default '',
	htPassword varchar(40) NOT NULL default '',
	PRIMARY KEY  (packageUpdateServerId)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_package_update;
CREATE TABLE wcf1_package_update (
	packageUpdateID int(10) unsigned NOT NULL auto_increment,
	packageUpdateServerID int(10) unsigned NOT NULL default 0,
	package varchar(255) NOT NULL default '',
	packageName varchar(255) NOT NULL default '',
	packageDescription varchar(255) NOT NULL default '',
	author varchar(255) NOT NULL default '',
	authorURL varchar(255) NOT NULL default '',
	standalone tinyint(1) NOT NULL DEFAULT 0,
	plugin varchar(255) NOT NULL default '',
	PRIMARY KEY (packageUpdateID),
	UNIQUE KEY (packageUpdateServerID, package)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_package_update_version;
CREATE TABLE wcf1_package_update_version (
	packageUpdateVersionID int(10) unsigned NOT NULL auto_increment,
	packageUpdateID int(10) unsigned NOT NULL default 0,
	packageVersion varchar(50) NOT NULL default '',
	updateType varchar(10) NOT NULL default '',
	timestamp int(10) unsigned NOT NULL default 0,
	file varchar(255) NOT NULL default '',
	PRIMARY KEY  (packageUpdateVersionID),
	UNIQUE KEY (packageUpdateID, packageVersion)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_package_update_requirement;
CREATE TABLE wcf1_package_update_requirement (
	packageUpdateVersionID int(10) unsigned NOT NULL default 0,
	package varchar(255) NOT NULL default '',
	minversion varchar(50) NOT NULL default '',
	UNIQUE KEY (packageUpdateVersionID, package)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_package_update_exclusion;
CREATE TABLE wcf1_package_update_exclusion (
	packageUpdateVersionID INT(10) NOT NULL DEFAULT 0,
	excludedPackage VARCHAR(255) NOT NULL DEFAULT '',
	excludedPackageVersion VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY (packageUpdateVersionID, excludedPackage)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_package_update_fromversion;
CREATE TABLE wcf1_package_update_fromversion (
	packageUpdateVersionID int(10) unsigned NOT NULL default 0,
	fromversion varchar(50) NOT NULL default '',
	UNIQUE KEY (packageUpdateVersionID, fromversion)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_search;
CREATE TABLE wcf1_search (
	searchID int(10) unsigned NOT NULL auto_increment,
	userID int(10) unsigned NOT NULL default 0,
	searchData mediumtext NOT NULL,
	searchDate int(10) unsigned NOT NULL default 0,
	searchType varchar(255) NOT NULL default '',
	searchHash char(40) NOT NULL default '',
	PRIMARY KEY (searchID),
	KEY (searchHash)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_session;
CREATE TABLE wcf1_session (
	sessionID char(40) NOT NULL default '',
	packageID int(10) unsigned NOT NULL default 0,
	userID int(10) unsigned NOT NULL default 0,
	ipAddress varchar(39) NOT NULL default '',
	userAgent varchar(255) NOT NULL default '',
	lastActivityTime int(10) unsigned NOT NULL default 0,
	requestURI varchar(255) NOT NULL default '',
	requestMethod varchar(4) NOT NULL default '',
	username varchar(255) NOT NULL default '',
	spiderID int(10) unsigned NOT NULL default 0,
	PRIMARY KEY (sessionID),
	KEY (packageID, lastActivityTime, spiderID),
	KEY (userID)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_session_data;
CREATE TABLE wcf1_session_data (
	sessionID char(40) NOT NULL default '',
	userData mediumtext NULL,
	sessionVariables mediumtext NULL,
	PRIMARY KEY (sessionID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_spider;
CREATE TABLE wcf1_spider (
	spiderID int(10) unsigned NOT NULL auto_increment,
	spiderIdentifier varchar(255) default '',
	spiderName varchar(255) default '',
	spiderURL varchar(255) default '',
	PRIMARY KEY (spiderID),
	UNIQUE KEY (spiderIdentifier)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_template;
CREATE TABLE wcf1_template (
	templateID INT(10) NOT NULL auto_increment,
	packageID INT(10) NOT NULL DEFAULT 0,
	templateName VARCHAR(255) NOT NULL DEFAULT '',
	templatePackID INT(10) NOT NULL DEFAULT 0,
	obsolete TINYINT(1) NOT NULL DEFAULT 0,
	PRIMARY KEY (templateID),
	KEY (packageID, templateName),
	KEY (packageID, templatePackID, templateName)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_template_pack;
CREATE TABLE wcf1_template_pack (
	templatePackID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	parentTemplatePackID INT(10) NOT NULL DEFAULT 0,
	templatePackName VARCHAR(255) NOT NULL DEFAULT '',
	templatePackFolderName VARCHAR(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_template_patch;
CREATE TABLE wcf1_template_patch (
	patchID int(10) unsigned NOT NULL auto_increment,
	packageID int(10) unsigned NOT NULL default 0,
	templateID int(10) unsigned NOT NULL default 0,
	success tinyint(1) unsigned NOT NULL default 0,
	fuzzFactor int(10) unsigned NOT NULL default 0,
	patch LONGTEXT,
	PRIMARY KEY  (patchID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_user;
CREATE TABLE wcf1_user (
	userID int(10) unsigned NOT NULL auto_increment,
	username varchar(255) NOT NULL default '',
	email varchar(255) NOT NULL default '',
	password varchar(40) NOT NULL default '',
	salt varchar(40) NOT NULL default '',
	languageID int(10) unsigned NOT NULL default 0,
	registrationDate int(10) unsigned NOT NULL default 0,
	PRIMARY KEY (userID),
	KEY (username),
	KEY (registrationDate)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_user_to_groups;
CREATE TABLE wcf1_user_to_groups (
	userID int(10) unsigned NOT NULL default 0,
	groupID int(10) unsigned NOT NULL default 0,
	PRIMARY KEY (userID,groupID),
	KEY (groupID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_user_to_languages;
CREATE TABLE wcf1_user_to_languages (
	userID int(10) unsigned NOT NULL default 0,
	languageID int(10) unsigned NOT NULL default 0,
	PRIMARY KEY (userID,languageID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_user_option_category;
CREATE TABLE wcf1_user_option_category (
	categoryID int(10) unsigned NOT NULL auto_increment,
	packageID int(10) unsigned NOT NULL default 0,
	categoryName varchar(255) NOT NULL default '',
	categoryIconS varchar(255) NOT NULL default '',
	categoryIconM varchar(255) NOT NULL default '',
	parentCategoryName varchar(255) NOT NULL default '',
	showOrder int(10) unsigned NOT NULL default 0,
	permissions TEXT NULL,
	options TEXT NULL,
	PRIMARY KEY (categoryID),
	UNIQUE KEY (categoryName, packageID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_user_option;
CREATE TABLE wcf1_user_option (
	optionID int(10) unsigned NOT NULL auto_increment,
	packageID int(10) unsigned NOT NULL default 0,
	optionName varchar(255) NOT NULL default '',
	categoryName varchar(255) NOT NULL default '',
	optionType varchar(255) NOT NULL default '',
	defaultValue mediumtext NULL,
	validationPattern text NULL,
	selectOptions mediumtext NULL,
	enableOptions mediumtext NULL,
	required tinyint(1) unsigned NOT NULL default 0,
	askDuringRegistration tinyint(1) unsigned NOT NULL default 0,
	editable tinyint(1) unsigned NOT NULL default 0, 
	visible tinyint(1) unsigned NOT NULL default 0, 
	outputClass varchar(255) NOT NULL default '',
	searchable tinyint(1) unsigned NOT NULL default 0,
	showOrder int(10) unsigned NOT NULL default 0,
	disabled tinyint(1) unsigned NOT NULL default 0,
	permissions TEXT NULL,
	options TEXT NULL,
	additionalData MEDIUMTEXT NULL,
	PRIMARY KEY (optionID),
	UNIQUE KEY (optionName, packageID),
	KEY (categoryName)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_user_option_value;
CREATE TABLE wcf1_user_option_value (
	userID int(10) unsigned NOT NULL default 0,
	PRIMARY KEY (userID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/* +++ default inserts +++ */
-- default user groups
INSERT INTO	wcf1_group
		(groupID, groupName, groupType)
VALUES	(1, 'Everyone', 1),
		(2, 'Guests', 2),
		(3, 'Users', 3),
		(4, 'Administrators', 4),
		(5, 'Moderators', 4),
		(6, 'Super Moderators', 4);
		
-- default user group options
INSERT INTO	wcf1_group_option
		(optionID, optionName, categoryName, optionType, defaultValue, showOrder)
VALUES		(1, 'admin.general.canUseAcp', 'admin.general', 'boolean', '0', 1),
		(2, 'admin.system.package.canInstallPackage', 'admin.system.package', 'boolean', '0', 1);
		
-- default user group option values
INSERT INTO	wcf1_group_option_value
		(groupID, optionID, optionValue)
VALUES	(1, 1, '0'), (1, 2, '0'), -- Everyone
		(2, 1, '0'), (2, 2, '0'), -- Guests
		(3, 1, '0'), (3, 2, '0'), -- Users
		(4, 1, '1'), (4, 2, '1'), -- Administrators
		(5, 1, '0'), (5, 2, '0'), -- Moderators
		(6, 1, '0'), (6, 2, '0'); -- Super Moderators
		
-- default update servers
INSERT INTO 	wcf1_package_update_server
		(packageUpdateServerID, server, status, statusUpdate, errorText, updatesFile, timestamp, htUsername, htPassword)
VALUES 		(1, 'http://update.woltlab.com', 'online', 1, NULL, 0, 1168257450, '', '');
INSERT INTO 	wcf1_package_update_server
		(packageUpdateServerID, server, status, statusUpdate, errorText, updatesFile, timestamp, htUsername, htPassword)
VALUES 		(2, 'http://update.woltlab.com/tempest/', 'online', 1, NULL, 0, 1168257450, '', '');
INSERT INTO 	wcf1_package_update_server
		(packageUpdateServerID, server, status, statusUpdate, errorText, updatesFile, timestamp, htUsername, htPassword)
VALUES 		(3, 'http://community.woltlab.com/packages/horizon/', 'online', 1, NULL, 0, 1168257450, '', '');
INSERT INTO 	wcf1_package_update_server
		(packageUpdateServerID, server, status, statusUpdate, errorText, updatesFile, timestamp, htUsername, htPassword)
VALUES 		(4, 'http://community.woltlab.com/packages/tempest/', 'online', 1, NULL, 0, 1168257450, '', '');

-- search engine spiders
INSERT INTO wcf1_spider (spiderID, spiderIdentifier, spiderName, spiderURL) VALUES 
(1, 'abcdatos', 'ABCdatos BotLink', 'http://www.robotstxt.org/wc/active/html/abcdatos.html'),
(2, 'abot/', 'abot', 'http://www.abot.com/'),
(3, 'accelatech rsscrawler', 'Accelatech', ''),
(4, 'accoona-ai-agent', 'Accoona', 'http://accoona.com/about/about_accoona.jsp'),
(5, 'aconon index', 'aconon Index  (raubfische.de)', ''),
(6, 'ahoy!', 'Ahoy!', 'http://www.robotstxt.org/wc/active/html/ahoythehomepagefinder.html'),
(7, 'alkalinebot', 'Alkaline', 'http://www.robotstxt.org/wc/active/html/Alkaline.html'),
(8, 'http://www.almaden.ibm.com/cs/crawler', 'Almaden Crawler', 'http://www.almaden.ibm.com/cs/crawler/'),
(9, 'emc spider', 'ananzi', ''),
(10, 'anthill', 'Anthill', 'http://www.robotstxt.org/wc/active/html/anthill.html'),
(11, 'aport', 'Aport', 'http://www.aport.ru/'),
(12, 'applesyndication', 'Apple', ''),
(13, 'arachnophilia', 'Arachnophilia', 'http://www.robotstxt.org/wc/active/html/arachnophilia.html'),
(14, 'araneo', 'Araneo', 'http://www.robotstxt.org/wc/active/html/araneo.html'),
(15, 'architextspider', 'ArchitextSpider', 'http://www.robotstxt.org/wc/active/html/architext.html'),
(16, 'arks/1.0', 'arks', 'http://www.robotstxt.org/wc/active/html/arks.html'),
(17, 'aspider', 'ASpider', 'http://www.robotstxt.org/wc/active/html/aspider.html'),
(18, 'atn_worldwide', 'ATN Worldwide', 'http://www.robotstxt.org/wc/active/html/atn.txt.html'),
(19, 'atomz', 'Atomz.com', 'http://www.robotstxt.org/wc/active/html/atomz.html'),
(20, 'auresys', 'AURESYS', 'http://www.robotstxt.org/wc/active/html/auresys.html'),
(21, 'backrub', 'BackRub', 'http://www.robotstxt.org/wc/active/html/backrub.html'),
(22, 'baiduspider', 'Baiduspider', 'http://www.baidu.com/search/spider.htm'),
(23, 'bbot', 'BBot', 'http://www.robotstxt.org/wc/active/html/bbot.html'),
(24, 'becomebot', 'BecomeBot', 'http://www.become.com/webmasters.html'),
(25, 'big brother', 'Big Brother', 'http://www.robotstxt.org/wc/active/html/bigbrother.html'),
(26, 'bigmirspider', 'Bigmir', 'http://www.bigmir.net/'),
(27, 'bitacle bot', 'Bitacle', 'http://bitacle.org/'),
(28, 'biz360 spider', 'Biz', 'http://www.biz360.com'),
(29, 'bjaaland', 'Bjaaland', 'http://www.robotstxt.org/wc/active/html/bjaaland.html'),
(30, 'blackwidow', 'BlackWidow', 'http://www.robotstxt.org/wc/active/html/blackwidow.html'),
(31, 'blogcrawler by xango', 'BlogCrawler', ''),
(32, 'blogdb', 'BlogDb', 'http://blogdb.jp'),
(33, 'blog search engine by blogfan.org', 'BlogFan', 'http://www.blogfan.org'),
(34, 'bloglines', 'Bloglies', 'http://www.bloglines.com'),
(35, 'blogpulse (isspider-3.0)', 'BlogPulse', ''),
(36, 'blogsearch', 'BlogSearch', 'http://www.icerocket.com'),
(37, 'blogsnowbot', 'BlogsNow', 'http://www.blogsnow.com/'),
(38, 'blogstreetbot', 'BlogStreetBot', 'http://www.blogstreet.com/'),
(39, 'bulkfeeds', 'BlogStreetBot', 'http://bulkfeeds.net'),
(40, 'boardpulse', 'BoardPulse', 'http://www.boardpulse.com/'),
(41, 'boardreader', 'BoardReader', 'http://www.boardreader.com/aboutus.asp'),
(42, 'boardviewer', 'BoardViewer', 'http://www.boardviewer.com/'),
(43, 'boitho.com-robot', 'Boitho', 'http://www.boitho.com/bot.html'),
(44, 'borg-bot', 'Borg-Bot', 'http://www.robotstxt.org/wc/active/html/borg-bot.html'),
(45, 'bspider', 'BSpider', 'http://www.robotstxt.org/wc/active/html/bspider.html'),
(46, 'cactvs chemistry spider', 'CACTVS Chemistry', ''),
(47, 'calif', 'Calif', ''),
(48, 'carp/3.6evolution', 'CaRP', 'http://www.biz360.com'),
(49, 'checkbot', 'Checkbot', ''),
(50, 'christcrawler.com', 'ChristCrawler.com', ''),
(51, 'www.cienciaficcion.net', 'cIeNcIaFiCcIoN.nEt', ''),
(52, 'cipinetbot', 'Cipinet', 'http://www.cipinet.com/bot.html'),
(53, 'cjnetworkquality', 'CJNetworkQuality', 'http://www.cj.com/networkquality/'),
(54, 'cmc/0.01', 'CMC/0.01', ''),
(55, 'coldfusion', 'ColdFusion', ''),
(56, 'combine', 'Combine System', ''),
(57, 'crawler (cometsearch@cometsystems.com)', 'cometsystems.com', ''),
(58, 'computingsite robi/1.0', 'ComputingSite Robi/1.0', ''),
(59, 'conceptbot', 'Conceptbot', ''),
(60, 'cooby.de crawler', 'Cooby.de Crawler', ''),
(61, 'coolbot', 'CoolBot', ''),
(62, 'cusco', 'Cusco', ''),
(63, 'cyberspyder', 'CyberSpyder', ''),
(64, 'daypopbot', 'daypop', ''),
(65, 'desertrealm.com', 'Desert Realm', ''),
(66, 'deweb', 'DeWeb(c)', ''),
(67, 'die blinde kuh', 'Die Blinde Kuh', 'http://www.robotstxt.org/wc/active/html/blindekuh.html'),
(68, 'dienstspider', 'DienstSpider', ''),
(69, 'digger/1.0 jdk/1.3.0', 'Digger', ''),
(70, 'digimarc webreader', 'Digimarc MarcSpider', ''),
(71, 'digimarc cgireader', 'Digimarc Marcspider/CGI', ''),
(72, 'diibot', 'Digital Integrity Robot', ''),
(73, 'grabber', 'Direct Hit Grabber', ''),
(74, 'dnabot/1.0', 'DNAbot', ''),
(75, 'dragonbot/1.0 libwww/5.0', 'DragonBot', ''),
(76, 'dwcp/2.0', 'DWCP (Dridus'' Web Cataloging Project)', ''),
(77, 'e-societyrobot', 'e-Society', 'http://www.yama.info.waseda.ac.jp/~yamana/es/index_eng.htm'),
(78, 'exactseek-pagereaper', 'eaxactseek-page', ''),
(79, 'ebiness/0.01a', 'EbiNess', ''),
(80, 'edgeio-retriever', 'Edgeio', 'http://www.edgeio.com'),
(81, 'eit-link-verifier-robot/0.2', 'EIT Link Verifier Robot', ''),
(82, 'elfinbot', 'ELFINBOT', ''),
(83, 'emacs-w3/v[0-9\\.]+', 'Emacs-w3 Search Engine', ''),
(84, 'esther', 'Esther', ''),
(85, 'euripbot/', 'EuripBot', ''),
(86, 'evliya celebi', 'Evliya Celebi', ''),
(87, 'exactseek_spider', 'ExactSeek_Spider', 'http://www.askjeevs.com'),
(88, 'ng/2.0', 'ExaLead', 'http://botspotter.net/bs-389.html'),
(89, 'exabot', 'ExaLead Beta', 'http://beta.exalead.com/search/C=0/2p=Help.7'),
(90, 'fast-webcrawler', 'FAST / AlltheWeb', 'http://help.yahoo.com/help/us/ysearch/slurp/index.html'),
(91, 'fastcrawler', 'FastCrawler', ''),
(92, 'feed24.com', 'Feed24', 'http://www.feed24.com'),
(93, 'feedblitz', 'FeedBlitz', 'http://www.feedblitz.com'),
(94, 'feedburner', 'FeedBurner', 'http://www.FeedBurner.com'),
(95, 'feedfetcher-google', 'FeedFetcher-Google', 'http://www.google.com/feedfetcher.html'),
(96, 'universalfeedparser', 'FeedParser', 'http://www.feedparser.org'),
(97, 'feedster crawler', 'Feedster', 'http://www.feedstermedia.com/'),
(98, 'fehlstart superspider', 'FEHLSTART', ''),
(99, 'felixide', 'Felix IDE', ''),
(100, 'esirover', 'FetchRover', ''),
(101, 'fido', 'fido', ''),
(102, 'findlinks', 'FindLinks', 'http://wortschatz.uni-leipzig.de/findlinks/'),
(103, 'findorybot', 'Findroy', 'http://www.findory.com'),
(104, 'fish-search-robot', 'Fish search', ''),
(105, 'mozilla/4.0 (compatible: fdse robot)', 'Fluid Dynamics', ''),
(106, 'fouineur.9bit.qc.ca', 'Fouineur', ''),
(107, 'freecrawl', 'Freecrawl', ''),
(108, 'funnelweb', 'FunnelWeb', ''),
(109, 'gaisbot', 'Gais', 'http://gais.cs.ccu.edu.tw/robot.php'),
(110, 'gamekitbot', 'GAMEKIT', 'http://www.uchoose.de/crawler/gamekitbot/'),
(111, 'gammaspider', 'gammaSpider', ''),
(112, 'gazz', 'gazz', ''),
(113, 'gcreep', 'GCreep', ''),
(114, 'geniebot', 'genieBot', 'http://64.5.245.11/faq/faq.html'),
(115, 'geourl', 'GeoURL', 'http://geourl.org/bot.html'),
(116, 'getterroboplus', 'GetterroboPlus Puu', ''),
(117, 'geturl.rexx', 'GetURL', ''),
(118, 'gigabot', 'Gigabot', 'http://www.gigablast.com/spider.html'),
(119, 'girafabot', 'Girafabot', 'http://www.girafa.com/'),
(120, 'goku', 'Goku', 'http://goku.ru/bot.htm; bot@goku.ru'),
(121, 'golem', 'Golem', ''),
(122, 'gonzo', 'Gonzo', ''),
(123, 'googlebot/', 'Google', 'http://www.google.com/bot.html'),
(124, 'mediapartners-google', 'Google AdSense', 'https://www.google.com/adsense/faq'),
(125, 'googlebot-image', 'Googlebot-Image', 'http://www.googlebot.com/bot.html'),
(126, 'gpostbot', 'Gpostbot', 'http://www.gpost.info/help.php?c=bot'),
(127, 'griffon', 'Griffon', ''),
(128, 'gromit', 'Gromit', ''),
(129, 'http://grub.org', 'Grub Client', ''),
(130, 'gulper web bot', 'Gulper Bot', ''),
(131, 'h?m?h?kki', 'H?m?h?kki', 'http://www.robotstxt.org/wc/active/html/finnish.html'),
(132, 'havindex', 'havIndex', ''),
(133, 'heinrichdermiragorobot', 'HeinrichderMiragoRobot', ''),
(134, 'henrythemiragorobot', 'HenryTheMiragoRobot', ''),
(135, 'heritrix', 'Heritrix', 'http://www.worio.com'),
(136, 'hku www robot', 'HKU WWW Octopus', ''),
(137, 'holycowdude', 'HolyCowDude', 'http://www.holycowdude.com/spider.htm'),
(138, 'hometown', 'Hometown', ''),
(139, 'htdig', 'ht://Dig', ''),
(140, 'aitcsrobot', 'HTML Index', ''),
(141, 'htmlgobble', 'HTMLgobble', ''),
(142, 'i robot', 'I, Robot', ''),
(143, 'iajabot', 'iajaBot', ''),
(144, 'ibm_planetwide', 'IBM_Planetwide', ''),
(145, '+http://www.icerocket.com/', 'IceRocket', 'http://www.icerocket.com/'),
(146, 'ichiro', 'ichiro', ''),
(147, 'iltrovatore-setaccio', 'IlTrovatore-Setaccio', 'http://www.iltrovatore.it/aiuto/faq.html'),
(148, 'image.kapsi.net', 'image.kapsi.net', ''),
(149, 'mozilla 3.01 pbwf (win95)', 'Imagelock', ''),
(150, 'incywincy', 'IncyWincy', ''),
(151, 'informant', 'Informant', ''),
(152, 'infoseek robot', 'InfoSeek Robot 1.0', ''),
(153, 'infoseek sidewinder', 'Infoseek Sidewinder', ''),
(154, 'infospiders', 'InfoSpiders', ''),
(155, 'ingrid', 'Ingrid', ''),
(156, 'slurp@inktomi', 'Inktomi', ''),
(157, 'insitor', 'Insitor', 'http://www.insitor.de/'),
(158, 'inspectorwww', 'Inspector Web', ''),
(159, 'iagent', 'IntelliAgent', ''),
(160, 'intelliseek', 'Intelliseek', 'http://www.intelliseek.com/'),
(161, 'internet cruiser robot', 'Internet Cruiser', ''),
(162, 'internetseer', 'Internet Seer', ''),
(163, 'sharp-info-agent', 'Internet Shinchakubin', ''),
(164, 'internetlinkagent', 'InternetLinkAgent', ''),
(165, 'irlbot', 'IRL Crawler', 'http://irl.cs.tamu.edu/crawler'),
(166, 'iron33', 'Iron33', ''),
(167, 'israelisearch', 'Israeli-search', ''),
(168, 'itchbot', 'itch', 'http://www.itch.com/infoforwebmasters.html'),
(169, 'javabee', 'JavaBee', ''),
(170, 'jbot', 'JBot', ''),
(171, 'jcrawler', 'JCrawler', ''),
(172, 'jetbot', 'JetEye', 'http://www.jeteye.com/jetbot.html'),
(173, 'jobo', 'JoBo', ''),
(174, 'jobot', 'Jobot', ''),
(175, 'joebot', 'JoeBot', ''),
(176, 'jumpstation', 'JumpStation', ''),
(177, 'katipo', 'Katipo', ''),
(178, 'kdd-explorer', 'KDD-Explorer', ''),
(179, 'kit-fireball', 'KIT-Fireball', ''),
(180, 'ko_yappo_robot', 'KO_Yappo_Robot', ''),
(181, 'labelgrab', 'LabelGrabber', ''),
(182, 'larbin', 'larbin', ''),
(183, 'legs', 'legs', ''),
(184, 'linkscan server', 'LinkScan', ''),
(185, 'linkwalker', 'LinkWalker', ''),
(186, 'livedoorcheckers/', 'livedoorCheckers', ''),
(187, 'lockon', 'Lockon', ''),
(188, 'logo.gif crawler', 'logo.gif', ''),
(189, 'lycos', 'Lycos', ''),
(190, 'magpie', 'Magpie', ''),
(191, 'mj12bot', 'Majestics MJ12bot', ''),
(192, 'mammoth', 'Mammoth', 'http://www.sli-systems.com'),
(193, 'marvin', 'Marvin', ''),
(194, 'marvin/infoseek', 'marvin/infoseek', ''),
(195, 'm/3.8', 'Mattie', ''),
(196, 'mediafox', 'MediaFox', ''),
(197, 'mercator', 'Mercator', 'http://research.compaq.com/SRC/mercator/'),
(198, 'merzscope', 'MerzScope', ''),
(199, 'metaspider', 'META', 'http://www.meta.com.ua/'),
(200, 'metager-linkchecker', 'MetaGer', ''),
(201, 'mindcrawler', 'MindCrawler', ''),
(202, 'miva', 'Miva', ''),
(203, 'udmsearch', 'mnoGoSearch', ''),
(204, 'moget', 'moget', ''),
(205, 'momspider', 'MOMspider', ''),
(206, 'monster', 'Monster', ''),
(207, 'moreoverbot', 'Moreover', 'http://www.moreover.com'),
(208, 'motor', 'Motor', ''),
(209, 'muscatferret', 'Muscat Ferret', ''),
(210, 'mwdsearch', 'Mwd.Search', ''),
(211, 'npbot', 'NameProtect', ''),
(212, 'naverbot', 'NaverBot', 'http://www.spidermatic.com/en/robot-spider/20'),
(213, 'nec-meshexplorer', 'NEC-MeshExplorer', ''),
(214, 'nederland.zoek', 'Nederland.zoek', ''),
(215, 'netcarta cyberpilot pro', 'NetCarta WebMap', ''),
(216, 'netcraft', 'Netcraft Web Server Survey', 'http://news.netcraft.com/'),
(217, 'netmechanic', 'NetMechanic', ''),
(218, 'netscoop', 'NetScoop', ''),
(219, 'newscan-online', 'newscan-online', ''),
(220, 'nextgensearchbot 1', 'NextGenSearchBot', 'http://www.zoominfo.com/NextGenSearchBot'),
(221, 'nhsewalker', 'NHSE Web Forager', ''),
(222, 'nif', 'NIF', 'http://www.newsisfree.com/robot.php users'),
(223, 'nimblecrawler', 'NimbleCrawler', 'http://www.healthline.com/aboutus.jsp'),
(224, 'nomad', 'Nomad', ''),
(225, 'norbert the spider', 'Norbert', 'http://www.Burf.com'),
(226, 'gulliver', 'Northern Light', ''),
(227, 'explorersearch', 'nzexplorer', ''),
(228, 'occam', 'Occam', ''),
(229, 'ocelli', 'Ocelli', 'http://www.globalspec.com/Ocelli'),
(230, 'online24-bot', 'Online24-Bot', ''),
(231, 'openbot', 'Openbot', 'http://www.openfind.com.tw/robot.html'),
(232, 'openfind', 'Openfind data gatherer', ''),
(233, 'orbsearch', 'Orb Search', ''),
(234, 'packrat', 'Pack Rat', ''),
(235, 'pageboy', 'PageBoy', ''),
(236, 'parasite', 'ParaSite', ''),
(237, 'patric', 'Patric', ''),
(238, 'pegasus', 'pegasus', ''),
(239, 'perlcrawler/1.0 xavatoria/2.0', 'PerlCrawler 1.0', ''),
(240, 'pgp-ka', 'PGP Key Agent', ''),
(241, 'duppies', 'Phantom', ''),
(242, 'phpdig', 'PhpDig', ''),
(243, 'piltdownman', 'PiltdownMan', ''),
(244, 'pimptrain''s robot', 'Pimptrain.com''s', ''),
(245, 'pingalink', 'PingALink', ''),
(246, 'pioneer', 'Pioneer', ''),
(247, 'pluckfeedcrawler', 'Pluck', 'http://www.pluck.com'),
(248, 'plumtreewebaccessor', 'PlumtreeWebAccessor', ''),
(249, 'podnova', 'PodNova', 'http://www.podnova.com'),
(250, 'pompos', 'Pompos', 'http://dir.com/pompos.html'),
(251, 'poppi', 'Poppi', ''),
(252, 'gestalticonoclast', 'Popular Iconoclast', ''),
(253, 'portaljuice.com', 'Portal Juice', ''),
(254, 'portalbspider', 'PortalB Spider', ''),
(255, 'www.kolinka.com', 'Project Kolinka Forum Search', 'http://www.kolinka.com/'),
(256, 'psbot', 'psbot', ''),
(257, 'qango.com web directory', 'Qango', 'http://www.qango.com'),
(258, 'stackrambler', 'Rambler', 'http://www.rambler.ru/'),
(259, 'raven', 'Raven Search', ''),
(260, 'resume robot', 'Resume Robot', ''),
(261, 'road runner: imagescape robot', 'Road Runner: The ImageScape Robot', ''),
(262, 'rhcs', 'RoadHouse Crawling System', ''),
(263, 'robbie', 'Robbie the Robot', ''),
(264, 'robocrawl', 'RoboCrawl', ''),
(265, 'robofox', 'RoboFox', ''),
(266, 'robot du crim 1.0a', 'Robot Francoroute', ''),
(267, 'robozilla', 'Robozilla', ''),
(268, 'roverbot', 'Roverbot', ''),
(269, 'rss-spider', 'RSS Feed Seeker', 'http://www.rss-spider.com/fsb.php'),
(270, 'rules', 'RuLeS', ''),
(271, 'safetynet robot', 'SafetyNet', ''),
(272, 'sbider', 'SBIder.', 'http://www.sitesell.com/sbider.html'),
(273, 'scharia', 'Scharia', ''),
(274, 'science-index', 'Science-Index', ''),
(275, 'scooter', 'Scooter', ''),
(276, 'searchnz', 'SearchNZ', 'http://www.searchnz.co.nz/'),
(277, 'searchprocess', 'SearchProcess', ''),
(278, 'seekbot', 'Seekbot', 'http://www.seekbot.net/bot.html'),
(279, 'senrigan', 'Senrigan', ''),
(280, 'sensis web crawler', 'Sensis Web Crawler', 'http://www.sensis.com.au/help.do'),
(281, 'sg-scout', 'SG-Scout', ''),
(282, 'shagseeker', 'ShagSeeker', ''),
(283, 'shai''hulud', 'Shai''Hulud', ''),
(284, 'simbot/1.0', 'Simmany Robot Ver1.0', ''),
(285, 'ssearcher100', 'Site Searcher', ''),
(286, 'site valet', 'Site Valet', ''),
(287, 'http://www.site-list.net', 'Site-List', 'http://www.site-list.net'),
(288, 'sitetech-rover', 'SiteTech-Rover', ''),
(289, '+sitidi.net/sitidibot/', 'SitiDi.net/SitiDiBot', ''),
(290, 'awapclient', 'Skymob.com', ''),
(291, 'slcrawler', 'SLCrawler', ''),
(292, 'sleek spider', 'Sleek', ''),
(293, 'esismartspider', 'Smart Spider', ''),
(294, 'snooper', 'Snooper', ''),
(295, 'sohu-search', 'sohu-search', ''),
(296, 'solbot', 'Solbot', ''),
(297, 'speedy spider', 'Speedy Spider', ''),
(298, 'sphere scout', 'Sphere', ''),
(299, 'sphider2', 'Sphider', ''),
(300, 'spiderbot', 'SpiderBot', ''),
(301, 'spiderline', 'Spiderline Crawler', ''),
(302, 'spiderman', 'SpiderMan', ''),
(303, 'spiderview', 'SpiderView(tm)', ''),
(304, 'mouse.house', 'spider_monkey', ''),
(305, 'suke', 'Suke', ''),
(306, 'suntek', 'suntek search engine', ''),
(307, 'szukacz', 'Szukacz', 'http://www.szukacz.pl/html/RobotEnglishVersion.html'),
(308, 't-h-u-n-d-e-r-s-t-o-n-e', 'T-H-U-N-D-E-R-S-T-O-N-E', ''),
(309, 'black widow', 'TACH Black Widow', ''),
(310, 'tarantula', 'Tarantula', ''),
(311, 'tarspider', 'tarspider', ''),
(312, 'dlw3robot', 'Tcl W3 Robot', ''),
(313, 'techbot', 'TechBOT', ''),
(314, 'technoratibot', 'Technorati', 'http://technorati.com/about/'),
(315, 'templeton', 'Templeton', ''),
(316, 'teoma', 'Teoma/Ask Jeeves', 'http://sp.teoma.com/docs/teoma/about/'),
(317, 'jubiirobot', 'The Jubii', ''),
(318, 'northstar', 'The NorthStar Robot', ''),
(319, 'w3index', 'The NWI Robot', ''),
(320, 'peregrinator-mathematics', 'The Peregrinator', ''),
(321, 'thumbshots-de-bot', 'thumbshots-de-Bot', ''),
(322, 'titan', 'TITAN', ''),
(323, 'titin', 'TitIn', ''),
(324, 'tlspider', 'TLSpider', ''),
(325, 'tmcrawler', 'TMCrawler', ''),
(326, 'slysearch', 'Turnitin.com', 'http://www.turnitin.com/static/products_services/search_engines.html'),
(327, 'turnitinbot/', 'TurnitinBot', ''),
(328, 'turtlescanner', 'Turtle', 'http://www.turtle.ru/'),
(329, 'twiceler', 'Twiceler', 'http://www.cuill.com/twiceler/robot.html'),
(330, 'ucsd-crawler', 'UCSD Crawl', ''),
(331, 'umbc-memeta-bot', 'UMBC', ''),
(332, 'unpartisan', 'Unpartisan', 'http://www.unpartisan.com'),
(333, 'urlck', 'URL Check', ''),
(334, 'url spider pro', 'URL Spider Pro', ''),
(335, 'valkyrie', 'Valkyrie', ''),
(336, 'verticrawl', 'Verticrawl', ''),
(337, 'victoria', 'Victoria', ''),
(338, 'vision-search', 'vision-search', ''),
(339, 'voilabot', 'VoilaBot', 'http://www.voila.com/'),
(340, 'voyager', 'Voyager', ''),
(341, 'vwbot_k', 'VWbot', ''),
(342, 'w3m2', 'W3M2', ''),
(343, 'w3mir', 'w3mir', ''),
(344, 'w@pspider', 'w@pSpider', ''),
(345, 'appie', 'Walhello appie', 'http://www.robotstxt.org/wc/active/html/appie.html'),
(346, 'crawlpaper', 'WallPaper', ''),
(347, 'root', 'Web Core / Roots', ''),
(348, 'webmoose', 'Web Moose', ''),
(349, 'webbandit', 'WebBandit', ''),
(350, 'webcatcher', 'WebCatcher', ''),
(351, 'webclipping', 'Webclipping', ''),
(352, 'webcopy', 'WebCopy', ''),
(353, 'webfetcher', 'webfetcher', ''),
(354, 'weblayers', 'weblayers', ''),
(355, 'weblinker', 'WebLinker', ''),
(356, 'wlm', 'Weblog Monitor', ''),
(357, 'webquest', 'WebQuest', ''),
(358, 'webreaper', 'WebReaper', ''),
(359, 'webs@recruit.co.jp', 'webs', ''),
(360, 'websearchbench', 'WebSearchBench', 'http://websearchbench.cs.uni-dortmund.de/'),
(361, 'wolp', 'WebStolperer', ''),
(362, 'webvac', 'WebVac', ''),
(363, 'webwalk', 'webwalk', ''),
(364, 'webwalker', 'WebWalker', ''),
(365, 'webwatch', 'WebWatch', ''),
(366, 'whatuseek_winona', 'whatUseek Winona', ''),
(367, 'surveybot', 'Whois Source', 'http://www.whois.sc/info/webmasters/surveybot.html'),
(368, 'hazel''s ferret web hopper', 'Wild Ferret Web Hopper', ''),
(369, 'winhttp', 'WinHTTP', ''),
(370, 'wired-digital-newsbot', 'Wired Digital', ''),
(371, 'zyborg', 'WiseNut', ''),
(372, 'omniexplorer_bot', 'WorldIndexer', 'http://www.omni-explorer.com'),
(373, 'wwwc', 'WWWC', ''),
(374, 'wwweasel robot', 'WWWeasel Robot', ''),
(375, 'wwwster', 'wwwster', ''),
(376, 'wwwwanderer', 'WWWWanderer', ''),
(377, 'tecomac-crawler', 'X-Crawler', ''),
(378, 'xget', 'XGET', ''),
(379, 'cosmos', 'XYLEME Robot', ''),
(380, 'yacybot', 'YaCy-Bot', 'http://yacy.net/yacy/bot.html'),
(381, 'yahooysmcm', 'Yahoo Publisher Network', 'http://publisher.yahoo.com/'),
(382, 'yahoo-blogs', 'Yahoo-Blogs', 'http://help.yahoo.com/help/us/ysearch/crawling/crawling-02.html'),
(383, 'yahoo-verticalcrawler', 'Yahoo-VerticalCrawler', ''),
(384, 'yahoofeedseeker', 'YahooFeedSeeker', 'http://my.yahoo.com/s/publishers.html'),
(385, 'yandex', 'Yandex', 'http://www.yandex.ru/'),
(386, 'zeus', 'Zeus Internet Marketing', 'http://www.cyber-robotics.com/'),
(387, 'http://www.zorkk.com', 'Zork', 'http://www.zorkk.com'),
(388, 'snapbot', 'Snapbot', 'http://www.snap.com/'),
(389, 'msrbot', 'MSRBOT', 'http://research.microsoft.com/research/sv/msrbot/'),
(390, 'ia_archiver', 'Archive.org', 'http://www.archive.org/about/exclude.php'),
(391, 'msnbot', 'MSNBot', 'http://search.msn.com/msnbot.htm'),
(392, 'yahoo! slurp', 'Yahoo! Slurp', 'http://help.yahoo.com/help/us/ysearch/slurp'),
(393, 'jobs.de', 'Jobs.de', 'http://www.jobs.de/'),
(394, 'discobot', 'Discovery', 'http://discoveryengine.com/discobot.html');