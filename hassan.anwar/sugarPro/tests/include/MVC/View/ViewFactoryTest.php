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
 
require_once 'include/MVC/View/ViewFactory.php';

class ViewFactoryTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $GLOBALS['app_strings'] = array();
        $GLOBALS['mod_strings'] = array();
    }
    
    public function tearDown()
    {
        unset($GLOBALS['app_strings']);
        unset($GLOBALS['mod_strings']);
    }
    
    public function testLoadView()
    {
    	$view = ViewFactory::loadView('detail', 'Contacts');
    	$className = get_class($view);
    	$this->assertEquals($className,'ContactsViewDetail', 'Ensure that we load the right view for Contacts');
    }
}
