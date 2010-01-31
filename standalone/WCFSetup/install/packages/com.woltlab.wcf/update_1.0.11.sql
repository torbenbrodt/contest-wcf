-- new tables
DROP TABLE IF EXISTS wcf1_acp_session_data;
CREATE TABLE IF NOT EXISTS wcf1_acp_session_data (
	sessionID char(40) NOT NULL default '',
	userData mediumtext NULL,
	sessionVariables mediumtext NULL,
	PRIMARY KEY (sessionID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- copy session data
@INSERT INTO	wcf1_acp_session_data
SELECT			sessionID, userData, sessionVariables
FROM			wcf1_acp_session;

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

DROP TABLE IF EXISTS wcf1_cache_resource;
CREATE TABLE wcf1_cache_resource (
	cacheResource VARCHAR(255) NOT NULL PRIMARY KEY
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_session_data;
CREATE TABLE wcf1_session_data (
	sessionID char(40) NOT NULL default '',
	userData mediumtext NULL,
	sessionVariables mediumtext NULL,
	PRIMARY KEY (sessionID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- altered tables
@ALTER TABLE wcf1_acp_session DROP userData;
@ALTER TABLE wcf1_acp_session DROP sessionVariables;
@ALTER TABLE wcf1_acp_session ENGINE=MEMORY;
@ALTER TABLE wcf1_acp_session CHANGE ipAddress ipAddress varchar(39) NOT NULL default '';

@ALTER TABLE wcf1_session DROP userData;
@ALTER TABLE wcf1_session DROP sessionVariables;
@ALTER TABLE wcf1_session ENGINE=MEMORY;
@ALTER TABLE wcf1_session CHANGE ipAddress ipAddress varchar(39) NOT NULL default '';

@ALTER TABLE wcf1_acp_menu_item ADD options TEXT NULL;

@ALTER TABLE wcf1_event_listener ADD niceValue TINYINT(3) NOT NULL DEFAULT 0;

@ALTER TABLE wcf1_group_option_category ADD permissions TEXT NULL;
@ALTER TABLE wcf1_group_option_category ADD options TEXT NULL;

@ALTER TABLE wcf1_group_option ADD permissions TEXT NULL;
@ALTER TABLE wcf1_group_option ADD options TEXT NULL;
@ALTER TABLE wcf1_group_option ADD additionalData MEDIUMTEXT NULL;

@ALTER TABLE wcf1_language_item ADD languageHasCustomValue tinyint(1) NOT NULL default 0;
@ALTER TABLE wcf1_language_item ADD languageCustomItemValue mediumtext NULL;
@ALTER TABLE wcf1_language_item ADD languageUseCustomValue tinyint(1) NOT NULL default 0;
@ALTER TABLE wcf1_language_item ADD KEY (languageHasCustomValue);

@ALTER TABLE wcf1_option_category ADD permissions TEXT NULL;
@ALTER TABLE wcf1_option_category ADD options TEXT NULL;

@ALTER TABLE wcf1_option ADD permissions TEXT NULL;
@ALTER TABLE wcf1_option ADD options TEXT NULL;
@ALTER TABLE wcf1_option ADD additionalData MEDIUMTEXT NULL;

@ALTER TABLE wcf1_package ADD instanceName varchar(255) NOT NULL default '';
@ALTER TABLE wcf1_package ADD installDate INT(10) NOT NULL DEFAULT 0;
@ALTER TABLE wcf1_package ADD updateDate INT(10) NOT NULL DEFAULT 0;

@ALTER TABLE wcf1_template ADD obsolete TINYINT(1) NOT NULL DEFAULT 0;

@ALTER TABLE wcf1_user ADD KEY (registrationDate);

@ALTER TABLE wcf1_user_option_category ADD permissions TEXT NULL;
@ALTER TABLE wcf1_user_option_category ADD options TEXT NULL;

@ALTER TABLE wcf1_user_option ADD askDuringRegistration tinyint(1) unsigned NOT NULL default 0;
@ALTER TABLE wcf1_user_option ADD permissions TEXT NULL;
@ALTER TABLE wcf1_user_option ADD options TEXT NULL;
@ALTER TABLE wcf1_user_option ADD additionalData MEDIUMTEXT NULL;
@ALTER TABLE wcf1_user_option ADD KEY (categoryName);

-- add tempest update server
INSERT INTO 	wcf1_package_update_server
		(packageUpdateServerID, server, status, statusUpdate, errorText, updatesFile, timestamp, htUsername, htPassword)
VALUES 		(NULL, 'http://community.woltlab.com/packages/tempest/', 'online', 1, NULL, 0, 1168257450, '', '');

