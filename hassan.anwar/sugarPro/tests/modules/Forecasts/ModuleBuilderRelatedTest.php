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

require_once('modules/ModuleBuilder/Module/DropDownBrowser.php');

class ModuleBuilderRelatedTest extends Sugar_PHPUnit_Framework_TestCase
{

    public static function setUpBeforeClass() {
        SugarTestHelper::setUp('app_list_strings');
    }

    public static function tearDownAfterClass() {
        SugarTestHelper::tearDown();
    }

    /**
     * This is a test to check that the commit stage labels are not shown on the drop down editor
     *
     * @group forecasts
     * @group bug59133
     */
    public function testRestrictedDropdownOptions() {
        $this->assertTrue(in_array('commit_stage_dom', DropDownBrowser::$restrictedDropdowns));
        $this->assertTrue(in_array('commit_stage_binary_dom', DropDownBrowser::$restrictedDropdowns));
        $this->assertTrue(in_array('commit_stage_custom_dom', DropDownBrowser::$restrictedDropdowns));
    }

}
