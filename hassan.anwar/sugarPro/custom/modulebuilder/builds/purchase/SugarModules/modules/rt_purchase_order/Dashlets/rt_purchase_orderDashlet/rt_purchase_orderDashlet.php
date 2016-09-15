<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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
/*********************************************************************************
 * $Id$
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/rt_purchase_order/rt_purchase_order.php');

class rt_purchase_orderDashlet extends DashletGeneric { 
    function rt_purchase_orderDashlet($id, $def = null) {
		global $current_user, $app_strings;
		require('modules/rt_purchase_order/metadata/dashletviewdefs.php');

        parent::DashletGeneric($id, $def);

        if(empty($def['title'])) $this->title = translate('LBL_HOMEPAGE_TITLE', 'rt_purchase_order');

        $this->searchFields = $dashletData['rt_purchase_orderDashlet']['searchFields'];
        $this->columns = $dashletData['rt_purchase_orderDashlet']['columns'];

        $this->seedBean = new rt_purchase_order();        
    }
}