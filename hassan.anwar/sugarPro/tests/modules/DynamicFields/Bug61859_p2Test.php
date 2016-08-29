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

require_once('modules/DynamicFields/DynamicField.php');

class Bug61859_p2Test extends Sugar_PHPUnit_Framework_TestCase
{

    static private $module = 'Leads';
    private $object = 'Lead';
    private $relatedModule = 'Contacts';

    static private $field;
    static private $dynamicField;

    /**
     * @group 61859
     */
    public function testAddField()
    {
        $this->addField('testfieldbug61859');
        SugarTestHelper::setUp('dictionary');

        $idName = $GLOBALS['dictionary'][$this->object]['fields'][self::$field->name]['id_name'];

        $this->assertArrayHasKey(self::$field->name, $GLOBALS['dictionary'][$this->object]['fields']);
        $this->assertArrayHasKey($idName, $GLOBALS['dictionary'][$this->object]['fields']);

        return $idName;
    }

    /**
     * @depends testAddField
     * @group 61859
     */
    public function testUpdateField($idName)
    {

        self::$field->label_value = 'UpdatedLabel';
        self::$field->save(self::$dynamicField);

        SugarTestHelper::setUp('dictionary');

        $this->assertEquals($idName, self::$field->ext3);

    }

    private function addField($name)
    {
        $labelName = 'LBL_' . strtoupper($name);
        $field = get_widget('relate');
        $field->audited = 0;
        $field->view = 'edit';
        $field->name = $name;
        $field->vname = $labelName;
        $field->label = $labelName;

        $field->ext2 = $this->relatedModule;
        $field->label_value = $name;
        $field->save(self::$dynamicField);

        self::$field = $field;
    }

    static public function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        SugarTestHelper::setUp('app_list_strings');
        SugarTestHelper::setUp('mod_strings', array(self::$module));
        SugarTestHelper::setUp('mod_strings', array('ModuleBuilder'));

        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('dictionary');
        SugarTestHelper::setUp('current_user');

        self::$dynamicField = new DynamicField(self::$module);
        self::$dynamicField->setup(BeanFactory::getBean(self::$module));

    }

    static public function tearDownAfterClass()
    {
        self::$field->delete(self::$dynamicField);

        SugarTestHelper::tearDown();
        parent::tearDownAfterClass();
    }

}
