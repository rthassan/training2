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
 
require_once 'include/utils/array_utils.php';

class DeepArrayDiffTest extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * @ticket 24067
     */
    public function testdeepArrayDiffWithBooleanFalse()
    {
        $array1 = array(
            'value1' => true,
            'value2' => false,
            'value3' => 'yummy'
            );
        
        $array2 = array(
            'value1' => true,
            'value2' => true,
            'value3' => 'yummy'
            );
        
    	$diffs = deepArrayDiff($array1,$array2);
        
        $this->assertEquals($diffs['value2'], false);
        $this->assertFalse(isset($diffs['value1']));
        $this->assertFalse(isset($diffs['value3']));
        
        
        $diffs = deepArrayDiff($array2,$array1);
        
        $this->assertEquals($diffs['value2'], true);
        $this->assertFalse(isset($diffs['value1']));
        $this->assertFalse(isset($diffs['value3']));
    }
}
