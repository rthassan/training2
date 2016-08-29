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

require_once 'include/api/SugarListApi.php';

/**
 *  RS-162: Prepare SugarList Api.
 */
class RS162Test extends Sugar_PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        SugarTestHelper::setUp('app_list_strings');
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('current_user', array(true, false));
    }

    public static function tearDownAfterClass()
    {
        SugarTestHelper::tearDown();
        parent::tearDownAfterClass();
    }

    public function testParseArgs()
    {
        $api = $this->getMockForAbstractClass('SugarListApi');
        $rest = SugarTestRestUtilities::getRestServiceMock();
        $result = $api->parseArguments(
            $rest,
            array()
        );
        $this->assertArrayHasKey('limit', $result);
        $this->assertArrayHasKey('offset', $result);
        $this->assertArrayHasKey('fields', $result);
        $this->assertArrayHasKey('orderBy', $result);
    }

    public function testConvertOrderByToSql()
    {
        $api = $this->getMockForAbstractClass('SugarListApi');
        $result = $api->convertOrderByToSql(array('date' => 'ASC', 'name' => 'DESC'));
        $this->assertEquals('date ASC,name DESC', $result);
    }
}
