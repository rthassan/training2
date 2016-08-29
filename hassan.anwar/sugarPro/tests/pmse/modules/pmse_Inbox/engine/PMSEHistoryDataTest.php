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
class PMSEHistoryDataTest extends PHPUnit_Framework_TestCase
{

    private $module= 'Leads';

    protected function setUp() {
        $this->object = new PMSEHistoryData($this->module);
    }

    public function testSavePredata()
    {
        $value['before_data'][1] = 'Ok';
        $this->object->savePredata(1,'Ok');
        $logData = $this->object->getLog();
        $this->assertEquals($value['before_data'], $logData['before_data']);
    }
    
    public function testSavePostData()
    {
        $value['after_data'][1] = 'Ok';
        $this->object->savePostData(1,'Ok');
        $logData = $this->object->getLog();
        $this->assertEquals($value['after_data'], $logData['after_data']);
    }
    
    public function testVerifyRepeated()
    {
        $value['after_data'][1] = 'Ok';
        $this->object->verifyRepeated('Ok','Ok');
    }
    
    public function testLock()
    {
        $value = 'conditons';
        $this->object->lock($value);
    }
}
 