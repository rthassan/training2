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

require_once('include/MVC/View/views/view.detail.php');

class ViewDetailTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $view = new ViewDetail();
        $this->assertEquals('detail', $view->type);
    }

    public function testSubclasses()
    {
        $view = new MockViewDetailDirect();
        $this->assertEquals('detail', $view->type);

        $view = new MockViewDetailConstructor();
        $this->assertEquals('detail', $view->type);
    }

}

class MockViewDetailDirect extends ViewDetail
{
    public function __construct()
    {
        parent::ViewDetail();
    }
}

class MockViewDetailConstructor extends ViewDetail
{
    public function __construct()
    {
        parent::__construct();
    }
}