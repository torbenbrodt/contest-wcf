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
	('wcf.contest.ratingoption.basic'),
	('wcf.contest.ratingoption.basic.format');

ALTER TABLE wcf1_contest_solution ADD ratings SMALLINT( 5 ) NOT NULL AFTER comments;

