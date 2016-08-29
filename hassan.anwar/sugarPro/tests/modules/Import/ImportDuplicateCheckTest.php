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

require_once 'modules/Import/ImportDuplicateCheck.php';

class ImportDuplicateCheckTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp() 
    {
        $beanList = array();
        $beanFiles = array();
        require('include/modules.php');
        $GLOBALS['beanList'] = $beanList;
        $GLOBALS['beanFiles'] = $beanFiles;
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $app_strings = array();
        require('include/language/en_us.lang.php');
        $GLOBALS['app_strings'] = $app_strings;
    }
    
    public function tearDown() 
    {
        unset($GLOBALS['beanList']);
        unset($GLOBALS['beanFiles']);
        unset($GLOBALS['app_strings']);
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
    }

    public function testUsersImportDuplicateCheckOnUsername()
    {
        $original = BeanFactory::getBean('Users');
        $original->user_name = 'ut_user_name_ '.date("YmdHis");
        $original->save(false);

        $duplicatedFocus = BeanFactory::getBean('Users');
        $duplicatedFocus->user_name = $original->user_name;

        //Ensure we can't import users with existing user_names
        $idc = new ImportDuplicateCheck($duplicatedFocus);
        $this->assertTrue($idc->isADuplicateRecord(array('idx_user_name')));

        //Ensure we can still import users with valid user names
        $notDuplicatedFocus = BeanFactory::getBean('Users');
        $notDuplicatedFocus->user_name = 'new_user_name_ '.mt_rand();
        $idcNonDupe     = new ImportDuplicateCheck($notDuplicatedFocus);
        $this->assertFalse($idcNonDupe->isADuplicateRecord(array('idx_user_name')));
    }

    public function testGetDuplicateCheckIndexesWithEmail()
    {
        $focus = BeanFactory::getBean('Contacts');
        
        $idc     = new ImportDuplicateCheck($focus);
        $indexes = $idc->getDuplicateCheckIndexes();
        foreach ($focus->getIndices() AS $key => $index) {
            $moduleIndexes[$index['name']] = true;
        }
        foreach ($indexes AS $name => $fields) {
            if (stristr($name, 'special')) {
                continue;
            }
            $this->assertArrayHasKey($name, $moduleIndexes, "Couldn't find index by: {$name}");    
        }


        $this->assertTrue(isset($indexes['special_idx_email1']));
        $this->assertTrue(isset($indexes['special_idx_email2']));
    }
    
    public function testGetDuplicateCheckIndexesNoEmail()
    {
        $focus = BeanFactory::getBean('Calls');
        
        $idc     = new ImportDuplicateCheck($focus);
        $indexes = $idc->getDuplicateCheckIndexes();
        foreach ($focus->getIndices() AS $key => $index) {
            $moduleIndexes[$index['name']] = true;
        }
        foreach ($indexes AS $name => $fields) {
            if (stristr($name, 'special')) {
                continue;
            }
            $this->assertArrayHasKey($name, $moduleIndexes, "Couldn't find index by: {$name}");    
        }

        $this->assertFalse(isset($indexes['special_idx_email1']));
        $this->assertFalse(isset($indexes['special_idx_email2']));
    }


    public function testIsADuplicateRecord()
    {
        $last_name = 'FooBar'.date("YmdHis");
        
        $focus = BeanFactory::getBean('Contacts');
        $focus->last_name = $last_name;
        $id = $focus->save(false);
        
        $focus = BeanFactory::getBean('Contacts');
        $focus->last_name = $last_name;
        
        $idc = new ImportDuplicateCheck($focus);
        
        $this->assertTrue($idc->isADuplicateRecord(array('idx_contacts_del_last::last_name')));
        
        $focus->mark_deleted($id);
    }
    
    public function testIsADuplicateRecordEmail()
    {
        $email = date("YmdHis").'@foobar.com';
        
        $focus = BeanFactory::getBean('Contacts');
        $focus->email1 = $email;
        $id = $focus->save(false);
        
        $focus = BeanFactory::getBean('Contacts');
        $focus->email1 = $email;
        
        $idc = new ImportDuplicateCheck($focus);
        
        $this->assertTrue($idc->isADuplicateRecord(array('special_idx_email1')));
        
        $focus->mark_deleted($id);
    }
    
    public function testIsADuplicateRecordNotFound()
    {
        $last_name = 'BadFooBar'.date("YmdHis");
        
        $focus = BeanFactory::getBean('Contacts');
        $focus->last_name = $last_name;
        
        $idc = new ImportDuplicateCheck($focus);
        
        $this->assertFalse($idc->isADuplicateRecord(array('idx_contacts_del_last::'.$last_name)));
    }
    
    public function testIsADuplicateRecordEmailNotFound()
    {
        $email = date("YmdHis").'@badfoobar.com';
        
        $focus = BeanFactory::getBean('Contacts');
        $focus->email1 = $email;
        
        $idc = new ImportDuplicateCheck($focus);
        
        $this->assertFalse($idc->isADuplicateRecord(array('special_idx_email1')));
    }

    //make sure exclusion array is respected when displaying the list of available indexes for dupe checking
    public function testExcludeIndexesFromDupeCheck()
    {
        //create the bean to test on
        $focus = BeanFactory::getBean('Contacts');

        //create the importDuplicateCheck object and get the list of duplicateCheckIndexes
        $idc = new ImportDuplicateCheck($focus);

        //get the list of importable indexes
        $indexes = $import_indexes = $focus->getIndices();


        //grab any custom indexes if they exist
        if($focus->hasCustomFields()){
            $custmIndexes = $focus->db->helper->get_indices($focus->table_name.'_cstm');
            $indexes = array_merge($custmIndexes,$indexes);
        }

        //get list indexes to be displayed
        $dupe_check_indexes = $idc->getDuplicateCheckIndexedFiles();

        //Make sure that the indexes used for dupe checking honors the exclusion array.  At a minimum, all beans will have
        //their id and teamset indexes excluded.
        $this->assertTrue(count($indexes) > count($dupe_check_indexes), 'Indexes specified for exclusion are not getting excluded from getDuplicateCheckIndexedFiles()');
    }


    //make sure only selected indexes are checked for dupes
    public function testCompareOnlySelectedIndexesFromDupeCheck()
    {
        //create a bean, values, populate and save
        $focus = BeanFactory::getBean('Contacts');
        $focus->first_name = 'first '.date("YmdHis");
        $focus->last_name = 'last '.date("YmdHis");
        $focus->assigned_user_id = '1';
        $focus->save();
        //because of fix 51264, stored beans can't be duplicates
        $focus->id = null;

        //create the importDuplicateCheck object and get the list of duplicateCheckIndexes
        $idc = new ImportDuplicateCheck($focus);

        //we are going to test agains the first name, last name, full name, and assigned to indexes
        //to prove that only selected indexes are being used.

        //lets do a straight dupe check with the same bean on first name, should return true
        $this->assertTrue($idc->isADuplicateRecord(array('idx_cont_last_first::first_name')),'simulated check against first name index (idx_cont_last_first::first_name) failed  (returned false instead of true).');

        //now lets test on full name index should also return true
        $this->assertTrue($idc->isADuplicateRecord(array('full_name::full_name')),'first simulated check against full name index (full_name::full_name) failed (returned false instead of true).  This check means BOTH first AND last name must match.');

        //now lets remove the first name and redo the check, should return false
        $focus->first_name = '';
        $idc = new ImportDuplicateCheck($focus);
        $this->assertFalse($idc->isADuplicateRecord(array('idx_cont_last_first::first_name')),'simulated check against first name index (idx_cont_last_first::first_name) failed (returned true instead of false).  This is wrong because we removed the first name so there should be no match.');

        //lets retest on full name index should return false now as first AND last do not match the original
        $this->assertFalse($idc->isADuplicateRecord(array('full_name::full_name')),'second simulated check against full name index (full_name::full_name) failed (returned true instead of false).  This check means BOTH first AND last name must match and is wrong because we removed the first name so there should be no match.');

        //now lets rename the contact and test on assigned user, should return true
        $focus->first_name = 'first '.date("YmdHis");
        $focus->last_name = 'last '.date("YmdHis");
        $idc = new ImportDuplicateCheck($focus);
        $this->assertTrue($idc->isADuplicateRecord(array('idx_del_id_user::assigned_user_id')),'simulated check against assigned user index (idx_del_id_user::assigned_user_id) failed (returned false instead of true).  This is wrong because we have not changed this field and it should remain a duplicate');

        //we're done, lets delete the focus bean now
        $focus->mark_deleted($focus->id);

    }



}
