<?php

/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */
require_once 'modules/Bugs/Bug.php';
require_once 'include/database/MysqlManager.php';

class Bug60780Test extends Sugar_PHPUnit_Framework_TestCase
{
    protected $has_disable_count_query_enabled;

    public function setUp()
    {
        global $sugar_config;

        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('current_user');
        $this->has_disable_count_query_enabled = !empty($sugar_config['disable_count_query']);
        if(!$this->has_disable_count_query_enabled) {
           $sugar_config['disable_count_query'] = true;
        }
    }

    public function tearDown()
    {
        global $sugar_config;
        if(!empty($this->bugid)) {
            $GLOBALS['db']->query("DELETE FROM bugs WHERE id='{$this->bugid}'");
        }
        if(!$this->has_disable_count_query_enabled) {
           unset($sugar_config['disable_count_query']);
        }
        SugarTestHelper::tearDown();
    }

    public function testCreateBug()
    {
        $bug = BeanFactory::newBean('Bugs');
        $bug->id = $this->bugid = create_guid();
        $bug->new_with_id = true;
        $bug->name = "Module Contains Field With 'select'; Test Info";
        $bug->description = file_get_contents(dirname(__FILE__)."/bug_60870_text.txt");
        $bug->save();

        $bug = new Bug();
        $bug->retrieve($this->bugid);
        $this->assertEquals($this->bugid, $bug->id);
    }

    public function testAddDistinct()
    {
        $query = "SELECT accounts.*,accounts_cstm.selected_c FROM accounts  INNER JOIN (select tst.team_set_id from team_sets_teams tst INNER JOIN team_memberships team_memberships ON tst.team_id = team_memberships.team_id
                                        AND team_memberships.user_id = 'seed_jim_id'
                                        AND team_memberships.deleted=0 group by tst.team_set_id) accounts_tf on accounts_tf.team_set_id  = accounts.team_set_id LEFT JOIN users
                                        ON accounts.assigned_user_id=users.id  LEFT JOIN  team_sets ts ON accounts.team_set_id=ts.id  AND ts.deleted=0
                LEFT JOIN  teams teams ON teams.id=ts.id AND teams.deleted=0 AND teams.deleted=0";
        $db = new Bug60780Test_Db($GLOBALS['db']);
        $db->addDistinctClause($query);
        $this->assertContains("INNER JOIN team_sets_teams tst ON tst.team_set_id = accounts.team_set_id", $query);
        $this->assertContains("accounts_cstm.selected_c", $query);
    }

}

class Bug60780Test_Db extends MysqlManager
{
    public function __construct($db) {
        $this->db = $db;
    }

    public function addDistinctClause(&$sql)
    {
        return $this->db->addDistinctClause($sql);
    }
}
