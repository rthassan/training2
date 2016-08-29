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
 * ExtAPILotusLiveMock.php
 *
 * This is a mock object to simulate calls to the ExtAPILotusLive class
 *
 * @author Collin Lee
 *
 */

require_once('include/externalAPI/LotusLive/ExtAPILotusLive.php');

class ExtAPILotusLiveMock extends ExtAPILotusLive
{
    var $sugarOauthMock;

    function __construct()
    {
        parent::__construct();
        $this->api_data = array();
        $this->api_data['subscriberId'] = '';
    }

    /**
     * getErrorStringFromCode
     * This method overrides a protected method
     *
     *
     */
    public function getErrorStringFromCode($error='')
    {
        return parent::getErrorStringFromCode($error);
    }

    /**
     * getClient
     * This method is used to override the getClient method
     *
     * @return mixed The SugarOauth instance
     */
    public function getClient()
    {
        return $this->sugarOauthMock;
    }
}
