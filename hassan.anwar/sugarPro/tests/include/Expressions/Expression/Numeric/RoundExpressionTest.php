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

class RoundExpressionTest extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderTestEvaluate
     */
    public function testEvaluate($test, $expected)
    {
        $result = Parser::evaluate($test)->evaluate();
        $this->assertEquals($expected, $result);
    }

    public function dataProviderTestEvaluate()
    {
        return array(
            array('round("2.666667", "6")', '2.666667'),
            array('round("2.666667", "5")', '2.66667'),
            array('round("2.666667", "4")', '2.6667'),
            array('round("2.666667", "3")', '2.667'),
            array('round("2.666667", "2")', '2.67'),
            array('round("2.666667", "1")', '2.7'),
            array('round("2.666667", "0")', '3'),
        );
    }
}

