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

class GetTemplateNameForNotificationEmailTest extends Sugar_PHPUnit_Framework_TestCase
{
    protected $accountBeanName;
    protected $accountObjectName;

    protected function setUp()
    {
        parent::setUp();
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('current_user');
        global $beanList, $objectList;
        if (isset($beanList['Accounts'])) {
            $this->accountBeanName = $beanList['Accounts'];
        }
        if (isset($objectList['Accounts'])) {
            $this->accountObjectName = $objectList['Accounts'];
        }
        $beanList['Accounts'] = 'CustomAccountGetTemplateName';
        $objectList['Accounts'] = 'Account';
    }

    protected function tearDown()
    {
        global $beanList, $objectList;
        if (isset($this->accountBeanName)) {
            $beanList['Accounts'] = $this->accountBeanName;
        }
        else {
            unset($beanList['Accounts']);
        }
        if (isset($this->accountObjectName)) {
            $objectList['Accounts'] = $this->accountObjectName;
        }
        else {
            unset($objectList['Accounts']);
        }
        SugarTestHelper::tearDown();
        parent::tearDown();
    }

    public function testCustomModule()
    {
        $account = new CustomAccountGetTemplateName();
        $this->assertEquals('Account', SugarTestReflection::callProtectedMethod($account, 'getTemplateNameForNotificationEmail'), 'Template name should be Account');
    }
}

class CustomAccountGetTemplateName extends Account
{
}
