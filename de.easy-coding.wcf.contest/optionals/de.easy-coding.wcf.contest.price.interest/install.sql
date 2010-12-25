ALTER TABLE wcf1_contest_price ADD interests INT(10) NOT NULL DEFAULT 0 AFTER position;

DROP TABLE IF EXISTS wcf1_contest_price_interest;
CREATE TABLE wcf1_contest_price_interest (
	interestID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	priceID INT(10) NOT NULL DEFAULT 0,
	participantID INT(10) NOT NULL DEFAULT 0,
	time INT(10) NOT NULL DEFAULT 0,
	KEY (priceID),
	KEY (participantID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
