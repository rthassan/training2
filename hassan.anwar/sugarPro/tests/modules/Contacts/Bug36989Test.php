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

require_once('modules/Contacts/Contact.php');
require_once('include/MVC/View/views/view.list.php');

class Bug36989Test extends Sugar_PHPUnit_Framework_TestCase
{
     private $module = "Contacts";
     private $searchFieldsBackup;
     private $customSearchFields;
     private $customSearchdefs;

     public function setUp()
    {
          SugarTestHelper::setUp('files');
          SugarTestHelper::setUp('beanList');
          SugarTestHelper::setUp('beanFiles');
          SugarTestHelper::setUp('current_user');
          SugarTestHelper::setUp('app_strings');

          SugarTestHelper::saveFile('custom/modules/Contacts/metadata/SearchFields.php');
          if(file_exists('custom/modules/Contacts/metadata/SearchFields.php'))
          {
              unlink('custom/modules/Contacts/metadata/SearchFields.php');
          }

          SugarTestHelper::saveFile('modules/Contacts/metadata/SearchFields.php');
          file_put_contents('modules/Contacts/metadata/SearchFields.php', '<?php $searchFields[\'Contacts\'] = array(\'test\' => array());');
          parent::setUp();
     }

    public function tearDown()
    {
        SugarTestHelper::tearDown();
        parent::tearDown();
    }

     function testOverrideSearchFields() {
          $list = new ViewList();
          $list->module = "Contacts";
          $list->seed = new Contact();
          $list->prepareSearchForm();
          $this->assertTrue(isset($list->searchForm->searchFields['test']));
    }
}
