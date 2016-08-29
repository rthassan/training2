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

require_once 'include/Expressions/Expression/Generic/SugarFieldExpression.php';

/**
 * @ticket 59069
 */
class Bug59069Test extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * @param string $type
     *
     * @dataProvider provider
     */
    public function testNoExceptionThrown($type)
    {
        $context = new stdClass();
        $context->field_defs = array(
            'test' => array(
                'type' => $type,
            ),
        );

        $expr = new SugarFieldExpression('test');
        $expr->context = $context;

        $context->test = '';
        $this->assertFalse($expr->evaluate());

        $context->test = 'foobar';
        $this->assertFalse($expr->evaluate());
    }

    public static function provider()
    {
        return array(
            array('datetime'),
            array('datetimecombo'),
            array('date'),
            array('time'),
        );
    }
}
