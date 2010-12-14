-- easy-coding.de example
INSERT INTO  `contest`.`wcf1_contest_interaction_ruleset` (
  `rulesetID` ,
  `kind` ,
  `table` ,
  `column`
)
VALUES (
  NULL ,  'user',  'wbb1_post',  'userID'
), (
  NULL ,  'user',  'wcf1_lexicon',  'author'
);

INSERT INTO  `contest`.`wcf1_contest_interaction` (
  `interactionID` ,
  `rulesetID` ,
  `contestID` ,
  `fromTime` ,
  `untilTime`
)
VALUES (
  NULL ,  '1',  '3', UNIX_TIMESTAMP(  '2010-12-09 00:00:00' ) , UNIX_TIMESTAMP(  '2010-12-23 23:59:59' )
), (
  NULL ,  '2',  '3', UNIX_TIMESTAMP(  '2010-12-09 00:00:00' ) , UNIX_TIMESTAMP(  '2010-12-23 23:59:59' )
);
