-- easy-coding.de example
UPDATE `wcf1_contest` SET `enableInteraction` = '1' WHERE `contestID` = 3 LIMIT 1;

INSERT INTO `wcf1_contest_interaction` (`interactionID`, `rulesetID`, `contestID`, `fromTime`, `untilTime`, `rulesetFactor`) VALUES
(1, 1, 3, 1291849200, 1293145199, 1),
(2, 2, 3, 1291849200, 1293145199, 15),
(3, 1, 4, 1300921200, 1303682399, 1),
(4, 2, 4, 1300921200, 1303682399, 10),
(5, 3, 4, 1300575600, 1303682399, 3),
(6, 4, 4, 1300575600, 1303682399, 3);

INSERT INTO `wcf1_contest_interaction_ruleset` (`rulesetID`, `kind`, `rulesetTable`, `rulesetColumn`, `rulesetColumnTime`) VALUES
(1, 'user', 'wbb1_1_post', 'userID', 'time'),
(2, 'user', 'wcf1_lexicon_item', 'author', 'createTime'),
(3, 'user', 'wcf1_user', 'userID', 'registrationDate'),
(4, 'participant', 'wcf1_contest_interaction_extra', 'participantID', '');

