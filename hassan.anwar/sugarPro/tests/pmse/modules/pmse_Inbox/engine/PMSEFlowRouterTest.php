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
class PMSEFlowRouterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var PMSEFlowRouter
     */
    private $flowRouterObject;

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

    /**
     * 
     * @param type $param
     * @covers PMSEFlowRouter::retrieveElement
     */
    public function testRetrieveElement()
    {
        $this->flowRouterObject = $this->getMockBuilder('PMSEFlowRouter')
                ->setMethods(null)
                ->disableOriginalConstructor()
                ->getMock();

        $flowData = array('cas_id' => 1, 'cas_index' => 2);
        $mockCaseFlowHandler = $this->getMockBuilder('PMSECaseFlowHandler')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieveElementByType'))
                ->getMock();

        $testPmseObject = new stdClass();

        $mockCaseFlowHandler->expects($this->exactly(1))
                ->method('retrieveElementByType')
                ->with($flowData)
                ->will($this->returnValue($testPmseObject));

        $this->flowRouterObject->setCaseFlowHandler($mockCaseFlowHandler);
        $this->flowRouterObject->retrieveElement($flowData);
    }

    public function testRouteFlowActionCreate()
    {
        $flowData = array(
            'id' => '837278dh2837e',
            'cas_id' => 2,
            'cas_index' => 3,
            'bpmn_type' => 'bpmnActivity',
            'bpmn_id' => 'aiuj2d8931'
        );
        
        $previousFlowData = array(
            'id' => '2189su9128sda',
            'cas_id' => 2,
            'cas_index' => 2,
            'bpmn_type' => 'bpmnActivity',
            'bpmn_id' => 'nsiojqwd98'
        );

        $executionResult = array(
            'route_action' => 'ROUTE',
            'flow_action' => 'CREATE',
            'flow_filters' => array(),
            'flow_data' => $flowData,
            'flow_id' => $flowData['id']
        );

        $nextElements = array(
            'next_elements' =>
            array(
                array(
                    'cas_id' => 2,
                    'cas_index' => 4
                ),
                array(
                    'cas_id' => 2,
                    'cas_index' => 5
                ),
                array(
                    'cas_id' => 2,
                    'cas_index' => 6
                )
            )
        );

        // We need to override the execute Element since that method is not 
        // evaluated in this test but is called inside the routeFlow method
        $this->flowRouterObject = $this->getMockBuilder('PMSEFlowRouter')
                ->setMethods(array('processElement', 'retrieveFollowingElements'))
                ->disableOriginalConstructor()
                ->getMock();

        // preparing the case flow handler mock
        $mockCaseFlowHandler = $this->getMockBuilder('PMSECaseFlowHandler')
                ->disableOriginalConstructor()
                ->setMethods(array( 'closePreviousFlow', 'prepareFlowData', 'saveFlowData'))
                ->getMock();

        $mockCaseFlowHandler->expects($this->exactly(1))
                ->method('closePreviousFlow');

        $this->flowRouterObject->expects($this->exactly(1))
                ->method('retrieveFollowingElements')
                ->will($this->returnValue($nextElements));

        $this->flowRouterObject->setCaseFlowHandler($mockCaseFlowHandler);

        $result = $this->flowRouterObject->routeFlow($executionResult, $previousFlowData);
        $this->assertArrayHasKey('processed_flow', $result);
        $this->assertArrayHasKey('next_elements', $result);
    }
    
    public function testRouteFlowActionUpdate()
    {
        $flowData = array(
            'id' => '837278dh2837e',
            'cas_id' => 2,
            'cas_index' => 3,
            'bpmn_type' => 'bpmnActivity',
            'bpmn_id' => 'aiuj2d8931'
        );
        
        $previousFlowData = array(
            'id' => '2189su9128sda',
            'cas_id' => 2,
            'cas_index' => 2,
            'bpmn_type' => 'bpmnActivity',
            'bpmn_id' => 'nsiojqwd98'
        );

        $executionResult = array(
            'route_action' => 'ROUTE',
            'flow_action' => 'UPDATE',
            'flow_filters' => array(),
            'flow_data' => $flowData,
            'flow_id' => $flowData['id']
        );

        $nextElements = array(
            'next_elements' =>
            array(
                array(
                    'cas_id' => 2,
                    'cas_index' => 4
                ),
                array(
                    'cas_id' => 2,
                    'cas_index' => 5
                ),
                array(
                    'cas_id' => 2,
                    'cas_index' => 6
                )
            )
        );

        // We need to override the execute Element since that method is not 
        // evaluated in this test but is called inside the routeFlow method
        $this->flowRouterObject = $this->getMockBuilder('PMSEFlowRouter')
                ->setMethods(array('processElement', 'retrieveFollowingElements'))
                ->disableOriginalConstructor()
                ->getMock();

        // preparing the case flow handler mock
        $mockCaseFlowHandler = $this->getMockBuilder('PMSECaseFlowHandler')
                ->disableOriginalConstructor()
                ->setMethods(array( 'closePreviousFlow', 'prepareFlowData', 'saveFlowData'))
                ->getMock();

        $mockCaseFlowHandler->expects($this->exactly(0))
                ->method('closePreviousFlow');

        $this->flowRouterObject->expects($this->exactly(1))
                ->method('retrieveFollowingElements')
                ->will($this->returnValue($nextElements));

        $this->flowRouterObject->setCaseFlowHandler($mockCaseFlowHandler);

        $result = $this->flowRouterObject->routeFlow($executionResult, $previousFlowData);
        $this->assertArrayHasKey('processed_flow', $result);
        $this->assertArrayHasKey('next_elements', $result);
    }

    public function testRouteFlowActionNone()
    {
        $flowData = array(
            'id' => '837278dh2837e',
            'cas_id' => 2,
            'cas_index' => 3,
            'bpmn_type' => 'bpmnActivity',
            'bpmn_id' => 'aiuj2d8931'
        );
        
        $previousFlowData = array(
            'id' => '2189su9128sda',
            'cas_id' => 2,
            'cas_index' => 2,
            'bpmn_type' => 'bpmnActivity',
            'bpmn_id' => 'nsiojqwd98'
        );

        $executionResult = array(            
            'route_action' => 'ROUTE',
            'flow_action' => 'NONE',
            'flow_filters' => array(),
            'flow_data' => $flowData,
            'flow_id' => $flowData['id']
        );

        $nextElements = array(
            'next_elements' =>
            array(
                array(
                    'cas_id' => 2,
                    'cas_index' => 4
                ),
                array(
                    'cas_id' => 2,
                    'cas_index' => 5
                ),
                array(
                    'cas_id' => 2,
                    'cas_index' => 6
                )
            )
        );

        // We need to override the execute Element since that method is not 
        // evaluated in this test but is called inside the routeFlow method
        $this->flowRouterObject = $this->getMockBuilder('PMSEFlowRouter')
                ->setMethods(array('processElement', 'retrieveFollowingElements'))
                ->disableOriginalConstructor()
                ->getMock();

        // preparing the case flow handler mock
        $mockCaseFlowHandler = $this->getMockBuilder('PMSECaseFlowHandler')
                ->disableOriginalConstructor()
                ->setMethods(array('closePreviousFlow', 'prepareFlowData', 'saveFlowData'))
                ->getMock();
      

        $mockCaseFlowHandler->expects($this->exactly(0))
                ->method('closePreviousFlow');

        $this->flowRouterObject->expects($this->exactly(1))
                ->method('retrieveFollowingElements')
                ->will($this->returnValue($nextElements));

        $this->flowRouterObject->setCaseFlowHandler($mockCaseFlowHandler);

        $result = $this->flowRouterObject->routeFlow($executionResult, $previousFlowData);
        $this->assertArrayHasKey('processed_flow', $result);
        $this->assertArrayHasKey('next_elements', $result);
    }
    
    public function testRouteFlowActionClose()
    {
        $flowData = array(
            'id' => '837278dh2837e',
            'cas_id' => 2,
            'cas_index' => 3,
            'bpmn_type' => 'bpmnActivity',
            'bpmn_id' => 'aiuj2d8931'
        );
        
        $previousFlowData = array(
            'id' => '2189su9128sda',
            'cas_id' => 2,
            'cas_index' => 2,
            'bpmn_type' => 'bpmnActivity',
            'bpmn_id' => 'nsiojqwd98'
        );       

        $executionResult = array(
            'processed_flow' => array(),
            'route_action' => 'WAIT',
            'flow_action' => 'CLOSE',
            'flow_filters' => array(),
            'flow_data' => $flowData,
            'flow_id' => $flowData['id']
        );

        $nextElements = array(
            'next_elements' =>
            array(
                array(
                    'cas_id' => 2,
                    'cas_index' => 4
                ),
                array(
                    'cas_id' => 2,
                    'cas_index' => 5
                ),
                array(
                    'cas_id' => 2,
                    'cas_index' => 6
                )
            )
        );

        // We need to override the execute Element since that method is not 
        // evaluated in this test but is called inside the routeFlow method
        $this->flowRouterObject = $this->getMockBuilder('PMSEFlowRouter')
                ->setMethods(array('processElement', 'retrieveFollowingElements'))
                ->disableOriginalConstructor()
                ->getMock();

        // preparing the case flow handler mock
        $mockCaseFlowHandler = $this->getMockBuilder('PMSECaseFlowHandler')
                ->disableOriginalConstructor()
                ->setMethods(array( 'closePreviousFlow', 'prepareFlowData', 'saveFlowData'))
                ->getMock();

        $mockCaseFlowHandler->expects($this->exactly(1))
                ->method('closePreviousFlow');

        $this->flowRouterObject->expects($this->exactly(1))
                ->method('retrieveFollowingElements')
                ->will($this->returnValue($nextElements));

        $this->flowRouterObject->setCaseFlowHandler($mockCaseFlowHandler);

        $result = $this->flowRouterObject->routeFlow($executionResult, $previousFlowData);
        $this->assertArrayHasKey('processed_flow', $result);
        $this->assertArrayHasKey('next_elements', $result);
    }

    public function testRetrieveFollowingElementsRoute()
    {
        $executionResult = array(
            'route_action' => 'ROUTE',
            'flow_filters' => array()
        );
        
        $flowData = array(
            'cas_id' => 2,
            'cas_index' => 3
        );

        $this->flowRouterObject = $this->getMockBuilder('PMSEFlowRouter')
                ->setMethods(array('filterFlows'))
                ->disableOriginalConstructor()
                ->getMock();
        
        $this->flowRouterObject->expects($this->once())
                ->method('filterFlows');

        $mockCaseFlowHandler = $this->getMockBuilder('PMSECaseFlowHandler')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieveFollowingElements'))
                ->getMock();

        $mockCaseFlowHandler->expects($this->exactly(1))
                ->method('retrieveFollowingElements');
        
        $this->flowRouterObject->setCaseFlowHandler($mockCaseFlowHandler);
        $this->flowRouterObject->retrieveFollowingElements($executionResult, $flowData);
    }

    public function testRetrieveFollowingElementsQueue()
    {
        $executionResult = array('route_action' => 'QUEUE', 'flow_filters'=>array());
        $flowData = array(
            'cas_id' => 2,
            'cas_index' => 3
        );

        $this->flowRouterObject = $this->getMockBuilder('PMSEFlowRouter')
                ->setMethods(array('queueJob'))
                ->disableOriginalConstructor()
                ->getMock();
        
        $mockCaseFlowHandler = $this->getMockBuilder('PMSECaseFlowHandler')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieveFollowingElements'))
                ->getMock();

        $mockCaseFlowHandler->expects($this->exactly(1))
                ->method('retrieveFollowingElements')
                ->will($this->returnValue(array()));
        
        $this->flowRouterObject->setCaseFlowHandler($mockCaseFlowHandler);

        $expectedResult = array();
        $result = $this->flowRouterObject->retrieveFollowingElements($executionResult, $flowData);
        $this->assertEquals($expectedResult, $result);
    }

    public function testQueueJob()
    {
        $flowData = array(
            'cas_id' => 2,
            'cas_index' => 3
        );

        $this->flowRouterObject = $this->getMockBuilder('PMSEFlowRouter')
                ->setMethods(null)
                ->disableOriginalConstructor()
                ->getMock();

        $mockJobQueueHandler = $this->getMockBuilder('PMSEJobQueueHandler')
                ->setMethods(array('submitPMSEJob'))
                ->disableOriginalConstructor()
                ->getMock();

        $mockJobQueueHandler->expects($this->exactly(1))
                ->method('submitPMSEJob')
                ->will($this->returnValue('abc'));

        $expectedResult = 'abc';

        $this->flowRouterObject->setJobQueueHandler($mockJobQueueHandler);
        $result = $this->flowRouterObject->queueJob($flowData);
        $this->assertEquals($expectedResult, $result);
    }

    public function testFilterFlows()
    {
        $this->flowRouterObject = $this->getMockBuilder('PMSEFlowRouter')
                ->setMethods(null)
                ->disableOriginalConstructor()
                ->getMock();
        
        $nextElements = array(
            array('bpmn_id'=>'first_id'),
            array('bpmn_id'=>'second_id'),
            array('bpmn_id'=>'third_id'),
            array('bpmn_id'=>'fourth_id')
        );
        
        $flowFilters = array(
            'first_id', 'third_id'
        );
                
        $expectedResult = array(
            array('bpmn_id'=>'first_id'),
            array('bpmn_id'=>'third_id')
        );

        $result = $this->flowRouterObject->filterFlows($nextElements, $flowFilters);
        $this->assertEquals($expectedResult, $result);
    }

//    public function testWakeUpEngine()
//    {
//        $this->flowRouterObject = $this->getMockBuilder('PMSEFlowRouter')
//                ->setMethods(array('retrieveFollowingElements', 'runEngine'))
//                ->disableOriginalConstructor()
//                ->getMock();
//        
//        $elements = array(
//            array('id'=>'first_id'),
//            array('id'=>'second_id'),
//            array('id'=>'third_id'),
//            array('id'=>'fourth_id')
//        );
//        
//        $this->flowRouterObject->expects($this->once())
//                ->method('retrieveFollowingElements')
//                ->will($this->returnValue($elements));
//        
//        $this->flowRouterObject->expects($this->exactly(4))
//                ->method('runEngine');
//        
//        $flowData = array(
//            'cas_id' => 2,
//            'cas_index' => 3
//        );
//        
//        $bean = new stdClass();
//        $createThread = false;
//        
//        $executionResult = array(
//            'route_action' => 'ROUTE'
//        );
//        
//        $this->flowRouterObject->wakeUpEngine($flowData, $createThread, $bean, $executionResult);
//    }

}
