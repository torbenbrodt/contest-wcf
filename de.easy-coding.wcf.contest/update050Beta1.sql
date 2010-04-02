ALTER TABLE wcf1_contest_price CHANGE state state ENUM('applied', 'accepted', 'declined', 'sent', 'received') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'applied';
UPDATE wcf1_contest_price SET state = 'applied' WHERE state = '';
