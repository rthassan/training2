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
require_once('install/install_utils.php');

class InstallUtilsTest extends Sugar_PHPUnit_Framework_TestCase
{
    private static $configJSContents;

    public static function setUpBeforeClass()
    {
        if(file_exists('config.js')) {
           self::$configJSContents = file_get_contents('config.js');
           unlink('config.js');
        }
    }

    public static function tearDownAfterClass()
    {
        //If we had existing config.js content, copy it back in
        if(!empty(self::$configJSContents)) {
            file_put_contents('config.js', self::$configJSContents);
        }
    }

	public function testParseAcceptLanguage()
	{
		$_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'en-US,en;q=0.8';
       	$lang = parseAcceptLanguage();
       	$this->assertEquals('en_us,en', $lang, 'parse_accept_language did not return proper values');
	}

	public function testRemoveConfig_SIFile(){
		if(write_array_to_file('config_si', array(), 'config_si.php')) {
			removeConfig_SIFile();
			$this->assertFileNotExists('config_si.php', 'removal of config_si did not succeed');
			SugarAutoLoader::delFromMap('config_si.php');
		}
	}

    /**
     * This is a test to check the creation of the config.js file used by the sidecar framework beginning in the 6.7 release.
     * In the future this configuration may move to be contained within a database.
     */
    public function testHandleSidecarConfig()
    {
        $file = sugar_cached('config.js');
        handleSidecarConfig();
        $this->assertFileExists($file);
        $configJSContents = file_get_contents($file);

        $this->assertNotEmpty($configJSContents);
        $this->assertRegExp('/\"platform\"\s*?\:\s*?\"base\"/', $configJSContents);
        $this->assertRegExp('/\"clientID\"\s*?\:\s*?\"sugar\"/', $configJSContents);
    }
}
