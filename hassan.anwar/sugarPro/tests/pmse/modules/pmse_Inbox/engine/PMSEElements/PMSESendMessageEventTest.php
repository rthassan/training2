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
class PMSESendMessageEventTest extends PHPUnit_Framework_TestCase 
{

    /**
     *
     * @var type 
     */
    protected $loggerMock;

    /**
     * @var PMSEElement
     */
    protected $sendMessageEvent;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->loggerMock = $this->getMockBuilder('PMSELogger')
            ->disableOriginalConstructor()
            ->setMethods(array('info', 'debug', 'warning', 'error'))
            ->getMock();
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
     */
    public function testRunResumingExecution()
    {
        $this->sendMessageEvent = $this->getMockBuilder('PMSESendMessageEvent')
            ->disableOriginalConstructor()
            ->setMethods(array('prepareResponse', 'sendEmail'))
            ->getMock();

        $this->sendMessageEvent->expects($this->once())
            ->method('prepareResponse');

        $this->sendMessageEvent->expects($this->once())
            ->method('sendEmail');

        $flowData = array();
        $bean = new stdClass();

        $this->sendMessageEvent->run($flowData, $bean, 'RESUME_EXECUTION');
    }
    
    /**
     *
     */
    public function testRunNormal()
    {
        $this->sendMessageEvent = $this->getMockBuilder('PMSESendMessageEvent')
            ->disableOriginalConstructor()
            ->setMethods(array('prepareResponse', 'sendEmail'))
            ->getMock();

        $this->sendMessageEvent->expects($this->once())
            ->method('prepareResponse');

        $flowData = array();
        $bean = new stdClass();

        $this->sendMessageEvent->run($flowData, $bean, '');
    }

    public function testSendEmail()
    {
        $this->sendMessageEvent = $this->getMockBuilder('PMSESendMessageEvent')
            ->disableOriginalConstructor()
            ->setMethods(array('retrieveBean'))
            ->getMock();

        $eventDefinition = $this->getMockBuilder('PMSEEventDefinition')
            ->disableOriginalConstructor()
            ->setMethods(array('retrieve'))
            ->getMock();

        $eventDefinition->evn_criteria = array();
        $eventDefinition->evn_params = json_encode(array());

        $this->sendMessageEvent->setEventDefinitionBean($eventDefinition);

        $caseHandler = $this->getMockBuilder('PMSECaseFlowHandler')
            ->disableOriginalConstructor()
            ->setMethods(array('retrieveBean'))
            ->getMock();
        
        $emailHandler = $this->getMockBuilder('PMSEEmailHandler')
            ->disableOriginalConstructor()
            ->setMethods(array('processEmailsFromJson', 'sendTemplateEmail'))
            ->getMock();

        $emailHandler->expects($this->exactly(1))
            ->method('processEmailsFromJson');

        $emailHandler->expects($this->exactly(1))
            ->method('sendTemplateEmail')
            ->will($this->returnValue(array(
                'result'=>array('successful sending'), 
            )));

        $this->sendMessageEvent->setCaseFlowHandler($caseHandler);
        $this->sendMessageEvent->setEmailHandler($emailHandler);

        $flowData = array (
            'cas_sugar_module' => 'Leads',
            'cas_sugar_object_id' => 'anc7832jd2387hd23',
            'bpmn_id' => 'aosijdi9qwdj',
            'id' => 'caseidja9823ju89d'
        );
        
        $this->sendMessageEvent->sendEmail($flowData);
    }
    
    public function testSendEmailErrorInfo()
    {
        $this->sendMessageEvent = $this->getMockBuilder('PMSESendMessageEvent')
            ->disableOriginalConstructor()
            ->setMethods(array('retrieveBean'))
            ->getMock();

        $eventDefinition = $this->getMockBuilder('PMSEEventDefinition')
            ->disableOriginalConstructor()
            ->setMethods(array('retrieve'))
            ->getMock();

        $eventDefinition->evn_criteria = array();
        $eventDefinition->evn_params = json_encode(array());

        $this->sendMessageEvent->setEventDefinitionBean($eventDefinition);

        $caseHandler = $this->getMockBuilder('PMSECaseFlowHandler')
            ->disableOriginalConstructor()
            ->setMethods(array('retrieveBean'))
            ->getMock();
        $emailHandler = $this->getMockBuilder('PMSEEmailHandler')
            ->disableOriginalConstructor()
            ->setMethods(array('processEmailsFromJson', 'sendTemplateEmail'))
            ->getMock();

        $emailHandler->expects($this->exactly(1))
            ->method('processEmailsFromJson');

        $emailHandler->expects($this->exactly(1))
            ->method('sendTemplateEmail')
            ->will($this->returnValue(array(
                'result'=>array(), 
                'ErrorInfo' => 'Some Error happened'
            )));

        $this->sendMessageEvent->setCaseFlowHandler($caseHandler);
        $this->sendMessageEvent->setEmailHandler($emailHandler);

        $flowData = array (
            'cas_sugar_module' => 'Leads',
            'cas_sugar_object_id' => 'anc7832jd2387hd23',
            'bpmn_id' => 'aosijdi9qwdj',
            'id' => 'caseidja9823ju89d'
        );

        $this->sendMessageEvent->setLogger($this->loggerMock);
        
        $this->sendMessageEvent->sendEmail($flowData);
    }
    
    public function testSendEmailErrorMessage()
    {
        $this->sendMessageEvent = $this->getMockBuilder('PMSESendMessageEvent')
            ->disableOriginalConstructor()
            ->setMethods(array('retrieveBean'))
            ->getMock();

        $eventDefinition = $this->getMockBuilder('PMSEEventDefinition')
            ->disableOriginalConstructor()
            ->setMethods(array('retrieve'))
            ->getMock();

        $eventDefinition->evn_criteria = array();
        $eventDefinition->evn_params = json_encode(array());

        $this->sendMessageEvent->setEventDefinitionBean($eventDefinition);

        $caseHandler = $this->getMockBuilder('PMSECaseFlowHandler')
            ->disableOriginalConstructor()
            ->setMethods(array('retrieveBean'))
            ->getMock();
        
        $emailHandler = $this->getMockBuilder('PMSEEmailHandler')
            ->disableOriginalConstructor()
            ->setMethods(array('processEmailsFromJson', 'sendTemplateEmail'))
            ->getMock();

        $emailHandler->expects($this->exactly(1))
            ->method('processEmailsFromJson');

        $emailHandler->expects($this->exactly(1))
            ->method('sendTemplateEmail')
            ->will($this->returnValue(array(
                'result'=>array('something'), 
                'ErrorMessage' => 'Some Error happened again'
            )));

        $this->sendMessageEvent->setCaseFlowHandler($caseHandler);
        $this->sendMessageEvent->setEmailHandler($emailHandler);

        $flowData = array (
            'cas_sugar_module' => 'Leads',
            'cas_sugar_object_id' => 'anc7832jd2387hd23',
            'bpmn_id' => 'aosijdi9qwdj',
            'id' => 'caseidja9823ju89d'
        );

        $this->sendMessageEvent->setLogger($this->loggerMock);
        
        $this->sendMessageEvent->sendEmail($flowData);
    }
}
 