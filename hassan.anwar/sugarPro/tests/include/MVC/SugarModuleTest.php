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
 
require_once 'include/MVC/SugarModule.php';

class SugarModuleTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
        SugarTestHelper::setUp("current_user");
        $GLOBALS['current_user']->is_admin = '1';
    }
    
    public function testLoadBean()
    {
        $beanList = array('Accounts'=>'Account');
        $beanFiles = array('Account'=>'modules/Accounts/Account.php');
        $bean = SugarModule::get('Accounts')->loadBean($beanList,$beanFiles,false);
        $this->assertInstanceOf('Account', $bean, "Expecting Account bean");
    }
    
    public function testLoadBeanInvalidBean()
    {
        $bean = SugarModule::get('JohnIsACoolGuy')->loadBean(array(),array(),false);
        $this->assertTrue(is_null($bean), "Invalid Module to loadBean - expecting NULL");
    }
    
    public function testModuleImpliments()
    {
        $this->assertTrue(SugarModule::get('Accounts')->moduleImplements('Company'));
    }
    
    public function testModuleImplimentsInvalidBean()
    {
        $this->assertFalse(SugarModule::get('JohnIsACoolGuy')->moduleImplements('Person'));
    }
    
    public function testModuleImplimentsWhenModuleDoesNotImplimentTemplate()
    {
        $this->assertFalse(SugarModule::get('Accounts')->moduleImplements('Person'));
    }
}
