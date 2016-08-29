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

require_once('include/dir_inc.php');
require_once('modules/UpgradeWizard/UpgradeRemoval.php');

class Bug46027Test extends Sugar_PHPUnit_Framework_TestCase 
{

	public function setUp()
	{		
		if(file_exists('custom/backup/include/utils/external_cache'))
		{
			rmdir_recursive('custom/backup/include/utils/external_cache');
			rmdir_recursive('custom/backup/include/utils');
			rmdir_recursive('custom/backup/include');	
		}
		
		if(file_exists('include/JSON.js'))
		{
			unlink('include/JSON.js');
		}		
		
		//Simulate file and directory that should be removed by UpgradeRemove62x.php
		copy('include/JSON.php', 'include/JSON.js');
		mkdir_recursive('include/utils/external_cache');		
	}
	
	/**
	 * ensure that the test directory and file are removed at the end of the test
	 */
	public function tearDown()
	{
		if(file_exists('include/utils/external_cache'))
		{
		   rmdir_recursive('include/utils/external_cache');
		}
		
		if(file_exists('include/JSON.js'))
		{
		   unlink('include/JSON.js');	
		}
		
		if(file_exists('custom/backup/include/utils/external_cache'))
		{
			rmdir_recursive('custom/backup/include/utils/external_cache');
			rmdir_recursive('custom/backup/include/utils');
			rmdir_recursive('custom/backup/include');
		}		
	}
	
	public function testUpgradeRemoval()
	{
		$instance = new UpgradeRemoval62xMock();
		$instance->processFilesToRemove($instance->getFilesToRemove(622));
		$this->assertTrue(!file_exists('include/utils/external_cache'), 'Assert that include/utils/external_cache was removed');
		$this->assertTrue(file_exists('custom/backup/include/utils/external_cache'), 'Assert that the custom/backup/include/utils/external_cache directory was created');		
		$this->assertTrue(!file_exists('include/JSON.js'), 'Assert that include/JSON.js file is removed');
		$this->assertTrue(file_exists('custom/backup/include/JSON.js'), 'Assert that include/JSON.js was moved to custom/backup/include/JSON.js');
	}
	
}

class UpgradeRemoval62xMock extends UpgradeRemoval
{
	
public function getFilesToRemove($version)
{
	$files = array();
	$files[] = 'include/utils/external_cache';
	$files[] = 'include/JSON.js';
	return $files;
}

}
?>