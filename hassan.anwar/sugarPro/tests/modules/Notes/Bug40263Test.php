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
 
require_once 'modules/Users/User.php';
require_once "modules/Notes/Note.php";

/**
 * @group bug40263
 */
class Bug40263Test extends Sugar_PHPUnit_Framework_TestCase
{
	var $user;
	var $note;

	public function setUp()
    {
		global $current_user;

		$this->user = SugarTestUserUtilities::createAnonymousUser();//new User();
		$this->user->first_name = "test";
		$this->user->last_name = "user";
		$this->user->user_name = "test_test";
		$this->user->save();
		$current_user=$this->user;

		$this->note = new Note();
		$this->note->name = "Bug40263 test Note";
		$this->note->save();
	}

	public function tearDown()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        $this->note->mark_deleted($this->note->id);
        $this->note->db->query("DELETE FROM notes WHERE id='{$this->note->id}'");
	}

	public function testGetListViewQueryCreatedBy()
    {
		require_once("include/ListView/ListViewDisplay.php");
        include("modules/Notes/metadata/listviewdefs.php");
        $displayColumns = array(
            'NAME' => array (
			    'width' => '40%',
			    'label' => 'LBL_LIST_SUBJECT',
			    'link' => true,
			    'default' => true,
			 ),
			 'CREATED_BY_NAME' => array (
			     'type' => 'relate',
			     'label' => 'LBL_CREATED_BY',
			     'width' => '10%',
			     'default' => true,
			 ),
		);
		$lvd = new ListViewDisplay();
		$lvd->displayColumns = $displayColumns;
		$fields = $lvd->setupFilterFields();
    	$query = $this->note->create_new_list_query('', 'id="' . $this->note->id . '"', $fields);
    	$regex = '/select.* created_by_name.*LEFT JOIN\s*users jt\d ON\s*notes.created_by\s*=\s*jt\d\.id.*/si';
    	return $this->assertRegExp($regex, $query, "Unable to find the created user in the notes list view query: $query");
    }

}

