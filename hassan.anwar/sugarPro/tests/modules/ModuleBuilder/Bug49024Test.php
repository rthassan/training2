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

require_once 'modules/ModuleBuilder/parsers/relationships/OneToOneRelationship.php' ;
require_once 'modules/ModuleBuilder/parsers/relationships/DeployedRelationships.php' ;
require_once 'modules/ModuleBuilder/parsers/relationships/UndeployedRelationships.php' ;

/**
 * Bug 49024
 * Relationships Created in Earlier Versions Cause Conflicts and AJAX Errors After Upgrade 
 * 
 * @ticket 49024
 */
class Bug49024Test extends Sugar_PHPUnit_Framework_TestCase
{
    private $objOneToOneRelationship;

    public function setUp()
    {
        $beanList = array();
        $beanFiles = array();
        require('include/modules.php');
        $GLOBALS['beanList'] = $beanList;
        $GLOBALS['beanFiles'] = $beanFiles;

        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();

        $this->objOneToOneRelationship = $this->getMockBuilder('OneToOneRelationship')
            ->disableOriginalConstructor()
            ->setMethods(array('getDefinition'))
            ->getMock();

        $this->objOneToOneRelationship->expects($this->any())
            ->method('getDefinition')
            ->will($this->returnValue(array(
                    'lhs_module' => 'lhs_module',
                    'rhs_module' => 'rhs_module'
                )));

    }

    public function tearDown()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);

        unset($GLOBALS['beanFiles']);
        unset($GLOBALS['beanList']);
        unset($this->objOneToOneRelationship);
    }

    /**
     * @group 49024
     */
    public function testDeployedRelationshipsUniqName()
    {
        $objDeployedRelationships = $this->getMockBuilder('DeployedRelationshipsBug49024Test')
            ->disableOriginalConstructor()
            ->setMethods(array('load', 'getRelationshipList'))
            ->getMock();

        $objDeployedRelationships->expects($this->any())
            ->method('getRelationshipList')
            ->will($this->returnValue(array()));

        $name = $objDeployedRelationships->getUniqueNameBug49024Test($this->objOneToOneRelationship);
        $this->assertEquals('lhs_module_rhs_module_1', $name);
    }

    /**
     * @group 49024
     */
    public function testDeployedRelationshipsUniqName2()
    {
        $objDeployedRelationships = $this->getMockBuilder('DeployedRelationshipsBug49024Test')
            ->disableOriginalConstructor()
            ->setMethods(array('load', 'getRelationshipList'))
            ->getMock();

        $objDeployedRelationships->expects($this->any())
            ->method('getRelationshipList')
            ->will($this->returnValue(array(
            'lhs_module_rhs_module_1' => true, 'lhs_module_rhs_module_2' => true
        )));

        $name = $objDeployedRelationships->getUniqueNameBug49024Test($this->objOneToOneRelationship);
        $this->assertEquals('lhs_module_rhs_module_3', $name);
    }

    /**
     * @group 49024
     */
    public function testUndeployedRelationshipsUniqName()
    {
        $objUndeployedRelationships = $this->getMockBuilder('UndeployedRelationshipsBug49024Test')
            ->disableOriginalConstructor()
            ->setMethods(array('load', 'getRelationshipList'))
            ->getMock();

        $objUndeployedRelationships->expects($this->any())
            ->method('getRelationshipList')
            ->will($this->returnValue(array()));

        $name = $objUndeployedRelationships->getUniqueNameBug49024Test($this->objOneToOneRelationship);
        $this->assertEquals('lhs_module_rhs_module', $name);
    }

    /**
     * @group 49024
     */
    public function testUndeployedRelationshipsUniqName2()
    {
        $objUndeployedRelationships = $this->getMockBuilder('UndeployedRelationshipsBug49024Test')
            ->disableOriginalConstructor()
            ->setMethods(array('load', 'getRelationshipList'))
            ->getMock();

        $objUndeployedRelationships->expects($this->any())
            ->method('getRelationshipList')
            ->will($this->returnValue(array(
                'lhs_module_rhs_module' => true, 'lhs_module_rhs_module_1' => true, 'lhs_module_rhs_module_2' => true
            )));

        $name = $objUndeployedRelationships->getUniqueNameBug49024Test($this->objOneToOneRelationship);
        $this->assertEquals('lhs_module_rhs_module_3', $name);
    }
}

class DeployedRelationshipsBug49024Test extends DeployedRelationships
{
    public function getUniqueNameBug49024Test ($relationship)
    {
        return $this->getUniqueName($relationship);
    }
}

class UndeployedRelationshipsBug49024Test extends UndeployedRelationships
{
    public function getUniqueNameBug49024Test ($relationship)
    {
        return $this->getUniqueName($relationship);
    }
}