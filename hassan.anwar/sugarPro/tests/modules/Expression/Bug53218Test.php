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

require_once 'data/SugarBean.php';
require_once 'modules/Expressions/Expression.php';
require_once 'modules/ModuleBuilder/parsers/relationships/DeployedRelationships.php' ;

class Bug53218Test extends Sugar_PHPUnit_Framework_TestCase
{

    /**
     * @var DeployedRelationships
     */
    protected $relationships = null;

    /**
     * @var OneToOneRelationship
     */
    protected $relationship = null;

    public function setUp()
    {
        SugarTestHelper::setUp('current_user', array(true, 1));
        SugarTestHelper::setUp('app_list_strings');
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('beanFiles');
        $this->relationships = new DeployedRelationships('Products');
        $definition = array(
            'lhs_module' => 'Products',
            'relationship_type' => 'one-to-one',
            'rhs_module' => 'Users'
        );
        $this->relationship = RelationshipFactory::newRelationship($definition);
        $this->relationships->add($this->relationship);
        $this->relationships->save();
        $this->relationships->build();
        SugarTestHelper::setUp('relation', array(
            'Products',
            'Users'
        ));
        parent::setUp();
    }

    public function tearDown()
    {
        $this->relationships->delete($this->relationship->getName());
        $this->relationships->save();
        parent::tearDown();
        SugarTestContactUtilities::removeAllCreatedContacts();
        SugarTestAccountUtilities::removeAllCreatedAccounts();
        SugarTestProductUtilities::removeAllCreatedProducts();
        SugarTestHelper::tearDown();
    }

    /**
     * @group 53218
     * @large
     */
    public function testWorkFlowConditionModules()
    {
        $_GET['opener_id']= 'rel_module';
        $expression = new Expression();
        $relations = $expression->get_selector_array('field', '', 'Products');
        $this->assertContains('Users (products_users_1)', $relations);
        $this->assertContains('products_users_1', $relations);
    }

    /**
     * @group 53218
     * @large
     */
    public function testDuplicateRelations()
    {
        $temp_module = SugarModule::get('Products')->loadBean();
        $temp_module->call_vardef_handler();
        $temp_select_array = $temp_module->vardef_handler->get_vardef_array(false, false, true, false);
        $field_defs = $temp_module->vardef_handler->module_object->field_defs;
        unset($field_defs['products_users_1_name']['vname']);
        $temp_select_array = getDuplicateRelationListWithTitle($temp_select_array, $field_defs, $temp_module->vardef_handler->module_object->module_dir);
        $this->assertEquals('Users (products_users_1_name)', $temp_select_array['products_users_1_name']);
    }

}
