UPDATE 	wcf1_group_option_value
SET	optionValue = 0
WHERE	groupID NOT IN (22)
	AND optionID IN (
		SELECT	optionID
		FROM	wcf1_group_option
		WHERE	optionName LIKE 'user.contest.%'
	)
	AND optionValue = '1'
