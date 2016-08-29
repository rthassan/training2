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
 
require_once 'modules/Emails/Email.php';

class SugarTestEmailUtilities
{
    private static $_createdEmails = array();

    private function __construct() {}

    public static function createEmail($id = '', $override = array()) 
    {
        global $timedate;
        
        $time = mt_rand();
    	$name = 'SugarEmail';
    	$email = new Email();
        $email->name = $name . $time;
        $email->type = 'out';
        $email->status = 'sent';
        $email->date_sent = $timedate->to_display_date_time(gmdate("Y-m-d H:i:s", (gmmktime() - (3600 * 24 * 2) ))) ; // Two days ago
        if(!empty($id))
        {
            $email->new_with_id = true;
            $email->id = $id;
        }
        foreach($override as $key => $value)
        {
            $email->$key = $value;
        }
        $email->save();
        if(!empty($override['parent_id']) && !empty($override['parent_type']))
        {
            self::createEmailsBeansRelationship($email->id, $override['parent_type'], $override['parent_id']);
        }
        self::$_createdEmails[] = $email;
        return $email;
    }

    public static function removeAllCreatedEmails() 
    {
        $email_ids = self::getCreatedEmailIds();
        $GLOBALS['db']->query('DELETE FROM emails WHERE id IN (\'' . implode("', '", $email_ids) . '\')');
        self::removeCreatedEmailBeansRelationships();
        self::$_createdEmails = array();
    }
    
    private static function createEmailsBeansRelationship($email_id, $parent_type, $parent_id)
    {
        $unique_id = create_guid();
        $GLOBALS['db']->query("INSERT INTO emails_beans ( id, email_id, bean_id, bean_module, date_modified, deleted ) ".
							  "VALUES ( '{$unique_id}', '{$email_id}', '{$parent_id}', '{$parent_type}', '".gmdate('Y-m-d H:i:s')."', 0)");
    }
    
    private static function removeCreatedEmailBeansRelationships(){
    	$email_ids = self::getCreatedEmailIds();
        $GLOBALS['db']->query('DELETE FROM emails_beans WHERE email_id IN (\'' . implode("', '", $email_ids) . '\')');
    }
    
    public static function getCreatedEmailIds() 
    {
        $email_ids = array();
        foreach (self::$_createdEmails as $email) {
            $email_ids[] = $email->id;
        }
        return $email_ids;
    }

    public static function setCreatedEmail($ids)
    {
        $ids = is_array($ids) ? $ids : array($ids);
        foreach ( $ids as $id )
        {
            $email = new Email();
            $email->id = $id;
            self::$_createdEmails[] = $email;
        }
    }
}
?>