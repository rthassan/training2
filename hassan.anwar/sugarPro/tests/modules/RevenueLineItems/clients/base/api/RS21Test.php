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

require_once 'clients/base/api/FilterApi.php';

/**
 * Tests RevenueLineItemsApiTest.
 */
class RS21Test extends Sugar_PHPUnit_Framework_TestCase
{
	/**
     * @var SugarApi
     */
    protected $api;

    /**
     * @var array
     */
    protected $user;


    protected function setUp()
    {
        parent::setUp();

        SugarTestHelper::setUp('app_list_strings');
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('beanList');

        $this->user = SugarTestHelper::setUp('current_user', array(true, false));
        $this->api = new FilterApi();
    }


    protected function tearDown()
    {
        SugarTestHelper::tearDown();
        parent::tearDown();
    }

    public function testFilterList()
    {
        $result = $this->api->filterList(
            SugarTestRestUtilities::getRestServiceMock($this->user),
            array(
            	'module' => 'RevenueLineItems',
            	'fields' => 'name,opportunity_name,account_name,sales_stage,
            	probability,date_closed,commit_stage,
            	product_template_name,category_name,quantity,likely_case,
            	best_case,worst_case,quote_name,assigned_user_name,currency_id,base_rate,quote_id,
            	opportunity_id,account_id,product_template_id,category_id,assigned_user_id,my_favorite,following',
            	'max_num' => '20',
            	'order_by' => 'name:desc',
        	)
        );

        $this->assertArrayHasKey('records', $result);
        
    }
}
