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
 * Bug49219Test.php
 * @author Collin Lee
 *
 * This test will attempt to assert two things:
 * 1) That upgrade for Meetings quickcreatedefs.php correctly remove footerTpl and headerTpl metadata attributes from
 * custom quickcreatedefs.php files (since we removed them from code base)
 * 2) That the SubpanelQuickCreate changes done for this bug can correctly pick up metadata footerTpl and headerTpl
 * attributes
 */
require_once 'include/dir_inc.php';
require_once 'include/EditView/SubpanelQuickCreate.php';

class Bug49219Test extends Sugar_PHPUnit_Framework_TestCase  {

var $merge;

function setUp() {
   global $beanList, $beanFiles, $current_user;
   require('include/modules.php');
   $current_user = SugarTestUserUtilities::createAnonymousUser();
   SugarTestMergeUtilities::setupFiles(array('Meetings'), array('quickcreatedefs'), 'tests/modules/UpgradeWizard/SugarMerge/metadata_files');
}


function tearDown() {
   SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
   SugarTestMergeUtilities::teardownFiles();
   unset($current_user);
}


/**
 * testUpgradeMeetingsQuickCreate641
 * @outputBuffering enabled
 * This test asserts that the footerTpl and headerTpl form attributes are removed from quickcreatedefs.php when
 * upgrading to 641
 */
function testUpgradeMeetingsQuickCreate641() {
   require('custom/modules/Meetings/metadata/quickcreatedefs.php');
   $this->assertArrayHasKey('headerTpl', $viewdefs['Meetings']['QuickCreate']['templateMeta']['form'], 'Unit test setup failed');
   $this->assertArrayHasKey('footerTpl', $viewdefs['Meetings']['QuickCreate']['templateMeta']['form'], 'Unit test setup failed');
   require_once 'modules/UpgradeWizard/SugarMerge/QuickCreateMerge.php';
   $this->merge = new QuickCreateMerge();
   $this->merge->merge('Meetings', 'tests/modules/UpgradeWizard/SugarMerge/metadata_files/640/modules/Meetings/metadata/quickcreatedefs.php','modules/Meetings/metadata/quickcreatedefs.php','custom/modules/Meetings/metadata/quickcreatedefs.php');
   SugarAutoLoader::buildCache();
   require('custom/modules/Meetings/metadata/quickcreatedefs.php');
   $this->assertArrayNotHasKey('headerTpl', $viewdefs['Meetings']['QuickCreate']['templateMeta']['form'], 'SugarMerge code does not remove headerTpl from quickcreatedefs.php');
   $this->assertArrayNotHasKey('footerTpl', $viewdefs['Meetings']['QuickCreate']['templateMeta']['form'], 'SugarMerge code does not remove footerTpl from quickcreatedefs.php');
}


/**
 * testSubpanelQuickCreate
 * @outputBuffering enabled
 * This test asserts that we can pick up the footerTpl and headerTpl attributes in the quickcreatedefs.php files
 */
function testSubpanelQuickCreate()
{
    $quickCreate = new SubpanelQuickCreate('Meetings', 'QuickCreate', true);
    $this->assertEquals('modules/Meetings/tpls/header.tpl', $quickCreate->ev->defs['templateMeta']['form']['headerTpl'], 'SubpanelQuickCreate fails to pick up headerTpl attribute');
    $this->assertEquals('modules/Meetings/tpls/footer.tpl', $quickCreate->ev->defs['templateMeta']['form']['footerTpl'], 'SubpanelQuickCreate fails to pick up footerTpl attribute');
    require_once 'modules/UpgradeWizard/SugarMerge/QuickCreateMerge.php';
    $this->merge = new QuickCreateMerge();
    $this->merge->merge('Meetings', 'tests/modules/UpgradeWizard/SugarMerge/metadata_files/640/modules/Meetings/metadata/quickcreatedefs.php','modules/Meetings/metadata/quickcreatedefs.php','custom/modules/Meetings/metadata/quickcreatedefs.php');
    SugarAutoLoader::buildCache();
    $quickCreate = new SubpanelQuickCreate('Meetings', 'QuickCreate', true);
    $this->assertEquals('include/EditView/header.tpl', $quickCreate->ev->defs['templateMeta']['form']['headerTpl'], 'SubpanelQuickCreate fails to pick up default headerTpl attribute');
    $this->assertEquals('include/EditView/footer.tpl', $quickCreate->ev->defs['templateMeta']['form']['footerTpl'], 'SubpanelQuickCreate fails to pick up default footerTpl attribute');

}

}