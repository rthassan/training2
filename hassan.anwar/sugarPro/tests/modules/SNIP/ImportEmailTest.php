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

require_once('data/SugarBean.php');
require_once('modules/SNIP/SugarSNIP.php');
require_once('modules/Emails/Email.php');
require_once('include/TimeDate.php');

/*
 * Tests SNIP's import email feature by calling $snip->importEmail() using dummy data. Does not test object creation.
 */

class ImportEmailTest extends Sugar_PHPUnit_Framework_TestCase {
	private $snip;
	private $date_time_format;

	// store ids of generated objects so we can delete them in tearDown
	private $email_id = '';
	private $meeting_id = '';

	public function testNewEmailWithEvent () {
        $this->markTestIncomplete("This test crashes when not run in isolation");
		// import email through snip
		$file_path = 'tests/modules/SNIP/SampleEvent.ics';

		$email['message']['message_id'] = '10011';
		$email['message']['from_name'] = 'Test Emailer <temailer@sugarcrm.com>';
		$email['message']['description'] = 'Email with event attachment';
		$email['message']['description_html'] = 'Email with <b>event</b> attachment';
		$email['message']['to_addrs'] = 'sarah@example.com';
		$email['message']['cc_addrs'] = 'bob@example.com';
		$email['message']['bcc_addrs'] = 'jim@example.com';
		$email['message']['date_sent'] = '2010-01-01 12:30:00';
		$email['message']['subject'] = 'PHPUnit Test Email with iCal';
		$email['message']['attachments'][] = array('filename' => $file_path, 'content' => base64_encode(file_get_contents($file_path)));
		$email['user'] = 'Administrator';
		$this->snip->importEmail($email);

		// get the email object if it imported correctly
		$e = BeanFactory::getBean('Emails');
		$e->retrieve_by_string_fields(array("message_id" => $email['message']['message_id']));
		$this->assertAttributeNotEmpty("id", $e, "ID is empty!");
		$this->email_id = $e->id;

		// populate the whole bean
        $e->retrieve($e->id);

		// get the meeting
		$meeting = BeanFactory::getBean('Meetings');
        $this->assertTrue(is_a($meeting,'SugarBean'));
		$meeting->retrieve_by_string_fields(array('parent_id' => $e->id, 'parent_type' => $e->module_dir));
		$this->assertTrue(isset($meeting->id) && !empty($meeting->id), 'Unable to retrieve meeting object');
		$this->meeting_id = $meeting->id;

		// check if the values match with the iCal event
		$this->assertEquals('Coffee with Jason', $meeting->name);
		$this->assertEquals('Conference Room - F123, Bldg. 002', $meeting->location);
		$this->assertEquals('Planned', $meeting->status);
		$this->assertEquals('2002-10-28 22:00:00', $GLOBALS['db']->fromConvert($meeting->date_start, 'datetime'));
		$this->assertEquals('2002-10-28 23:00:00', $GLOBALS['db']->fromConvert($meeting->date_end, 'datetime'));
		$this->assertEquals('Emails', $meeting->parent_type);
		$this->assertEquals($email['message']['subject'], $meeting->parent_name);
	}

	public function testNewEmail()
	{
        $this->markTestIncomplete('Needs to be fixed by FRM team.');
		global $current_user;

		// import email through snip
		$email['message']['message_id'] = '12345';
		$email['message']['from_name'] = 'Test Emailer <temailer@sugarcrm.com>';
		$email['message']['description'] = 'This is a test email';
		$email['message']['description_html'] = 'This is a <b>test</b> <u>email</u>';
		$email['message']['to_addrs'] = 'sugar.phone@example.name';
		$email['message']['cc_addrs'] = 'sugar.section.dev@example.net';
		$email['message']['bcc_addrs'] = 'qa.sugar@example.net';
		$email['message']['date_sent'] = '2010-01-01 12:30:00';
		$email['message']['subject'] = 'PHPUnit Test Email';
		$email['user'] = 'Administrator';
		$this->snip->importEmail($email);

		// get the email object if it imported correctly
		$e = BeanFactory::getBean('Emails');
		$e->retrieve_by_string_fields(array("message_id" => $email['message']['message_id']));
		$this->assertAttributeNotEmpty("id", $e, "ID is empty!");
		$this->email_id = $e->id;

		// populate the whole bean
		$e->retrieve($e->id);

        // validate if everything was saved correctly
		$this->assertEquals($email['message']['message_id'], $e->message_id);
		$this->assertEquals($email['message']['from_name'], $e->from_addr_name);
		$this->assertEquals($email['message']['description'], $e->description);
		$this->assertEquals($email['message']['description_html'], $e->description_html);
		$this->assertEquals($email['message']['to_addrs'], $e->to_addrs);
		$this->assertEquals($email['message']['cc_addrs'], $e->cc_addrs);
		$this->assertEquals($email['message']['bcc_addrs'], $e->bcc_addrs);
		$this->assertEquals($email['message']['subject'], $e->name);
		$this->assertEquals(gmdate($this->date_time_format,strtotime($email['message']['date_sent'])), $e->date_sent);
	}

	public function testDescriptionHTML()
	{
        $this->markTestIncomplete('Needs to be fixed by FRM team.');
		global $current_user;

		// import email through snip
		$email['message']['message_id'] = '23456';
		$email['message']['from_name'] = 'Test Emailer <temailer@sugarcrm.com>';
		$email['message']['description_html'] = 'This is a <b>test</b> <u>email</u>';
		$email['message']['description'] = '';
		$email['message']['to_addrs'] = 'sugar.phone@example.name';
		$email['message']['date_sent'] = '2010-01-01 12:30:00';
		$email['message']['subject'] = 'PHPUnit Test Email';
		$email['user'] = 'Administrator';
		$this->snip->importEmail($email);
        $sd = strip_tags($email['message']['description_html']);

		// get the email object if it imported correctly
		$e = BeanFactory::getBean('Emails');
		$e->retrieve_by_string_fields(array("message_id" => $email['message']['message_id']));
		$this->assertAttributeNotEmpty("id", $e, "ID is empty!");
		$this->email_id = $e->id;

		// populate the whole bean
		$e->retrieve($e->id);
		$this->assertEquals($email['message']['message_id'], $e->message_id);
		$this->assertEquals($sd, $e->description, "Bad plaintext description");
		$this->assertEquals($email['message']['description_html'], $e->description_html, "Bad html description");
	}

	public function testExistingEmail ()
	{
        $this->markTestIncomplete('Needs to be fixed by FRM team.');
		// import email through snip
		$email['message']['message_id'] = '2002';
		$email['message']['from_name'] = 'Test Emailer <temailer@sugarcrm.com>';
		$email['message']['description'] = 'Existing email test';
		$email['message']['description_html'] = 'Existing <b>email</b> test';
		$email['message']['to_addrs'] = 'sales.support@example.biz';
		$email['message']['cc_addrs'] = 'sugar.info.the@example.info';
		$email['message']['bcc_addrs'] = '';
		$email['message']['date_sent'] = '2011-06-09 00:01:00';
		$email['message']['subject'] = 'PHPUnit Test Existing Email';
		$email['user'] = 'Administrator';
		$this->snip->importEmail($email);

		// now, create another email with the same message id
		$a_email['message']['message_id'] = '2002';
		$a_email['message']['from_name'] = 'Test Emailer <temailer@sugarcrm.com>';
		$a_email['message']['description'] = 'Another existing email test';
		$a_email['message']['description_html'] = 'Another existing <b>email</b> test';
		$a_email['message']['to_addrs'] = 'support.sugar@example.co.jp';
		$a_email['message']['cc_addrs'] = '';
		$a_email['message']['bcc_addrs'] = 'dev.support@example.tw';
		$a_email['message']['date_sent'] = '2011-09-06 01:13:00';
		$a_email['message']['subject'] = 'PHPUnit Test Another Existing Email';
		$a_email['user'] = 'Administrator';
		$this->snip->importEmail($a_email);

		// now, get the email with the mesage id '2002'
		$e = BeanFactory::getBean('Emails');
		$e->retrieve_by_string_fields(array("message_id" => $email['message']['message_id']));
		$this->assertAttributeNotEmpty("id", $e, "ID is empty!");
		$this->email_id = $e->id;

		// populate the whole bean
		$e->retrieve($e->id);

        // everything should match the content of the first email because the second email should've been rejected
		$this->assertEquals($email['message']['message_id'], $e->message_id);
		$this->assertEquals($email['message']['from_name'], $e->from_addr_name);
		$this->assertEquals($email['message']['description'], $e->description);
		$this->assertEquals($email['message']['description_html'], $e->description_html);
		$this->assertEquals($email['message']['to_addrs'], $e->to_addrs);
		$this->assertEquals($email['message']['cc_addrs'], $e->cc_addrs);
		$this->assertEquals($email['message']['bcc_addrs'], $e->bcc_addrs);
		$this->assertEquals($email['message']['subject'], $e->name);
		$this->assertEquals(gmdate($this->date_time_format,strtotime($email['message']['date_sent'])), $e->date_sent);
	}

	public function testRelateCase()
	{
	    $case = new aCase();
        $case->name = 'PHPUnint test case';
        $case->save();
        $caseid = $case->id;
        $this->assertNotEmpty($caseid);
        $case->retrieve($caseid);
	    $case_num = $case->case_number;

        $macro = str_replace("%1", $case_num, $case->getEmailSubjectMacro());

		// import email through snip
		$email['message']['message_id'] = 'test-sugar-email-case-import'.uniqid();
		$email['message']['from_name'] = 'Test Emailer <temailer@sugarcrm.com>';
		$email['message']['description'] = 'This is a test email';
		$email['message']['description_html'] = 'This is a <b>test</b> <u>email</u>';
		$email['message']['to_addrs'] = 'sugar.phone@example.name';
		$email['message']['cc_addrs'] = 'sugar.section.dev@example.net';
		$email['message']['bcc_addrs'] = 'qa.sugar@example.net';
		$email['message']['date_sent'] = '2010-01-01 12:30:00';
		$email['message']['subject'] = "Re: $macro PHPUnit Test Existing Email";
		$email['user'] = $GLOBALS['current_user']->user_name;
		$this->snip->importEmail($email);

		// get the email object if it imported correctly
		$e = BeanFactory::getBean('Emails');
		$e->retrieve_by_string_fields(array("message_id" => $email['message']['message_id']));
		$this->assertTrue(isset($e->id) && !empty($e->id), 'Unable to retrieve email object');
		$this->email_id = $e->id;

		$case->retrieve($caseid);
		$case->load_relationship("emails");
        $ids = $case->emails->get();
        $this->assertContains($e->id, $ids, "Email not found linked to the case");
	}


	public function setUp () {
	    // setup test user and initiate snip
        SugarTestHelper::setUp("current_user");
        SugarTestHelper::setUp("beanList");
        SugarTestHelper::setUp("beanFiles");
        
		$this->snip = SugarSNIP::getInstance();

		// get configured date format
		$timedate = new TimeDate();
		$this->date_time_format = $timedate->get_date_time_format();
	}

	public function tearDown ()
	{
		// delete emails that were imported
    	$GLOBALS['db']->query("DELETE FROM emails WHERE id = '{$this->email_id}'");
    	$GLOBALS['db']->query("DELETE FROM emails_text WHERE email_id = '{$this->email_id}'");
    	$GLOBALS['db']->query("DELETE FROM cases WHERE name='PHPUnint test case'");

    	// delete other beans
    	if (!empty ($this->meeting_id)) {
	    	$GLOBALS['db']->query("DELETE FROM meetings WHERE id = '{$this->meeting_id}'");
	    	$GLOBALS['db']->query("DELETE FROM meetings_users WHERE meeting_id = '{$this->meeting_id}'");
	    	$GLOBALS['db']->query("DELETE FROM meetings_contacts WHERE meeting_id = '{$this->meeting_id}'");
    	}

		// remove test user
        SugarTestHelper::tearDown();
		unset($this->snip);
		unset($this->email_id);
		unset($this->meeting_id);
	}
}
?>
