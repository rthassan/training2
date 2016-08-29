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

require_once("data/BeanFactory.php");

class Bug65865Test extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();

        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('current_user');
    }

    public function tearDown()
    {
        SugarTestAccountUtilities::removeAllCreatedAccounts();
        SugarTestuserUtilities::removeAllCreatedAnonymousUsers();
        SugarTestHelper::tearDown();
        parent::tearDown();
    }

    public function testGetBeanDeleted()
    {
        $account = SugarTestAccountUtilities::createAccount();
        $account->name = "Test deleted";
        $account->save();
        $account->mark_deleted($account->id);
        $this->assertNotNull(BeanFactory::getBean('Accounts', $account->id, array('deleted' => false, 'strict_retrieve' => true)));
        $this->assertNull(BeanFactory::getBean('Accounts', $account->id,  array('strict_retrieve' => true)));
    }

    public function testGetBeanDisableRowLevelSecurity()
    {
        $account = SugarTestAccountUtilities::createAccount();
        $account->name = "Test disable_row_level_security";
        $user = SugarTestUserUtilities::createAnonymousUser();
        $teamSet = new TeamSet();
        $teamSet->addTeams($user->getPrivateTeamID());
        $account->team_id = $user->getPrivateTeamID();
        $account->team_set_id = $teamSet->id;
        $account->assigned_user_id = $user->id;
        $account->disable_row_level_security = true;
        $account->save();
        $this->assertNotNull(BeanFactory::getBean('Accounts', $account->id, array('disable_row_level_security' => true, 'strict_retrieve' => true)));
        $this->assertNull(BeanFactory::getBean('Accounts', $account->id,  array('strict_retrieve' => true)));
    }
}
