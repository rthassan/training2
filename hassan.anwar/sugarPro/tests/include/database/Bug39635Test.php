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

/**
 * Bug #39635
 * max length error for Forcasting fields
 *
 * @author mgusev@sugarcrm.com
 * @ticket 39635
 */
class Bug39635Test extends Sugar_PHPUnit_Framework_TestCase
{

    /**
     * @group 39635
     */

    public function testRepairTableParams()
    {
        $bigInt = 9876543210;
        $fieldDef = array(
            'dbType' => 'long'
        );

        $oDB = DBManagerFactory::getInstance();
        $result = $oDB->massageValue($bigInt, $fieldDef);

        $this->assertEquals($bigInt, $result);
    }
}
