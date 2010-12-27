ALTER TABLE wcf1_contest_interaction ADD rulesetFactor int(10) unsigned NOT NULL DEFAULT '0' AFTER untilTime;
ALTER TABLE wcf1_contest_ruleset DROP rulesetFactor;
