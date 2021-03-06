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
class SugarBean
{
    public $id;
    public $bou_uid;
    public $object_name;
    public $column_fields;

    public function __construct($name = '')
    {
        $this->object_name = $name;
    }

    public function getIndices()
    {
    }
    
    public function get_where()
    {
    }

    public function retrieve_by_string_fields($array) {
        foreach ($array as $key => $field) {
            $this->$key = $field;
        }
        return $this;
    }

    public function retrieve($array) {
        foreach ($array as $key => $field) {
            $this->$key = $field;
        }
        return $this;
    }
    
    public function save()
    {
        return true;
    }
    
    public function toArray()
    {
        return get_object_vars($this);
    }
    
}