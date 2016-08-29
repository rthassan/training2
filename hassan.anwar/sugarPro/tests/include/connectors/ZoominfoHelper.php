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
class ZoominfoTestHelper {
    const STREAM_NAME = "zoominfo";

    public function __construct()
    {
        stream_wrapper_register(self::STREAM_NAME, 'ZoominfoMockStream', STREAM_IS_URL);
    }

    public function __destruct()
    {
        // ...
        stream_wrapper_unregister(self::STREAM_NAME);
    }

    public function url($type='query')
    {
        return self::STREAM_NAME."://$type/query?pc=";
    }
}

class ZoominfoMockStream
{
    public $query_params = array();
    protected $data = '';

    function stream_open($path, $mode, $options, &$opened_path)
    {
        $dir = dirname(__FILE__);
        $urlinfo = parse_url($path);
        $this->query_params = array();
        parse_str($urlinfo['query'], $this->query_params);
        $smarty = new Sugar_Smarty();
        foreach($this->query_params as $name => $value) {
            $smarty->assign($name, $value);
        }
        $this->data = $smarty->fetch($dir."/".$urlinfo['host']."-zoominfo.xml");
        $this->position = 0;
        return true;
    }

    function stream_close()
    {
        $this->data = '';
        return true;
    }

    function stream_read($count)
    {
        $ret = substr($this->data, $this->position, $count);
        $this->position += strlen($ret);
        return $ret;
    }

    function stream_tell()
    {
        return $this->position;
    }

    function stream_eof()
    {
        return $this->position >= strlen($this->data);
    }
}
