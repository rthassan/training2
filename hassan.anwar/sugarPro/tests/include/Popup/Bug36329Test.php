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
 
require_once('include/OutboundEmail/OutboundEmail.php');

/**
 * @ticket 23140
 */
class Bug36329Test extends Sugar_PHPUnit_Framework_TestCase
{
	var $save_query;
	var $current_language;

	public function setUp()
	{
		global $sugar_config;
		if(isset($sugar_config['save_query']))
        {
            $this->save_query = $sugar_config['save_query'];
        }
		$this->current_language = $GLOBALS['current_language'];

		global $current_user;
		$current_user = new User();
		$current_user->retrieve('1');

		global $mod_strings, $app_strings;
		$mod_strings = return_module_language('en_us', 'Accounts');
		$app_strings = return_application_language('en_us');

		$beanList = array();
		$beanFiles = array();
		require('include/modules.php');
		$GLOBALS['beanList'] = $beanList;
		$GLOBALS['beanFiles'] = $beanFiles;

		require('sugar_version.php');
		$GLOBALS['sugar_version'] = $sugar_version;
	}

	public function tearDown()
	{
	    global $sugar_config;
		if(!empty($this->save_query)) {
		   $sugar_config['save_query'] = $this->save_query;
		}

		$GLOBALS['current_language'] = $this->current_language;
		//unset($GLOBALS['mod_strings']);
		//unset($GLOBALS['app_strings']);
		//unset($GLOBALS['beanList']);
		//unset($GLOBALS['beanFiles']);
	}

    public function test_populate_only_no_query()
    {
    	$GLOBALS['sugar_config']['save_query'] = 'populate_only';
    	$_REQUEST['module'] = 'Accounts';
    	$_REQUEST['action'] = 'Popup';
    	$_REQUEST['mode'] = 'single';
    	$_REQUEST['create'] = 'true';
    	$_REQUEST['metadata'] = 'undefined';
    	require_once('include/MVC/View/SugarView.php');
    	require_once('include/MVC/View/views/view.popup.php');
    	require_once('include/utils/layout_utils.php');
    	$popup = new ViewPopup();
    	$popup->module = 'Accounts';
    	require_once('modules/Accounts/Account.php');
    	$popup->bean = new account();
    	$this->expectOutputRegex('/Perform a search using the search form above/');
    	$popup->display();
    }


    public function test_populate_only_with_query()
    {
    	$GLOBALS['sugar_config']['save_query'] = 'populate_only';
    	$_REQUEST['module'] = 'Accounts';
    	$_REQUEST['action'] = 'Popup';
    	$_REQUEST['mode'] = 'single';
    	$_REQUEST['create'] = 'true';
    	$_REQUEST['metadata'] = 'undefined';
    	$_REQUEST['name_advanced'] = 'Test';
    	$_REQUEST['query'] = 'true';
    	require_once('include/MVC/View/SugarView.php');
    	require_once('include/MVC/View/views/view.popup.php');
    	require_once('include/utils/layout_utils.php');
    	$popup = new ViewPopup();
    	$popup->module = 'Accounts';
    	require_once('modules/Accounts/Account.php');
    	$popup->bean = new account();
    	// Negative regexp
    	$this->expectOutputNotRegex('/Perform a search using the search form above/');
    	$popup->display();
    }
}
