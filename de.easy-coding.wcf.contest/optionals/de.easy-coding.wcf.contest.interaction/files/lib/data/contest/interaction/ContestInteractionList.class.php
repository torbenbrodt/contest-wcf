<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/contest/participant/ViewableContestParticipant.class.php');

/**
 * Represents a list of contest events.
 * 
 * @author	Torben Brodt
 * @copyright	2010 easy-coding.de
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 * @package	de.easy-coding.wcf.contest.interaction
 */
class ContestInteractionList extends DatabaseObjectList {

	/**
	 * contains cached data
	 *
	 * @var array<mixed>
	 */
	protected $cachedList = array();

	/**
	 * expiration time in seconds
	 *
	 * @var integer
	 * @var Contest
	 */
	protected $maxLifetime = 120;

	/**
	 * @var Contest
	 */
	protected $contest = null;

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
	 * construct new list
	 *
	 * @param	Contest		$contest
	 */
	public function __construct(Contest $contest) {
		$this->contest = $contest;
	}

	/**
	 * @see DatabaseObjectListCached::countObjects()
	 */
	public function countObjects() {
		$this->updateData();

		$sql = 'SELECT		COUNT(contest_participant.participantID) AS count
			FROM		wcf'.WCF_N.'_contest_interaction_data cid
			INNER JOIN	wcf'.WCF_N.'_contest_participant contest_participant USING(participantID)
			WHERE		cid.contestID = '.intval($this->contest->contestID);

		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}

	/**
	 * @return	void
	 */
	public function updateData() {

		// already updated
		if($this->updated) {
			return;
		}
		$this->updated = true;

		// if contest is closed and interaction update was after end of contest, we do not need to update the data
		if($this->contest->state == 'closed' && $this->contest->interactionLastUpdate > $this->contest->untilTime) {
			return;
		}

		// if contest update is only x seconds gone
		if($this->contest->state != 'closed' && $this->contest->interactionLastUpdate > TIME_NOW - $this->maxLifetime) {
			return;
		}

		// remember update
		$sql = 'UPDATE	wcf'.WCF_N.'_contest
			SET	interactionLastUpdate = '.TIME_NOW.'
			WHERE	contestID = '.intval($this->contest->contestID);
		WCF::getDB()->sendQuery($sql);

		// update user
		$sql = 'DROP TEMPORARY TABLE IF EXISTS wcf'.WCF_N.'_contest_interaction_user_tmp;';
		WCF::getDB()->sendQuery($sql);

		$sql = 'CREATE TEMPORARY TABLE wcf'.WCF_N.'_contest_interaction_user_tmp (
			userID int(10) unsigned NOT NULL DEFAULT "0",
			c int(10) unsigned NOT NULL DEFAULT "0"
		) Engine=MEMORY DEFAULT CHARSET=utf8';
		WCF::getDB()->sendQuery($sql);

		$sql = 'SELECT		*
			FROM 		wcf'.WCF_N.'_contest_interaction contest_interaction
			INNER JOIN	wcf'.WCF_N.'_contest_interaction_ruleset contest_interaction_ruleset USING(rulesetID)
			WHERE		contest_interaction.contestID = '.intval($this->contest->contestID).'
			AND		contest_interaction_ruleset.kind = "user"';
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {

			$sql = 'INSERT INTO     wcf'.WCF_N.'_contest_interaction_user_tmp
				SELECT		'.$row['rulesetColumn'].' AS userID,
						COUNT('.$row['rulesetColumn'].') * '.$row['rulesetFactor'].' AS c
				FROM		'.$row['rulesetTable'].'
				WHERE		'.$row['rulesetColumnTime'].' BETWEEN '.$row['fromTime'].' AND '.$row['untilTime'].'
				GROUP BY	'.$row['rulesetColumn'];
			WCF::getDB()->sendQuery($sql);
		}
		
		// update group
		$sql = 'DROP TEMPORARY TABLE IF EXISTS wcf'.WCF_N.'_contest_interaction_group_tmp;';
		WCF::getDB()->sendQuery($sql);

		$sql = 'CREATE TEMPORARY TABLE wcf'.WCF_N.'_contest_interaction_group_tmp (
			groupID int(10) unsigned NOT NULL DEFAULT "0",
			c int(10) unsigned NOT NULL DEFAULT "0"
		) Engine=MEMORY DEFAULT CHARSET=utf8';
		WCF::getDB()->sendQuery($sql);

		$sql = 'SELECT		*
			FROM 		wcf'.WCF_N.'_contest_interaction contest_interaction
			INNER JOIN	wcf'.WCF_N.'_contest_interaction_ruleset contest_interaction_ruleset USING(rulesetID)
			WHERE		contest_interaction.contestID = '.intval($this->contest->contestID).'
			AND		contest_interaction_ruleset.kind = "group"';
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {

			$sql = 'INSERT INTO     wcf'.WCF_N.'_contest_interaction_group_tmp
				SELECT		'.$row['rulesetColumn'].' AS groupID,
						COUNT('.$row['rulesetColumn'].') * '.$row['rulesetFactor'].' AS c
				FROM		'.$row['rulesetTable'].'
				WHERE		'.$row['rulesetColumnTime'].' BETWEEN '.$row['fromTime'].' AND '.$row['untilTime'].'
				GROUP BY	'.$row['rulesetColumn'];
			WCF::getDB()->sendQuery($sql);
		}

		$sql = 'DROP TEMPORARY TABLE IF EXISTS wcf'.WCF_N.'_contest_interaction_tmp;';
		WCF::getDB()->sendQuery($sql);
		
		$sql = 'CREATE TEMPORARY TABLE wcf'.WCF_N.'_contest_interaction_tmp (
			participantID int(10) unsigned NOT NULL DEFAULT "0",
			c int(10) unsigned NOT NULL DEFAULT "0"
		) Engine=MEMORY DEFAULT CHARSET=utf8';
		WCF::getDB()->sendQuery($sql);

		// update participant		
		$sql = 'SELECT		*
			FROM 		wcf'.WCF_N.'_contest_interaction contest_interaction
			INNER JOIN	wcf'.WCF_N.'_contest_interaction_ruleset contest_interaction_ruleset USING(rulesetID)
			WHERE		contest_interaction.contestID = '.intval($this->contest->contestID).'
			AND		contest_interaction_ruleset.kind = "participant"';
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {

			$sql = 'INSERT INTO     wcf'.WCF_N.'_contest_interaction_tmp
				SELECT		'.$row['rulesetColumn'].' AS participantID,
						SUM('.$row['rulesetColumn'].') * '.$row['rulesetFactor'].' AS c
				FROM		'.$row['rulesetTable'].'
				GROUP BY	'.$row['rulesetColumn'];
			WCF::getDB()->sendQuery($sql);
		}

		
		// insert standard participants
		$sql = 'INSERT INTO	wcf'.WCF_N.'_contest_interaction_tmp

			SELECT		participantID,
					1 AS c
			FROM		wcf'.WCF_N.'_contest_participant contest_participant
			WHERE		contest_participant.contestID = '.intval($this->contest->contestID).'
			AND		contest_participant.state = "accepted"';
		WCF::getDB()->sendQuery($sql);

		// group score by userid
		$sql = 'INSERT INTO	wcf'.WCF_N.'_contest_interaction_tmp

			SELECT		contest_participant.participantID,
					SUM(cit2.c) AS c
			FROM		wcf'.WCF_N.'_contest_participant contest_participant
			INNER JOIN	wcf'.WCF_N.'_contest_interaction_user_tmp cit2 USING(userID)
			WHERE		contest_participant.contestID = '.intval($this->contest->contestID).'
			AND		contest_participant.userID > 0
			AND		contest_participant.state = "accepted"
			GROUP BY	contest_participant.userID';
		WCF::getDB()->sendQuery($sql);

		// group score by groupid
		$sql = 'INSERT INTO	wcf'.WCF_N.'_contest_interaction_tmp

			SELECT		contest_participant.participantID,
					SUM(cit2.c) AS c
			FROM		wcf'.WCF_N.'_contest_participant contest_participant
			INNER JOIN	wcf'.WCF_N.'_contest_interaction_group_tmp cit2 USING(groupID)
			WHERE		contest_participant.contestID = '.intval($this->contest->contestID).'
			AND		contest_participant.groupID > 0
			AND		contest_participant.state = "accepted"
			GROUP BY	contest_participant.groupID';
		WCF::getDB()->sendQuery($sql);

		// group score by groupid (by usertogroup)
		$sql = 'INSERT INTO	wcf'.WCF_N.'_contest_interaction_tmp

			SELECT		contest_participant.participantID,
					SUM(cit2.c) AS c
			FROM		wcf'.WCF_N.'_contest_participant contest_participant
			INNER JOIN	wcf'.WCF_N.'_user_to_groups user_to_groups USING(groupID)
			INNER JOIN	wcf'.WCF_N.'_contest_interaction_user_tmp cit2 ON cit2.userID = user_to_groups.userID
			WHERE		contest_participant.contestID = '.intval($this->contest->contestID).'
			AND		contest_participant.groupID > 0
			AND		contest_participant.state = "accepted"
			GROUP BY	contest_participant.groupID';
		WCF::getDB()->sendQuery($sql);

		// insert coupon data
		$sql = 'INSERT INTO	wcf'.WCF_N.'_contest_interaction_tmp
					(participantID, c)
			SELECT		participantID,
					score
			FROM		wcf'.WCF_N.'_contest_coupon_participant contest_coupon_participant
			INNER JOIN	wcf'.WCF_N.'_contest_coupon contest_coupon
			ON		contest_coupon_participant.couponID = contest_coupon.couponID
			WHERE		contestID = '.intval($this->contest->contestID);
		WCF::getDB()->sendQuery($sql);

		// insert extra points
		$sql = 'INSERT INTO	wcf'.WCF_N.'_contest_interaction_tmp
					(participantID, c)
			SELECT		participantID,
					score
			FROM		wcf'.WCF_N.'_contest_interaction_extra
			WHERE		contestID = '.intval($this->contest->contestID);
		WCF::getDB()->sendQuery($sql);

		// write back data
		$sql = 'DELETE FROM	wcf'.WCF_N.'_contest_interaction_data
			WHERE		contestID = '.intval($this->contest->contestID);
		WCF::getDB()->sendQuery($sql);

		// insert user participants in commonly used table
		$sql = 'INSERT INTO	wcf'.WCF_N.'_contest_interaction_data
					(contestID, participantID, score)
			SELECT		'.intval($this->contest->contestID).' AS contestID,
					participantID,
					SUM(c)
			FROM		wcf'.WCF_N.'_contest_interaction_tmp
			GROUP BY	participantID';
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * @see DatabaseObjectListCached::readObjects()
	 */
	public function readObjects() {
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
				SELECT		IF(contest_participant.groupID > 0, contest_participant.groupID, contest_participant.userID) AS id,
						contest_participant.participantID,
						cid.score AS c,
						IF(contest_participant.groupID > 0, "group", "user") AS kind
				FROM		wcf'.WCF_N.'_contest_interaction_data cid
				INNER JOIN	wcf'.WCF_N.'_contest_participant contest_participant USING(participantID)
				WHERE		cid.contestID = '.intval($this->contest->contestID).'
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
		$this->cachedList = $rows;
	}
	
	/**
	 * Returns the objects of the list.
	 * 
	 * @return	DatabaseObject
	 */
	public function getObjects() {
		return $this->cachedList;
	}
}
?>
