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

/**
 * Make sure that list data is properly sorted by relate full name field
 */
class SortByRelateFullNameTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function testSortByRelateFullName()
    {
        $contact = BeanFactory::getBean('Notes');
        $query = $contact->create_new_list_query('contact_name', null, array(), array(), 0, '', true);

        $order_by = $query['order_by'];

        // ORDER BY should contain "last_name" since it's in "sort_on" attribute of contact.name
        $this->assertContains('last_name', $order_by);

        // but shouldn't contain "first_name" since it's not in "sort_on" attribute of contact.name
        $this->assertNotContains('first_name', $order_by);
    }
}
