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
 
require_once("modules/ModuleBuilder/views/view.module.php");

class Bug44372Test extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        global $mod_strings;
        $mod_strings = return_module_language($GLOBALS['current_language'], 'Administration');
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown()
    {
        unset($GLOBALS['mod_strings']);
    }

    /**
     * @outputBuffering enabled
     */
    public function testViewModule()
    {
    	$_REQUEST = array(
            "view_package"=>"",
    	    "module"=>""
        );
        $view = new ViewModule();
        $view->display();
        $this->assertTrue(is_string($view->module), "Assert that view class variable module is not an object");
        // this is to suppress output. Need to fix properly with a good unit test.
        $this->expectOutputRegex('//');
    }
}
