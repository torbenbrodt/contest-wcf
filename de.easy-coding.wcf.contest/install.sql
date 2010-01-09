DROP TABLE IF EXISTS wcf1_contest;
CREATE TABLE wcf1_contest (
	contestID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	userID INT(10) NOT NULL,
	groupID INT(10) NOT NULL,
	subject VARCHAR(255) NOT NULL DEFAULT '',
	message TEXT NULL,
	time INT(10) NOT NULL DEFAULT 0,
	start INT(10) NOT NULL DEFAULT 0,
	finish INT(10) NOT NULL DEFAULT 0,
	attachments SMALLINT(5) NOT NULL DEFAULT 0,
	enableSmilies TINYINT(1) NOT NULL DEFAULT 1,
	enableHtml TINYINT(1) NOT NULL DEFAULT 0,
	enableBBCodes TINYINT(1) NOT NULL DEFAULT 1,
	solutions SMALLINT(5) NOT NULL DEFAULT 0,
	jurytalks SMALLINT(5) NOT NULL DEFAULT 0,
	state ENUM('private', 'waiting', 'reviewed', 'scheduled') NOT NULL DEFAULT 'private',
	FULLTEXT KEY (subject, message),
	KEY (userID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_contest_solution;
CREATE TABLE wcf1_contest_solution (
	solutionID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	contestID INT(10) NOT NULL,
	userID INT(10) NOT NULL DEFAULT 0,
	username VARCHAR(255) NOT NULL DEFAULT '',
	solution TEXT NULL,
	time INT(10) NOT NULL DEFAULT 0,
	attachments SMALLINT(5) NOT NULL DEFAULT 0,
	KEY (contestID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_contest_jurytalk;
CREATE TABLE wcf1_contest_jurytalk (
	jurytalkID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	contestID INT(10) NOT NULL,
	userID INT(10) NOT NULL DEFAULT 0,
	username VARCHAR(255) NOT NULL DEFAULT '',
	message TEXT NULL,
	time INT(10) NOT NULL DEFAULT 0,
	KEY (contestID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_contest_class;
CREATE TABLE wcf1_contest_class (
	classID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_contest_to_class;
CREATE TABLE wcf1_contest_to_class (
	classID INT(10) NOT NULL DEFAULT 0,
	contestID INT(10) NOT NULL DEFAULT 0,
	PRIMARY KEY (classID, contestID),
	KEY (contestID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_contest_jury;
CREATE TABLE wcf1_contest_jury (
	juryID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	contestID INT(10) NOT NULL DEFAULT 0,
	userID INT(10) NOT NULL DEFAULT 0,
	groupID INT(10) NOT NULL DEFAULT 0,
	state ENUM('invited', 'accepted', 'declined', 'left') NOT NULL DEFAULT 'invited',
	UNIQUE KEY (userID, contestID),
	UNIQUE KEY (groupID, contestID),
	KEY (contestID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_contest_participant;
CREATE TABLE wcf1_contest_participant (
	participantID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	contestID INT(10) NOT NULL DEFAULT 0,
	userID INT(10) NOT NULL DEFAULT 0,
	groupID INT(10) NOT NULL DEFAULT 0,
	state ENUM('invited', 'accepted', 'declined', 'left') NOT NULL DEFAULT 'invited',
	UNIQUE KEY (userID, contestID),
	UNIQUE KEY (groupID, contestID),
	KEY (contestID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_contest_sponsor;
CREATE TABLE wcf1_contest_sponsor (
	sponsorID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	contestID INT(10) NOT NULL DEFAULT 0,
	userID INT(10) NOT NULL DEFAULT 0,
	groupID INT(10) NOT NULL DEFAULT 0,
	state ENUM('unknown', 'accepted', 'declined') NOT NULL DEFAULT 'unknown',
	UNIQUE KEY (userID, contestID),
	UNIQUE KEY (groupID, contestID),
	KEY (contestID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_contest_price;
CREATE TABLE wcf1_contest_price (
	priceID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	contestID INT(10) NOT NULL,
	sponsorID INT(10) NOT NULL DEFAULT 0,
	subject VARCHAR(255) NOT NULL DEFAULT '',
	message TEXT NULL,
	time INT(10) NOT NULL DEFAULT 0,
	position SMALLINT(5) NOT NULL DEFAULT 0,
	KEY (contestID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


INSERT INTO wcf1_contest_class (title) VALUES 
	('wcf.user.contest.entry.classes.beginner'),
	('wcf.user.contest.entry.classes.expert');
