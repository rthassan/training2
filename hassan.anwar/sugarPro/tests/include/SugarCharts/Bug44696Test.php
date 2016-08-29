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

require_once('include/SugarCharts/SugarChart.php');

/**
 * Bug 44696 - Wrong shortcut to the opportunities module from the dashlet
 * "Pipeline By Sales Stage"
 *
 * @ticket 44696
 * @ticket 53845
 */
class Bug44696Test extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * @var SugarChart
     */
    protected $sugarChartObject;
    /**
     * @var User
     */
    protected $currentUser;

    protected function setUp()
    {
        parent::setUp();

        SugarTestHelper::init();
        SugarTestHelper::setUp('current_user');
        $this->currentUser = $GLOBALS['current_user'];
        $this->currentUser->setPreference(
            'default_number_grouping_seperator',
            '.'
        );
        $this->currentUser->setPreference('default_decimal_seperator', ',');

        $sugarChartObject = new SugarChart();
        $sugarChartObject->group_by = array('sales_stage', 'user_name');
        $sugarChartObject->data_set = $this->getDataSet();
        $sugarChartObject->base_url = array(
            'module' => 'Opportunities',
            'action' => 'index',
            'query' => 'true',
            'searchFormTab' => 'advanced_search'
        );
        $sugarChartObject->url_params = array();
        $sugarChartObject->is_currency = true;
        // we have 5 users 
        $sugarChartObject->super_set = array(
            'will',
            'max',
            'sarah',
            'sally',
            'chris'
        );
        $this->sugarChartObject = $sugarChartObject;
    }

    protected function tearDown()
    {
        SugarTestHelper::tearDown();
        parent::tearDown();
    }

    /**
     * We check, that groups with NULL value remain their order in subgroups tag
     * and won't fall down under not null valued groups.
     * This way we guarantee that links will be put correctly to each user in
     * whole user list (will, max, etc.).
     *
     * @group 44696
     * @group 53845
     */
    public function testCorrectXml()
    {
        $actual = $this->sugarChartObject->xmlDataGenericChart();
        $expected = $this->compareXml();
        $order = array("\r\n", "\n", "\r", "\t");
        $replace = "";
        // remove all break lines and spaces and tabs
        $expected = str_replace($order, $replace, $expected);
        $actual = str_replace($order, $replace, $actual);
        $this->assertEquals($expected, $actual);
    }

    protected function getDataSet()
    {
        return array(
            array(
                'sales_stage' => 'Proposal/Price Quote',
                'user_name' => 'max',
                'assigned_user_id' => 'seed_max_id',
                'opp_count' => '1',
                'total' => 50.1234,
                'key' => 'Proposal/Price Quote',
                'value' => 'Proposal/Price Quote',
            ),
            array(
                'sales_stage' => 'Proposal/Price Quote',
                'user_name' => 'sally',
                'assigned_user_id' => 'seed_sally_id',
                'opp_count' => '2',
                'total' => 75.98765,
                'key' => 'Proposal/Price Quote',
                'value' => 'Proposal/Price Quote',
            ),
        );
    }

    /**
     * Expected XML from SugarChart after.
     *
     * @return string
     *   The XML string that we expect to be returned by the chart.
     */
    protected function compareXml()
    {
        $dataSet = $this->getDataSet();
        $max = $dataSet[0]['total'];
        $sally = $dataSet[1]['total'];
        $total = $max + $sally;

        list($maxSubAmount, $maxSubAmountFormatted) = $this->getAmounts($max);
        list($sallySubAmount, $sallySubAmountFormatted) = $this->getAmounts(
            $sally
        );
        list($totalSubAmount, $totalSubAmountFormatted) = $this->getAmounts(
            $total
        );

        return "<group>
			<title>Proposal/Price Quote</title>
			<value>{$totalSubAmount}</value>
			<label>{$totalSubAmountFormatted}</label>
			<link>index.php?module=Opportunities&action=index&query=true&searchFormTab=advanced_search&sales_stage=Proposal%2FPrice+Quote</link>
			<subgroups>
				<group>
					<title>will</title>
					<value>NULL</value>
					<label></label>
					<link>index.php?module=Opportunities&action=index&query=true&searchFormTab=advanced_search&sales_stage=Proposal%2FPrice+Quote</link>
				</group>
				<group>
					<title>max</title>
					<value>{$maxSubAmount}</value>
					<label>{$maxSubAmountFormatted}</label>
					<link>index.php?module=Opportunities&action=index&query=true&searchFormTab=advanced_search&sales_stage=Proposal%2FPrice+Quote&assigned_user_id[]=seed_max_id</link>
				</group>
				<group>
					<title>sarah</title>
					<value>NULL</value>
					<label></label>
					<link>index.php?module=Opportunities&action=index&query=true&searchFormTab=advanced_search&sales_stage=Proposal%2FPrice+Quote</link>
				</group>
				<group>
					<title>sally</title>
					<value>{$sallySubAmount}</value>
					<label>{$sallySubAmountFormatted}</label>
					<link>index.php?module=Opportunities&action=index&query=true&searchFormTab=advanced_search&sales_stage=Proposal%2FPrice+Quote&assigned_user_id[]=seed_sally_id</link>
				</group>
				<group>
					<title>chris</title>
					<value>NULL</value>
					<label></label>
					<link>index.php?module=Opportunities&action=index&query=true&searchFormTab=advanced_search&sales_stage=Proposal%2FPrice+Quote</link>
				</group>
			</subgroups></group>";
    }

    /**
     * Get the amounts that the chart should have for the value given.
     *
     * @param float $value
     *   The value to get formatted as chart would.
     *
     * @return array
     *   A list with: the value in K's and the value in currency format.
     */
    protected function getAmounts($value)
    {
        global $locale;
        global $sugar_config;

        $subAmount = round($value, $locale->getPrecision($this->currentUser));
        // TODO use the Localization::getLocaleFormattedNumber when it works!
        // FIXME SugarCharts should use the user format preferences for charts
        $subAmountFormatted = number_format(
            $value,
            $locale->getPrecision($this->currentUser),
            $sugar_config['default_decimal_seperator'],
            $sugar_config['default_number_grouping_seperator']
        );
        $subAmountFormatted = $locale->getCurrencySymbol(
            $this->currentUser
        ) . $subAmountFormatted . 'K';

        return array($subAmount, $subAmountFormatted);
    }
}
