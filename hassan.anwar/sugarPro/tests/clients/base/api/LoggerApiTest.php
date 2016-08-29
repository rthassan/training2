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
require_once 'clients/base/api/LoggerApi.php';

/**
 * @group ApiTests
 */
class LoggerApiTest extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * @var LoggerApi
     */
    protected $api;

    /**
     * @var RestService
     */
    protected $serviceMock;

    protected function setUp()
    {
        $this->api = new LoggerApi();
        $this->serviceMock = SugarTestRestUtilities::getRestServiceMock();
    }

    public static function tearDownAfterClass()
    {
        SugarTestHelper::tearDown();
    }

    public function testLogMessage()
    {
        $result = $this->api->logMessage($this->serviceMock, array('level' => 'fatal', 'message' => 'Unit Test'));

        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('status', $result);
    }
}
