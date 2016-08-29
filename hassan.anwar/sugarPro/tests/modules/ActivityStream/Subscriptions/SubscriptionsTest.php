<?php
/**
 * @group ActivityStream
 */
class SubscriptionsTest extends Sugar_PHPUnit_Framework_TestCase
{
    private $user;
    private $record;

    public function setUp()
    {
        parent::setUp();
        SugarTestHelper::setUp('current_user');
        $this->user   = $GLOBALS['current_user'];
        $this->record = self::getUnsavedRecord();
    }

    public function tearDown()
    {
        SugarTestAccountUtilities::removeAllCreatedAccounts();
        SugarTestActivityUtilities::removeAllCreatedActivities();
        BeanFactory::setBeanClass('Activities');
        BeanFactory::setBeanClass('Accounts');
        SugarTestHelper::tearDown();
        parent::tearDown();
    }

    /**
     * @covers Subscription::getSubscribedUsers
     */
    public function testGetSubscribedUsers()
    {
        $kls = BeanFactory::getBeanName('Subscriptions');
        $return = $kls::getSubscribedUsers($this->record);
        $this->assertInternalType('array', $return);
        $this->assertCount(0, $return);

        $kls::subscribeUserToRecord($this->user, $this->record);
        $return = $kls::getSubscribedUsers($this->record);
        $this->assertInternalType('array', $return);
        $this->assertCount(1, $return);
        $this->assertEquals($return[0]['created_by'], $this->user->id);
    }

    /**
     * @covers Subscription::getSubscribedRecords
     */
    public function testGetSubscribedRecords()
    {
        $kls = BeanFactory::getBeanName('Subscriptions');
        $return = $kls::getSubscribedRecords($this->user);
        $this->assertInternalType('array', $return);
        $this->assertCount(0, $return);

        $kls::subscribeUserToRecord($this->user, $this->record);
        $return = $kls::getSubscribedRecords($this->user);
        $this->assertInternalType('array', $return);
        $this->assertCount(1, $return);
        $this->assertEquals($return[0]['parent_id'], $this->record->id);
    }

    /**
     * @covers Subscription::checkSubscription
     */
    public function testCheckSubscription()
    {
        $kls = BeanFactory::getBeanName('Subscriptions');
        $return = $kls::checkSubscription($this->user, $this->record);
        $this->assertNull($return, "A subscription shouldn't exist for a new record.");

        $guid = $kls::subscribeUserToRecord($this->user, $this->record);
        $return = $kls::checkSubscription($this->user, $this->record);
        $this->assertEquals($guid, $return);
    }

    /**
     * @covers Subscription::subscribeUserToRecord
     */
    public function testSubscribeUserToRecord()
    {
        $kls = BeanFactory::getBeanName('Subscriptions');
        $return = $kls::subscribeUserToRecord($this->user, $this->record);
        // Expect a Subscription bean GUID if we're creating the subscription.
        $this->assertInternalType('string', $return);

        $return = $kls::subscribeUserToRecord($this->user, $this->record);
        // Expect false if we cannot add another subscription for the user.
        $this->assertFalse($return);
    }

    /**
     * @covers Subscription::addActivitySubscriptions
     */
    public function testAddActivitySubscriptions()
    {
        $GLOBALS['reload_vardefs'] = true;
        $bean = SugarTestAccountUtilities::createAccount();
        $activity = SugarTestActivityUtilities::createActivity();
        $activity->activity_type = 'create';
        $activity->parent_id = $bean->id;
        $activity->parent_type = 'Accounts';
        $activity->save();

        $data = array(
            'act_id'        => $activity->id,
            'user_partials' => array(
                array(
                    'created_by' => $this->user->id,
                ),
            ),
        );
        $subscriptionsBeanName = BeanFactory::getBeanName('Subscriptions');
        $subscriptionsBeanName::addActivitySubscriptions($data);
        $activity->load_relationship('activities_users');
        $expected = array($this->user->id);
        $actual = $activity->activities_users->get();
        $this->assertEquals($expected, $actual, 'Should have added the user relationship to the activity.');
        unset($GLOBALS['reload_vardefs']);
    }

    private static function getUnsavedRecord()
    {
        // SugarTestAccountUtilities::createAccount saves the bean, which
        // triggers the OOB subscription logic. For that reason, we create our
        // own record and give it an ID.
        $record = new Account();
        $record->id = create_guid();
        return $record;
    }
}
