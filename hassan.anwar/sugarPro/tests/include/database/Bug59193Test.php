<?php
/**
 * @ticket 59193
 */
class Bug59193Test extends Sugar_PHPUnit_Framework_TestCase
{
	var $disableCountQuery;

	public function setUp()
	{
	    global $sugar_config;
	    $this->disableCountQuery = isset($sugar_config['disable_count_query']) ? $sugar_config['disable_count_query'] : false;
	    $sugar_config['disable_count_query'] = true;
	}

	public function tearDown()
	{
		global $sugar_config;
		$sugar_config['disable_count_query'] = $this->disableCountQuery;
	}

	/**
	 * Test if query with two team clauses on different tables works.
	 */
	public function testAddDistinct()
	{
	      $q = <<<END
SELECT accounts.id primaryid, accounts.name, l1.id, l1.last_name l1_full_name
								FROM accounts

INNER JOIN (select tst.team_set_id from team_sets_teams tst INNER JOIN team_memberships team_memberships ON tst.team_id = team_memberships.team_id
							AND team_memberships.user_id = 'seed_jim_id'
							AND team_memberships.deleted=0 group by tst.team_set_id) accounts_tf on accounts_tf.team_set_id  = accounts.team_set_id

INNER JOIN  accounts_contacts l1_1 ON accounts.id=l1_1.account_id AND l1_1.deleted=0

INNER JOIN (SELECT contacts.* FROM contacts INNER JOIN (select tst.team_set_id from team_sets_teams tst INNER JOIN team_memberships team_memberships ON tst.team_id = team_memberships.team_id
								                                        AND team_memberships.user_id = 'seed_jim_id'
								                                        AND team_memberships.deleted=0 group by tst.team_set_id) contacts_tf on contacts_tf.team_set_id  = contacts.team_set_id  ) l1 ON l1.id=l1_1.contact_id AND l1.deleted=0

								 WHERE ((1=1))
								AND  accounts.deleted=0
END;
	      $res = $GLOBALS['db']->limitQuery($q, 0, 100);
	      $this->assertNotEmpty($res);
	}

}