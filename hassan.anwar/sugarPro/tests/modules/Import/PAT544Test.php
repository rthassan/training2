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


require_once('modules/Import/Importer.php');
require_once('modules/Import/sources/ImportFile.php');

/**
 * Export as iso-8859-1 and reimport breaks special characters
 * 
 * @author bsitnikovski@sugarcrm.com
 * @ticket PAT-544
 */
class BugPAT544Test extends Sugar_PHPUnit_Framework_TestCase
{

    private $account1;
    private $account2;
    private $file;

    public function setUp()
    {
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();

        $this->account1 = SugarTestAccountUtilities::createAccount("", array("name" => "AÜLLER"));
        $this->account2 = SugarTestAccountUtilities::createAccount("", array("name" => "ESPAÑA"));

        $this->file = "\"{$this->account1->id}\",\"A�LLER\"\n\"{$this->account2->id}\",\"ESPA�A\"";
    }

    public function tearDown()
    {
        SugarTestAccountUtilities::removeAllCreatedAccounts();
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
    }

    public function testParentsAreRelatedDuringImport()
    {
        $file = 'upload://testPAT544.csv';
        $ret = file_put_contents($file, $this->file);
        $this->assertGreaterThan(0, $ret, 'Failed to write to '.$file.' for content '.var_export($this->file, true));

        $importSource = new ImportFile($file, ',', '"');

        $bean = BeanFactory::getBean('Accounts');

        $_REQUEST['columncount'] = 2;
        $_REQUEST['colnum_0'] = 'id';
        $_REQUEST['colnum_1'] = 'name';
        $_REQUEST['import_module'] = 'Accounts';
        $_REQUEST['importlocale_charset'] = 'ISO-8859-1';
        $_REQUEST['importlocale_timezone'] = 'GMT';
        $_REQUEST['importlocale_default_currency_significant_digits'] = '2';
        $_REQUEST['importlocale_currency'] = '-99';
        $_REQUEST['importlocale_dec_sep'] = '.';
        $_REQUEST['importlocale_currency'] = '-99';
        $_REQUEST['importlocale_default_locale_name_format'] = 's f l';
        $_REQUEST['importlocale_num_grp_sep'] = ',';
        $_REQUEST['importlocale_dateformat'] = 'm/d/y';
        $_REQUEST['importlocale_timeformat'] = 'h:i:s';
        $_REQUEST['import_type'] = 'update';

        $importer = new Importer($importSource, $bean);
        $importer->import();

        $tmpAcc = BeanFactory::getBean('Accounts', $this->account1->id);
        $this->assertEquals($tmpAcc->name, "AÜLLER");

        $tmpAcc = BeanFactory::getBean('Accounts', $this->account2->id);
        $this->assertEquals($tmpAcc->name, "ESPAÑA");
    }
}
