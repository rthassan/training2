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

require_once ('include/SugarFields/Fields/Datetime/SugarFieldDatetime.php');

/**
 * @group Bug49691
 */
class Bug49691bTest extends Sugar_PHPUnit_Framework_TestCase {

    var $bean;
    var $sugarField;

    var $oldDate;
    var $oldTime;

    public function setUp() {
        global $sugar_config;
        $this->oldDate = $sugar_config['default_date_format'];
        $sugar_config['default_date_format'] = 'm/d/Y';
        $this->oldTime = $sugar_config['default_time_format'];
        $sugar_config['default_time_format'] = 'H:i';
        $this->bean = new Bug49691bMockBean();
        $this->sugarField = new SugarFieldDatetime("Account");
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
    }

    public function tearDown() {
        global $sugar_config;
        unset($GLOBALS['current_user']);
        $sugar_config['default_date_format'] = $this->oldDate;
        $sugar_config['default_time_format'] = $this->oldTime;
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($this->sugarField);
    }

    /**
     * @dataProvider providerFunction
     * @return void
     */
    public function testDBDateConversion($dateValue, $expected) {
        global $current_user;

        $this->bean->test_c = $dateValue;

        $this->sugarField->save($this->bean, array('test_c'=>$dateValue),'test_c', null, '');

        $this->assertNotEmpty($this->bean->test_c);
        $this->assertSame($expected, $this->bean->test_c);
    }

    public function providerFunction() {
        return array(
            array('01/01/2012', '2012-01-01'),
            array('2012-01-01', '2012-01-01'),
        );
    }
}

class Bug49691bMockBean {
    var $test_c;
}
