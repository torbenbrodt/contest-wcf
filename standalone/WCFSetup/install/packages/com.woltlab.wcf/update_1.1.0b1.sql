-- add tempest update server
INSERT INTO 	wcf1_package_update_server
		(packageUpdateServerID, server, status, statusUpdate, errorText, updatesFile, timestamp, htUsername, htPassword)
VALUES 		(NULL, 'http://community.woltlab.com/tempest/', 'online', 1, NULL, 0, 1168257450, '', '');

@ALTER TABLE wcf1_acp_session CHANGE ipAddress ipAddress varchar(39) NOT NULL default '';
@ALTER TABLE wcf1_acp_session_log CHANGE ipAddress ipAddress varchar(39) NOT NULL default '';
@ALTER TABLE wcf1_acp_session_access_log CHANGE ipAddress ipAddress varchar(39) NOT NULL default '';
@ALTER TABLE wcf1_session CHANGE ipAddress ipAddress varchar(39) NOT NULL default '';