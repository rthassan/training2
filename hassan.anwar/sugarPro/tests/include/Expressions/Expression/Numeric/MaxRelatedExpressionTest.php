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

class MaxRelatedExpressionTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function testRelatedSum()
    {
        $opp = $this->getMockBuilder('Opportunity')
            ->setMethods(array('save', 'load_relationship'))
            ->getMock();


        $link2 = $this->getMockBuilder('Link2')
            ->disableOriginalConstructor()
            ->setMethods(array('getBeans'))
            ->getMock();

        $opp->revenuelineitems = $link2;

        $rlis = array();
        // lets create 3 rlis which with 10 * the index, which will give us the total of 60
        for ($x = 1; $x <= 3; $x++) {
            $rli = $this->getMockBuilder('RevenueLineItem')
                ->setMethods(array('save'))
                ->getMock();

            $rli->quantity = SugarMath::init(10, 0)->mul($x)->result();

            $rlis[] = $rli;
        }

        $opp->expects($this->any())
            ->method('load_relationship')
            ->will($this->returnValue(true));

        $link2->expects($this->any())
            ->method('getBeans')
            ->will($this->returnValue($rlis));

        $expr = 'rollupMax($revenuelineitems, "quantity")';
        $result = Parser::evaluate($expr, $opp)->evaluate();
        $this->assertSame('30', $result);
    }

}
