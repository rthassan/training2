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

require_once('include/utils.php');
/**
 * Bug47277Test.php
 * This test founds out, if function string_format returns '', and not empty space, which causes an error, e.g. IN ()
 */
class Bug47277Test extends Sugar_PHPUnit_Framework_TestCase
{
    public function testStringFormatDontReturnsEmptyValue() 
    {
        $sourceString = "SELECT accounts.name FROM accounts WHERE id IN";
        $string = "{$sourceString} ({0})";
        $args = array ('');
        $this->assertEquals("{$sourceString} ('')", string_format($string, $args));
    }
}