DROP TABLE IF EXISTS wcf1_contest_coupon;
CREATE TABLE IF NOT EXISTS wcf1_contest_coupon (
	couponID int(10) unsigned NOT NULL AUTO_INCREMENT,
	contestID int(10) unsigned NOT NULL DEFAULT '0',
	couponCode varchar(64) NOT NULL DEFAULT '',
	fromTime INT(10) NOT NULL DEFAULT 0,
	untilTime INT(10) NOT NULL DEFAULT 0,
	score int(11) NOT NULL DEFAULT '0',
	state ENUM('single') NOT NULL DEFAULT '',
	INDEX contestID (contestID)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_contest_coupon_participant;
CREATE TABLE IF NOT EXISTS wcf1_contest_coupon_participant (
	couponID int(10) unsigned NOT NULL DEFAULT '0',
	participantID int(10) unsigned NOT NULL DEFAULT '0',
	INDEX contestID (contestID),
	INDEX participantID (participantID)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
