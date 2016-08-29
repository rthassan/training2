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
 
require_once 'include/SubPanel/SubPanelDefinitions.php';
require_once 'modules/MySettings/TabController.php';

class Bug58089Test extends Sugar_PHPUnit_Framework_TestCase
{
    protected $_tabController;
    protected $_currentTabs;
    protected $_currentSubpanels = array('hidden' => array(), 'shown' => array());
    protected $_modListGlobal;
    protected $_subPanelDefinitions;
    protected $_testDefs;
    protected $_exemptModules;
    
    public function setUp() {
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
        
        // Globals setup
        SugarTestHelper::setUp('moduleList');
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('app_list_strings');
        SugarTestHelper::setUp('current_user');
        
        // @hack - Projects totally overrides the exempt module list in its subpanel
        // viewdefs, so to run this test effectively, Projects needs to be 
        // disabled if it is enabled. - rgonzalez
        $this->_modListGlobal = $GLOBALS['moduleList'];
        $key = array_search('Project', $GLOBALS['moduleList']);
        unset($GLOBALS['moduleList'][$key]);
        
        // Get the current module and subpanel settings
        $this->_tabController = new TabController();
        $this->_currentTabs = $this->_tabController->get_system_tabs();
        $this->_subPanelDefinitions = new SubPanelDefinitions(BeanFactory::getBean('Calls'));
        $subpanels = $this->_subPanelDefinitions->get_all_subpanels();
        $subpanels_hidden = $this->_subPanelDefinitions->get_hidden_subpanels();

        if (!empty($subpanels)) {
            $this->_currentSubpanels['shown'] = $subpanels;
        }
        
        if (!empty($subpanels_hidden)) {
            $this->_currentSubpanels['hidden'] = $subpanels_hidden;
        }
        
        // Handle exempt modules, since this global gets set in other places in
        // the code base and is causing the last unit test to fail because of the
        // override that happens in the Project module subpaneldefs.php file.
        $this->_exemptModules = empty($GLOBALS['modules_exempt_from_availability_check']) ? array() : $GLOBALS['modules_exempt_from_availability_check'];
        unset($GLOBALS['modules_exempt_from_availability_check']);
        
        // Copied from include/utils/security_utils.php
        $modules_exempt_from_availability_check['Activities']='Activities';
        $modules_exempt_from_availability_check['History']='History';
        $modules_exempt_from_availability_check['Calls']='Calls';
        $modules_exempt_from_availability_check['Meetings']='Meetings';
        $modules_exempt_from_availability_check['Tasks']='Tasks';
        
        $modules_exempt_from_availability_check['CampaignLog']='CampaignLog';
        $modules_exempt_from_availability_check['CampaignTrackers']='CampaignTrackers';
        $modules_exempt_from_availability_check['Prospects']='Prospects';
        $modules_exempt_from_availability_check['ProspectLists']='ProspectLists';
        $modules_exempt_from_availability_check['EmailMarketing']='EmailMarketing';
        $modules_exempt_from_availability_check['EmailMan']='EmailMan';
        $modules_exempt_from_availability_check['ProjectTask']='ProjectTask';
        $modules_exempt_from_availability_check['Users']='Users';
        $modules_exempt_from_availability_check['Teams']='Teams';
        $modules_exempt_from_availability_check['SchedulersJobs']='SchedulersJobs';
        $modules_exempt_from_availability_check['DocumentRevisions']='DocumentRevisions';
        
        $GLOBALS['modules_exempt_from_availability_check'] = $modules_exempt_from_availability_check;
    }
    
    public function tearDown() {
        // Restore the globals 
        $GLOBALS['moduleList'] = $this->_modListGlobal;
        if (!empty($this->_exemptModules)) {
            $GLOBALS['modules_exempt_from_availability_check'] = $this->_exemptModules;
        }
        
        // Restore the system tabs to pre-test state
        $this->_tabController->set_system_tabs($this->_currentTabs);
        
        // Restore the hidden subpanels to pre-test state
        $this->_subPanelDefinitions->set_hidden_subpanels($this->_currentSubpanels['hidden']);
        
        // Clean up the rest
        SugarTestHelper::tearDown();
    }

    /**
     * Tests that Notes is a shown subpanel for Accounts out of the box
     * 
     * @group Bug58089
     */
    public function testNotesSubpanelOnAccountsAllowedOnDefaultInstallation() {
        $subpanel = new aSubPanel('history', $this->_testDefs, BeanFactory::getBean('Accounts'));
        $this->assertArrayHasKey('notes', $subpanel->sub_subpanels, "Notes module not found in History subpanel's Notes subpanel");
    }
    
    /**
     * Tests that Notes is not a shown subpanel for Accounts when removed from subpanels
     * 
     * @group Bug58089
     */
    public function testNotesSubpanelOnAccountsNotAllowedWhenNotesIsHiddenFromSubpanels() {
        // Remove Notes from the subpanel modules and test it is NOT shown
        $hidden = $this->_currentSubpanels['hidden'];
        $hidden['notes'] = 'notes';
        $hiddenKeyArray = TabController::get_key_array($hidden);
        $this->_subPanelDefinitions->set_hidden_subpanels($hiddenKeyArray);
        
        // Rebuild the cache
        $this->_subPanelDefinitions->get_all_subpanels(true);
        $this->_subPanelDefinitions->get_hidden_subpanels();
        
        $subpanel = new aSubPanel('history', $this->_testDefs, BeanFactory::getBean('Accounts'));
        $this->assertEmpty($subpanel->sub_subpanels, "History subpanel's subpanel should be empty after Notes removed from subpanel module list");
    }
}