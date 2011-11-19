ALTER TABLE wcf1_contest_interaction_ruleset 
  ADD rulesetColumnFactor varchar(64) NOT NULL DEFAULT '' AFTER rulesetColumnTime,
  CHANGE score score int(11) NOT NULL DEFAULT '0';
