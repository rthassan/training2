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
 
require_once 'modules/Import/UsersLastImport.php';

class UsersLastImportTest extends Sugar_PHPUnit_Framework_TestCase
{
    private $_importModule = 'Notes';
    private $_importObject = 'Note';
    private $_importRecordCount = 3;
    private $_importIds = array();
    private $_usersLastImport;
    private $_usersLastImportIds;
    
    public function setUp() 
    {
        SugarTestHelper::setUp("current_user");
        $this->_usersLastImport = new UsersLastImport();
        $this->_addImportedRecords();
    }
    
    public function tearDown() 
    {
        $focus = $this->_loadBean($this->_importModule);
        
        $sql = "DELETE FROM {$focus->table_name} WHERE id IN ('" . implode("','", $this->_importIds) . "')";
        $GLOBALS['db']->query($sql);
        
        $sql = 'DELETE FROM users_last_import WHERE id IN (\'' . implode("','",$this->_usersLastImportIds) . '\')';
        $GLOBALS['db']->query($sql);
        SugarTestHelper::tearDown();
    }
    
    private function _loadBean($module)
    {
        return BeanFactory::getBean($module);
    }
    
    private function _addImportedRecords()
    {
        for ( $i = 0; $i < $this->_importRecordCount; $i++ ) {
            $focus = $this->_loadBean($this->_importModule);
            $focus->name = "record $i";
            $focus->save();
            $this->_importIds[$i] = $focus->id;
            
            $last_import = new UsersLastImport();
            $last_import->assigned_user_id = $GLOBALS['current_user']->id;
            $last_import->import_module = $this->_importModule;
            $last_import->bean_type = $this->_importObject;
            $last_import->bean_id = $this->_importIds[$i];
            $this->_usersLastImportIds[] = $last_import->save();
        }
    }
    
    public function testMarkDeletedByUserId()
    {
        $this->_usersLastImport->mark_deleted_by_user_id($GLOBALS['current_user']->id);
        
        $query = "SELECT * FROM users_last_import 
                    WHERE assigned_user_id = '{$GLOBALS['current_user']->id}'";
        
        $result = $GLOBALS['db']->query($query);
       
        $this->assertFalse($GLOBALS['db']->fetchByAssoc($result),'There should not be any records in the table now');
    }
    
    public function testUndo()
    {
        $this->assertTrue(
            $this->_usersLastImport->undo(
                $this->_importModule
                )
            );
        
        $focus = $this->_loadBean($this->_importModule);
        
        $query = "SELECT * FROM {$focus->table_name}
                    WHERE id IN ('" . 
                        implode("','",$this->_importIds) . "')";
        
        $result = $GLOBALS['db']->query($query);
        
        $this->assertFalse($GLOBALS['db']->fetchByAssoc($result),'There should not be any records in the table now');
    }
    
    /**
     * @ticket 21828
     */
    public function testUndoRemovedAddedEmailAddresses()
    {
        $time = date('Y-m-d H:i:s');
        $unid = uniqid();
        
        $focus = new Account();
        $focus->id = "Account_".$unid;
        
        $last_import = new UsersLastImport();
        $last_import->assigned_user_id = $GLOBALS['current_user']->id;
        $last_import->import_module = 'Accounts';
        $last_import->bean_type = 'Account';
        $last_import->bean_id = $focus->id;
        $last_import->save();
        
        $this->email_addr_bean_rel_id = 'email_addr_bean_rel_'.$unid;
        $this->email_address_id = 'email_address_id_'.$unid;
        $GLOBALS['db']->query("insert into email_addr_bean_rel (id , email_address_id, bean_id, bean_module, primary_address, date_created , date_modified) values ('{$this->email_addr_bean_rel_id}', '{$this->email_address_id}', '{$focus->id}', 'Accounts', 1, '$time', '$time')");
				
        $GLOBALS['db']->query("insert into email_addresses (id , email_address, email_address_caps, date_created, date_modified) values ('{$this->email_address_id}', 'test@g.com', 'TEST@G.COM', '$time', '$time')");

        // setup
        require('include/modules.php');
        $GLOBALS['beanList'] = $beanList;
        $GLOBALS['beanFiles'] = $beanFiles;
        
        $this->assertTrue(
            $last_import->undo(
                $last_import->import_module
                )
            );
        
        $result = $GLOBALS['db']->query("SELECT * FROM email_addr_bean_rel where id = '{$this->email_addr_bean_rel_id}'");
		$rows = $GLOBALS['db']->fetchByAssoc($result);
    	$this->assertFalse($rows);
    	
    	$result = $GLOBALS['db']->query("SELECT * FROM email_addresses where id = '{$this->email_address_id}'");
		$rows = $GLOBALS['db']->fetchByAssoc($result);
    	$this->assertFalse($rows);
        
        $GLOBALS['db']->query("DELETE FROM users_last_import WHERE id = '{$last_import->id}'");
    }
    
    public function testUndoById()
    {
        $this->assertTrue(
            $this->_usersLastImport->undoById(
                $this->_usersLastImportIds[0]
                )
            );
        
        $focus = $this->_loadBean($this->_importModule);
        
        $query = "SELECT * FROM {$focus->table_name}
                    WHERE id = '{$this->_importIds[0]}'";
        
        $result = $GLOBALS['db']->query($query);
        
        $this->assertFalse($GLOBALS['db']->fetchByAssoc($result),'There should not be any records in the table now');

    }
    
    public function testGetBeansByImport()
    {
        foreach ( UsersLastImport::getBeansByImport('Notes') as $objectName )
            $this->assertEquals($objectName,'Note');
    }
}
