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
require_once 'modules/Administration/updater_utils.php';

class UpdaterUtilsTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->oldLicense = $GLOBALS['license'];
        $GLOBALS['license'] = new UpdateUtilsSettingMock();
        $this->settings = $GLOBALS['license'];

        $this->fakeLicense = array(
            'license_users' => 50,
            'num_lic_oc' => 0,
            'license_num_portal_users' => 500,
            'license_validation_key' => 'abcdefgh',
            'license_vk_end_date' => gmdate('Y-m-d',gmmktime(1,2,3,4,5,gmdate('Y')+2)),
            'license_expire_date' => gmdate('Y-m-d',gmmktime(1,2,3,4,5,gmdate('Y')+2)),
            'enforce_portal_user_limit' => 1,
            'enforce_user_limit' => 1,
        );
    }

    public function tearDown()
    {
        $GLOBALS['license'] = $this->oldLicense;
    }

    public function testEnforcePortalUserLimit()
    {
        $fakeLicenseData = $this->fakeLicense;
        
        checkDownloadKey($fakeLicenseData);
        $this->assertTrue((bool)$this->settings->savedSettings['license']['enforce_portal_user_limit'],"Not enforcing portal user limit when we should be.");

        $GLOBALS['license'] = $this->settings;
        $this->settings->savedSettings = array();
        $fakeLicenseData['enforce_portal_user_limit'] = '0';
        checkDownloadKey($fakeLicenseData);
        $this->assertFalse((bool)$this->settings->savedSettings['license']['enforce_portal_user_limit'],"Enforcing portal user limit when we shouldn't be.");
        
    }
}

class UpdateUtilsSettingMock
{
    public $savedSettings = array();

    public function saveSetting($section, $key, $data) {
        $this->savedSettings[$section][$key] = $data;
    }
}