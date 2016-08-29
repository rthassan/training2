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

require_once 'modules/ModuleBuilder/parsers/relationships/OneToManyRelationship.php';

/**
 * One to Many relationship created between Targets module
 * and another module results in "Targets" field in related module cannot be filled.
 * @ticket PAT-713
 * @author bsitnikovski@sugarcrm.com
 */
class BugPAT713Test extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function tearDown()
    {
    }

    public function modules()
    {
        return array(
            array('Cases'),
            array('Contacts'),
        );
    }

    /**
     * @dataProvider modules
     * @param string $relatedModule
     */
    public function testLinkFieldRname($relatedModule)
    {
        $relationship = new OneToManyRelationship(array(
            'rhs_label' => $relatedModule,
            'lhs_label' => 'Targets',
            'rhs_subpanel' => 'default',
            'lhs_module' => 'Prospects',
            'rhs_module' => $relatedModule,
            'relationship_type' => 'one-to-many',
            'readonly' => false,
            'deleted' => false,
            'relationship_only' => false,
            'for_activities' => false,
            'is_custom' => false,
            'from_studio' => true,
            'relationship_name' => 'prospects_25478',
        ));
        $vardefs   = $relationship->buildVardefs();

        if (isset($vardefs[$relatedModule][1]) &&
            isset($vardefs[$relatedModule][1]['link']) &&
            $vardefs[$relatedModule][1]['type'] == 'relate') {
            $linkField   = $vardefs[$relatedModule][1];
            $relatedBean = BeanFactory::getBean($relatedModule);
            $fieldMap    = array_keys($relatedBean->field_name_map);

            $this->assertContains($linkField['rname'], $fieldMap, 'Rname field does not exist in related module.');

            $fieldDef = $relatedBean->field_name_map[$linkField['rname']];

            $this->assertFalse($fieldDef['type'] == 'relate', 'Related field does not belong to related module.');
        } else {
            $this->fail('Link field not found.');
        }
    }
}
