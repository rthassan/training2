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


 
require_once 'modules/Import/CsvAutoDetect.php';

class Bug45907Test extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        // if beanList got unset, set it back
        if (!isset($GLOBALS['beanList'])) {
            require('include/modules.php');
            $GLOBALS['beanList'] = $beanList;
        }
    }

    public function tearDown()
    {
    }

    /**
     * @ticket 45907
     */
    public function testCsvWithExtraInfo()
    {
        $sample_file = $GLOBALS['sugar_config']['upload_dir'].'/Bug45907Test.csv';
        $file = 'tests/modules/Import/Bug45907Test.csv';
        copy($file, $sample_file);

        $auto = new CsvAutoDetect($file, 4); // parse only the first 4 lines
        $del = $enc = $hasHeader = false;

        // there is extra non csv info at the bottom of the file
        // but it should still parse ok because we only parse the first 4 lines
        $ret = $auto->getCsvSettings($del, $enc);
        $this->assertEquals(true, $ret, 'Failed to parse and get csv properties');

        // delimiter
        $this->assertEquals(',', $del, 'Incorrect delimiter');

        // enclosure
        $this->assertEquals('"', $enc, 'Incorrect enclosure');

        // header
        $ret = $auto->hasHeader($hasHeader, 'Accounts');
        $this->assertTrue($ret, 'Failed to detect header');
        $this->assertTrue($hasHeader, 'Incorrect header');

        // remove temp file
        unlink($sample_file);
    }

}
