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

/*
 * Bug 45181: Please remove "Log Memory Usage" if useless
 * @ticket 45181
 */

class Bug45181 extends Sugar_PHPUnit_Framework_TestCase {
    private $sugar_config;
    private $sugarView;

    function setUp()
    {
        $GLOBALS['app_strings'] = return_application_language($GLOBALS['current_language']);
        global $sugar_config;
        $this->sugar_config = $sugar_config;
        $this->sugarView = new Bug45181TestSugarViewMock();
        $this->sugarView->module = 'Contacts';
        $this->sugarView->action = 'EditView';
        if (is_file('memory_usage.log'))
        {
            unlink('memory_usage.log');
        }
    }

    function tearDown()
    {
        global $sugar_config;
        if (is_file('memory_usage.log'))
        {
            unlink('memory_usage.log');
        }
        $sugar_config = $this->sugar_config;
        unset($this->sugar_config);
        unset($GLOBALS['app_strings']);
    }


    /**
     * testLogMemoryUsageOn
     * This test asserts that when log_memory_usage is set to true we receive a log message from the function
     * call and the memory_usage.log file is created.
     *
     * @outputBuffering enabled
     */
    function testLogMemoryUsageOn()
    {
        if(!function_exists('memory_get_usage') || !function_exists('memory_get_peak_usage'))
        {
            $this->markTestSkipped('Skipping test since memory_get_usage and memory_get_peak_usage function are unavailable');
            return;
        }
        global $sugar_config;
        $sugar_config['log_memory_usage'] = true;
        $output = $this->sugarView->logMemoryStatisticsTest("\n");
        $this->assertNotEmpty($output, "Failed to recognize log_memory_usage = true setting");
        $this->assertFileExists('memory_usage.log', 'Unable to create memory_usage.log file');
    }

    /**
     * testLogMemoryUsageOff
     * This test asserts that when log_memory_usage is set to false we do not receive a log message from the function
     * call nor is the memory_usage.log file created.
     *
     * @outputBuffering enabled
     *
     */
    function testLogMemoryUsageOff()
    {
        if(!function_exists('memory_get_usage') || !function_exists('memory_get_peak_usage'))
        {
            $this->markTestSkipped('Skipping test since memory_get_usage and memory_get_peak_usage function are unavailable');
            return;
        }
        global $sugar_config;
        $sugar_config['log_memory_usage'] = false;
        $output = $this->sugarView->logMemoryStatisticsTest("\n");
        $this->assertEmpty($output, "Failed to recognize log_memory_usage = false setting");
        $this->assertFileNotExists('memory_usage.log');
    }
}

require_once('include/MVC/View/SugarView.php');
class Bug45181TestSugarViewMock extends SugarView
{
    public function logMemoryStatisticsTest($newline)
    {
        return $this->logMemoryStatistics($newline);
    }
}