<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

require_once "include/generic/SugarWidgets/SugarWidget.php";

class SugarWidgetTest extends Sugar_PHPUnit_Framework_TestCase
{

    public static function setUpBeforeClass()
    {
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('beanList');
    }

    public static function tearDownAfterClass()
    {
        SugarTestHelper::tearDown();
    }

    /**
     * This is a test to ensure that the global list of exempt modules will not cause SugarWidget::isModuleHidden to return true
     *
     */
    public function testIsHiddenModuleForExemptModules() {
        global $modules_exempt_from_availability_check;
        $expectedCount = count($modules_exempt_from_availability_check);
        $falseCount = 0;
        foreach($modules_exempt_from_availability_check as $module) {
            if(!SugarWidget::isModuleHidden($module)) {
                $falseCount++;
            }
        }

        $this->assertEquals($expectedCount, $falseCount, "Failed asserting that modules in \$modules_exempt_from_availability_check return false");
    }

}