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

require_once 'modules/KBContents/KBContentsApiHelper.php';

class KBContentsTest extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * @var KBContentMock
     */
    protected $bean;

    /**
     * Category root node
     *
     * @var CategoryMock $categoryRoot
     */
    protected $categoryRoot;

    public function setUp()
    {
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('app_strings');
        SugarTestHelper::setUp('app_list_strings');
        SugarTestHelper::setUp('moduleList');
        SugarTestHelper::setUp('current_user', array(true, true));
        $this->bean = SugarTestKBContentUtilities::createBean();
        $this->categoryRoot = SugarTestCategoryUtilities::createRootBean();
    }

    public function tearDown()
    {
        SugarTestCategoryUtilities::removeAllCreatedBeans();
        SugarTestKBContentUtilities::removeAllCreatedBeans();
        SugarTestHelper::tearDown();
    }

    public function testFormatForApi()
    {
        $helper = new KBContentsApiHelper(SugarTestRestUtilities::getRestServiceMock());
        $data = $helper->formatForApi($this->bean);
        $lang = $this->bean->getPrimaryLanguage();

        $this->assertEquals($data['name'], $this->bean->name);
        $this->assertEquals($data['language'], $lang['key']);
        $this->assertInternalType('array', $data['attachment_list']);
    }

    public function testKBContentsDefaults()
    {
        $this->assertEquals($this->bean->language, 'en');
        $this->assertEquals($this->bean->status, KBContent::DEFAULT_STATUS);
        $this->assertEquals($this->bean->revision, 1);
    }

    public function testPrimaryLanguage()
    {
        $this->assertEquals(array(
            'label' => 'English',
            'key' => 'en'
        ), $this->bean->getPrimaryLanguage());
    }

    public function testDocumentRelationship()
    {
        $doc = BeanFactory::getBean('KBDocuments');
        $doc->fetch($this->bean->kbdocument_id);
        $this->assertEquals($this->bean->name, $doc->name);
    }

    public function testArticleRelationship()
    {
        $article = BeanFactory::getBean('KBArticles');
        $article->fetch($this->bean->kbarticle_id);
        $this->assertEquals($this->bean->name, $article->name);
    }

    public function testLocalizationsLink()
    {
        $this->bean->load_relationship('localizations');
        $this->assertInstanceOf('LocalizationsLink', $this->bean->localizations);

        $query = new SugarQuery;
        $query->from($this->bean);

        $joinSugarQuery = $this->bean->localizations->buildJoinSugarQuery($query);
        $this->assertInternalType('object', $joinSugarQuery);
    }

    public function testRevisionsLink()
    {
        $this->bean->load_relationship('revisions');
        $this->assertInstanceOf('RevisionsLink', $this->bean->revisions);

        $query = new SugarQuery;
        $query->from($this->bean);

        $joinSugarQuery = $this->bean->revisions->buildJoinSugarQuery($query);
        $this->assertInternalType('object', $joinSugarQuery);
    }

    /**
     * New non-published revision should be active.
     */
    public function testActiveRevision()
    {
        $revisionData = array(
            'kbarticle_id' => $this->bean->kbarticle_id,
            'kbdocument_id' => $this->bean->kbdocument_id,
        );

        $revision1 = SugarTestKBContentUtilities::createBean($revisionData);
        $this->assertEquals($this->bean->active_rev, 0);
        $this->assertEquals($revision1->active_rev, 1);

        $revision2 = SugarTestKBContentUtilities::createBean($revisionData);
        $this->assertEquals($this->bean->active_rev, 0);
        $this->assertEquals($revision1->active_rev, 0);
        $this->assertEquals($revision2->active_rev, 1);
    }

    /**
     * Published revision becomes active.
     */
    public function testPublishedRevisionIsActive()
    {
        $publishStatuses = $this->bean->getPublishedStatuses();
        $revisionData = array(
            'kbarticle_id' => $this->bean->kbarticle_id,
            'kbdocument_id' => $this->bean->kbdocument_id,
        );

        $this->bean->active_rev = 1;
        $this->bean->status = $publishStatuses[0];
        $this->bean->save();

        $draftRevision = SugarTestKBContentUtilities::createBean($revisionData);
        $this->assertEquals($this->bean->active_rev, 1);
        $this->assertEquals($draftRevision->active_rev, 0);

        $publishedRevision = SugarTestKBContentUtilities::createBean(
            $revisionData + array(
                'status' => $publishStatuses[0],
            )
        );
        $this->assertEquals($this->bean->active_rev, 0);
        $this->assertEquals($draftRevision->active_rev, 0);
        $this->assertEquals($publishedRevision->active_rev, 1);
    }

    /**
     * Published article becomes active.
     */
    public function testPublishedBeanIsActive()
    {
        $publishStatuses = $this->bean->getPublishedStatuses();
        $revisionData = array(
            'kbarticle_id' => $this->bean->kbarticle_id,
            'kbdocument_id' => $this->bean->kbdocument_id,
        );

        $this->bean->active_rev = 1;
        $this->bean->save();

        $revision = SugarTestKBContentUtilities::createBean($revisionData);
        $this->assertEquals($this->bean->active_rev, 0);
        $this->assertEquals($revision->active_rev, 1);

        $this->bean->status = $publishStatuses[0];
        $this->bean->save();

        $this->assertEquals($this->bean->active_rev, 1);
        $this->assertEquals($revision->active_rev, 0);
    }

    public function testUpdateCategoryExternalVisibility()
    {
        $subnode = new CategoryMock();
        $subnode->name = 'SugarCategory' . mt_rand();
        $this->categoryRoot->addNodeMock($subnode, 2, 1);
        SugarTestCategoryUtilities::addCreatedBean($subnode->save());

        // Scenario 1: Save not external document, status is default (draft). Expected result: category is not external
        $this->bean->category_id = $subnode->id;
        $this->bean->save();

        $categoryBean = BeanFactory::retrieveBean('Categories', $subnode->id, array(
            'use_cache' => false,
        ));

        $this->assertEquals(0, $categoryBean->is_external);

        // Scenario 2: Save external document, status is default (draft). Expected result: category is not external
        $this->bean->is_external = 1;
        $this->bean->save();

        $categoryBean = BeanFactory::retrieveBean('Categories', $subnode->id, array(
            'use_cache' => false,
        ));

        $this->assertEquals(0, $categoryBean->is_external);

        // Scenario 2: Save external document, status is published. Expected result: category is external
        $this->bean->status = KBContent::ST_PUBLISHED;
        $this->bean->save();

        $categoryBean = BeanFactory::retrieveBean('Categories', $subnode->id, array(
            'use_cache' => false,
        ));

        $this->assertEquals(1, $categoryBean->is_external);

        // Scenario 2: Delete document. Expected result: category is not external
        $this->bean->mark_deleted($this->bean->id);
        $categoryBean = BeanFactory::retrieveBean('Categories', $subnode->id, array(
            'use_cache' => false,
        ));

        $this->assertEquals(0, $categoryBean->is_external);
    }

    /**
     * After Content is approved set Approved By to current user
     */
    public function testApprovedbyAutoset()
    {
        $bean = SugarTestKBContentUtilities::createBean();
        /**
         * this line is needed for getting a valid data
         * from $this->db->getDataChanges in the KBContent::save method
         */
        $bean->loadFromRow($bean->toArray());

        $this->assertNotEquals(KBContent::ST_APPROVED, $bean->status);

        //$user = SugarTestUserUtilities::createAnonymousUser();
        $user = $GLOBALS['current_user'];

        $bean->status = KBContent::ST_APPROVED;
        $bean->save();

        $this->assertEquals(KBContent::ST_APPROVED, $bean->status);
        // approver id should be equal to the current user id
        $this->assertEquals($bean->kbsapprover_id, $user->id);
    }

    /**
     * Test save usefulness on empty bean.
     *
     * @expectedException SugarApiException
     */
    public function testSaveUsefulnessWithBeanWithoutId()
    {
        $this->bean->id = false;
        $this->bean->saveUsefulness();
    }

    /**
     * Test save usefulness on new bean.
     *
     * @expectedException SugarApiException
     */
    public function testSaveUsefulnessWithNewBean()
    {
        $this->bean->new_with_id = true;
        $this->bean->saveUsefulness();
    }

    /**
     * Test of saveUsefulness().
     */
    public function testSaveUsefulness() {

        $beanDateModified = $this->bean->date_modified;
        $beanModifiedBy = $this->bean->modified_by;

        $this->bean->useful = 1;
        $this->bean->notuseful = 0;

        $this->assertTrue($this->bean->update_date_modified);
        $this->assertTrue($this->bean->update_modified_by);

        $this->bean->saveUsefulness();
        $this->assertFalse($this->bean->update_date_modified);
        $this->assertFalse($this->bean->update_modified_by);

        $this->bean->retrieve();
        $this->assertEquals('1', $this->bean->useful);
        $this->assertEquals('0', $this->bean->notuseful);
        $this->assertEquals($beanDateModified, $this->bean->date_modified);
        $this->assertEquals($beanModifiedBy, $this->bean->modified_by);
    }

    /**
     * Test that in saving of new kbcontent we omit usefulness.
     */
    public function testSavingNewBeanOmitUsefulness()
    {
        $bean = SugarTestKBContentUtilities::createBean(array('useful' => 1), false);
        $bean->new_with_id = true;
        SugarTestKBContentUtilities::saveBean($bean);
        $this->assertEquals('0', $bean->useful);
    }

    /**
     * Test that in saving of new kbcontent we omit usefulness.
     */
    public function testSavingExistingBeanOmitUsefulness()
    {
        $this->bean->retrieve(); // to fill bean->fetched_row
        $this->bean->useful = 1;
        $this->bean->save();
        $this->assertEquals('0', $this->bean->useful);
    }
}
