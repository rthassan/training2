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

require_once('modules/ModuleBuilder/parsers/parser.label.php');

class Bug45645Test extends Sugar_PHPUnit_Framework_TestCase
{
    protected $custLangFile;
    protected $lang = 'en_us';
    protected $testModule = 'Opportunities';
    protected $testLabel = 'LBL_ACCOUNT_NAME';
    protected $oldLabel;
    protected $newLabel;
    
    protected function setup()
    {
        $this->custLangFile = "custom/modules/{$this->testModule}/Ext/Language/en_us.lang.ext.php";
    }
    
    protected function tearDown()
    {
        // Set things back to what they were
        $params = array($this->testLabel => $this->oldLabel);
        ParserLabel::addLabels($this->lang, $params, $this->testModule);
    }

    public function testLabelSaving()
    {
        $mod_strings = return_module_language($this->lang, $this->testModule);
        $this->oldLabel = $mod_strings[$this->testLabel];
        $this->newLabel = 'test ' . $this->oldLabel;

        // save the new label to the language file
        $params = array($this->testLabel => $this->newLabel);
        ParserLabel::addLabels($this->lang, $params, $this->testModule);

        // read the language file to get the new value
        $this->assertFileExists($this->custLangFile, "Label extension file does not exist");
        include $this->custLangFile;
        $this->assertEquals($this->newLabel, $mod_strings[$this->testLabel], 'Label not changed.');
    }
}


?>
