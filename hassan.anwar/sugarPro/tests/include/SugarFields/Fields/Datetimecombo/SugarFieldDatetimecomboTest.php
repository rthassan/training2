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

class SugarFieldDatetimecomboTest extends Sugar_PHPUnit_Framework_TestCase
{

    public static function setUpBeforeClass()
    {
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('current_user');
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
        $timedate = TimeDate::getInstance();
        $db = DBManagerFactory::getInstance();
        $now = $timedate->getNow();
        $isoDate = $timedate->asIso($now);
        $dbDatetime = $timedate->asDb($now);
        $expectedTime = $timedate->to_display_date_time($db->fromConvert($dbDatetime, 'datetime'));
        $expectedTime = preg_replace('/([pm|PM|am|AM]+)/', ' \1', $expectedTime);

        $obj = BeanFactory::getBean('Opportunities');
        $obj->date_modified = $isoDate;

        $vardef = $obj->field_defs['date_modified'];

        $field = SugarFieldHandler::getSugarField('datetimecombo');
        $value = $field->exportSanitize($obj->date_modified, $vardef, $obj);
        $this->assertEquals($expectedTime, $value);

        $obj->date_modified = $dbDatetime;
        $value = $field->exportSanitize($obj->date_modified, $vardef, $obj);
        $this->assertEquals($expectedTime, $value);
    }

}