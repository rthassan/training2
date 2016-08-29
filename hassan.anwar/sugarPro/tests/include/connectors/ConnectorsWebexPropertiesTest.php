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
require_once('include/connectors/ConnectorsTestCase.php');

class ConnectorsWebexPropertiesTest extends Sugar_Connectors_TestCase {

	function setUp() {
        SugarTestHelper::setUp('app_list_strings');
        parent::setUp();
    	if(file_exists('custom/modules/Connectors/connectors/sources/ext/eapm/webex/config.php')) {
    	   mkdir_recursive('custom/modules/Connectors/backup/connectors/sources/ext/eapm/webex');
    	   copy_recursive('custom/modules/Connectors/connectors/sources/ext/eapm/webex', 'custom/modules/Connectors/backup/connectors/sources/ext/eapm/webex');
    	} else {
    	   mkdir_recursive('custom/modules/Connectors/connectors/sources/ext/eapm/webex');
    	}
    }

    function tearDown() {
        parent::tearDown();
        if(file_exists('custom/modules/Connectors/backup/connectors/sources/ext/eapm/webex')) {
    	   copy_recursive('custom/modules/Connectors/backup/connectors/sources/ext/eapm/webex', 'custom/modules/Connectors/connectors/sources/ext/eapm/webex');
           ConnectorsTestUtility::rmdirr('custom/modules/Connectors/backup/connectors/sources/ext/eapm/webex');
        }
        SugarTestHelper::tearDown();
    }

    function testWebexProperty() {

        require_once('modules/Connectors/controller.php');
    	require_once('include/MVC/Controller/SugarController.php');
    	$controller = new ConnectorsController();
    	$_REQUEST['action'] = 'SaveModifyProperties';
    	$_REQUEST['module'] = 'Connectors';
    	$url = 'http://test/'.create_guid();
    	$_REQUEST['source0'] = 'ext_eapm_webex';
    	$_REQUEST['ext_eapm_webex_url'] = $url;
    	$_REQUEST['from_unit_test'] = true;
    	$controller->action_SaveModifyProperties();

    	require('custom/modules/Connectors/connectors/sources/ext/eapm/webex/config.php');
    	$webex = SourceFactory::getSource('ext_eapm_webex', false);
    	$this->assertEquals($url, $webex->getProperty('url'));
    }

}
