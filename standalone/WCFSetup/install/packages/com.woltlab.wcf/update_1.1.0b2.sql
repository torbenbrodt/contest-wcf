@ALTER TABLE wcf1_acp_session CHANGE ipAddress ipAddress varchar(39) NOT NULL default '';
@ALTER TABLE wcf1_acp_session_log CHANGE ipAddress ipAddress varchar(39) NOT NULL default '';
@ALTER TABLE wcf1_acp_session_access_log CHANGE ipAddress ipAddress varchar(39) NOT NULL default '';
@ALTER TABLE wcf1_session CHANGE ipAddress ipAddress varchar(39) NOT NULL default '';