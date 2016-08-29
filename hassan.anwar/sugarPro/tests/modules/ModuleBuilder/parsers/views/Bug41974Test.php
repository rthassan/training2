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

require_once ('modules/ModuleBuilder/parsers/views/GridLayoutMetaDataParser.php');
require_once ('modules/ModuleBuilder/parsers/views/ListLayoutMetaDataParser.php');

class Bug41974Test extends Sugar_PHPUnit_Framework_TestCase {

    public function setUp()
    {
        require('include/modules.php');
        $GLOBALS['beanList'] = $beanList;
        $GLOBALS['beanFiles'] = $beanFiles;
        $GLOBALS['app_list_strings'] = return_app_list_strings_language($GLOBALS['current_language']);
    }

    public function testCaseNumberReadOnlyFieldNotRequired() {
        $parser = new GridLayoutMetaDataParser(MB_EDITVIEW, 'Cases');
        $required_fields = $parser->getRequiredFields();
        $vals = array_flip($required_fields);
        $this->assertTrue(isset($vals['"name"']), 'Assert that the AbstractMetaDataParser->getRequiredFields function returns name as required');
        $this->assertFalse(isset($vals['"case_number"']), 'Assert that the AbstractMetaDataParser->getRequiredFields function does not return case_number as required');

        $parser = new ListLayoutMetaDataParser(MB_LISTVIEW, 'Cases');
        $required_fields = $parser->getRequiredFields();
        $vals = array_flip($required_fields);
        $this->assertTrue(isset($vals['"name"']), 'Assert that the AbstractMetaDataParser->getRequiredFields function returns name as required');
        $this->assertFalse(isset($vals['"case_number"']), 'Assert that the AbstractMetaDataParser->getRequiredFields function does not return case_number as required');

    }
}