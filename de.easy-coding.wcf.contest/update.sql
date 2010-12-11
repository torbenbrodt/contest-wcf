ALTER TABLE wcf1_contest ADD enableOpenSolution TINYINT(1) NOT NULL DEFAULT 1 AFTER enableBBCodes;
ALTER TABLE wcf1_contest ADD enableSolution TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT 1 AFTER enableParticipantCheck;
