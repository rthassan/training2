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

require_once 'include/SugarSmarty/plugins/function.sugar_translate.php';

class FunctionSugarTranslateTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function providerJsEscapedSting()
    {
        return array(
            array(
                "Friend's",
                "Friend\'s",
                ),
            array(
                "Friend\'s",
                "Friend\\\\\\'s",
                ),
            array(
                "Friend&#39;s",
                "Friend\'s",
                ),
            array(
                "Friend&#39;'s",
                "Friend\'\'s",
                ),
            array(
                "Friend&#039;s",
                "Friend\'s",
                ),
            array(
                "Friend&#039;'s",
                "Friend\'\'s",
                ),
            );
    }

    /**
     * @dataProvider providerJsEscapedSting
     * @ticket 41983
     */
    public function testJsEscapedSting($string, $returnedString) 
    {
        $langpack = new SugarTestLangPackCreator();
        $langpack->setModString('LBL_TEST_JS_ESCAPED_STRING', $string, 'Contacts');
        $langpack->save();

        $smarty = new Sugar_Smarty;
        
        $this->assertEquals($returnedString, smarty_function_sugar_translate(
            array(
                'label'  => 'LBL_TEST_JS_ESCAPED_STRING',
                'module' => 'Contacts',
                'for_js'  =>  true,
            ),
            $smarty)
        );
    }

    public function providerStripColonSting()
    {
        return array(
            array(
                "Friend:",
                "Friend:",
                ),
            array(
                "Friend : ",
                "Friend : ",
                ),
            array(
                ": Friend",
                ": Friend",
                ),
            array(
                "Fr:iend",
                "Fr:iend",
                ),
        );
    }

    /**
     * @dataProvider providerStripColonSting
     * @ticket 41983
     */
    public function testStripColonString($string, $returnedString) 
    {
        $langpack = new SugarTestLangPackCreator();
        $langpack->setModString('LBL_TEST_JS_ESCAPED_STRING', $string, 'Contacts');
        $langpack->save();

        $smarty = new Sugar_Smarty;
        
        $this->assertEquals($returnedString, smarty_function_sugar_translate(
            array(
                'label'  => 'LBL_TEST_JS_ESCAPED_STRING',
                'module' => 'Contacts',
                'trimColon'  =>  false,
            ),
            $smarty)
        );
    }
    
}
