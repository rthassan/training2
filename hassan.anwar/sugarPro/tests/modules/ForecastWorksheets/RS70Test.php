<?php
/**
 * RS-70: Prepare ForecastWorksheets Module
 */
class RS70Test extends Sugar_PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('current_user');

        $params = array();
        $params['forecast_by'] = 'RevenueLineItems';

        SugarTestForecastUtilities::setUpForecastConfig($params);
    }

    public function tearDown()
    {
        SugarTestTimePeriodUtilities::removeAllCreatedTimePeriods();
    }

    public static function tearDownAfterClass()
    {
        SugarTestForecastUtilities::tearDownForecastConfig();
        parent::tearDownAfterClass();
        SugarTestHelper::tearDown();
    }

    /**
     * Test covers behavior of saveWorksheetOpportunity method
     */
    public function testSaveWorksheetOpportunity()
    {
        /** @var PHPUnit_Framework_MockObject_MockObject|Opportunity $parent */
        $parent = $this->getMock('Opportunity', array('save'));
        $parent->expects($this->once())->method('save');
        $parent->id = create_guid();
        $parent->new_with_id = true;
        BeanFactory::registerBean($parent->module_name, $parent, $parent->id);

        $bean = new ForecastWorksheet();
        $bean->parent_type = $parent->module_name;
        $bean->parent_id = $parent->id;

        $bean->probability = create_guid();
        $bean->best_case = create_guid();
        $bean->likely_case = create_guid();
        $bean->sales_stage = create_guid();
        $bean->commit_stage = create_guid();
        $bean->worst_case = create_guid();

        $bean->saveWorksheet();

        $this->assertEquals($bean->probability, $parent->probability);
        $this->assertEquals($bean->best_case, $parent->best_case);
        $this->assertEquals($bean->likely_case, $parent->amount);
        $this->assertEquals($bean->sales_stage, $parent->sales_stage);
        $this->assertEquals($bean->commit_stage, $parent->commit_stage);
        $this->assertEquals($bean->worst_case, $parent->worst_case);
    }

    /**
     * Test covers behavior of saveWorksheetRevenueLineItem method
     */
    public function testSaveWorksheetRevenueLineItem()
    {
        /** @var PHPUnit_Framework_MockObject_MockObject|RevenueLineItem $parent */
        $parent = $this->getMock('RevenueLineItem', array('save'));
        $parent->expects($this->once())->method('save');
        $parent->id = create_guid();
        $parent->new_with_id = true;
        BeanFactory::registerBean($parent->module_name, $parent, $parent->id);

        $bean = new ForecastWorksheet();
        $bean->parent_type = $parent->module_name;
        $bean->parent_id = $parent->id;

        $bean->probability = create_guid();
        $bean->best_case = create_guid();
        $bean->likely_case = create_guid();
        $bean->sales_stage = create_guid();
        $bean->commit_stage = create_guid();
        $bean->worst_case = create_guid();
        $bean->date_closed = create_guid();

        $bean->saveWorksheet();

        $this->assertEquals($bean->probability, $parent->probability);
        $this->assertEquals($bean->best_case, $parent->best_case);
        $this->assertEquals($bean->likely_case, $parent->likely_case);
        $this->assertEquals($bean->date_closed, $parent->date_closed);
        $this->assertEquals($bean->sales_stage, $parent->sales_stage);
        $this->assertEquals($bean->commit_stage, $parent->commit_stage);
        $this->assertEquals($bean->worst_case, $parent->worst_case);
    }

    /**
     * Test covers behavior of setWorksheetArgs method
     */
    public function testSetWorksheetArgs()
    {
        $bean = new ForecastWorksheet();
        $agrs = array(
            'id' => 'id1',
            'test' => 'test1',
        );
        $bean->setWorksheetArgs($agrs);
        $this->assertEquals($agrs, $bean->args);
        foreach ($agrs as $k => $v) {
            $this->assertEquals($v, $bean->$k);
        }
    }

    /**
     * Test coverts behavior of saveRelatedOpportunity method
     */
    public function testSaveRelatedOpportunity()
    {
        $opportunity = new Opportunity();
        $opportunity->id = create_guid();
        $opportunity->account_id = create_guid();

        /** @var PHPUnit_Framework_MockObject_MockObject|ForecastWorksheet $bean */
        $bean = $this->getMock('ForecastWorksheet', array('retrieve_by_string_fields', 'getRelatedName', 'copyValues', 'save', 'removeMigratedRow'));
        $bean->expects($this->once())->method('retrieve_by_string_fields');
        $bean->expects($this->once())->method('getRelatedName')->with($this->equalTo('Accounts'), $this->equalTo($opportunity->account_id))->will($this->returnValue('Account ' . __CLASS__));
        $bean->expects($this->once())->method('copyValues')->with($this->anything(), $this->equalTo($opportunity));
        $bean->expects($this->once())->method('save');
        $bean->expects($this->once())->method('removeMigratedRow')->with($this->equalTo($opportunity));

        $bean->saveRelatedOpportunity($opportunity, false);
        $this->assertEquals($opportunity->module_name, $bean->parent_type);
        $this->assertEquals($opportunity->id, $bean->parent_id);
        $this->assertEquals(1, $bean->draft);
    }

    /**
     * Test covers behavior of saveRelatedProduct method
     */
    public function testSaveRelatedProduct()
    {
        $parent = new RevenueLineItem();
        $parent->id = create_guid();

        /** @var PHPUnit_Framework_MockObject_MockObject|ForecastWorksheet $bean */
        $bean = $this->getMock('ForecastWorksheet', array('retrieve_by_string_fields', 'getRelatedName', 'copyValues', 'removeMigratedRow', 'save'));
        $bean->expects($this->once())->method('retrieve_by_string_fields');
        $bean->expects($this->never())->method('getRelatedName');
        $bean->expects($this->once())->method('copyValues')->with($this->isType('array'), $this->equalTo($parent));
        $bean->expects($this->once())->method('removeMigratedRow')->with($this->equalTo($parent));
        $bean->expects($this->once())->method('save');

        $bean->saveRelatedProduct($parent);
        $this->assertEquals($parent->module_name, $bean->parent_type);
        $this->assertEquals($parent->id, $bean->parent_id);
        $this->assertEquals(1, $bean->draft);
    }

    /**
     * Test covers behavior of saveRelatedProductFillNames method
     *
     * @dataProvider getDataForTestSaveRelatedProductFillNames
     *
     * @param string $module
     * @param        $propertyId
     * @param        $propertyName
     *
     * @internal     param string $property
     */
    public function testSaveRelatedProductFillNames($module, $propertyId, $propertyName)
    {
        $parent = new RevenueLineItem();
        $parent->id = create_guid();
        $parent->$propertyId = create_guid();

        /** @var PHPUnit_Framework_MockObject_MockObject|ForecastWorksheet $bean */
        $bean = $this->getMock('ForecastWorksheet', array('retrieve_by_string_fields', 'getRelatedName', 'copyValues', 'removeMigratedRow', 'save'));
        $bean->expects($this->any())->method('retrieve_by_string_fields');
        $bean->expects($this->any())->method('copyValues');
        $bean->expects($this->any())->method('removeMigratedRow');
        $bean->expects($this->any())->method('save');
        $bean->expects($this->once())->method('getRelatedName')->with($this->equalTo($module), $this->equalTo($parent->$propertyId))->will($this->returnValue($module));

        $bean->saveRelatedProduct($parent);
        $this->assertEquals($module, $parent->$propertyName);
    }

    /**
     * Data provider for testSaveRelatedProductFillNames
     * @return array
     */
    public function getDataForTestSaveRelatedProductFillNames()
    {
        return array(
            array('Accounts', 'account_id', 'account_name'),
            array('Opportunities', 'opportunity_id', 'opportunity_name'),
            array('ProductTemplates', 'product_template_id', 'product_template_name'),
            array('ProductCategories', 'category_id', 'category_name'),
        );
    }

    /**
     * Test covers behavior of timePeriodHasMigrated method
     *
     * @dataProvider getDataForTestTimePeriodHasMigrated
     *
     * @param string $worksheetDate
     * @param string $objDate
     * @param bool $expected
     */
    public function testTimePeriodHasMigrated($worksheetDate, $objDate, $expected)
    {
        SugarTestTimePeriodUtilities::createTimePeriod('2013-01-01', '2013-05-01');
        SugarTestTimePeriodUtilities::createTimePeriod('2013-05-01', '2013-10-01');
        $bean = new ForecastWorksheet();
        $actual = SugarTestReflection::callProtectedMethod($bean, 'timeperiodHasMigrated', array(
                $worksheetDate,
                $objDate,
            ));
        $this->assertEquals($expected, $actual);
    }

    /**
     * Data provider for testTimePeriodHasMigrated
     *
     * @return array
     */
    public function getDataForTestTimePeriodHasMigrated()
    {
        return array(
            array(
                '2013-05-05 00:00:00',
                '2013-05-05 00:00:00',
                false,
            ),
            array(
                '2013-01-05 00:00:00',
                '2013-05-05 00:00:00',
                true,
            ),
        );
    }

    /**
     * Test covers behavior of copyValues method
     */
    public function testCopyValues()
    {
        $bean = new ForecastWorksheet();
        $account = new Account();
        $account->id = 'id1';
        $account->name = 'name1';
        SugarTestReflection::callProtectedMethod($bean, 'copyValues', array(
                array('id', 'name'),
                $account,
            ));
        $this->assertEquals($account->id, $bean->id);
        $this->assertEquals($account->name, $bean->name);
    }

    /**
     * Test covers behavior of commitWorksheet method
     */
    public function testCommitWorksheet()
    {
        $tp = SugarTestTimePeriodUtilities::createTimePeriod();

        $acc = SugarTestAccountUtilities::createAccount();
        $opp = SugarTestOpportunityUtilities::createOpportunity(null, $acc);

        $opp->assigned_user_id = $GLOBALS['current_user']->id;
        $opp->date_closed = $tp->start_date;
        $opp->save();

        $rli = SugarTestRevenueLineItemUtilities::createRevenueLineItem();
        $rli->opportunity_id = $opp->id;
        $rli->account_id = $acc->id;
        $rli->assigned_user_id = $GLOBALS['current_user']->id;
        $rli->date_closed = $tp->start_date;
        $rli->save();

        $bean = $this->getMock('ForecastWorksheet', array('createUpdateForecastWorksheetJob',
                                                          'removeReassignedItems'));

        $bean->expects($this->any())->method('createUpdateForecastWorksheetJob');

        $bean->expects($this->once())
             ->method('removeReassignedItems');

        $actual = SugarTestReflection::callProtectedMethod($bean, 'commitWorksheet', array(
                $GLOBALS['current_user']->id,
                $tp->id,
            ));
        $this->assertTrue($actual);

        SugarTestAccountUtilities::removeAllCreatedAccounts();
        SugarTestOpportunityUtilities::removeAllCreatedOpportunities();
        SugarTestRevenueLineItemUtilities::removeAllCreatedRevenueLineItems();
    }

    /**
     * Test covers that execution of queries doesn't gather an error
     */
    public function testReassignForecast()
    {
        $user = SugarTestUserUtilities::createAnonymousUser();
        $user->reports_to_id = $GLOBALS['current_user']->id;
        $user->save();

        $bean = new ForecastWorksheet();
        $bean->assigned_user_id = $GLOBALS['current_user']->id;
        $bean->save();

        SugarTestWorksheetUtilities::setCreatedWorksheet(array($bean->id));

        $actual = SugarTestReflection::callProtectedMethod($bean, 'reassignForecast', array(
                $GLOBALS['current_user']->id,
                $user->id,
            ));
        $this->assertNotEmpty($actual);
    }

    /**
     * Test covers that execution of queries doesn't gather an error
     */
    public function testWorksheetTotals()
    {
        $tp = SugarTestTimePeriodUtilities::createTimePeriod();

        $bean = new ForecastWorksheet();
        $actual = $bean->worksheetTotals($tp->id, $GLOBALS['current_user']->id);
        $this->assertNotEmpty($actual);
    }
}
