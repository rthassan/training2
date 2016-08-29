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
class PMSEPreProcessorTest extends PHPUnit_Framework_TestCase
{
    
    protected $loggerMock;

    /**
     * Sets up the test data, for example,
     *     opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->loggerMock = $this->getMockBuilder("PSMELogger")
                ->disableOriginalConstructor()
                ->setMethods(array('info', 'debug'))
                ->getMock();
    }

    /**
     * Removes the initial test configurations for each test, for example:
     *     close a network connection. 
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }
    
    public function testGetFlowDataListDirect()
    {
        $request = new PMSERequest();
        $arguments = array('idFlow' => '282', 'id' => '676', 'cas_id'=> '191');
        $request->setType('direct');
        $request->setArguments($arguments);
        
        $preProcessorMock = $this->getMockBuilder('PMSEPreProcessor')
                ->disableOriginalConstructor()
                ->setMethods(array('getFlowById'))
                ->getMock();
        
        $preProcessorMock->expects($this->once())
                ->method('getFlowById')
                ->will($this->returnValue(array(true)));
        
        $result = $preProcessorMock->getFlowDataList($request);
        
        $this->assertEquals(array(true), $result);
    }
    
    public function testGetFlowDataListHook()
    {
        $request = new PMSERequest();
        $arguments = array('idFlow' => '282', 'id' => '676', 'cas_id'=> '191');
        $request->setArguments($arguments);
        $request->setType('hook');
        
        $preProcessorMock = $this->getMockBuilder('PMSEPreProcessor')
                ->disableOriginalConstructor()
                ->setMethods(array('getAllEvents'))
                ->getMock();

//      Flows to sort
        $first = array("evn_params" => 'new', "date_entered" => '2015-11-13 16:35:15');
        $second = array("evn_params" => 'updated', "date_entered" => '2015-11-13 16:35:16');
        $third = array("evn_params" => 'allupdates', "date_entered" => '2015-11-13 16:35:17');
        $fourth = array("evn_params" => 'new', "date_entered" => '2015-11-13 16:35:18');
        $fifth = array("evn_params" => 'updated', "date_entered" => '2015-11-13 16:35:12');
        $sixth = array("evn_params" => 'allupdates', "date_entered" => '2015-11-13 16:35:19');

        $preProcessorMock->expects($this->once())
                ->method('getAllEvents')
                ->will($this->returnValue(array($second, $third, $first, $fourth, $fifth, $sixth)));
        
        $result = $preProcessorMock->getFlowDataList($request);

        $this->assertEquals(array($first,$fourth,$fifth,$second,$third,$sixth), $result);
    }
    
    public function testGetFlowDataListQueue()
    {
        $request = new PMSERequest();
        $arguments = array('idFlow' => '282', 'id' => '676', 'cas_id'=> '191');
        $request->setArguments($arguments);
        $request->setType('queue');
        
        $preProcessorMock = $this->getMockBuilder('PMSEPreProcessor')
                ->disableOriginalConstructor()
                ->setMethods(array('getFlowById'))
                ->getMock();
        
        $preProcessorMock->expects($this->once())
                ->method('getFlowById')
                ->will($this->returnValue(array(true)));
        
        $result = $preProcessorMock->getFlowDataList($request);
        
        $this->assertEquals(array(true), $result);
    }
    
    public function testGetFlowDataListEngine()
    {
        $request = new PMSERequest();
        $arguments = array('idFlow' => '282', 'id' => '676', 'cas_id'=> '191');
        $request->setArguments($arguments);
        $request->setType('engine');

        $preProcessorMock = $this->getMockBuilder('PMSEPreProcessor')
                ->disableOriginalConstructor()
                ->setMethods(array('getFlowsByCasId'))
                ->getMock();
        
        $preProcessorMock->expects($this->once())
                ->method('getFlowsByCasId')
                ->will($this->returnValue(array(true)));
        
        $result = $preProcessorMock->getFlowDataList($request);
        
        $this->assertEquals(array(true), $result);
    }
    
    public function testGetFlowDataListInvalid()
    {
        $request = new PMSERequest();
        $arguments = array('idFlow' => '282', 'id' => '676', 'cas_id'=> '191');
        $request->setArguments($arguments);
        $request->setType('invalid_type');

        $preProcessorMock = $this->getMockBuilder('PMSEPreProcessor')
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();
        
        $result = $preProcessorMock->getFlowDataList($request);
        
        $this->assertEquals(array(), $result);
    }
    
    public function testGetAllEvents()
    {
        
        $beanMock = $this->getMockBuilder('SugarBean')
                ->disableOriginalConstructor()
                ->setMethods(array())
                ->getMock();
        $beanMock->id = 'abc123';
        $beanMock->module_name = 'Leads';
        
        $sugarQueryMock = $this->getMockBuilder('SugarQuery')
                ->disableOriginalConstructor()
                ->setMethods(array('select', 'where', 'from', 'joinRaw', 'queryAnd', 'addRaw', 'compileSql', 'execute'))
                ->getMock();
        
        $sugarQueryMock->expects($this->once())
                ->method('where')
                ->will($this->returnValue($sugarQueryMock));
        
        $sugarQueryMock->expects($this->once())
                ->method('queryAnd')
                ->will($this->returnValue($sugarQueryMock));
        
        $sugarQueryMock->expects($this->once())
                ->method('execute')
                ->will($this->returnValue('QUERY_RESULT'));
        
        $selectMock = $this->getMockBuilder('Select')
                ->disableOriginalConstructor()
                ->setMethods(array('fieldRaw'))
                ->getMock();
        
        $sugarQueryMock->select = $selectMock;
        
        $preProcessorMock = $this->getMockBuilder('PMSEPreProcessor')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieveBean', 'retrieveSugarQuery'))
                ->getMock();
        
        $preProcessorMock->expects($this->once())
                ->method('retrieveSugarQuery')
                ->will($this->returnValue($sugarQueryMock));
        
        $sugarBeanMock = $this->getMockBuilder('SugarBean')
                ->disableAutoload()
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();
        
        $preProcessorMock->expects($this->once())
                ->method('retrieveBean')
                ->will($this->returnValue($sugarBeanMock));
        
        $preProcessorMock->setLogger($this->loggerMock);
        
        $result = $preProcessorMock->getAllEvents($beanMock);
        $this->assertEquals('QUERY_RESULT', $result);
    }
    
    public function testGetFlowsByCasId()
    {                     
        
        $sugarQueryMock = $this->getMockBuilder('SugarQuery')
                ->disableOriginalConstructor()
                ->setMethods(array('select', 'where', 'from', 'joinRaw', 'queryAnd', 'addRaw', 'compileSql', 'execute'))
                ->getMock();
        
        $sugarQueryMock->expects($this->once())
                ->method('where')
                ->will($this->returnValue($sugarQueryMock));
        
        $sugarQueryMock->expects($this->once())
                ->method('queryAnd')
                ->will($this->returnValue($sugarQueryMock));
        
        $sugarQueryMock->expects($this->once())
                ->method('execute')
                ->will($this->returnValue('QUERY_RESULT'));
        
        $selectMock = $this->getMockBuilder('Select')
                ->disableOriginalConstructor()
                ->setMethods(array('fieldRaw'))
                ->getMock();
        
        $sugarQueryMock->select = $selectMock;
        
        $preProcessorMock = $this->getMockBuilder('PMSEPreProcessor')
                ->disableOriginalConstructor()
                ->setMethods(array('retrieveBean', 'retrieveSugarQuery'))
                ->getMock();
        
        $preProcessorMock->expects($this->once())
                ->method('retrieveSugarQuery')
                ->will($this->returnValue($sugarQueryMock));
        
        $sugarBeanMock = $this->getMockBuilder('SugarBean')
                ->disableAutoload()
                ->disableOriginalConstructor()
                ->setMethods(NULL)
                ->getMock();
        
        $preProcessorMock->expects($this->once())
                ->method('retrieveBean')
                ->will($this->returnValue($sugarBeanMock));
        
        $preProcessorMock->setLogger($this->loggerMock);
        
        $casId = 'abc123';
        
        $result = $preProcessorMock->getFlowsByCasId($casId);
        $this->assertEquals('QUERY_RESULT', $result);
    }
    
    public function testProcessRequestValidRequest()
    {
        $beanMock = $this->getMockBuilder('SugarBean')
                ->disableOriginalConstructor()
                ->setMethods(array('load_relationships'))
                ->getMock();
        
        $request = new PMSERequest();
        $request->setBean($beanMock);
        
        $resultRequest = new PMSERequest();
        $resultRequest->validate();
        
        $preProcessorMock = $this->getMockBuilder('PMSEPreProcessor')                
                ->disableOriginalConstructor()
                ->setMethods(array('getFlowDataList', 'processFlowData', 'processBean'))
                ->getMock();
        
        $preProcessorMock->expects($this->once())
                ->method('processBean')
                ->will($this->returnValue($beanMock));
        
        $preProcessorMock->expects($this->once())
                ->method('getFlowDataList')
                ->will($this->returnValue(array(array('bpmn_type' => 'bpmEvent', 'bpmn_id'=>'event_0', 'cas_id' => 1))));
        
        $validatorMock = $this->getMockBuilder('PMSEValidator')
                ->disableOriginalConstructor()
                ->setMethods(array('validateRequest'))
                ->getMock();
        
        $validatorMock->expects($this->once())
                ->method('validateRequest')
                ->will($this->returnValue($resultRequest));
        
        $executerMock = $this->getMockBuilder('PMSEExecuter')
                ->disableOriginalConstructor()
                ->setMethods(array('runEngine'))
                ->getMock();
        
        $executerMock->expects($this->once())
                ->method('runEngine');
        
        $preProcessorMock->setExecuter($executerMock);
        $preProcessorMock->setLogger($this->loggerMock);
        $preProcessorMock->setValidator($validatorMock);
        
        $preProcessorMock->processRequest($request);
    }
    
    public function testProcessRequestInvalidRequest()
    {
        $beanMock = $this->getMockBuilder('SugarBean')
                ->disableOriginalConstructor()
                ->setMethods(array('load_relationships'))
                ->getMock();
        
        $request = new PMSERequest();
        $request->setBean($beanMock);
        
        $resultRequest = new PMSERequest();
        $resultRequest->invalidate();
        
        $preProcessorMock = $this->getMockBuilder('PMSEPreProcessor')                
                ->disableOriginalConstructor()
                ->setMethods(array('getFlowDataList', 'processFlowData', 'processBean'))
                ->getMock();
        
        $preProcessorMock->expects($this->once())
                ->method('processBean')
                ->will($this->returnValue($beanMock));
        
        $preProcessorMock->expects($this->once())
                ->method('getFlowDataList')
                ->will($this->returnValue(array(array('bpmn_type' => 'bpmEvent', 'bpmn_id'=>'event_0', 'cas_id' => 1))));
        
        $validatorMock = $this->getMockBuilder('PMSEValidator')
                ->disableOriginalConstructor()
                ->setMethods(array('validateRequest'))
                ->getMock();
        
        $validatorMock->expects($this->once())
                ->method('validateRequest')
                ->will($this->returnValue($resultRequest));
        
        $executerMock = $this->getMockBuilder('PMSEExecuter')
                ->disableOriginalConstructor()
                ->setMethods(array('runEngine'))
                ->getMock();

        
        $preProcessorMock->setExecuter($executerMock);
        $preProcessorMock->setLogger($this->loggerMock);
        $preProcessorMock->setValidator($validatorMock);
        
        $preProcessorMock->processRequest($request);
    }
}
