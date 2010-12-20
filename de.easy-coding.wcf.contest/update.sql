ALTER TABLE wcf1_contest ADD priceExpireSeconds INT(10) NOT NULL DEFAULT 0 AFTER time;
ALTER TABLE wcf1_contest_solution ADD priceExpireTime INT(10) NOT NULL DEFAULT 0 AFTER time;
ALTER TABLE wcf1_contest_solution_rating CHANGE score score smallint(5) NOT NULL DEFAULT 0;
