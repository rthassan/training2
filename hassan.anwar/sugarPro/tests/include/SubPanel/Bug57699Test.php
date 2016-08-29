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

require_once 'tests/include/SubPanel/SubPanelTestBase.php';

/**
 * Bug 57699
 * 
 * Setup and TearDown are called in the parent
 */
class Bug57699Test extends SubPanelTestBase
{
    protected $_testModule = 'Accounts';
    
    public function setUp() {
        parent::setUp();
        
        // Set up our test defs
        $this->_testDefs = array(
            'order' => 40,
            'title_key' => 'LBL_HISTORY_SUBPANEL_TITLE',
            'type' => 'collection',
            'subpanel_name' => 'history',   //this values is not associated with a physical file.
            'sort_order' => 'desc',
            'sort_by' => 'date_entered',
            'header_definition_from_subpanel'=> 'calls',
            'module'=>'History',
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopCreateNoteButton'),
            ),	
            'collection_list' => array(		
                'notes' => array(
                    'module' => 'Notes',
                    'subpanel_name' => 'ForCalls',
                    'get_subpanel_data' => 'notes',
                ),		
            ), 
        );
    }
    
    /**
     * Tests that Notes is a shown subpanel for Calls out of the box
     * 
     * @group Bug57699
     */
    public function testNotesSubpanelOnCallsAllowedOnDefaultInstallation() {
        $subpanel = new aSubPanel('history', $this->_testDefs, $this->_testBean);
        $this->assertArrayHasKey('notes', $subpanel->sub_subpanels, "Notes module not found in History subpanel's Notes subpanel");
    }
    
    /**
     * Tests that Notes is a shown subpanel for Calls even when removed from the
     * module tabs
     * 
     * @group Bug57699
     */
    public function testNotesSubpanelOnCallsAllowedWhenNotesIsHiddenFromNav() {
        // Adjust the module list by removing Notes from nav and prove it's still there
        $currentTabs = $this->_currentTabs;
        unset($currentTabs['Notes']);
        $this->_tabController->set_system_tabs($currentTabs);
        
        $subpanel = new aSubPanel('history', $this->_testDefs, $this->_testBean);
        $this->assertArrayHasKey('notes', $subpanel->sub_subpanels, "Notes module not found in History subpanel's Notes subpanel after module list modified");
    }
    
    /**
     * Tests that Notes is not a shown subpanel for Calls when removed from subpanels
     * 
     * @group Bug57699
     */
    public function testNotesSubpanelOnCallsNotAllowedWhenNotesIsHiddenFromSubpanels() {
        // Remove Notes from the subpanel modules and test it is NOT shown
        $hidden = $this->_currentSubpanels['hidden'];
        $hidden['notes'] = 'notes';
        $hiddenKeyArray = TabController::get_key_array($hidden);
        $this->_subPanelDefinitions->set_hidden_subpanels($hiddenKeyArray);
        
        // Rebuild the cache
        $this->_subPanelDefinitions->get_all_subpanels(true);
        $this->_subPanelDefinitions->get_hidden_subpanels();
        
        $subpanel = new aSubPanel('history', $this->_testDefs, $this->_testBean);
        $this->assertEmpty($subpanel->sub_subpanels, "History subpanel's subpanel should be empty after Notes removed from subpanel module list");
    }
}