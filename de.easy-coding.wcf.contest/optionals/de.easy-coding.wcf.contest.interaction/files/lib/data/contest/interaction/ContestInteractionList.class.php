<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectListCached.class.php');

/**
 * Represents a list of contest events.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.interaction
 */
class ContestInteractionList extends DatabaseObjectListCached {

	/**
	 * expiration time in seconds
	 *
	 * @var integer
	 */
	protected $maxLifetime = 120;

	/**
	 * for pagination
	 *
	 * @var integer
	 */
	public $sqlLimit = 50;

	/**
	 * sql order by statement
	 *
	 * @var	string
	 */
	public $sqlOrderBy = 'c DESC';
	
	protected $updated = false;

	/**
	 * @see DatabaseObjectListCached::countObjects()
	 */
	public function _countObjects() {
		$this->updateData();
		
		$sql = 'SELECT
					COUNT(x.id) AS count
			FROM (

				SELECT		contest_participant.userID AS id
				FROM		wcf'.WCF_N.'_contest_participant contest_participant
				INNER JOIN	wcf'.WCF_N.'_contest_xmas USING(userID)
				WHERE		contest_participant.userID > 0
				AND		contest_participant.state = "accepted"
				'.(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '').'
				GROUP BY	contest_participant.userID
			UNION
				SELECT		contest_participant.groupID AS id
				FROM		wcf'.WCF_N.'_contest_participant contest_participant
				INNER JOIN	wcf'.WCF_N.'_user_to_groups user_to_groups USING(groupID)
				INNER JOIN	wcf'.WCF_N.'_contest_xmas contest_xmas ON contest_xmas.userID = user_to_groups.userID
				WHERE		contest_participant.groupID > 0
				AND		contest_participant.state = "accepted"
				'.(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '').'
				GROUP BY	contest_participant.groupID
			) x
			'.$this->sqlJoins;

		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}
	
	public function updateData() {
		if($this->updated) {
			return;
		}
		$this->updated = true;

		$sql = 'DROP TABLE IF EXISTS wcf'.WCF_N.'_contest_xmas_tmp;';
		WCF::getDB()->sendQuery($sql);

		$sql = 'CREATE TABLE	wcf'.WCF_N.'_contest_xmas_tmp Engine=MEMORY
			SELECT		userID,
					COUNT(userID) AS c
			FROM 		wbb'.WBB_N.'_post
			WHERE		time BETWEEN UNIX_TIMESTAMP("2010-12-09 00:00:00") AND UNIX_TIMESTAMP("2010-12-24 23:59:59")
			GROUP BY	userID;';
		WCF::getDB()->sendQuery($sql);

		$sql = 'INSERT INTO     wcf'.WCF_N.'_contest_xmas_tmp
                        SELECT          author,
                                        COUNT(author) * 15 AS c
                        FROM            wcf'.WCF_N.'_lexicon_item
                        WHERE           createTime BETWEEN UNIX_TIMESTAMP("2010-12-09 00:00:00") AND UNIX_TIMESTAMP("2010-12-24 23:59:59")
                        GROUP BY        author;';
                WCF::getDB()->sendQuery($sql);

                // fire event
                EventHandler::fireAction($this, 'updateData');

		$sql = 'DROP TABLE IF EXISTS wcf'.WCF_N.'_contest_xmas;';
                WCF::getDB()->sendQuery($sql);

		$sql = 'CREATE TABLE    wcf'.WCF_N.'_contest_xmas Engine=MEMORY
                        SELECT          userID,
                                        SUM(c) AS c
                        FROM            wcf'.WCF_N.'_contest_xmas_tmp
                        GROUP BY        userID;';
                WCF::getDB()->sendQuery($sql);

		$sql = 'ALTER TABLE	wcf'.WCF_N.'_contest_xmas ADD PRIMARY KEY (userid);';
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * @see DatabaseObjectListCached::readObjects()
	 */
	public function _readObjects() {
		$this->updateData();

		$sql = 'SELECT
					avatar_table.*,
					u.userID,
					g.groupID,
					g.groupName, 
					u.username,
					x.c
			FROM (

				SELECT		contest_participant.userID AS id,
						SUM(c) AS c,
						"user" AS kind
				FROM		wcf'.WCF_N.'_contest_participant contest_participant
				INNER JOIN	wcf'.WCF_N.'_contest_xmas USING(userID)
				WHERE		contest_participant.userID > 0
				AND		contest_participant.state = "accepted"
				'.(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '').'
				GROUP BY	contest_participant.userID
			UNION
				SELECT		contest_participant.groupID AS id,
						SUM(c) AS c,
						"group" AS kind
				FROM		wcf'.WCF_N.'_contest_participant contest_participant
				INNER JOIN	wcf'.WCF_N.'_user_to_groups user_to_groups USING(groupID)
				INNER JOIN	wcf'.WCF_N.'_contest_xmas contest_xmas ON contest_xmas.userID = user_to_groups.userID
				WHERE		contest_participant.groupID > 0
				AND		contest_participant.state = "accepted"
				'.(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '').'
				GROUP BY	contest_participant.groupID
			) x
			LEFT JOIN	wcf'.WCF_N.'_user u ON x.kind = "user" AND x.id = u.userID
			LEFT JOIN	wcf'.WCF_N.'_avatar avatar_table
			ON		(avatar_table.avatarID = u.avatarID)
			LEFT JOIN	wcf'.WCF_N.'_group g ON x.kind = "group" AND x.id = g.groupID
			'.$this->sqlJoins.'
			'.(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '').'
			'.(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);

		$rows = array();
		while ($row = WCF::getDB()->fetchArray($result)) {
			$rows[] = new ViewableContestJury(null, $row);
		}
		return $rows;
	}
}
?>
