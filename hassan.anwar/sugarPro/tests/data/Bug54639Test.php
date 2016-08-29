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

require_once('data/SugarBean.php');

/**
 * @ticket 47731
 * @ticket 54639
 */
class Bug54639Test extends Sugar_PHPUnit_Framework_TestCase
{
    private $contact = null;

    /**
     *
     */
    public function setUp()
    {
        $this->contact = new Contact();
        $this->contact->field_defs["as_tetrispe_accounts_name"] = array (
            'name' => 'as_tetrispe_accounts_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_AS_TETRISPERSON_ACCOUNTS_FROM_ACCOUNTS_TITLE',
            'save' => true,
            'id_name' => 'as_tetrispac95ccounts_ida',
            'link' => 'as_tetrisperson_accounts',
            'table' => 'accounts',
            'module' => 'Accounts',
            'rname' => 'name',
        );

        $this->contact->field_defs["as_tetrispac95ccounts_ida"] = array (
            'name' => 'as_tetrispac95ccounts_ida',
            'type' => 'link',
            'relationship' => 'as_tetrisperson_accounts',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'right',
            'vname' => 'LBL_AS_TETRISPERSON_ACCOUNTS_FROM_AS_TETRISPERSON_TITLE',
        );
    }

    /**
     * Test getting import fields from a bean when a relationship has been defined and the id field is only defined as a link
     * and not a relate entry. The id field should be exposed so that users can select it from a list during the import process.
     *
     * @group bug54639
     * @return void
     */
    public function testGetImportableFields()
    {
        $c = new Contact();
        $importableFields = $c->get_importable_fields();
        $this->assertTrue(isset($importableFields['as_tetrispac95ccounts_ida']));
    }
}