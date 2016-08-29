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
require_once 'include/database/DBManagerFactory.php';

class Bug57563Test extends Sugar_PHPUnit_Framework_TestCase
{
    private $_db;

    public function setUp()
    {
        if(empty($this->_db)){
            $this->_db = DBManagerFactory::getInstance();
        }
    }

    public function tearDown()
    {
    }

    public function test_union_query_for_limitQuery()
    {
        $query_object = new CustomQuery();

        $m_closed = $query_object->db->convert('opportunities.date_closed', 'month');
        $today = $query_object->db->convert('', 'today');
        $m_date[0] = $query_object->db->convert($today, 'month');
        for($i=1; $i<6; $i++)
        {
            $m_date[$i] = $query_object->db->convert($query_object->db->convert($today, 'add_date', array($i, 'month')), 'month');
        }
        $m_date5 = $query_object->db->convert($today, 'add_date', array(5, "month"));

        $query = "(
            SELECT
             'New Business' \"Opportunity Type\",
            sum( case when $m_closed = $m_date[0] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}0\",
            sum( case when $m_closed = $m_date[1] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}1\",
            sum( case when $m_closed = $m_date[2] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}2\",
            sum( case when $m_closed = $m_date[3] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}3\",
            sum( case when $m_closed = $m_date[4] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}4\",
            sum( case when $m_closed = $m_date[5] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}5\",
            SUM(opportunities.amount_usdollar) AS \"Total Revenue\"
            FROM opportunities
             LEFT JOIN accounts_opportunities
            ON opportunities.id=accounts_opportunities.opportunity_id
            LEFT JOIN accounts
            ON accounts_opportunities.account_id=accounts.id
            WHERE opportunities.date_closed <= $m_date5 AND  opportunities.date_closed >= $today AND opportunities.opportunity_type = 'New Business'
            ) UNION (
            SELECT
             'Existing Business' \"Opportunity Type\",
            sum( case when $m_closed = $m_date[0] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}0\",
            sum( case when $m_closed = $m_date[1] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}1\",
            sum( case when $m_closed = $m_date[2] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}2\",
            sum( case when $m_closed = $m_date[3] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}3\",
            sum( case when $m_closed = $m_date[4] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}4\",
            sum( case when $m_closed = $m_date[5] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}5\",
            SUM(opportunities.amount_usdollar) AS \"Total Revenue\"
            FROM opportunities
             LEFT JOIN accounts_opportunities
            ON opportunities.id=accounts_opportunities.opportunity_id
            LEFT JOIN accounts
            ON accounts_opportunities.account_id=accounts.id
            WHERE opportunities.date_closed <= $m_date5 AND  opportunities.date_closed >= $today AND opportunities.opportunity_type = 'Existing Business'
            ) UNION (
            SELECT
             'Total Revenue' \"Opportunity Type\",
            sum( case when $m_closed = $m_date[0] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}0\",
            sum( case when $m_closed = $m_date[1] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}1\",
            sum( case when $m_closed = $m_date[2] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}2\",
            sum( case when $m_closed = $m_date[3] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}3\",
            sum( case when $m_closed = $m_date[4] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}4\",
            sum( case when $m_closed = $m_date[5] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}5\",
            SUM(opportunities.amount_usdollar) AS \"Total Revenue\"
            FROM opportunities
             LEFT JOIN accounts_opportunities
            ON opportunities.id=accounts_opportunities.opportunity_id
            LEFT JOIN accounts
            ON accounts_opportunities.account_id=accounts.id
            WHERE opportunities.date_closed <= $m_date5 AND  opportunities.date_closed >= $today
            )";

        $this->_db->limitQuery($query, 0, 1);

        $this->assertEmpty($this->_db->lastError(), "lastError should return empty");
    }
}
