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

require_once 'upgrade/UpgradeDropdownsHelper.php';

/**
 * @covers UpgradeDropdownsHelper
 */
class UpgradeDropdownsHelperTest extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * @covers UpgradeDropdownsHelper::getDropdowns
     */
    public function testGetDropdowns_ReturnsCoreDropDowns()
    {
        $mockHelper = $this->getMock('UpgradeDropdownsHelper', array('getAppListStringsFromFile'));
        $mockHelper->expects($this->once())
            ->method('getAppListStringsFromFile')
            ->willReturn(array(
                'sales_stage_default_key' => 'Prospecting',
                'activity_dom' => array(
                    'Call' => 'Call',
                    'Meeting' => 'Meeting',
                    'Task' => 'Task',
                    'Email' => 'Email',
                    'Note' => 'Note',
                ),
                'meeting_status_dom' => array(
                    'Planned' => 'Planned',
                    'Held' => 'Held',
                    'Not Held' => 'Not Held',
                ),
            ));

        $actual = $mockHelper->getDropdowns('include/language/en_us.lang.php');

        $this->assertArrayHasKey('activity_dom', $actual);
        $this->assertArrayHasKey('meeting_status_dom', $actual);
        $this->assertEquals('Task', $actual['activity_dom']['Task']);
    }

    public function getDropDownsRestrictedDropDownsAreIgnoredProvider()
    {
        return array(
            array('eapm_list'),
            array('eapm_list_documents'),
            array('eapm_list_import'),
            array('extapi_meeting_password'),
            array('Elastic_boost_options'),
            array('commit_stage_dom'),
            array('commit_stage_custom_dom'),
            array('commit_stage_binary_dom'),
            array('forecasts_config_ranges_options_dom'),
            array('forecasts_timeperiod_types_dom'),
            array('forecasts_chart_options_group'),
            array('forecasts_config_worksheet_layout_forecast_by_options_dom'),
            array('forecasts_timeperiod_options_dom'),
            array('generic_timeperiod_options'),
        );
    }

    /**
     * @covers UpgradeDropdownsHelper::getDropdowns
     * @dataProvider getDropDownsRestrictedDropDownsAreIgnoredProvider
     * @param $dropdown
     */
    public function testGetDropdowns_RestrictedDropDownsAreIgnored($dropdown)
    {
        $mockHelper = $this->getMock('UpgradeDropdownsHelper', array('getAppListStringsFromFile'));
        $mockHelper->expects($this->once())
            ->method('getAppListStringsFromFile')
            ->willReturn(
                array(
                    $dropdown => array(
                        'Foo' => 'foo',
                        'Bar' => 'bar',
                        'Biz' => 'biz',
                        'Baz' => 'baz',
                    ),
                )
            );

        $actual = $mockHelper->getDropdowns('include/language/en_us.lang.php');

        $this->assertEmpty($actual);
    }

    /**
     * @covers UpgradeDropdownsHelper::getDropdowns
     */
    public function testGetDropdowns_FileDoesNotExist_ReturnsAnEmptyArray()
    {
        $helper = new UpgradeDropdownsHelper();
        $actual = $helper->getDropdowns('./foobar');

        $this->assertEmpty($actual);
    }

    /**
     * @covers UpgradeDropdownsHelper::getDropdowns
     */
    public function testGetDropdowns_GLOBALSIsUsedInTheCustomizations_ReturnsCustomDropDowns()
    {
        $custom = <<<EOF
\$GLOBALS['app_list_strings']['activity_dom'] = array(
    'Call' => 'Call',
    'Meeting' => 'Meeting',
    'Task' => 'To Do',
    'Email' => 'Email',
    'Note' => 'Note',
    'SMS' => 'Text Message',
);

EOF;

        $tmpFileName = time();
        file_put_contents($tmpFileName, "<?php\n{$custom}\n");

        $helper = new UpgradeDropdownsHelper();
        $actual = $helper->getDropdowns($tmpFileName);

        $this->assertArrayHasKey('activity_dom', $actual);
        $this->assertEquals('To Do', $actual['activity_dom']['Task']);

        unlink($tmpFileName);
    }
}
