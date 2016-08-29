<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
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

require_once('include/externalAPI/Dnb/ExtAPIDnb.php');

/**
 * Class ExtAPIDnbTest
 */
class ExtAPIDnbTest extends Sugar_PHPUnit_Framework_TestCase
{
    protected function setUp() {
        //create object of extapidnb
        $this->dnbApiObj = new ExtAPIDnb();
        //create a mock object for curlWrapper
        $this->dnbCurlMock = $this->getMock('DnbCurlWrapper');
    }

    /**
     * @dataProvider providerForCheckToken
     */
    public function testCheckTokenValidity($mockResponse, $expectedResult) {
        //set the mock object to dnbApiObj
        $this->dnbApiObj->setCurlWrapper($this->dnbCurlMock);
        $this->dnbCurlMock->expects($this->any())
            ->method('execute')
            ->will($this->returnValue($mockResponse));
        $this->dnbCurlMock->expects($this->any())
            ->method('getErrorNo')
            ->will($this->returnValue(null));
        $this->dnbCurlMock->expects($this->any())
            ->method('getError')
            ->will($this->returnValue(null));
        $this->assertEquals($this->dnbApiObj->checkTokenValidity(), $expectedResult);
    }

    public function providerForCheckToken()
    {
        return array (
            array(
                array('httpStatus' => 204, 'curlResponse' => "Authorization: dnbAuthToken"),
                true
            ),
            array(
                array('httpStatus' => 200, 'curlResponse' => "Authorization: dnbAuthToken"),
                true
            ),
            array(
                array('httpStatus' => 200, 'curlResponse' => false),
                false
            )
        );
    }
}
