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

//this file is temporarily moved during a unit test to test the behaviour of createdefs.php handling.

$createdef['contacts@testsugar.info']['Contacts'] = array(
        'fields' => array(
            'last_name' => '{from_name}',
            'department' => '{email_id}',
            'date_entered' => '{date}',
            'description' => '{description} {email_id} {message_id} {subject} {from}',
            'lead_source' => 'Email',
        ),
);

$createdef['cases@testsugar.info']['Cases'] = array(
        'fields' => array(
	        'name' => '{from_name}',
            'status' => '{email_id}',
	        'date_entered' => '{date}',
	        'description' => '{description} {email_id} {message_id} {subject} {from}',
        ),
);

$createdef['opp@testsugar.info']['Opportunities'] = array(
        'fields' => array(
            'name' => '{from_name}',
            'sales_stage' => '{email_id}',
            'date_entered' => '{date}',
            'description' => '{description} {email_id} {message_id} {subject} {from}',
        ),
);
