ALTER TABLE wcf1_contest ADD enableInteraction TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT 0;

DROP TABLE IF EXISTS wcf1_contest_interaction_ruleset;
CREATE TABLE IF NOT EXISTS wcf1_contest_interaction_ruleset (
  rulesetID int(10) unsigned NOT NULL AUTO_INCREMENT,
  kind enum('user','group') NOT NULL,
  rulesetTable varchar(64) NOT NULL DEFAULT '',
  rulesetColumn varchar(64) NOT NULL DEFAULT '',
  rulesetColumnTime varchar(64) NOT NULL DEFAULT '',
  rulesetFactor int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (rulesetID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_contest_interaction;
CREATE TABLE IF NOT EXISTS wcf1_contest_interaction (
  interactionID int(10) unsigned NOT NULL AUTO_INCREMENT,
  rulesetID int(10) unsigned NOT NULL DEFAULT '0',
  contestID int(10) unsigned NOT NULL DEFAULT '0',
  fromTime INT(10) NOT NULL DEFAULT 0,
  untilTime INT(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (interactionID),
  INDEX contestID (contestID)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
