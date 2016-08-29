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

require_once('include/ListView/ListViewFacade.php');
require_once('modules/Import/views/view.last.php');

class Bug48496Test extends Sugar_PHPUnit_Framework_TestCase
{
    var $backup_config;

    public function setUp()
    {
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $GLOBALS['app_strings'] = return_application_language($GLOBALS['current_language']);
        $GLOBALS['module']='Imports';
        $_REQUEST['module']='Imports';
        $_REQUEST['import_module']='Accounts';
        $_REQUEST['action']='last';
        $_REQUEST['type']='';
        $_REQUEST['has_header'] = 'off';
        sugar_touch('upload/import/status_'.$GLOBALS['current_user']->id.'.csv');
    }

    public function tearDown()
    {
        unlink('upload/import/status_'.$GLOBALS['current_user']->id.'.csv');
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
        unset($GLOBALS['app_strings']);
        unset($GLOBALS['module']);
        unset($_REQUEST['module']);
        unset($_REQUEST['import_module']);
        unset($_REQUEST['action']);
        unset($_REQUEST['type']);
        unset($_REQUEST['has_header']);
    }

    public function testQueryDoesNotContainDuplicateUsersLastImportClauses() {
        global $current_user;

        $params = array(
            'custom_from' => ', users_last_import',
            'custom_where' => " AND users_last_import.assigned_user_id = '{$current_user->id}'
                AND users_last_import.bean_type = 'Account'
                AND users_last_import.bean_id = accounts.id
                AND users_last_import.deleted = 0
                AND accounts.deleted = 0",
        );

        $seed = BeanFactory::getBean('Accounts');

        $lvfMock = $this->getMock('ListViewFacade', array('setup', 'display'), array($seed, 'Accounts'));

        $lvfMock->expects($this->any())
            ->method('setup')
            ->with($this->anything(),
            '',
            $params,
            $this->anything(),
            $this->anything(),
            $this->anything(),
            $this->anything(),
            $this->anything(),
            $this->anything(),
            $this->anything());

        $viewLast = new ImportViewLastWrap();
        $viewLast->init($seed);
        $viewLast->lvf = $lvfMock;

        $viewLast->publicGetListViewResults();
    }

}

class ImportViewLastWrap extends ImportViewLast {
    public function publicGetListViewResults() {
        return $this->getListViewResults();
    }
}
