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

require_once "modules/Tasks/Task.php";
require_once('modules/ModuleBuilder/parsers/views/ListLayoutMetaDataParser.php');

class Bug45715Test extends Sugar_PHPUnit_Framework_TestCase
{

    public function testFieldsVisibilityToStudioListView()
    {
        $task = new Task();
        $parser = new ListLayoutMetaDataParserMock('listview', $task->module_dir);
        $this->assertFalse($parser->isValidField('contact_email',$task->field_defs['contact_email']), 'Assert isValidField for contact_email returns false');
        $this->assertTrue($parser->isValidField('contact_phone',$task->field_defs['contact_phone']) , 'Assert isValidField for contact_phone returns true');
        $this->assertFalse($parser->isValidField('date_due_flag',$task->field_defs['date_due_flag']), 'Assert isValidField for date_due_flag returns false');
        $this->assertTrue($parser->isValidField('date_start',$task->field_defs['date_start']) , 'Assert isValidField for date_start returns true');

    }

}

class ListLayoutMetaDataParserMock extends ListLayoutMetaDataParser
{
    function __construct ($view , $moduleName , $packageName = '')
    {
        $this->view = $view;
    }
}