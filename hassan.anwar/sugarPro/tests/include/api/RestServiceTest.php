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

require_once 'tests/SugarTestReflection.php';
require_once 'include/api/RestService.php';

class RestServiceTest extends Sugar_PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('beanList');

    }

    public static function tearDownAfterClass()
    {
        SugarTestHelper::tearDown();
        $_GET = array();
        if (isset($GLOBALS['HTTP_RAW_POST_DATA'])) {
            unset($GLOBALS['HTTP_RAW_POST_DATA']);
        }
    }

    public function tearDown()
    {
        while ( ob_get_level() > 1 ) {
            ob_end_flush();
        }
    }

    public function testGetRequestArgs()
    {
        $request = $this->getMock('request', array('getPathVars', 'getPostContents', 'getQueryVars'));
        $request->expects($this->any())
                ->method('getPathVars')
                ->will($this->returnValue(array()));

        $_GET = array('my_json'=>'{"christopher":"walken","bill":"murray"}');
        $GLOBALS['HTTP_RAW_POST_DATA'] = '';
        $request->expects($this->any())
            ->method('getPostContents')
            ->will($this->onConsecutiveCalls(
            "", '{"my_json":{"christopher":"walken","bill":"murray"}}', '{"my_json":{"christopher":"walken","bill":"murray"}}}'
        ));

        $request->expects($this->any())
        ->method('getQueryVars')
        ->will($this->onConsecutiveCalls(
            array('my_json'=>'{"christopher":"walken","bill":"murray"}'),
            array('my_json'=>'{"christopher":"walken","bill":"murray"}}'),
            array()
        ));


        $service = new RestService();
        SugarTestReflection::setProtectedValue($service, 'request', $request);

        $output = SugarTestReflection::callProtectedMethod($service, 'getRequestArgs', array(array('jsonParams'=>array('my_json'))));
        $this->assertArrayHasKey('christopher', $output['my_json'], "Missing Christopher => Walken #1");
        $this->assertArrayHasKey('bill', $output['my_json'], "Missing Bill => Murray #1");

        $hadException = false;
        try {
            $output = SugarTestReflection::callProtectedMethod($service, 'getRequestArgs', array(array('jsonParams'=>array('my_json'))));
        } catch ( SugarApiExceptionInvalidParameter $e ) {
            $hadException = true;
        }

        $this->assertTrue($hadException, "Did not throw an exception on invalid JSON #1");

        $output = SugarTestReflection::callProtectedMethod($service, 'getRequestArgs', array(array()));

        $this->assertArrayHasKey('christopher', $output['my_json'], "Missing Christopher => Walken #2");
        $this->assertArrayHasKey('bill', $output['my_json'], "Missing Bill => Murray #2");

        $hadException = false;
        try {
            $output = SugarTestReflection::callProtectedMethod($service, 'getRequestArgs', array(array()));
        } catch ( SugarApiExceptionInvalidParameter $e ) {
            $hadException = true;
        }

        $this->assertTrue($hadException, "Did not throw an exception on invalid JSON #2");

    }

    /**
     * @expectedException SugarApiExceptionError
     */
    public function testHandleErrorOutput()
    {
        ob_start();
        echo "I'm a little teapot.";
        echo "Short and stout.";
        echo "Here is my handle.";
        echo "Here is my exception.";

        $restService = new RestService();
        $restService->handleErrorOutput();

    }
}
