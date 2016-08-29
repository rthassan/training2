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
class PMSEUserRoleParserTest extends PHPUnit_Framework_TestCase
{
    protected $dataParser;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
    }

    protected function setUp()
    {
        parent::setUp();
        $this->dataParser = $this->getMockBuilder('PMSEUserRoleParser')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();
    }

    public function testParseCriteriaTokenCurrentUserIsAdmin()
    {
        $resultObject = new stdClass();
        $resultObject->num_rows = 1;

        $evaluatedBeanMock = $this->getMockBuilder('leadMock')
            ->setMockClassName('leads')
            ->getMock();

        $evaluatedBeanMock->assigned_user_id = "1";

        $userBeanMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();
        
        $userBeanMock->is_admin = 1;

        $currentUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();

        $currentUserMock->is_admin = 1;

        $criteriaToken = json_decode('{
            "expModule": null,
            "expField": "current_user",
            "expOperator": "equals",
            "expValue": null,
            "expType": "USER_ADMIN",
            "expLabel": "Current user is admin"
        }');
        
        $expectedCriteriaToken = json_decode('{
            "expModule": null,
            "expField": "current_user",
            "expOperator": "equals",
            "expValue": "is_admin",
            "expType": "USER_ADMIN",
            "expLabel": "Current user is admin",
            "expToken": "{::future::Users::id::}",
            "currentValue": "is_admin"
        }');

        $this->dataParser->setCurrentUser($currentUserMock);
        $this->dataParser->setUserBean($userBeanMock);
        $this->dataParser->setEvaluatedBean($evaluatedBeanMock);
        $resultCriteriaObject = $this->dataParser->parseCriteriaToken($criteriaToken);
        $this->assertEquals($expectedCriteriaToken, $resultCriteriaObject);
    }
    
    public function testParseCriteriaTokenOwnerIsAdmin()
    {
        $resultObject = new stdClass();
        $resultObject->num_rows = 1;

        $evaluatedBeanMock = $this->getMockBuilder('leadMock')
            ->setMockClassName('leads')
            ->getMock();
        
        $evaluatedBeanMock->assigned_user_id = "1";

//        $userBeanMock = $this->getMock('User');
//
//        $userBeanMock->expects($this->exactly(1))
//            ->method('retrieve')
//            ->with($this->isType('string'));
////            ->will($this->returnValue($supervisorUserMock));
//
//        $userBeanMock->is_admin = 0;
        
        $userBeanMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieve'))
                ->getMock();
        $userBeanMock->expects($this->exactly(1))
            ->method('retrieve')
            ->with($this->isType('string'));
        $userBeanMock->is_admin = 1;
        
//        $currentUserMock = $this->getMock('User');
//        $currentUserMock->is_admin = 1;

        $criteriaToken = json_decode('{
            "expModule": null,
            "expField": "owner",
            "expOperator": "equals",
            "expValue": null,
            "expType": "USER_ADMIN",
            "expLabel": "Record owner is admin"
        }');
        
        $expectedCriteriaToken = json_decode('{
            "expModule": null,
            "expField": "owner",
            "expOperator": "equals",
            "expValue": "is_admin",
            "expType": "USER_ADMIN",
            "expLabel": "Record owner is admin",
            "expToken": "{::future::Users::id::}",
            "currentValue": "is_admin"
        }');

//        $this->dataParser->setCurrentUser($currentUserMock);
        $this->dataParser->setUserBean($userBeanMock);
        $this->dataParser->setEvaluatedBean($evaluatedBeanMock);
        $resultCriteriaObject = $this->dataParser->parseCriteriaToken($criteriaToken);
        $this->assertEquals($expectedCriteriaToken, $resultCriteriaObject);
    }
    
    public function testParseCriteriaTokenSupervisorIsAdmin()
    {
        $resultObject = new stdClass();
        $resultObject->num_rows = 1;

        $evaluatedBeanMock = $this->getMockBuilder('leadMock')
            ->disableOriginalConstructor()
            ->setMockClassName('leads')
            ->getMock();

        $evaluatedBeanMock->assigned_user_id = "1";

        $supervisorUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();
        $supervisorUserMock->is_admin=1;
        
        $userBeanMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieve'))
                ->getMock();

        $userBeanMock->expects($this->exactly(1))
            ->method('retrieve')
            ->with($this->isType('string'))
            ->will($this->returnValue($supervisorUserMock));

        $userBeanMock->is_admin = 1;

        $currentUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();
        
        $currentUserMock->reports_to_id = "1";
        $currentUserMock->is_admin = 1;

        $criteriaToken = json_decode('{
            "expModule": null,
            "expField": "supervisor",
            "expOperator": "equals",
            "expValue": null,
            "expType": "USER_ADMIN",
            "expLabel": "Supervisor is admin"
        }');
        
        $expectedCriteriaToken = json_decode('{
            "expModule": null,
            "expField": "supervisor",
            "expOperator": "equals",
            "expValue": "is_admin",
            "expType": "USER_ADMIN",
            "expLabel": "Supervisor is admin",
            "expToken": "{::future::Users::id::}",
            "currentValue": "is_admin"
        }');

        $this->dataParser->setCurrentUser($currentUserMock);
        $this->dataParser->setUserBean($userBeanMock);
        $this->dataParser->setEvaluatedBean($evaluatedBeanMock);
        $resultCriteriaObject = $this->dataParser->parseCriteriaToken($criteriaToken);
        $this->assertEquals($expectedCriteriaToken, $resultCriteriaObject);
    }
    
    public function testParseCriteriaTokenSupervisorNull()
    {
        $resultObject = new stdClass();
        $resultObject->num_rows = 1;

        $evaluatedBeanMock = $this->getMockBuilder('leadMock')
            ->setMockClassName('leads')
            ->getMock();

        $evaluatedBeanMock->assigned_user_id = "1";

        $supervisorUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();

        $supervisorUserMock->is_admin=1;
        
        $userBeanMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieve'))
                ->getMock();

        $userBeanMock->expects($this->exactly(1))
            ->method('retrieve')
            ->with($this->isType('string'))
            ->will($this->returnValue(null));

        $userBeanMock->is_admin = 1;

        $currentUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();

        $currentUserMock->reports_to_id = "1";
        $currentUserMock->is_admin = 1;

        $criteriaToken = json_decode('{
            "expModule": null,
            "expField": "supervisor",
            "expOperator": "equals",
            "expValue": null,
            "expType": "USER_ADMIN",
            "expLabel": "Supervisor is admin"
        }');
        $this->dataParser->setCurrentUser($currentUserMock);
        $this->dataParser->setUserBean($userBeanMock);
        $this->dataParser->setEvaluatedBean($evaluatedBeanMock);
        $resultCriteriaObject = $this->dataParser->parseCriteriaToken($criteriaToken);
        $this->assertEquals('', $resultCriteriaObject->currentValue);
    }
    
    public function testParseCriteriaTokenCurrentUserHasRoleAdmin()
    {
        $resultObject = new stdClass();
        $resultObject->num_rows = 1;

        $evaluatedBeanMock = $this->getMockBuilder('leadMock')
            ->setMockClassName('leads')
            ->getMock();

        $evaluatedBeanMock->assigned_user_id = "1";

        $supervisorUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();

        $supervisorUserMock->is_admin=1;
        
        $userBeanMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();

        $userBeanMock->is_admin = 1;

        $currentUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();

        $currentUserMock->reports_to_id = "1";
        $currentUserMock->is_admin = 1;

        $dbHandlerMock = $this->getMockBuilder('db')
            ->setMethods(array('query'))
            ->getMock();

        $criteriaToken = json_decode('{
            "expModule": null,
            "expField": "current_user",
            "expOperator": "equals",
            "expValue": "is_admin",
            "expType": "USER_ROLE",
            "expLabel": "Supervisor is admin"
        }');
        
        $expectedCriteriaToken = json_decode('{
            "expModule": null,
            "expField": "current_user",
            "expOperator": "equals",
            "expValue": "is_admin",
            "expType": "USER_ROLE",
            "expLabel": "Supervisor is admin",
            "expToken": "{::future::Users::id::}",
            "currentValue": "is_admin"
        }');

        $this->dataParser->setCurrentUser($currentUserMock);
        $this->dataParser->setUserBean($userBeanMock);
        $this->dataParser->setDbHandler($dbHandlerMock);
        $this->dataParser->setEvaluatedBean($evaluatedBeanMock);
        $resultCriteriaObject = $this->dataParser->parseCriteriaToken($criteriaToken);
        $this->assertEquals($expectedCriteriaToken, $resultCriteriaObject);
    }
    
    public function testParseCriteriaTokenCurrentUserHasRole()
    {
        $resultObject = new stdClass();
        $resultObject->num_rows = 1;

        $evaluatedBeanMock = $this->getMockBuilder('leadMock')
            ->setMockClassName('leads')
            ->getMock();

        $evaluatedBeanMock->assigned_user_id = "1";

        $supervisorUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();

        $supervisorUserMock->is_admin=1;
        
        $userBeanMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();

        $userBeanMock->is_admin = 1;

        $currentUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();

        $currentUserMock->id = "1";
        $currentUserMock->reports_to_id = "1";
        $currentUserMock->is_admin = 1;

        $dbHandlerMock = $this->getMockBuilder('db')
            ->setMethods(array('query'))
            ->getMock();
        $dbHandlerMock->expects($this->exactly(1))
            ->method('query')
            ->with($this->isType('string'))
            ->will($this->returnValue($resultObject));
        $dbHandlerMock->resultObject = $resultObject;

        $criteriaToken = json_decode('{
            "expModule": null,
            "expField": "current_user",
            "expOperator": "equals",
            "expValue": "1",
            "expType": "USER_ROLE",
            "expLabel": "Supervisor is admin"
        }');
        
        $expectedCriteriaToken = json_decode('{
            "expModule": null,
            "expField": "current_user",
            "expOperator": "equals",
            "expValue": "1",
            "expType": "USER_ROLE",
            "expLabel": "Supervisor is admin",
            "expToken": "{::future::Users::id::}",
            "currentValue": "1"
        }');

        $this->dataParser->setCurrentUser($currentUserMock);
        $this->dataParser->setUserBean($userBeanMock);
        $this->dataParser->setDbHandler($dbHandlerMock);
        $this->dataParser->setEvaluatedBean($evaluatedBeanMock);
        $resultCriteriaObject = $this->dataParser->parseCriteriaToken($criteriaToken);
        $this->assertEquals($expectedCriteriaToken, $resultCriteriaObject);
    }
    
    public function testParseCriteriaTokenOwnerHasRole()
    {
        $resultObject = new stdClass();
        $resultObject->num_rows = 1;

        $evaluatedBeanMock = $this->getMockBuilder('leadMock')
            ->setMockClassName('leads')
            ->getMock();

        $evaluatedBeanMock->assigned_user_id = "1";

        $supervisorUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieve'))
                ->getMock();
        
        $supervisorUserMock->is_admin=1;
        
        $userBeanMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieve'))
                ->getMock();

        $userBeanMock->is_admin = 1;
        $userBeanMock->id = 1;

        $currentUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieve'))
                ->getMock();

        $currentUserMock->reports_to_id = "1";
        $currentUserMock->is_admin = 1;

        $dbHandlerMock = $this->getMockBuilder('db')
            ->setMethods(array('query'))
            ->getMock();
        $dbHandlerMock->expects($this->exactly(1))
            ->method('query')
            ->with($this->isType('string'))
            ->will($this->returnValue($resultObject));
        $dbHandlerMock->resultObject = $resultObject;

        $criteriaToken = json_decode('{
            "expModule": null,
            "expField": "owner",
            "expOperator": "equals",
            "expValue": "1",
            "expType": "USER_ROLE",
            "expLabel": "Supervisor is admin"
        }');
        
        $expectedCriteriaToken = json_decode('{
            "expModule": null,
            "expField": "owner",
            "expOperator": "equals",
            "expValue": "1",
            "expType": "USER_ROLE",
            "expLabel": "Supervisor is admin",
            "expToken": "{::future::Users::id::}",
            "currentValue": "1"
        }');

        $this->dataParser->setCurrentUser($currentUserMock);
        $this->dataParser->setUserBean($userBeanMock);
        $this->dataParser->setDbHandler($dbHandlerMock);
        $this->dataParser->setEvaluatedBean($evaluatedBeanMock);
        $resultCriteriaObject = $this->dataParser->parseCriteriaToken($criteriaToken);
        $this->assertEquals($expectedCriteriaToken, $resultCriteriaObject);
    }
    
    public function testParseCriteriaTokenOwnerHasRoleIsAdmin()
    {
        $resultObject = new stdClass();
        $resultObject->num_rows = 1;

        $evaluatedBeanMock = $this->getMockBuilder('leadMock')
            ->setMockClassName('leads')
            ->getMock();

        $evaluatedBeanMock->assigned_user_id = "1";

        $supervisorUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieve'))
                ->getMock();

        $supervisorUserMock->is_admin=1;
        
        $userBeanMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieve'))
                ->getMock();

        $userBeanMock->is_admin = 1;
        $userBeanMock->id = 1;

        $currentUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieve'))
                ->getMock();

        $currentUserMock->reports_to_id = "1";
        $currentUserMock->is_admin = 1;

        $criteriaToken = json_decode('{
            "expModule": null,
            "expField": "owner",
            "expOperator": "equals",
            "expValue": "is_admin",
            "expType": "USER_ROLE",
            "expLabel": "Supervisor is admin"
        }');
        $this->dataParser->setCurrentUser($currentUserMock);
        $this->dataParser->setUserBean($userBeanMock);
        $this->dataParser->setEvaluatedBean($evaluatedBeanMock);
        $resultCriteriaObject = $this->dataParser->parseCriteriaToken($criteriaToken);
        $this->assertEquals('is_admin', $resultCriteriaObject->currentValue);
    }
    
    public function testParseCriteriaTokenOwnerHasRoleAdmin()
    {
        $resultObject = new stdClass();
        $resultObject->num_rows = 1;

        $evaluatedBeanMock = $this->getMockBuilder('leadMock')
            ->setMockClassName('leads')
            ->getMock();

        $evaluatedBeanMock->assigned_user_id = "1";

        $supervisorUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieve'))
                ->getMock();

        $supervisorUserMock->is_admin=1;
        
        $userBeanMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieve'))
                ->getMock();
        
        $userBeanMock->is_admin = 1;

        $currentUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieve'))
                ->getMock();
        
        $currentUserMock->reports_to_id = "1";
        $currentUserMock->is_admin = 1;
        $currentUserMock->id = "1";

        $dbHandlerMock = $this->getMockBuilder('db')
            ->setMethods(array('query'))
            ->getMock();

        $criteriaToken = json_decode('{
            "expModule": null,
            "expField": "current_user",
            "expOperator": "equals",
            "expValue": "is_admin",
            "expType": "USER_ROLE",
            "expLabel": "Supervisor is admin"
        }');
        
        $expectedCriteriaToken = json_decode('{
            "expModule": null,
            "expField": "current_user",
            "expOperator": "equals",
            "expValue": "is_admin",
            "expType": "USER_ROLE",
            "expLabel": "Supervisor is admin",
            "expToken": "{::future::Users::id::}",
            "currentValue": "is_admin"
        }');

        $this->dataParser->setCurrentUser($currentUserMock);
        $this->dataParser->setUserBean($userBeanMock);
        $this->dataParser->setDbHandler($dbHandlerMock);
        $this->dataParser->setEvaluatedBean($evaluatedBeanMock);
        $resultCriteriaObject = $this->dataParser->parseCriteriaToken($criteriaToken);
        $this->assertEquals($expectedCriteriaToken, $resultCriteriaObject);
    }
    
    public function testParseCriteriaTokenSupervisorHasRole()
    {
        $resultObject = new stdClass();
        $resultObject->num_rows = 1;

        $evaluatedBeanMock = $this->getMockBuilder('leadMock')
            ->setMockClassName('leads')
            ->getMock();

        $evaluatedBeanMock->assigned_user_id = "1";

        $supervisorUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieve'))
                ->getMock();
        $supervisorUserMock->is_admin=1;
        $supervisorUserMock->id="1";
        
        $userBeanMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieve'))
                ->getMock();

        $userBeanMock->expects($this->exactly(1))
            ->method('retrieve')
            ->with($this->isType('string'))
            ->will($this->returnValue($supervisorUserMock));
        
        $userBeanMock->is_admin = 1;

        $currentUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieve'))
                ->getMock();
        $currentUserMock->reports_to_id = "1";
        $currentUserMock->is_admin = 1;

        $dbHandlerMock = $this->getMockBuilder('db')
            ->setMethods(array('query'))
            ->getMock();
        
        $dbHandlerMock->expects($this->exactly(1))
            ->method('query')
            ->with($this->isType('string'))
            ->will($this->returnValue($resultObject));
        $dbHandlerMock->resultObject = $resultObject;

        $criteriaToken = json_decode('{
            "expModule": null,
            "expField": "supervisor",
            "expOperator": "equals",
            "expValue": "1",
            "expType": "USER_ROLE",
            "expLabel": "Supervisor is admin"
        }');
        
        $expectedCriteriaToken = json_decode('{
            "expModule": null,
            "expField": "supervisor",
            "expOperator": "equals",
            "expValue": "1",
            "expType": "USER_ROLE",
            "expLabel": "Supervisor is admin",
            "expToken": "{::future::Users::id::}",
            "currentValue": "1"
        }');

        $this->dataParser->setCurrentUser($currentUserMock);
        $this->dataParser->setUserBean($userBeanMock);
        $this->dataParser->setDbHandler($dbHandlerMock);
        $this->dataParser->setEvaluatedBean($evaluatedBeanMock);
        $resultCriteriaObject = $this->dataParser->parseCriteriaToken($criteriaToken);
        $this->assertEquals($expectedCriteriaToken, $resultCriteriaObject);
    }
    
    public function testParseCriteriaTokenSupervisor()
    {
        $resultObject = new stdClass();
        $resultObject->num_rows = 1;

        $evaluatedBeanMock = $this->getMockBuilder('leadMock')
            ->setMockClassName('leads')
            ->getMock();

        $evaluatedBeanMock->assigned_user_id = "1";

        $supervisorUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieve'))
                ->getMock();
        $supervisorUserMock->is_admin = 1;
        $supervisorUserMock->id="1";
        
        $userBeanMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieve'))
                ->getMock();

        $userBeanMock->expects($this->exactly(1))
            ->method('retrieve')
            ->with($this->isType('string'))
            ->will($this->returnValue($supervisorUserMock));
        
        $userBeanMock->is_admin = 1;

        $currentUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieve'))
                ->getMock();
        $currentUserMock->reports_to_id = "1";
        $currentUserMock->is_admin = 1;

        $criteriaToken = json_decode('{
            "expModule": null,
            "expField": "supervisor",
            "expOperator": "equals",
            "expValue": "is_admin",
            "expType": "USER_ROLE",
            "expLabel": "Supervisor is admin"
        }');
        $this->dataParser->setCurrentUser($currentUserMock);
        $this->dataParser->setUserBean($userBeanMock);
        $this->dataParser->setEvaluatedBean($evaluatedBeanMock);
        $resultCriteriaObject = $this->dataParser->parseCriteriaToken($criteriaToken);
        $this->assertEquals('is_admin', $resultCriteriaObject->currentValue);
    }
    
    public function testParseCriteriaTokenCurrentUserHasIdentity()
    {
        $resultObject = new stdClass();
        $resultObject->num_rows = 1;

        $evaluatedBeanMock = $this->getMockBuilder('leadMock')
            ->setMockClassName('leads')
            ->getMock();

        $evaluatedBeanMock->assigned_user_id = "1";

        $supervisorUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieve'))
                ->getMock();
        $supervisorUserMock->is_admin=1;
        $supervisorUserMock->id="1";
        
        $userBeanMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieve'))
                ->getMock();
        $userBeanMock->is_admin = 1;

        $currentUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieve'))
                ->getMock();
        $currentUserMock->reports_to_id = "1";
        $currentUserMock->is_admin = 1;
        $currentUserMock->id = 1;

        $criteriaToken = json_decode('{
            "expModule": null,
            "expField": "current_user",
            "expOperator": "equals",
            "expValue": "1",
            "expType": "USER_IDENTITY",
            "expLabel": "Supervisor is admin"
        }');
        
        $expectedCriteriaToken = json_decode('{
            "expModule": null,
            "expField": "current_user",
            "expOperator": "equals",
            "expValue": "1",
            "expType": "USER_IDENTITY",
            "expLabel": "Supervisor is admin",
            "expToken": "{::future::Users::id::}",
            "currentValue": "1"
        }');

        $this->dataParser->setCurrentUser($currentUserMock);
        $this->dataParser->setUserBean($userBeanMock);
        $this->dataParser->setEvaluatedBean($evaluatedBeanMock);
        $resultCriteriaObject = $this->dataParser->parseCriteriaToken($criteriaToken);
        $this->assertEquals($expectedCriteriaToken, $resultCriteriaObject);
    }
    
    public function testParseCriteriaTokenOwnerHasIdentity()
    {
        $resultObject = new stdClass();
        $resultObject->num_rows = 1;

        $evaluatedBeanMock = $this->getMockBuilder('leadMock')
            ->setMockClassName('leads')
            ->getMock();

        $evaluatedBeanMock->assigned_user_id = "1";

        $supervisorUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();
        $supervisorUserMock->is_admin=1;
        $supervisorUserMock->id="1";
        
        $userBeanMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();
        $userBeanMock->is_admin = 1;
        $userBeanMock->id = "1";

        $currentUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();
        $currentUserMock->reports_to_id = "1";
        $currentUserMock->is_admin = 1;

        $criteriaToken = json_decode('{
            "expModule": null,
            "expField": "owner",
            "expOperator": "equals",
            "expValue": "1",
            "expType": "USER_IDENTITY",
            "expLabel": "Supervisor is admin"
        }');
        
        $expectedCriteriaToken = json_decode('{
            "expModule": null,
            "expField": "owner",
            "expOperator": "equals",
            "expValue": "1",
            "expType": "USER_IDENTITY",
            "expLabel": "Supervisor is admin",
            "expToken": "{::future::Users::id::}",
            "currentValue": "1"
        }');

        $this->dataParser->setCurrentUser($currentUserMock);
        $this->dataParser->setUserBean($userBeanMock);
        $this->dataParser->setEvaluatedBean($evaluatedBeanMock);
        $resultCriteriaObject = $this->dataParser->parseCriteriaToken($criteriaToken);
        $this->assertEquals($expectedCriteriaToken, $resultCriteriaObject);
    }
    
    public function testParseCriteriaTokenSupervisorHasIdentity()
    {
        $resultObject = new stdClass();
        $resultObject->num_rows = 1;

        $evaluatedBeanMock = $this->getMockBuilder('leadMock')
            ->setMockClassName('leads')
            ->getMock();

        $evaluatedBeanMock->assigned_user_id = "1";

        $supervisorUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();
        $supervisorUserMock->is_admin=1;
        $supervisorUserMock->id="1";
        
        $userBeanMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();
        $userBeanMock->is_admin = 1;

        $currentUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();
        $currentUserMock->reports_to_id = "1";
        $currentUserMock->is_admin = 1;
        $currentUserMock->id = "1";

        $criteriaToken = json_decode('{
            "expModule": null,
            "expField": "supervisor",
            "expOperator": "equals",
            "expValue": "1",
            "expType": "USER_IDENTITY",
            "expLabel": "Supervisor = \"1\""
        }');
        
        $expectedCriteriaToken = json_decode('{
            "expModule": null,
            "expField": "supervisor",
            "expOperator": "equals",
            "expValue": "1",
            "expType": "USER_IDENTITY",
            "expLabel": "Supervisor = \"1\"",
            "expToken": "{::future::Users::id::}",
            "currentValue": "1"
        }');

        $this->dataParser->setCurrentUser($currentUserMock);
        $this->dataParser->setUserBean($userBeanMock);
        $this->dataParser->setEvaluatedBean($evaluatedBeanMock);
        $resultCriteriaObject = $this->dataParser->parseCriteriaToken($criteriaToken);
        $this->assertEquals($expectedCriteriaToken, $resultCriteriaObject);
    }
    
    public function testParseCriteriaTokenOwnerHasIdentityNull()
    {
        $resultObject = new stdClass();
        $resultObject->num_rows = 1;

        $evaluatedBeanMock = $this->getMockBuilder('leadMock')
            ->setMockClassName('leads')
            ->getMock();

        $evaluatedBeanMock->assigned_user_id = null;

        $supervisorUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();
        $supervisorUserMock->is_admin=1;
        $supervisorUserMock->id="1";
        
        $userBeanMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();
        $userBeanMock->is_admin = 1;
        $userBeanMock->id = "1";

        $currentUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();
        $currentUserMock->reports_to_id = '1';
        $currentUserMock->is_admin = 1;

        $criteriaToken = json_decode('{
            "expModule": null,
            "expField": "owner",
            "expOperator": "equals",
            "expValue": "1",
            "expType": "USER_IDENTITY",
            "expLabel": "Supervisor is admin"
        }');
        $this->dataParser->setCurrentUser($currentUserMock);
        $this->dataParser->setUserBean($userBeanMock);
        $this->dataParser->setEvaluatedBean($evaluatedBeanMock);
        $resultCriteriaObject = $this->dataParser->parseCriteriaToken($criteriaToken);
        $this->assertEquals('false', $resultCriteriaObject->currentValue);
    }
    
    public function testParseCriteriaTokenSupervisorHasIdentityNull()
    {
        $resultObject = new stdClass();
        $resultObject->num_rows = 1;

        $evaluatedBeanMock = $this->getMockBuilder('leadMock')
            ->setMockClassName('leads')
            ->getMock();

        $evaluatedBeanMock->assigned_user_id = '1';

        $supervisorUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieve'))
                ->getMock();
        $supervisorUserMock->is_admin=1;
        $supervisorUserMock->id="1";
        
        $userBeanMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieve'))
                ->getMock();
        $userBeanMock->is_admin = 1;

        $currentUserMock = $this->getMockBuilder('User')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieve'))
                ->getMock();
        $currentUserMock->reports_to_id = null;
        $currentUserMock->is_admin = 1;
        $currentUserMock->id = "1";

        $criteriaToken = json_decode('{
            "expModule": null,
            "expField": "supervisor",
            "expOperator": "equals",
            "expValue": "1",
            "expType": "USER_IDENTITY",
            "expLabel": "Supervisor = \"1\""
        }');
        $this->dataParser->setCurrentUser($currentUserMock);
        $this->dataParser->setUserBean($userBeanMock);
        $this->dataParser->setEvaluatedBean($evaluatedBeanMock);
        $resultCriteriaObject = $this->dataParser->parseCriteriaToken($criteriaToken);
        $this->assertEquals('false', $resultCriteriaObject->currentValue);
    }
    
    public function testDecomposeToken()
    {
        $resultCriteriaObject = $this->dataParser->decomposeToken("{::future::Users::id::}");
        $this->assertEquals('future', $resultCriteriaObject[0]);
        $this->assertEquals('Users', $resultCriteriaObject[1]);
        $this->assertEquals('id', $resultCriteriaObject[2]);
    }
}
