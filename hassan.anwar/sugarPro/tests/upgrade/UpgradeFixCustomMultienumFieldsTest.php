<?php
require_once 'tests/upgrade/UpgradeTestCase.php';
require_once 'upgrade/scripts/post/7_FixCustomMultienumFields.php';

class UpgradeFixCustomMultienumFieldsTest extends UpgradeTestCase
{
    /**
     * @var SugarUpgradeFixCustomMultienumFields
     */
    protected $script;

    /**
     * @var string
     */
    public $metaFolder = 'custom/Extension/modules/Accounts/Ext/Vardefs/';

    /**
     * @var string
     */
    public $metaFileName = 'sugarfield_test_multienum_field.php';

    public function setUp()
    {
        parent::setUp();

        SugarTestHelper::setUp('current_user', array(true, 1));
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('beanFiles');

        $customMultienumMeta = <<<EOQ
<?php
\$dictionary['Accounts']['fields']['test_multienum_field']['default'] = '^^';
\$dictionary['Accounts']['fields']['test_multienum_field']['type'] = 'multienum';
\$dictionary['Accounts']['fields']['test_multienum_field']['dependency'] = '';
EOQ;
        mkdir_recursive($this->metaFolder);
        SugarAutoLoader::put($this->metaFolder . $this->metaFileName, $customMultienumMeta, true);

        $this->upgrader->setVersions(6.7, 'ent', 7.5, 'ent');

        $this->script = $this->getMockBuilder('SugarUpgradeFixCustomMultienumFields')
            ->setConstructorArgs(array($this->upgrader))
            ->setMethods(array('getCustomFieldFiles'))
            ->getMock();

        $this->script->expects($this->any())->method('getCustomFieldFiles')
            ->will(
                $this->returnValue(
                    array(
                        $this->metaFolder . $this->metaFileName,
                    )
                )
            );
    }

    public function tearDown()
    {
        rmdir_recursive($this->metaFolder);
        SugarTestHelper::tearDown();
        parent::tearDown();
    }

    public function testMultienumFieldContainsIsMultiSelect()
    {
        $this->script->run();

        $dictionary = array();
        require $this->metaFolder . $this->metaFileName;

        $this->assertTrue($dictionary['Accounts']['fields']['test_multienum_field']['isMultiSelect']);
    }
}
