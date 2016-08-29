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
 
require_once 'include/utils.php';

class SugarVersionTest extends Sugar_PHPUnit_Framework_TestCase
{
	/**
     * @dataProvider providerVersionStatus
     */
	public function testVersionStatus(
        $version, 
        $expectedResult
        )
    {
		$returnedStatus = getVersionStatus($version);
		$this->assertEquals($returnedStatus,$expectedResult,
            "{$returnedStatus} status did not match expected status of {$expectedResult}");
	}
	
	public function providerVersionStatus()
	{
		return array(
            array('5.5.0RC1','RC'),
            array('5.5.0RC','RC'),
            array('5.5.0','GA'),
            array('5.5.0Beta','BETA'),
            array('5.5.0BEta1','BETA'),
            array('5.2','GA'),
            array('5.2RC2','RC'),
        );
    }
    
	/**
     * @dataProvider providerVersionMajorMinor
     */
	public function testVersionMajorMinor(
	    $version, 
	    $expectedResult
	    )
	{
		$returnedVersion = getMajorMinorVersion($version);
		$this->assertEquals($returnedVersion,$expectedResult,
            "{$returnedVersion} MajorMinor version did not match expected version of {$expectedResult}");
	}
	
	public function providerVersionMajorMinor()
	{
		return array(
            array('5.5.0RC1','5.5'),
            array('5.5.1RC','5.5.1'),
            array('5.0','5.0'),
            array('5.0Beta','5.0'),
            array('5.5.1RC','5.5.1'),
        );
    }
}
