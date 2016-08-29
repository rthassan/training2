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

class ForecastSalesStageExpressionTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
        SugarTestHelper::setUp('app_list_strings');
    }


    public function tearDown()
    {
        SugarTestHelper::tearDown();
    }

    public function testEvaluateDoesNotContainClosedSalesStages()
    {
        $result = Parser::evaluate('forecastSalesStages(false, false)')->evaluate();
        $this->assertNotContains('Closed Lost', $result);
        $this->assertNotContains('Closed Won', $result);
    }

    public function testEvaluateContainsClosedWon()
    {
        $result = Parser::evaluate('forecastSalesStages(true, false)')->evaluate();
        $this->assertNotContains('Closed Lost', $result);
        $this->assertContains('Closed Won', $result);
    }

    public function testEvaluateContainsClosedWonAndClosedLost()
    {
        $result = Parser::evaluate('forecastSalesStages(true, true)')->evaluate();
        $this->assertContains('Closed Lost', $result);
        $this->assertContains('Closed Won', $result);
    }
}
