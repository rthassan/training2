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


/* This unit test class covers the ACLs added for extra modules, this does not cover the Users/Employees modules, those are more intense. */
class SugarACLModuleTest extends Sugar_PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('current_user');
        $old_current_user = $GLOBALS['current_user'];
        $new_current_user = new SugarBeanAclModuleUserMock();
        $GLOBALS['current_user'] = $new_current_user;
        $new_current_user->retrieve($old_current_user->id);
    }

    public static function tearDownAfterClass()
    {
        SugarTestHelper::tearDown();
    }

    public function tearDown()
    {
        $this->getAclAction()->clearACLCache();
        $GLOBALS['current_user']->clearAdminForAllModules();
    }

    public function getAclAction()
    {
        static $aclAction;
        if ( !isset($aclAction) ) {
            $aclAction = BeanFactory::getBean('ACLActions');
        }
        return $aclAction;
    }

    public function moduleAccessTestSet()
    {
        return array(
            // Normal users will have full access to the Accounts module, so this is just checking we didn't mess that up.
            array('module'=>'Accounts',         'view'=>'any'  ,'edit'=>'any'  ,'delete'=>'any'  ),
            array('module'=>'ACLActions',       'view'=>'admin','edit'=>'admin','delete'=>'admin','acl_module'=>'Users'),
            array('module'=>'ACLFields',        'view'=>'admin','edit'=>'admin','delete'=>'admin','acl_module'=>'Users'),
            array('module'=>'ACLRoles',         'view'=>'admin','edit'=>'admin','delete'=>'admin','acl_module'=>'Users'),
            array('module'=>'ContractTypes',    'view'=>'admin','edit'=>'admin','delete'=>'admin','acl_module'=>'Contracts'),
            array('module'=>'Currencies',       'view'=>'any'  ,'edit'=>'admin','delete'=>'admin'),
            array('module'=>'CustomQueries',    'view'=>'any'  ,'edit'=>'admin','delete'=>'admin'),
            array('module'=>'DataSets',         'view'=>'any'  ,'edit'=>'admin','delete'=>'admin'),
            array('module'=>'Expressions',      'view'=>'dev'  ,'edit'=>'dev'  ,'delete'=>'dev'),
            array('module'=>'Holidays',         'view'=>'any'  ,'edit'=>'admin','delete'=>'admin','acl_module'=>'Users'),
            // Manufacturers won't be showing on acl-role edit panel, user can't view/edit/delete it
            // array('module'=>'Manufacturers',    'view'=>'any'  ,'edit'=>'admin','delete'=>'admin','acl_module'=>'Products'),
            array('module'=>'OAuthKeys',        'view'=>'admin','edit'=>'admin','delete'=>'admin'),
            array('module'=>'ProductCategories','view'=>'any'  ,'edit'=>'admin','delete'=>'admin','acl_module'=>'Products'),
            array('module'=>'ProductTemplates', 'view'=>'any'  ,'edit'=>'admin','delete'=>'admin','acl_module'=>'Products'),
            array('module'=>'ProductTypes',     'view'=>'any'  ,'edit'=>'admin','delete'=>'admin','acl_module'=>'Products'),
            array('module'=>'Releases',         'view'=>'any'  ,'edit'=>'admin','delete'=>'admin','acl_module'=>'Bugs'),
            array('module'=>'Roles',            'view'=>'any'  ,'edit'=>'admin','delete'=>'admin','acl_module'=>'Users'),
            array('module'=>'Schedulers',       'view'=>'admin','edit'=>'admin','delete'=>'admin'),
            array('module'=>'SchedulersJobs',   'view'=>'admin','edit'=>'admin','delete'=>'admin'),
            array('module'=>'Shippers',         'view'=>'any'  ,'edit'=>'admin','delete'=>'admin','acl_module'=>'Products'),
            array('module'=>'TaxRates',         'view'=>'any'  ,'edit'=>'admin','delete'=>'admin','acl_module'=>'Quotes'),
            array('module'=>'Teams',            'view'=>'any'  ,'edit'=>'admin','delete'=>'admin','acl_module'=>'Users'),
            array('module'=>'TimePeriods',      'view'=>'any'  ,'edit'=>'admin','delete'=>'admin','acl_module'=>'Forecasts'),
        );
    }

    /**
     * Tests a specific setup of ACL's
     * @dataProvider moduleAccessTestSet
     */
    public function testAcl($module, $view, $edit, $delete, $acl_module = '')
    {
        if ( empty($acl_module) ) {
            $acl_module = $module;
        }
        $testBean = BeanFactory::newBean($module);

        // First, no admin, no module admin, no developer for any.
        $canView = $testBean->ACLAccess('view');
        if ( $view == 'any' ) {
            $this->assertTrue($canView,"Any user should be able to view.");
        } else {
            $this->assertFalse($canView,"Only admins should be able to view.");
        }

        $canEdit = $testBean->ACLAccess('edit');
        if ( $edit == 'any' ) {
            $this->assertTrue($canEdit,"Any user should be able to edit.");
        } else {
            $this->assertFalse($canEdit,"Only admins should be able to edit.");
        }

        $canDelete = $testBean->ACLAccess('delete');
        if ( $delete == 'any' ) {
            $this->assertTrue($canDelete,"Any user should be able to delete.");
        } else {
            $this->assertFalse($canDelete,"Only admins should be able to delete.");
        }

        // Second, is admin, not module admin specifically
        $GLOBALS['current_user']->is_admin = 1;
        $this->getAclAction()->clearACLCache();
        $canView = $testBean->ACLAccess('view');
        if ( $view == 'any' || $view == 'admin' ) {
            $this->assertTrue($canView,"I am a system admin and I should be able to view.");
        } else {
            $this->assertFalse($canView,"A system admin was denied the abilitiy to view.");
        }

        $canEdit = $testBean->ACLAccess('edit');
        if ( $edit == 'any' || $edit == 'admin' ) {
            $this->assertTrue($canEdit,"I am a system admin and I should be able to edit.");
        } else {
            $this->assertFalse($canEdit,"A system admin was denied the abilitiy to edit.");
        }

        $canDelete = $testBean->ACLAccess('delete');
        if ( $delete == 'any' || $delete == 'admin' ) {
            $this->assertTrue($canDelete,"I am a system admin and I should be able to delete.");
        } else {
            $this->assertFalse($canDelete,"A system admin was denied the abilitiy to delete.");
        }

        // Third, not system admin, but module admin
        $GLOBALS['current_user']->is_admin = 0;
        $GLOBALS['current_user']->setAdminForModule($acl_module);
        $this->getAclAction()->clearACLCache();

        $canView = $testBean->ACLAccess('view');
        if ( $view == 'any' || $view == 'admin' ) {
            $this->assertTrue($canView,"I am a module admin and I should be able to view.");
        } else {
            $this->assertFalse($canView,"A module admin was denied the abilitiy to view.");
        }

        $canEdit = $testBean->ACLAccess('edit');
        if ( $edit == 'any' || $edit == 'admin' ) {
            $this->assertTrue($canEdit,"I am a module admin and I should be able to edit.");
        } else {
            $this->assertFalse($canEdit,"A module admin was denied the abilitiy to edit.");
        }

        $canDelete = $testBean->ACLAccess('delete');
        if ( $delete == 'any' || $delete == 'admin' ) {
            $this->assertTrue($canDelete,"I am a module admin and I should be able to delete.");
        } else {
            $this->assertFalse($canDelete,"A module admin was denied the abilitiy to delete.");
        }

        $GLOBALS['current_user']->clearAdminForAllModules();

        // Fourth, no admin, developer for any module.
        $GLOBALS['current_user']->is_admin = 0;
        $GLOBALS['current_user']->setDeveloperForAny(true);
        $this->getAclAction()->clearACLCache();

        $canView = $testBean->ACLAccess('view');
        $canEdit = $testBean->ACLAccess('edit');
        $canDelete = $testBean->ACLAccess('delete');

        $GLOBALS['current_user']->setDeveloperForAny(false);

        if ($view == 'any' || $view == 'dev') {
            $this->assertTrue($canView, 'Developer should be able to view.');
        } else {
            $this->assertFalse($canView, 'Only admin should be able to view.');
        }

        if ($edit == 'any' || $edit == 'dev') {
            $this->assertTrue($canEdit, 'Developer should be able to edit.');
        } else {
            $this->assertFalse($canEdit, 'Only admin should be able to edit.');
        }

        if ($delete == 'any' || $delete == 'dev') {
            $this->assertTrue($canDelete, 'Developer should be able to delete.');
        } else {
            $this->assertFalse($canDelete, 'Only admin should be able to delete.');
        }
    }
}

/*
 * Testing ACL's is annoying, it does all these checks on the current user
 * It's tricky to get the current user to cooperate, so we're going to build
 * a mock that will let us set the properties directly.
 */

class SugarBeanAclModuleUserMock extends User
{
    protected $adminForModules = array();
    protected $isDeveloperForAny = false;

    public function clearAdminForAllModules()
    {
        $this->adminForModules = array();
    }

    public function setAdminForModule($module)
    {
        $this->adminForModules[$module] = true;
    }

    public function isAdminForModule($module)
    {
        if ( $this->isAdmin() ) {
            return true;
        }

        if ( isset($this->adminForModules[$module]) && $this->adminForModules[$module] ) {
            return true;
        } else {
            return false;
        }
    }

    public function getAdminModules() {
        return array_keys($this->adminForModules);
    }

    public function setDeveloperForAny($status) {
        $this->isDeveloperForAny = $status;
    }

    public function isDeveloperForAnyModule() {
        return $this->isDeveloperForAny;
    }
}
