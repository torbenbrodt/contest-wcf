UPDATE 	wcf1_group_option_value, (
	SELECT	optionID, defaultValue
	FROM	wcf1_group_option
	WHERE	optionName LIKE 'user.contest.%'
) x
SET	wcf1_group_option_value.optionValue = x.defaultValue
WHERE	wcf1_group_option_value.optionID IN (
	SELECT	optionID
	FROM	wcf1_group_option
	WHERE	optionName LIKE 'user.contest.%'
) AND wcf1_group_option_value.optionID = x.optionID;
