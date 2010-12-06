DROP TABLE IF EXISTS wcf1_contest_promotion;
CREATE TABLE wcf1_contest_promotion (
	contestID INT(10) NOT NULL DEFAULT 0,
	languageID INT(10) NOT NULL DEFAULT 0,
	message TEXT NULL,
	PRIMARY KEY (contestID, languageID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE wcf1_user ADD contestPromotionDisabled VARCHAR(32) NOT NULL DEFAULT '';

-- Möchtest du kostenlos am easy-coding Weihnachtsgewinnspiel teilnehmen? Für jedes Posting gibt es ein Los.
