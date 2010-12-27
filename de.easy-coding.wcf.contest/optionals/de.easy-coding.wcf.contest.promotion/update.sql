ALTER TABLE wcf1_contest_promotion DROP PRIMARY KEY;
ALTER TABLE wcf1_contest_promotion ADD PRIMARY KEY (contestID, languageID, action);
