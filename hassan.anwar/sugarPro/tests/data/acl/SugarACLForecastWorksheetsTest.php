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

class SugarACLForecastWorksheetsTest extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * @group forecasts
     */
    public function testCheckAccessWithViewEqualToField()
    {
        $beanMock = $this->getMock('Product', array('ACLFieldAccess'));
        $beanMock->expects($this->once())
            ->method('ACLFieldAccess')
            ->will($this->returnValue(true));

        $userMock = $this->getMock('User');
        $userMock->id = 'test_user_id';

        $acl_class = $this->getMock('SugarACLForecastWorksheets', array('getForecastByBean'));
        $acl_class->expects($this->once())
            ->method('getForecastByBean')
            ->will($this->returnValue($beanMock));

        $context = array('field' => 'test_field', 'action' => 'write', 'user' => $userMock);

        $ret = $acl_class->checkAccess('ForecastWorksheets', 'field', $context);

        $this->assertTrue($ret);
    }

    /**
     * @group forecasts
     */
    public function testCheckAccessWithViewNotEqualToField()
    {
        $beanMock = $this->getMock('Product', array('ACLFieldAccess'));
        $beanMock->expects($this->never())
            ->method('ACLFieldAccess');

        $userMock = $this->getMock('User');
        $userMock->id = 'test_user_id';

        $acl_class = $this->getMock('SugarACLForecastWorksheets', array('getForecastByBean'));
        $acl_class->expects($this->once())
            ->method('getForecastByBean')
            ->will($this->returnValue($beanMock));

        $context = array('field' => 'test_field', 'action' => 'write', 'user' => $userMock);

        $ret = $acl_class->checkAccess('ForecastWorksheets', 'view', $context);

        $this->assertTrue($ret);
    }
}
