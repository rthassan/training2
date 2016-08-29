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
require_once("include/Expressions/Expression/Parser/Parser.php");

class Bug50807Test extends Sugar_PHPUnit_Framework_TestCase
{
    public function testStrToLower()
    {
        $expr = 'strToLower("SAUTÉES")';
        $result = Parser::evaluate($expr)->evaluate();
        $this->assertEquals("sautées", $result);
    }

    public function testStrToUpper()
    {
        $expr = 'strToUpper("sautées")';
        $result = Parser::evaluate($expr)->evaluate();
        $this->assertEquals("SAUTÉES", $result);
    }
}
