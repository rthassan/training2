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
require_once('include/SugarFields/SugarFieldHandler.php');

class SugarFieldMultienumTest extends Sugar_PHPUnit_Framework_TestCase
{

    public static function setUpBeforeClass()
    {
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('app_list_strings');
    }

    public static function tearDownAfterClass()
    {
        SugarTestHelper::tearDown();
    }

    /**
     * @group export
     */
    public function testExportSanitize()
    {
        global $app_list_strings;
        $app_list_strings['multienum_test'] = array(
            'a' => 'A',
            'b' => 'B',
            'c' => 'C'
        );

        $obj = BeanFactory::getBean('Opportunities');
        $vardef = $obj->field_defs['sales_stage'];
        $vardef['options'] = 'multienum_test';

        $field = SugarFieldHandler::getSugarField('multienum');
        $value = $field->exportSanitize('^a^,^b^,^c^', $vardef, $obj);
        $this->assertEquals('A,B,C', $value);

        $value = $field->exportSanitize('a', $vardef, $obj);
        $this->assertEquals('A', $value);

    }

}