ALTER TABLE wcf1_contest_price CHANGE state state ENUM('applied', 'accepted', 'declined', 'sent', 'received') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'applied';
UPDATE wcf1_contest_price SET state = 'applied' WHERE state = '';
UPDATE wcf1_contest_event SET eventName = REPLACE(eventName, "Update", "Create") WHERE eventName LIKE '%Update%';
UPDATE wcf1_contest_event SET eventName = 'contestCreate' WHERE eventName = 'Create';

UPDATE wcf1_contest_solution x, (
 SELECT solutionID, COUNT(solutionID) AS ratings FROM wcf1_contest_solution_rating GROUP BY solutionID) y
 SET x.ratings = y.ratings WHERE x.solutionID = y.solutionID;

ALTER TABLE wcf1_contest_class ADD parentClassID INT UNSIGNED NOT NULL DEFAULT '0' AFTER classID;
ALTER TABLE wcf1_contest_class ADD position SMALLINT NOT NULL DEFAULT '0' AFTER title;
ALTER TABLE wcf1_contest_ratingoption ADD classID INT UNSIGNED NOT NULL DEFAULT '0' AFTER optionID;
ALTER TABLE wcf1_contest_ratingoption ADD position SMALLINT( 5 ) NOT NULL DEFAULT '0' AFTER classID;
ALTER TABLE wcf1_contest_ratingoption DROP title;
ALTER TABLE wcf1_contest_class DROP title;
