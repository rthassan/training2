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
class PMSECriteriaEvaluatorTest extends PHPUnit_Framework_TestCase 
{
    /**
     * Sets up the test data, for example, 
     *     opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        
    }

    /**
     * Removes the initial test configurations for each test, for example:
     *     close a network connection. 
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }
    
    public function testIsCriteriaTokenTRUE()
    {
        $fixture  = '[
                        {
                            "expType": "USER_IDENTITY",
                            "expSubtype": "string",
                            "expLabel": "Current user == Will Westin",
                            "expValue": "seed_will_id",
                            "expOperator": "equals",
                            "expField": "current_user"
                        }
                    ]';

        $expression = json_decode($fixture);
        
        $criteriaEvaluatorMock = $this->getMockBuilder('PMSECriteriaEvaluator')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();
        
        $result = $criteriaEvaluatorMock->isCriteriaToken(array_pop($expression));
        $expected = true;
        $this->assertEquals($expected, $result);
        
    }
    
    public function testIsCriteriaTokenFALSE()
    {
        $fixture = '[
            {
                "expType": "CONSTANT",
                "expSubtype": "number",
                "expLabel": "0",
                "expValue": 0
            }            
        ]';
        
        $expression = json_decode($fixture);
        
        $criteriaEvaluatorMock = $this->getMockBuilder('PMSECriteriaEvaluator')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();

        $result = $criteriaEvaluatorMock->isCriteriaToken(array_pop($expression));
        $expected = false;
        $this->assertEquals($expected, $result);

    }
    
    public function testEvaluateCriteriaTokenTRUE()
    {
        $fixture  = '[
                        {
                            "expType": "CONTROL",
                            "expSubtype": "string",
                            "expLabel": "Task # 4 >= Approved",
                            "expOperator": "major_equals_than",
                            "expValue": "Approved",
                            "currentValue" : "Approved",
                            "expField": "17842861053ee3573bcb7a4046264057"
                        }
                    ]';

        $expression = json_decode($fixture);
        
        $criteriaEvaluatorMock = $this->getMockBuilder('PMSECriteriaEvaluator')
                ->setMethods(NULL)
                ->getMock();
        
        $expectedToken = new stdClass();
        $expectedToken->expValue = true;
        $expectedToken->expLabel = "true";
        $expectedToken->expSubtype = "boolean";
        $expectedToken->expType = "CONSTANT";
        
        $result = $criteriaEvaluatorMock->evaluateCriteriaToken(array_pop($expression));
        
        $this->assertEquals($expectedToken, $result);
        
    }
    
    public function testEvaluateCriteriaTokenFALSE()
    {
        $fixture  = '[
                        {
                            "expType": "USER_IDENTITY",
                            "expSubtype": "string",
                            "expLabel": "Current user == Will Westin",
                            "expValue": "seed_will_id",
                            "currentValue": "seed_jim_id",
                            "expOperator": "equals",
                            "expField": "current_user"
                        }
                    ]';

        $expression = json_decode($fixture);
        
        $criteriaEvaluatorMock = $this->getMockBuilder('PMSECriteriaEvaluator')                
                ->setMethods(NULL)
                ->getMock();
        
        $expectedToken = new stdClass();
        $expectedToken->expValue = false;
        $expectedToken->expLabel = "false";
        $expectedToken->expSubtype = "boolean";
        $expectedToken->expType = "CONSTANT";
        
        $result = $criteriaEvaluatorMock->evaluateCriteriaToken(array_pop($expression));
        
        $this->assertEquals($expectedToken, $result);
        
    }
    public function testEvaluateCriteriaTokenList()
    {
        $fixture  = '[
                        {
                            "expType": "CONSTANT",
                            "expSubtype": "string",
                            "expLabel": "FIRST_ELEMENT",
                            "expValue": "FIRST_ELEMENT"
                        },
                        {
                            "expType": "COMPARISON",
                            "expLabel": "!=",
                            "expValue": "!="
                        },
                        {
                            "expType": "MODULE",
                            "expSubtype": "TextField",
                            "expLabel": "Account Name == 3",
                            "expValue": 3,
                            "currentValue": 3,
                            "expOperator": "equals",
                            "expModule": "lead_direct_reports",
                            "expField": "account_name"
                        },
                        {
                            "expType": "LOGIC",
                            "expLabel": "AND",
                            "expValue": "AND"
                        },
                        {
                            "expType": "CONSTANT",
                            "expSubtype": "string",
                            "expLabel": "SECOND_ELEMENT",
                            "expValue": "SECOND_ELEMENT"
                        },
                        {
                            "expType": "COMPARISON",
                            "expLabel": "!=",
                            "expValue": "!="
                        },
                        {
                            "expType": "CONTROL",
                            "expSubtype": "string",
                            "expLabel": "Task # 4 = Approved",
                            "expOperator": "equals",
                            "expValue": "Approved",
                            "currentValue": "Reject",
                            "expField": "17842861053ee3573bcb7a4046264057"
                        }
                    ]';

        $expression = json_decode($fixture);
        
        $criteriaEvaluatorMock = $this->getMockBuilder('PMSECriteriaEvaluator')                
                ->setMethods(NULL)
                ->getMock();
        
        $expectedList = '[
                            {
                                "expType": "CONSTANT",
                                "expSubtype": "string",
                                "expLabel": "FIRST_ELEMENT",
                                "expValue": "FIRST_ELEMENT"
                            },
                            {
                                "expType": "COMPARISON",
                                "expLabel": "!=",
                                "expValue": "!="
                            },
                            {
                                "expType": "CONSTANT",
                                "expSubtype": "boolean",
                                "expLabel": "true",
                                "expValue": true                                
                            },
                            {
                                "expType": "LOGIC",
                                "expLabel": "AND",
                                "expValue": "AND"
                            },
                            {
                                "expType": "CONSTANT",
                                "expSubtype": "string",
                                "expLabel": "SECOND_ELEMENT",
                                "expValue": "SECOND_ELEMENT"
                            },
                            {
                                "expType": "COMPARISON",
                                "expLabel": "!=",
                                "expValue": "!="
                            },
                            {
                                "expType": "CONSTANT",
                                "expSubtype": "boolean",
                                "expLabel": "false",
                                "expValue": false                                
                            }
                        ]';

        $expectedList = json_decode($expectedList);
        $result = $criteriaEvaluatorMock->evaluateCriteriaTokenList($expression);
        
        $this->assertEquals($expectedList, $result);
        
    }
    
    //put your tests code here
}
