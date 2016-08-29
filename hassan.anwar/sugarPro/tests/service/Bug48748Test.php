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

require_once('tests/service/RestTestCase.php');

class Bug48748Test extends RestTestCase
{

    protected $package = 'Accounts';
    protected $packageExists = false;
    protected $aclRole;
    protected $aclField;


    public function setUp()
    {
        parent::setUp();

        //If somehow this package already exists copy it
        if(file_exists('custom/modules/' . $this->package))
        {
           $this->packageExists = true;
           mkdir_recursive('custom/modules/' . $this->package . '_bak');
           copy_recursive('custom/modules/' . $this->package, 'custom/modules/' . $this->package . '_bak');
        }

        //Make the custom package directory and simulate copying the file in
        mkdir_recursive('custom/modules/' . $this->package . '/Ext/WirelessLayoutdefs');
        if( $fh = @fopen('custom/modules/' . $this->package . '/Ext/WirelessLayoutdefs/wireless.subpaneldefs.ext.php', 'w+'))
        {
$string = <<<EOQ
<?php
\$layout_defs["{$this->package}"]["subpanel_setup"]['{$this->package}_accounts'] = array (
  'order' => 100,
  'module' => 'Contacts',
  'subpanel_name' => 'default',
  'title_key' => 'LBL_BUG48784TEST',
  'get_subpanel_data' => 'Bug48748Test',
);

?>
EOQ;
            fputs( $fh, $string);
            fclose( $fh );
            SugarAutoLoader::addToMap('custom/modules/' . $this->package . '/Ext/WirelessLayoutdefs/wireless.subpaneldefs.ext.php', true);
        }


        global $current_user;

        //Create an anonymous user for login purposes/
        SugarTestHelper::setUp("current_user");
        $current_user->status = 'Active';
        $current_user->is_admin = 1;
        $current_user->save();
        $GLOBALS['db']->commit(); // Making sure we commit any changes before continuing

        $_SESSION['avail_modules'][$this->package] = 'write';
    }

    public function tearDown()
    {
        parent::tearDown();
        if($this->packageExists)
        {
            //Copy original contents back in
            copy_recursive('custom/modules/' . $this->package . '_bak', 'custom/modules/' . $this->package);
            rmdir_recursive('custom/modules/' . $this->package . '_bak');
        } else {
            rmdir_recursive('custom/modules/' . $this->package);
            SugarAutoLoader::delFromMap('custom/modules/' . $this->package, true);
        }

        unset($_SESSION['avail_modules'][$this->package]);
        SugarTestHelper::tearDown();
    }

    public function testWirelessModuleLayoutForCustomModule()
    {

        $this->assertTrue(file_exists('custom/modules/' . $this->package . '/Ext/WirelessLayoutdefs/wireless.subpaneldefs.ext.php'));
        //$contents = file_get_contents('custom/modules/' . $this->package . '/Ext/WirelessLayoutdefs/wireless.subpaneldefs.ext.php');
        include('custom/modules/' . $this->package . '/Ext/WirelessLayoutdefs/wireless.subpaneldefs.ext.php');

        global $current_user;
        $result = $this->_login($current_user);
        $session = $result['id'];
        $results = $this->_makeRESTCall('get_module_layout',
        array(
            'session' => $session,
            'module' => array($this->package),
            'type' => array('wireless'),
            'view' => array('subpanel'),
            )
        );

        $this->assertEquals('Bug48748Test', $results[$this->package]['wireless']['subpanel']["{$this->package}_accounts"]['get_subpanel_data'], 'Cannot load custom wireless.subpaneldefs.ext.php file');
    }
}
