<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectListCached.class.php');
require_once(WCF_DIR.'lib/data/contest/participant/ViewableContestParticipant.class.php');

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
	
	/**
	 * @var boolean
	 */
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
				INNER JOIN	wcf'.WCF_N.'_contest_interaction_tmp2 USING(userID)
				WHERE		contest_participant.userID > 0
				AND		contest_participant.state = "accepted"
				'.(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '').'
				GROUP BY	contest_participant.userID
			UNION
				SELECT		contest_participant.groupID AS id
				FROM		wcf'.WCF_N.'_contest_participant contest_participant
				INNER JOIN	wcf'.WCF_N.'_user_to_groups user_to_groups USING(groupID)
				INNER JOIN	wcf'.WCF_N.'_contest_interaction_tmp2 contest_interaction_tmp2 ON contest_interaction_tmp2.userID = user_to_groups.userID
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

		$sql = 'DROP TABLE IF EXISTS wcf'.WCF_N.'_contest_interaction_tmp1;';
		WCF::getDB()->sendQuery($sql);

		$sql = 'CREATE TABLE wcf'.WCF_N.'_contest_interaction_tmp1 (
			userID int(10) unsigned NOT NULL DEFAULT "0",
			c int(10) unsigned NOT NULL DEFAULT "0"
		) Engine=MEMORY DEFAULT CHARSET=utf8';
		WCF::getDB()->sendQuery($sql);

		$sql = 'SELECT		*
			FROM 		wcf'.WCF_N.'_contest_interaction
			INNER JOIN	wcf'.WCF_N.'_contest_interaction_ruleset USING(rulesetID)
			'.(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '');
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {

			$sql = 'INSERT INTO     wcf'.WCF_N.'_contest_interaction_tmp1
				SELECT		'.$row['rulesetColumn'].' AS userID,
						COUNT('.$row['rulesetColumn'].') * '.$row['rulesetFactor'].' AS c
				FROM		'.$row['rulesetTable'].'
				WHERE		'.$row['rulesetColumnTime'].' BETWEEN '.$row['fromTime'].' AND '.$row['untilTime'].'
				GROUP BY	'.$row['rulesetColumn'];
			WCF::getDB()->sendQuery($sql);	
		}

		// fire event
		EventHandler::fireAction($this, 'updateData');

		$sql = 'DROP TABLE IF EXISTS wcf'.WCF_N.'_contest_interaction_tmp2;';
		WCF::getDB()->sendQuery($sql);

		$sql = 'CREATE TABLE    wcf'.WCF_N.'_contest_interaction_tmp2 Engine=MEMORY
			SELECT		userID,
					SUM(c) AS c
			FROM		wcf'.WCF_N.'_contest_interaction_tmp1
			GROUP BY	userID;';
		WCF::getDB()->sendQuery($sql);

		$sql = 'ALTER TABLE	wcf'.WCF_N.'_contest_interaction_tmp2 ADD PRIMARY KEY (userid);';
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
					x.participantID,
					x.c
			FROM (

				SELECT		contest_participant.userID AS id,
						contest_participant.participantID,
						SUM(c) AS c,
						"user" AS kind
				FROM		wcf'.WCF_N.'_contest_participant contest_participant
				INNER JOIN	wcf'.WCF_N.'_contest_interaction_tmp2 USING(userID)
				WHERE		contest_participant.userID > 0
				AND		contest_participant.state = "accepted"
				'.(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '').'
				GROUP BY	contest_participant.userID
			UNION
				SELECT		contest_participant.groupID AS id,
						contest_participant.participantID,
						SUM(c) AS c,
						"group" AS kind
				FROM		wcf'.WCF_N.'_contest_participant contest_participant
				INNER JOIN	wcf'.WCF_N.'_user_to_groups user_to_groups USING(groupID)
				INNER JOIN	wcf'.WCF_N.'_contest_interaction_tmp2 contest_interaction_tmp2 ON contest_interaction_tmp2.userID = user_to_groups.userID
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
			'.(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);

		$rows = array();
		while ($row = WCF::getDB()->fetchArray($result)) {
			$rows[] = new ViewableContestParticipant(null, $row);
		}
		return $rows;
	}
}
?>
