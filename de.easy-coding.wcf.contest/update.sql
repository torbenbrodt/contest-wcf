ALTER TABLE wcf1_contest ADD priceExpireSeconds INT(10) NOT NULL DEFAULT 0 AFTER time;
ALTER TABLE wcf1_contest ADD enablePricechoice TINYINT(1) NOT NULL DEFAULT 0 AFTER enableParticipantCheck;
ALTER TABLE wcf1_contest_solution ADD pickTime INT(10) NOT NULL DEFAULT 0 AFTER time;
ALTER TABLE wcf1_contest_solution_rating CHANGE score score smallint(5) NOT NULL DEFAULT 0;
