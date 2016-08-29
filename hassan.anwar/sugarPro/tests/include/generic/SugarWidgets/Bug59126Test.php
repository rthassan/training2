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

require_once('include/generic/LayoutManager.php');
require_once('include/generic/SugarWidgets/SugarWidgetFieldrelate.php');

class Bug59126Test extends Sugar_PHPUnit_Framework_TestCase
{
    private $contact;

    public function testLastName()
    {
        $layoutDef = array(
            'table' => $this->contact->table_name,
            'input_name0' => array(),
            'name' => 'contacts',
            'rname' => 'last_name',
        );
        $html = $this->getSugarWidgetFieldRelate()->displayInput($layoutDef);
        $regExpPattern =$this->getAssertRegExp($this->contact->id, "{$this->contact->last_name}");
        $this->assertRegExp($regExpPattern, $html);
    }

    public function testFirstLastName()
    {
        $layoutDef = array(
            'table' => $this->contact->table_name,
            'input_name0' => array(),
            'name' => 'contacts',
            'rname' => 'last_name',
            'db_concat_fields' => array('first_name', 'last_name'),
        );
        $html = $this->getSugarWidgetFieldRelate()->displayInput($layoutDef);
        $regExpPattern = $this->getAssertRegExp(
            $this->contact->id,
            "{$this->contact->first_name}\s+{$this->contact->last_name}"
        );
        $this->assertRegExp($regExpPattern, $html);
    }

    public function testCustomField()
    {
        $layoutDef = array(
            'table' => $this->contact->table_name,
            'module' => $this->contact->module_name,
            'custom_module' => 'Contacts',
            'input_name0' => array(),
            'name' => 'customField',
            'rname' => 'name',
        );
        $html = $this->getSugarWidgetFieldRelate()->displayInput($layoutDef);
        $regExpPattern = $this->getAssertRegExp(
            $this->contact->id,
            "{$this->contact->first_name}\s+{$this->contact->last_name}"
        );
        $this->assertRegExp($regExpPattern, $html);
    }

    private function  getAssertRegExp($value, $text)
    {
        $pattern = '/\<option.+value="' . $value . '".*\>' . $text . '\<\/option\>/i';
        return $pattern;
    }

    private function getSugarWidgetFieldRelate()
    {
        $LayoutManager = new LayoutManager();
        $temp = (object)array('db' => $GLOBALS['db'], 'report_def_str' => '');
        $LayoutManager->setAttributePtr('reporter', $temp);
        $Widget = new SugarWidgetFieldRelate($LayoutManager);
        return $Widget;
    }

    public function setUp()
    {
        SugarTestHelper::setUp('current_user');
        $this->contact = SugarTestContactUtilities::createContact();
    }

    public function tearDown()
    {
        SugarTestContactUtilities::removeAllCreatedContacts();
        SugarTestHelper::tearDown();
    }

}
