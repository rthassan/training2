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

/**
 * @ticket 67014
 */
class Bug67014Test extends Sugar_PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        $_GET = array();
        parent::tearDown();
    }

    public function testNoException()
    {
        global $service_object;

        require_once 'service/core/SugarWebService.php';
        $service_object = $this->getMockForAbstractClass('SugarWebService');

        require_once 'service/v4/SugarWebServiceUtilv4.php';
        $helper = new SugarWebServiceUtilv4();

        require_once 'soap/SoapError.php';
        $error = new SoapError();

        $_GET['oauth_signature_method'] = null;

        $result = $helper->checkOAuthAccess($error);
        $this->assertFalse($result);
    }
}
