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

require_once "modules/ModuleBuilder/parsers/views/ListLayoutMetaDataParser.php";
require_once 'modules/ModuleBuilder/parsers/views/DeployedMetaDataImplementation.php' ;

/**
 * Check ListLayoutMetaDataParser fills listviewdefs correctly for flex relate custom field to be displayed
 * in ListView layout.
 *
 * Field should contain:
 * 'related_fields' key - for data access (entity name)
 * 'id'                 - for entity id in link
 * 'dynamic_module'     - for entity module in link
 */
class Bug40969Test extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    protected $vardefs =
        array(
            'name'         => array(
                                  'name'     => 'name',
                                  'vname'    => 'LBL_OPPORTUNITY_NAME',
                                  'type'     => 'name',
                                  'dbType'   => 'varchar',
                                  'required' => true,
                              ),
            'date_entered' => array(
                                  'name'  => 'date_entered',
                                  'vname' => 'LBL_DATE_ENTERED',
                                  'type'  => 'datetime',
                              ),
            'parent_name'  => array(
                                  'source'        => 'non-db',
                                  'name'          => 'parent_name',
                                  'vname'         => 'LBL_FLEX_RELATE',
                                  'type'          => 'parent',
                                  'options'       => 'parent_type_display',
                                  'type_name'     => 'parent_type',
                                  'id_name'       => 'parent_id',
                                  'parent_type'   => 'record_type_display',
                                  'id'            => 'Opportunitiesparent_name',
                                  'custom_module' => 'Opportunities',
                              ),
            'parent_id'    => array(
                                  'source'        => 'custom_fields',
                                  'name'          => 'parent_id',
                                  'vname'         => 'LBL_PARENT_ID',
                                  'type'          => 'id',
                                  'id'            => 'Opportunitiesparent_id',
                                  'custom_module' => 'Opportunities',
                              ),
            'parent_type'  => array(
                                  'required'      => false,
                                  'source'        => 'custom_fields',
                                  'name'          => 'parent_type',
                                  'vname'         => 'LBL_PARENT_TYPE',
                                  'type'          => 'parent_type',
                                  'dbType'        => 'varchar',
                                  'id'            => 'Opportunitiesparent_type',
                                  'custom_module' => 'Opportunities',
                              ),
        );

    /**
     * @var array
     */
    public $originalVardefs =
        array(
            'name'         => array(
                                  'width'   => 30,
                                  'label'   => 'LBL_LIST_OPPORTUNITIES_NAME',
                                  'link'    => true,
                                  'default' => true,
                              ),
            'dete_entered' => array(
                                  'width'   => 10,
                                  'label'   => 'LBL_DATE_ENTERED',
                                  'default' => true,
                              ),
        );

    public function setUp()
    {
        $_POST = array(
                     'group_0' => array('name', 'date_entered', 'parent_name'),
                 );
    }

    public function tearDown()
    {
        $_POST = array();
    }

    public function testCustomFlexFieldListViewDefs()
    {
        $methods = array('getFielddefs', 'getOriginalViewdefs', 'getViewdefs');

        // Mock ListLayoutMetaDataParser Meta Implementation and make it return test values
        $implementation = $this->getMock('DeployedMetaDataImplementation', $methods, array(), '', false);

        $implementation->expects($this->any())->method('getFielddefs')->will($this->returnValue($this->vardefs));
        $implementation->expects($this->any())->method('getOriginalViewdefs')->will($this->returnValue($this->originalVardefs));
        $implementation->expects($this->any())->method('getViewdefs')->will($this->returnValue($this->originalVardefs));

        $metaParser =  new Bug40969ListLayoutMetaDataParser($implementation, $this->vardefs);

        $metaParser->testBug40969();

        // Assert Flex Relate field contain required listview defs to be correctly displayed
        $this->assertArrayHasKey('parent_name', $metaParser->_viewdefs);
        $this->assertArrayHasKey('dynamic_module', $metaParser->_viewdefs['parent_name']);
        $this->assertArrayHasKey('id', $metaParser->_viewdefs['parent_name']);
        $this->assertArrayHasKey('link', $metaParser->_viewdefs['parent_name']);
        $this->assertTrue($metaParser->_viewdefs['parent_name']['link']);
        $this->assertArrayHasKey('related_fields', $metaParser->_viewdefs['parent_name']);
        $this->assertEquals(array('parent_id', 'parent_type'), $metaParser->_viewdefs['parent_name']['related_fields']);
    }

}

/**
 * Helper class to access protected "_populateFromRequest" method
 */
class Bug40969ListLayoutMetaDataParser extends ListLayoutMetaDataParser
{
    /**
     * @var DeployedMetaDataImplementation
     */
    public $implementation;

    public function __construct($implementation)
    {
        $this->implementation = $implementation;

        $this->_viewdefs = array_change_key_case($this->implementation->getViewdefs());
        $this->_fielddefs = $this->implementation->getFielddefs() ;
    }

    public function testBug40969()
    {
        return $this->_populateFromRequest();
    }

}
