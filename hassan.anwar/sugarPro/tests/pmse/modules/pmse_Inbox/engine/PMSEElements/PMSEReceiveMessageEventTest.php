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
class PMSEReceiveMessageEventTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var PMSEElement
     */
    protected $receiveMessageEvent;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    public function testRunWithoutAction()
    {
        $this->receiveMessageEvent = $this->getMockBuilder('PMSEReceiveMessageEvent')
            ->disableOriginalConstructor()
            ->setMethods(array('prepareResponse'))
            ->getMock();
        
        $this->receiveMessageEvent->expects($this->exactly(2))
            ->method('prepareResponse');
        
        $flowData = array();
        
        $bean = new stdClass();
        
        $this->receiveMessageEvent->run($flowData, $bean, '');
    }
    
    public function testRunWithInvalidAction()
    {
        $this->receiveMessageEvent = $this->getMockBuilder('PMSEReceiveMessageEvent')
            ->disableOriginalConstructor()
            ->setMethods(array('prepareResponse', 'checkIfUsesAnEventBasedGateway', 'checkIfExistEventBased'))
            ->getMock();
        
        $caseHandlerMock = $this->getMockBuilder('PMSECaseFlowHandler')
            ->disableOriginalConstructor()
            ->setMethods(array('retrieveBean'))
            ->getMock();
        
        $this->receiveMessageEvent->expects($this->exactly(1))
            ->method('prepareResponse');
        
        $flowData = array(
            "cas_id" => 1,
            "cas_index" => 2,
            "cas_previous" => 2,
            "cas_sugar_module" => 'Opportunities',
            "bpmn_id" => "9283jd9238j3d",
            "rel_process_module" => 'Leads',
            "rel_element_module" => 'Leads',
            "cas_sugar_object_id" => "893u2d89qj2398d",
            "rel_element_relationship" => "Some related module"
        );
        
        $bean = $this->getMockBuilder('SugarBean')
            ->setMethods(array('load_relationships'))
            ->getMock();
        $bean->parent_type = 'Leads';
        $bean->parent_id = '893u2d89qj2398d';
        
        $definitionMock = new stdClass();
        $definitionMock->evn_criteria = "Some Criteria";        
        
        $this->receiveMessageEvent->setCaseFlowHandler($caseHandlerMock);
        $this->receiveMessageEvent->run($flowData, $bean, 'WAKE_UP');
    }
    
    public function testRunWithValidAction()
    {
        $this->receiveMessageEvent = $this->getMockBuilder('PMSEReceiveMessageEvent')
            ->disableOriginalConstructor()
            ->setMethods(array('prepareResponse', 'checkIfUsesAnEventBasedGateway', 'checkIfExistEventBased'))
            ->getMock();
        
        $caseHandlerMock = $this->getMockBuilder('PMSECaseFlowHandler')
            ->disableOriginalConstructor()
            ->setMethods(array('retrieveBean'))
            ->getMock();
        
        $this->receiveMessageEvent->expects($this->exactly(2))
            ->method('prepareResponse');
        
        $this->receiveMessageEvent->expects($this->once())
            ->method('checkIfUsesAnEventBasedGateway');

        $this->receiveMessageEvent->expects($this->once())
            ->method('checkIfExistEventBased');
        
        $flowData = array(
            "cas_id" => 1,
            "cas_index" => 2,
            "cas_previous" => 2,
            "bpmn_id" => "9283jd9238j3d",
            "cas_sugar_module" => 'Opportunities',
            "rel_process_module" => 'Leads',
            "rel_element_module" => 'Leads',
            "cas_sugar_object_id" => "893u2d89qj2398d",
            "rel_element_relationship" => "Some related module"
        );
        
        $bean = $this->getMockBuilder('SugarBean')
            ->setMethods(array('load_relationships'))
            ->getMock();
        
        $bean->parent_type = 'Leads';
        $bean->parent_id = '893u2d89qj2398d';
        
        $definitionMock = new stdClass();
        $definitionMock->evn_criteria = "Some Criteria";
        $this->receiveMessageEvent->setCaseFlowHandler($caseHandlerMock);
        $this->receiveMessageEvent->run($flowData, $bean, 'EVALUATE_RELATED_MODULE');
    }
}
