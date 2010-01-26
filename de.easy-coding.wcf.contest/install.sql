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
	comments SMALLINT(5) NOT NULL DEFAULT 0,
	prices SMALLINT(5) NOT NULL DEFAULT 0,
	participants SMALLINT(5) NOT NULL DEFAULT 0,
	jurytalks SMALLINT(5) NOT NULL DEFAULT 0,
	jurys SMALLINT(5) NOT NULL DEFAULT 0,
	sponsortalks SMALLINT(5) NOT NULL DEFAULT 0,
	state ENUM('private', 'waiting', 'reviewed', 'scheduled') NOT NULL DEFAULT 'private',
	FULLTEXT KEY (subject, message),
	KEY (userID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_contest_solution;
CREATE TABLE wcf1_contest_solution (
	solutionID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	contestID INT(10) NOT NULL,
	userID INT(10) NOT NULL DEFAULT 0,
	groupID INT(10) NOT NULL,
	message TEXT NULL,
	time INT(10) NOT NULL DEFAULT 0,
	state ENUM('unknown', 'accepted', 'declined') NOT NULL DEFAULT 'unknown',
	attachments SMALLINT(5) NOT NULL DEFAULT 0,
	KEY (contestID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_contest_comment;
CREATE TABLE wcf1_contest_comment (
	commentID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	contestID INT(10) NOT NULL,
	userID INT(10) NOT NULL DEFAULT 0,
	username VARCHAR(255) NOT NULL DEFAULT '',
	comment TEXT NULL,
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

DROP TABLE IF EXISTS wcf1_contest_sponsortalk;
CREATE TABLE wcf1_contest_sponsortalk (
	sponsortalkID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
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
	time INT(10) NOT NULL DEFAULT 0,
	state ENUM('invited', 'accepted', 'declined', 'left') NOT NULL DEFAULT 'invited',
	KEY (userID, contestID),
	KEY (groupID, contestID),
	KEY (contestID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_contest_participant;
CREATE TABLE wcf1_contest_participant (
	participantID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	contestID INT(10) NOT NULL DEFAULT 0,
	userID INT(10) NOT NULL DEFAULT 0,
	groupID INT(10) NOT NULL DEFAULT 0,
	time INT(10) NOT NULL DEFAULT 0,
	state ENUM('invited', 'accepted', 'declined', 'left') NOT NULL DEFAULT 'invited',
	KEY (userID, contestID),
	KEY (groupID, contestID),
	KEY (contestID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_contest_sponsor;
CREATE TABLE wcf1_contest_sponsor (
	sponsorID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	contestID INT(10) NOT NULL DEFAULT 0,
	userID INT(10) NOT NULL DEFAULT 0,
	groupID INT(10) NOT NULL DEFAULT 0,
	time INT(10) NOT NULL DEFAULT 0,
	state ENUM('unknown', 'accepted', 'declined') NOT NULL DEFAULT 'unknown',
	KEY (userID, contestID),
	KEY (groupID, contestID),
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
	state ENUM('unknown', 'accepted', 'declined') NOT NULL DEFAULT 'unknown',
	position SMALLINT(5) NOT NULL DEFAULT 0,
	KEY (contestID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_contest_menu_item;
CREATE TABLE wcf1_contest_menu_item (
	menuItemID int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
	menuItem varchar(255) NOT NULL DEFAULT '',
	parentMenuItem varchar(255) NOT NULL DEFAULT '',
	menuItemLink varchar(255) NOT NULL DEFAULT '',
	menuItemIcon varchar(255) NOT NULL DEFAULT '',
	showOrder int(10) NOT NULL DEFAULT '0',
	permissions text,
	options text,
	UNIQUE KEY menuItem (menuItem)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO wcf1_contest_class (title) VALUES 
	('wcf.user.contest.entry.classes.beginner'),
	('wcf.user.contest.entry.classes.expert');

INSERT INTO wcf1_contest_menu_item (menuItem, parentMenuItem, menuItemLink, menuItemIcon, showOrder, permissions, options) VALUES
	('wcf.contest.menu.link.overview', '', 'index.php?page=Contest&contestID=%s', 'contestM.png', 1, '', ''),
	('wcf.contest.menu.link.jury', '', 'index.php?page=ContestJury&contestID=%s', 'contestM.png', 2, '', ''),
	('wcf.contest.menu.link.jurytalk', '', 'index.php?page=ContestJurytalk&contestID=%s', 'contestM.png', 3, '', ''),
	('wcf.contest.menu.link.sponsortalk', '', 'index.php?page=ContestSponsortalk&contestID=%s', 'contestM.png', 4, '', ''),
	('wcf.contest.menu.link.participant', '', 'index.php?page=ContestParticipant&contestID=%s', 'contestM.png', 5, '', ''),
	('wcf.contest.menu.link.sponsor', '', 'index.php?page=ContestSponsor&contestID=%s', 'contestM.png', 6, '', ''),
	('wcf.contest.menu.link.solution', '', 'index.php?page=ContestSolution&contestID=%s', 'contestM.png', 7, '', ''),
	('wcf.contest.menu.link.price', '', 'index.php?page=ContestPrice&contestID=%s', 'contestM.png', 7, '', '');
