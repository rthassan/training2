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
 
class SugarTestLangPackCreatorTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        SugarCache::$isCacheReset = false;

        if( empty($GLOBALS['current_language']) )
            $GLOBALS['current_language'] = $GLOBALS['sugar_config']['default_language'];
    }
    
    public function testSetAnyLanguageStrings() 
    {
        $langpack = new SugarTestLangPackCreator();
        
        $langpack->setAppString('NTC_WELCOME','stringname');
        $langpack->setAppListString('checkbox_dom',array(''=>'','1'=>'Yep','2'=>'Nada'));
        $langpack->setModString('LBL_MODULE_NAME','stringname','Contacts');
        $langpack->save();
        
        $app_strings = return_application_language($GLOBALS['current_language']);
        $app_list_strings = return_app_list_strings_language($GLOBALS['current_language']);
        $mod_strings = return_module_language($GLOBALS['current_language'], 'Contacts');
        
        $this->assertEquals($app_strings['NTC_WELCOME'],'stringname');
        
        $this->assertEquals($app_list_strings['checkbox_dom'],
            array(''=>'','1'=>'Yep','2'=>'Nada'));
        
        $this->assertEquals($mod_strings['LBL_MODULE_NAME'],'stringname');
    }
    
    public function testUndoStringsChangesMade()
    {
        $langpack = new SugarTestLangPackCreator();
        
        $app_strings = return_application_language($GLOBALS['current_language']);
        $prevString = $app_strings['NTC_WELCOME'];
        
        $langpack->setAppString('NTC_WELCOME','stringname');
        $langpack->save();
        
        $app_strings = return_application_language($GLOBALS['current_language']);
        
        $this->assertEquals($app_strings['NTC_WELCOME'],'stringname');
        
        // call the destructor directly to undo our changes
        unset($langpack);
        
        $app_strings = return_application_language($GLOBALS['current_language']);
        
        $this->assertEquals($app_strings['NTC_WELCOME'],$prevString);
    }
}
