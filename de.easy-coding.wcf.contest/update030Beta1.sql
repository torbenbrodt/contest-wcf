DROP TABLE IF EXISTS wcf1_contest_ratingoption;
CREATE TABLE wcf1_contest_ratingoption (
	optionID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(128) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_contest_solution_rating;
CREATE TABLE wcf1_contest_solution_rating (
	ratingID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	solutionID INT(10) NOT NULL,
	userID INT(10) NOT NULL DEFAULT 0,
	optionID INT(10) NOT NULL DEFAULT 0,
	score INT(1) NOT NULL DEFAULT 0,
	time INT(10) NOT NULL DEFAULT 0,
	UNIQUE KEY (solutionID, userID, optionID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO wcf1_contest_ratingoption (title) VALUES 
	('wcf.contest.rating.option.quality'),
	('wcf.contest.rating.option.documentation');

ALTER TABLE wcf1_contest_solution ADD ratings SMALLINT( 5 ) NOT NULL AFTER comments;
ALTER TABLE wcf1_contest CHANGE state state ENUM('private', 'applied', 'accepted', 'declined', 'scheduled', 'closed') NOT NULL DEFAULT 'private';
ALTER TABLE wcf1_contest_price ADD solutionID INT UNSIGNED NOT NULL AFTER sponsorID;
ALTER TABLE wcf1_contest_price ADD INDEX ( solutionID );
ALTER TABLE wcf1_contest_solution ADD participantID INT UNSIGNED NOT NULL AFTER contestID;
INSERT IGNORE INTO wcf1_contest_participant (contestID, userID, groupID) SELECT contestID, userID, groupID FROM wcf1_contest_solution;
UPDATE wcf1_contest_solution x SET participantID = (SELECT participantID FROM wcf1_contest_participant y WHERE x.contestID = y.contestID AND x.userID = y.userID AND x.groupID = y.groupID);
ALTER TABLE wcf1_contest_solution DROP userID , DROP groupID;
ALTER TABLE wcf1_contest_price CHANGE state state ENUM('unknown', 'accepted', 'declined', 'sent', 'received') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'unknown';
ALTER TABLE wcf1_contest_solution ADD INDEX ( participantID );

ALTER TABLE wcf1_contest ADD INDEX ( state );
