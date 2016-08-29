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
require_once("include/Expressions/Expression/AbstractExpression.php");
require_once("include/Expressions/Expression/Parser/Parser.php");

/**
 * @outputBuffering enabled
 */
class validDateTest extends Sugar_PHPUnit_Framework_TestCase
{

	public static function setUpBeforeClass()
	{
        parent::setUp();
        SugarTestHelper::setUp("current_user");
        $GLOBALS['current_user']->setPreference("datef", "n/d/Y");
	}

	public static function tearDownAfterClass()
	{
	    parent::tearDown();
	}

    /**
     * @group bug39037
     */
	public function testValidDate()
	{
        try {
            $expr = 'isValidDate("5/15/2010")';
            $result = Parser::evaluate($expr)->evaluate();
            $this->assertEquals($result, AbstractExpression::$TRUE);
        } catch (Exception $e){
        	$this->assertTrue(false, "Parser threw exception: {$e->getMessage()}");
        }
    }

    public function testInvalidString()
	{
        try {
            $expr = 'isValidDate("not a date")';
            $result = Parser::evaluate($expr)->evaluate();
            $this->assertEquals($result, AbstractExpression::$FALSE);
        } catch (Exception $e){
        	$this->assertTrue(false, "Parser threw exception: {$e->getMessage()}");
        }
    }

    public function testInvalidDateFormat()
	{
        try {
            $expr = 'isValidDate("5-15-2010")';
            $result = Parser::evaluate($expr)->evaluate();
            $this->assertEquals($result, AbstractExpression::$FALSE);
        } catch (Exception $e){
        	$this->assertTrue(false, "Parser threw exception: {$e->getMessage()}");
        }
    }

    public function testInvalidMonth()
	{
        try {
            $expr = 'isValidDate("25/15/2010")';
            $result = Parser::evaluate($expr)->evaluate();
            $this->assertEquals($result, AbstractExpression::$FALSE);
        } catch (Exception $e){
        	$this->assertTrue(false, "Parser threw exception: {$e->getMessage()}");
        }
    }

    public function testInvalidDay()
	{
        try {
            $expr = 'isValidDate("5/32/2010")';
            $result = Parser::evaluate($expr)->evaluate();
            $this->assertEquals($result, AbstractExpression::$FALSE);
        } catch (Exception $e){
        	$this->assertTrue(false, "Parser threw exception: {$e->getMessage()}");
        }
    }

    public function testInvalidYear()
	{
        try {
            $expr = 'isValidDate("5/15/Q")';
            $result = Parser::evaluate($expr)->evaluate();
            $this->assertEquals($result, AbstractExpression::$FALSE);
        } catch (Exception $e){
        	$this->assertTrue(false, "Parser threw exception: {$e->getMessage()}");
        }
    }

    public function testEmptyString()
    {
        $expr = 'isValidDate("")';
        $result = Parser::evaluate($expr)->evaluate();
        $this->assertEquals($result, AbstractExpression::$FALSE);
    }
}
