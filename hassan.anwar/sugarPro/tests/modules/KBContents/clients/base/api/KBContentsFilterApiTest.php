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

require_once 'modules/KBContents/clients/base/api/KBContentsFilterApi.php';

class KBContentsFilterApiTest extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * @var RestService
     */
    protected $service = null;

    /**
     * @var KBContentsFilterApi
     */
    protected $api = null;

    /**
     * @var KBContentsMock
     */
    protected $bean;

    public function setUp()
    {
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('current_user', array(true, true));

        $this->service = SugarTestRestUtilities::getRestServiceMock();
        $this->api = $this->getMock('KBContentsFilterApi',
            array('getElasticQueryBuilder')
        );
        $this->bean = SugarTestKBContentUtilities::createBean();
    }

    public function tearDown()
    {
        $this->service = null;
        $this->api = null;

        SugarTestKBContentUtilities::removeAllCreatedBeans();
        SugarTestHelper::tearDown();
    }

    /**
     * Data Provider for testFilterList
     *
     * @return array
     */
    public function dataProviderForFilterList()
    {
        /**
         * @param $args
         * @param $isElasticCalled
         */
        return array(
            array(
                array(
                    'module' => 'KBContents',
                    'filter' => array(
                        array(
                            'kbdocument_body' => array(
                                '$contains' => 'test'
                            )
                        )
                    ),
                ),
                true
            ),
            array(
                array(
                    'module' => 'KBContents',
                    'filter' => array(
                        array(
                            'kbdocument_body' => array(
                                '$not_contains' => 'test'
                            )
                        )
                    ),
                ),
                true
            ),
            array(
                array(
                    'module' => 'KBContents',
                ),
                false
            ),
        );
    }

    /**
     * Test asserts that filterByContainingExcludingWords method is called if kbdocument_body filter parameter set.
     *
     * @dataProvider dataProviderForFilterList
     * @param array $args
     * @param boolean $expected flag if filterByContainingExcludingWords method was called
     */
    public function testFilterList($args, $expected)
    {
        $api = $this->getMock('KBContentsFilterApi', array('filterByContainingExcludingWords'));
        if ($expected) {
            $api->expects($this->once())->method('filterByContainingExcludingWords')->with(
                $this->equalTo($this->service)
            )->willReturn(array());
        } else {
            $api->expects($this->never())->method('filterByContainingExcludingWords')->with(
                $this->equalTo($this->service)
            );
        }
        $api->filterList($this->service, $args);
    }

    /**
     * Data provider for testFilterByContainingExcludingWords
     *
     * @return array
     */
    public function dataProvider()
    {
        return array(
            array(
                array(
                    array(
                        'kbdocument_body' => array(
                            '$contains' => 'test'
                        )
                    )
                )
            ),
            array(
                array(
                    array(
                        'kbdocument_body' => array(
                            '$not_contains' => 'test'
                        )
                    )
                )
            )
        );
    }

    /**
     * Test asserts that filterByContainingExcludingWords method returns right parameters.
     *
     * @dataProvider dataProvider
     */
    public function testFilterByContainingExcludingWords($filter)
    {
        $builderMock = $this->getMockBuilder('Sugarcrm\Sugarcrm\Elasticsearch\Query\QueryBuilder')
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();

        $resultSetMock = $this->getMockBuilder('Sugarcrm\Sugarcrm\Elasticsearch\Adapter\ResultSet')
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();

        $this->api->expects($this->once())->method('getElasticQueryBuilder')->will($this->returnValue($builderMock));
        $builderMock->expects($this->any())->method('executeSearch')->will($this->returnValue($resultSetMock));

        $result = $this->api->filterList($this->service, array(
            'module' => $this->bean->module_name,
            'record' => $this->bean->id,
            'filter' => $filter,
            'order_by' => 'name'
        ));

        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('next_offset', $result);
        $this->assertArrayHasKey('records', $result);
        $this->assertInternalType('array', $result['records']);
    }
}
