-- easy-coding.de example
UPDATE `wcf1_contest` SET `enableInteraction` = '1' WHERE `contestID` = 3 LIMIT 1;

INSERT INTO `wcf1_contest_interaction_ruleset` (
 `rulesetID` ,
 `kind` ,
 `rulesetTable` ,
 `rulesetColumn` ,
 `rulesetColumnTime` ,
 `rulesetFactor` 
)
VALUES (
 NULL , 'user', 'wbb1_1_post', 'userID', 'time', '1'
), (
 NULL , 'user', 'wcf1_lexicon_item', 'author', 'createTime', '15'
);

INSERT INTO `wcf1_contest_interaction` (
 `interactionID` ,
 `rulesetID` ,
 `contestID` ,
 `fromTime` ,
 `untilTime`
)
VALUES (
 NULL , '1', '3', UNIX_TIMESTAMP('2010-12-09 00:00:00') , UNIX_TIMESTAMP('2010-12-23 23:59:59')
), (
 NULL , '2', '3', UNIX_TIMESTAMP('2010-12-09 00:00:00') , UNIX_TIMESTAMP('2010-12-23 23:59:59')
);
