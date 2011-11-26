DROP TABLE IF EXISTS wcf1_contest_interaction_coupon;
CREATE TABLE IF NOT EXISTS wcf1_contest_interaction_coupon (
	contestID int(10) unsigned NOT NULL DEFAULT '0',
	couponCode varchar(64) NOT NULL DEFAULT '',
	fromTime INT(10) NOT NULL DEFAULT 0,
	untilTime INT(10) NOT NULL DEFAULT 0,
	score int(11) NOT NULL DEFAULT '0',
	state ENUM('single') NOT NULL DEFAULT '',
	INDEX contestID (contestID)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
