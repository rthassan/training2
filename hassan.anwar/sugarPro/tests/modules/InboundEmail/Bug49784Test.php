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


require_once('include/utils/LogicHook.php');
require_once('modules/InboundEmail/InboundEmail.php');
require_once('modules/Accounts/Account.php');
require_once('modules/Cases/Case.php');
require_once('modules/Emails/Email.php');


/*
 * This simulates the processing of an inbound email that is to be linked to a case.
 * We create a logic hook attached to after_relationship_add, which should only run once.
 * Logic is borrowed heavily from Bug46122Test.php
 * @ticket 49784
 */
class Bug49784Test extends Sugar_PHPUnit_Framework_TestCase
{
    var $hasCustomCasesLogicHookFile = false;
    var $casesHookFile = 'custom/modules/Cases/logic_hooks.php';
    var $casesCountFile = 'custom/modules/Cases/count.php';
    var $user ='';
    var $case ='';
    var $account='';
    var $email ='';

    public function setUp()
    {
        $this->user = SugarTestUserUtilities::createAnonymousUser();
        $GLOBALS['current_user'] = $this->user;

        //Setup logichook files
        if(file_exists($this->casesHookFile))
        {
            $this->hasCustomCasesLogicHookFile = true;
            copy($this->casesHookFile, $this->casesHookFile.'.bak');
        }
        $hook_array['after_relationship_add'][] = Array(1, 'Cases increment count', $this->casesCountFile,'CaseCount', 'countMe');
        $pi = pathinfo($this->casesHookFile);
        SugarAutoLoader::ensureDir($pi['dirname']);
        write_array_to_file("hook_array", $hook_array, $this->casesHookFile);
        SugarAutoLoader::addToMap($this->casesHookFile, false);
        LogicHook::refreshHooks();

        //now  write out the script that the logichook executes.  This will keep track of times called
        global $hookRunCount;
        $hookRunCount = 0;
        $fileCont = '<?php class CaseCount {
            function countMe($bean, $event, $arguments){
                global $hookRunCount;
                if($event =="after_relationship_add" && $arguments["module"]=="Cases" && $arguments["related_module"]=="Emails")
                    $hookRunCount++;
                }}?>';
        file_put_contents($this->casesCountFile, $fileCont);
        SugarAutoLoader::addToMap($this->casesCountFile, false);


    	//setup test account for case
		$this->account = new Account();
        $this->account->name = 'test account for bug 39855';
        $this->account->assigned_user_id = 'SugarUser';
        $this->account->save();

        //create case
        $this->case = new aCase();
        $this->case->name = 'test case for unitTest 49784';
        $this->case->account_id = $this->account->id;
        $this->case->status = 'New';
        $this->case->save();
        //retrieve so we have latest info (case number)
        $this->case->retrieve($this->case->id);


        //create email with case in subject
        $this->email = new Email();
        $this->email->type = 'inbound';
        $this->email->status = 'unread';
        $this->email->from_addr_name = $this->email->cleanEmails("sender@domain.eu");
        $this->email->to_addrs_names = $this->email->cleanEmails("to@domain.eu");
        $this->email->cc_addrs_names = $this->email->cleanEmails("cc@domain.eu");
        $this->email->name = 'RE: [CASE:'.$this->case->case_number.'] '.$this->case->name;
        $this->email->save();

    }

    public function tearDown()
    {
        //Remove the custom logic hook files
        if($this->hasCustomCasesLogicHookFile && file_exists($this->casesHookFile.'.bak'))
        {
            copy($this->casesHookFile.'.bak', $this->casesHookFile);
            unlink($this->casesHookFile.'.bak');
        } else if(file_exists($this->casesHookFile)) {
            SugarAutoLoader::unlink($this->casesHookFile);
        }
        SugarAutoLoader::unlink($this->casesCountFile);
        $GLOBALS['db']->query("delete from emails where id='{$this->email->id}'");
        $GLOBALS['db']->query("delete from accounts where id='{$this->account->id}'");
        $GLOBALS['db']->query("delete from cases where id='{$this->case->id}'");


        unset($GLOBALS['logic_hook']);
        unset($this->account);
        unset($this->case);
        unset($this->email);
        unset($this->user);
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
    }

    public function testCaseHandlingOfInboundEmail()
    {
        //lets make sure the file exists
        $this->assertFileExists($this->casesCountFile, 'file to be run from logic hook that keeps track of execution count was not written');

        //create a new inbound email and call process that links to case
        $i = new InboundEmail();
        $i->handleCaseAssignment($this->email);

        //make sure the logic hook only ran once
        $this->assertEquals(1, $GLOBALS['hookRunCount'], 'Logic hook should only have run once during the inbound email processing.');
    }

}

?>
