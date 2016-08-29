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
 * @ticket 10329
 *
 *		Original Bug: Studio Layout Fields should be alphabetical and detail view should remove used fields 
 *		1. Admin > Studio
 *		2. Module Name > Layouts > Edit and or Detail View
 *		3. Note fields in lower left and upper left quadrants.
 *		Desired behavior: alphabetized fields.  
 *
 */
class Bug10329Test extends Sugar_PHPUnit_Framework_TestCase
{
	private $_parser;
	 
    public function setUp()
    {
		require('include/modules.php');
		$GLOBALS['beanList'] = $beanList;
		$GLOBALS['beanFiles'] = $beanFiles;    	
    	$GLOBALS['app_list_strings'] = return_app_list_strings_language($GLOBALS['current_language']);
    	require_once ('modules/ModuleBuilder/parsers/ParserFactory.php') ;
		$this->_parser = ParserFactory::getParser('EditView','Accounts');
    }

    public function tearDown()
	{
		//unset($GLOBALS['app_list_strings']);
		//unset($this->_parser);
	}

    public function testTranslateLabel()
    {
        $avail_fields = $this->_parser->getAvailableFields();
        //verify that translateLabel exists
        $this->assertArrayHasKey('translatedLabel', $avail_fields[0]);
    }
}
