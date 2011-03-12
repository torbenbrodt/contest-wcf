ALTER TABLE wcf1_contest ADD interactionLastUpdate INT( 10 ) UNSIGNED NOT NULL DEFAULT 0 AFTER enableInteraction;

DROP TABLE IF EXISTS wcf1_contest_interaction_data;
CREATE TABLE IF NOT EXISTS wcf1_contest_interaction_data (
  contestID int(10) unsigned NOT NULL DEFAULT '0',
  participantID int(10) unsigned NOT NULL DEFAULT '0',
  score int(10) unsigned NOT NULL DEFAULT '0',
  INDEX contestID (contestID)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
