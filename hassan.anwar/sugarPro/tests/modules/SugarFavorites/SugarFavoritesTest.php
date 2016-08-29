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
 
require_once('modules/SugarFavorites/SugarFavorites.php');
require_once('modules/SugarFavorites/controller.php');

class SugarFavoritesTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setup()
    {
        SugarTestHelper::setUp("current_user");
    }
    
    public function tearDown()
    {
        SugarTestContactUtilities::removeAllCreatedContacts();
        SugarTestHelper::tearDown();
    }
    
    public function testStarAndUnstarRecord()
    {
        $this->markTestIncomplete('Failing. Need to be fixed by FRM team');
        $contactFocus = SugarTestContactUtilities::createContact();
        
        $_REQUEST['fav_module'] = 'Contacts';
        $_REQUEST['fav_id'] = $contactFocus->id;
        
        $controller = new SugarFavoritesController;
        $controller->loadBean();
        $controller->pre_save();
        $controller->action_save();
        
        $this->assertTrue(SugarFavorites::isUserFavorite($_REQUEST['fav_module'],$_REQUEST['fav_id']));
        
        $controller->action_delete();
        
        $this->assertFalse(SugarFavorites::isUserFavorite($_REQUEST['fav_module'],$_REQUEST['fav_id']));
    }
    
    public function testGetStarredRecordsForAModule()
    {
        $this->markTestIncomplete('Failing. Need to be fixed by FRM team');
        $contactFocus = SugarTestContactUtilities::createContact();
        
        $_REQUEST['fav_module'] = 'Contacts';
        $_REQUEST['fav_id'] = $contactFocus->id;
        
        $controller = new SugarFavoritesController;
        $controller->loadBean();
        $controller->pre_save();
        $controller->action_save();
        
        $results = SugarFavorites::getUserFavoritesByModule($_REQUEST['fav_module']);
        
        $this->assertEquals($results[0]->record_id,$contactFocus->id);
        
        $controller->action_delete();
        
        $this->assertFalse(SugarFavorites::isUserFavorite($_REQUEST['fav_module'],$_REQUEST['fav_id']));
    }

    public function testGetUserIdsForFavoriteRecordByModuleRecord() {
        $contactFocus = SugarTestContactUtilities::createContact();
        
        $_REQUEST['fav_module'] = 'Contacts';
        $_REQUEST['fav_id'] = $contactFocus->id;
        
        $controller = new SugarFavoritesController;
        $controller->loadBean();
        $controller->pre_save();
        $controller->action_save();


        $assigned_user_ids = SugarFavorites::getUserIdsForFavoriteRecordByModuleRecord('Contacts', $contactFocus->id);

        $this->assertNotEmpty($assigned_user_ids, "Should have got back an assigned user ID");

        $assigned_user_ids = SugarFavorites::getUserIdsForFavoriteRecordByModuleRecord('TestNonExistantModule', '8675309');

        $this->assertEmpty($assigned_user_ids, "Should not have got back an assigned user ID");

    }

    public function testLotsaToggles() {
        // create a favorite
        $contactFocus = SugarTestContactUtilities::createContact();
        
        $_REQUEST['fav_module'] = 'Contacts';
        $_REQUEST['fav_id'] = $contactFocus->id;
        
        $controller = new SugarFavoritesController;
        $controller->loadBean();
        $controller->pre_save();
        $controller->action_save();

        $guid = SugarFavorites::generateGUID('Contacts', $contactFocus->id, $GLOBALS['current_user']->id);

        // toggle it a few times
        $fav = new SugarFavorites();
        $fav->toggleExistingFavorite($guid, 1);
        $fav->toggleExistingFavorite($guid, 0);
        $fav->toggleExistingFavorite($guid, 1);
        $fav->toggleExistingFavorite($guid, 0);
        // verify I still have it as a favorite

        $assigned_user_ids = SugarFavorites::getUserIdsForFavoriteRecordByModuleRecord('Contacts', $contactFocus->id);

        $this->assertNotEmpty($assigned_user_ids, "Should have got back an assigned user ID");
    }

    public function testToggleExistingFavorite_DeletedIsNot0Or1_ReturnsFalse()
    {
        $fav    = BeanFactory::getBean("SugarFavorites");
        $actual = $fav->toggleExistingFavorite("123", 5);
        $this->assertFalse($actual, "Should abort and return false when the deleted parameter is neither 0 or 1.");
    }

    public function testToggleExistingFavorite_DeletedIs0_CallsMark_undeletedAndReturnsTrue()
    {
        $favMock = $this->getMock("SugarFavorites", array("mark_deleted", "mark_undeleted"));
        $favMock->expects($this->never())->method("mark_deleted");
        $favMock->expects($this->once())->method("mark_undeleted");

        $actual = $favMock->toggleExistingFavorite("123", 0);
        $this->assertTrue($actual, "Should call mark_undeleted and return true when the deleted parameter is 0.");
    }

    public function testToggleExistingFavorite_DeletedIs1_CallsMark_deletedAndReturnsTrue()
    {
        $favMock = $this->getMock("SugarFavorites", array("mark_deleted", "mark_undeleted"));
        $favMock->expects($this->once())->method("mark_deleted");
        $favMock->expects($this->never())->method("mark_undeleted");

        $actual = $favMock->toggleExistingFavorite("123", 1);
        $this->assertTrue($actual, "Should call mark_deleted and return true when the deleted parameter is 0.");
    }

    public function testSave_DeletedIs0_CallsSubscribeUserToRecord()
    {
        $contact = SugarTestContactUtilities::createContact();
        $favMock = $this->getMock("SugarFavorites", array("subscribeUserToRecord"));
        $favMock->expects($this->once())->method("subscribeUserToRecord");

        $favMock->module           = $contact->module_dir;
        $favMock->record_id        = $contact->id;
        $favMock->created_by       = $GLOBALS['current_user']->id;
        $favMock->assigned_user_id = $GLOBALS['current_user']->id;
        $favMock->deleted          = 0;
        $favMock->save();

        $GLOBALS["db"]->query("DELETE FROM sugarfavorites WHERE id='{$favMock->id}'");
    }

    public function testSave_DeletedIs1_NeverCallsSubscribeUserToRecord()
    {
        $contact = SugarTestContactUtilities::createContact();
        $favMock = $this->getMock("SugarFavorites", array("subscribeUserToRecord"));
        $favMock->expects($this->never())->method("subscribeUserToRecord");

        $favMock->module           = $contact->module_dir;
        $favMock->record_id        = $contact->id;
        $favMock->created_by       = $GLOBALS['current_user']->id;
        $favMock->assigned_user_id = $GLOBALS['current_user']->id;
        $favMock->deleted          = 1;
        $favMock->save();

        $GLOBALS["db"]->query("DELETE FROM sugarfavorites WHERE id='{$favMock->id}'");
    }

    public function testMark_undeleted_CallsSubscribeUserToRecord()
    {
        $contact = SugarTestContactUtilities::createContact();
        $favMock = $this->getMock("SugarFavorites", array("subscribeUserToRecord"));
        $favMock->expects($this->once())->method("subscribeUserToRecord");

        $favMock->module           = $contact->module_dir;
        $favMock->record_id        = $contact->id;
        $favMock->created_by       = $GLOBALS['current_user']->id;
        $favMock->assigned_user_id = $GLOBALS['current_user']->id;
        $favMock->deleted          = 1;
        $favMock->save();
        $favMock->mark_undeleted($favMock->id);

        $GLOBALS["db"]->query("DELETE FROM sugarfavorites WHERE id='{$favMock->id}'");
    }
}
