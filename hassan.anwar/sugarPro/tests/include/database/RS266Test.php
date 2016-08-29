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
 * Test RS266Test
 *
 * This is test for test change column (add,drop,alter) function in MSSQL manager(s) family.
 */
class RS266Test extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * @var MssqlManager
     */
    protected $db;

    /**
     * Test table name.
     *
     * @var string
     */
    protected $tableName = 'RS266Test';

    public function setUp()
    {
        $db = DBManagerFactory::getInstance();

        if(!$db instanceof MssqlManager) {
            $this->markTestSkipped('Skipped');
            return;
        }
        $this->db = $db;
        $this->dropTestTableIfExists();
    }

    /**
     * Drop table if table exists in database.
     */
    private function dropTestTableIfExists()
    {
        $this->db->query("IF OBJECT_ID('{$this->tableName}', 'Table') IS NOT NULL DROP TABLE {$this->tableName}");
    }

    public function tearDown()
    {
        if ($this->db) {
            $this->dropTestTableIfExists();
            $this->db = null;
        }
    }

    /**
     * Gets the columns info
     *
     * @return array
     */
    protected function getColumnsInfo()
    {
        $sql = "SELECT
                   COLUMN_NAME as column_name
                  ,CAST(ORDINAL_POSITION AS INT) as position
                  ,COLUMN_DEFAULT as column_default
                  ,CASE WHEN c.IS_NULLABLE = 'YES' THEN 1 ELSE 0 END AS is_nullable
                  ,DATA_TYPE as data_type
                  ,CASE WHEN ic.object_id IS NULL THEN 0 ELSE 1 END AS identity_column
                  ,CAST(ISNULL(ic.seed_value,0) AS INT) AS identity_seed
                  ,CAST(ISNULL(ic.increment_value,0) AS INT) AS identity_increment
                FROM INFORMATION_SCHEMA.COLUMNS c
                JOIN sys.columns sc ON  c.TABLE_NAME = OBJECT_NAME(sc.object_id) AND c.COLUMN_NAME = sc.Name
                LEFT JOIN sys.identity_columns ic ON c.TABLE_NAME = OBJECT_NAME(ic.object_id) AND c.COLUMN_NAME = ic.Name
                JOIN sys.types st ON COALESCE(c.DOMAIN_NAME,c.DATA_TYPE) = st.name
                LEFT OUTER JOIN sys.objects dobj ON dobj.object_id = sc.default_object_id AND dobj.type = 'D'
                LEFT JOIN sys.computed_columns cc ON c.TABLE_NAME=OBJECT_NAME(cc.object_id) AND sc.column_id=cc.column_id
                WHERE c.TABLE_NAME = '{$this->tableName}'
                ORDER BY c.ORDINAL_POSITION";

        $data = array();
        $result = $this->db->query($sql);

        while(false !== $row = $this->db->fetchRow($result)) {
            $data[$row['column_name']] = $row;
        }
        return $data;
    }

    /**
     * Data provider for add column test.
     *
     * @return array
     */
    public function dataProviderAddColumn()
    {
        return array(
            // with identity
            array(
                array(
                    'name' => 'number',
                    'auto_increment' => true,
                    'isnull' => false,
                    'required' => true,
                    'type' => 'int',
                ),
                array(
                    'identity_column' => 1,
                    'is_nullable' => 0,
                    'data_type' => 'int',
                    'position' => 3,
                ),
            ),
            // without identity
            array(
                array(
                    'name' => 'number',
                    'isnull' => true,
                    'required' => false,
                    'type' => 'int',
                ),
                array(
                    'identity_column' => 0,
                    'is_nullable' => 1,
                    'data_type' => 'int',
                    'position' => 3,
                ),
            ),
        );
    }

    /**
     * Test add column.
     *
     * @dataProvider dataProviderAddColumn
     */
    public function testAddColumn($columnDef, $expected)
    {
        $this->db->query("CREATE TABLE {$this->tableName} (id int NOT NULL, somename varchar NOT NULL)");

        $result = $this->db->addColumn($this->tableName, $columnDef);
        $this->assertNotEmpty($result);

        $info = $this->getColumnsInfo();

        $this->assertArrayHasKey('id', $info);
        $this->assertArrayHasKey('somename', $info);
        $this->assertArrayHasKey('number', $info);

        $this->assertEquals(0, $info['id']['identity_column']);
        $this->assertEquals(0, $info['id']['is_nullable']);
        $this->assertEquals(1, $info['id']['position']);
        $this->assertEquals('int', $info['id']['data_type']);

        $this->assertEquals(0, $info['somename']['identity_column']);
        $this->assertEquals(0, $info['somename']['is_nullable']);
        $this->assertEquals(2, $info['somename']['position']);
        $this->assertEquals('varchar', $info['somename']['data_type']);

        $this->assertEquals($expected['identity_column'], $info['number']['identity_column']);
        $this->assertEquals($expected['is_nullable'], $info['number']['is_nullable']);
        $this->assertEquals($expected['position'], $info['number']['position']);
        $this->assertEquals($expected['data_type'], $info['number']['data_type']);
    }

    /**
     * Data provider for add column test when identity already exists.
     *
     * @return array
     */
    public function dataProviderAddColumnWhenIdentityAlreadyExistsInTable()
    {
        return array(
            // with identity
            array(
                array(
                    'name' => 'number',
                    'isnull' => false,
                    'required' => true,
                    'isnull' => 'false',
                    'type' => 'int',
                    'auto_increment' => 1,
                ),
                array(
                    'identity_column' => 0,
                    'is_nullable' => 0,
                    'data_type' => 'int',
                    'position' => 4,
                ),
            ),
            // without identity
            array(
                array(
                    'name' => 'number',
                    'isnull' => true,
                    'required' => false,
                    'type' => 'int',
                ),
                array(
                    'identity_column' => 0,
                    'is_nullable' => 1,
                    'data_type' => 'int',
                    'position' => 4,
                ),
            ),
        );
    }

    /**
     * Test add column when identity already exists in table.
     *
     * @dataProvider dataProviderAddColumnWhenIdentityAlreadyExistsInTable
     */
    public function testAddColumnWhenIdentityAlreadyExistsInTable($columnDef, $expected)
    {
        $this->db->query("CREATE TABLE {$this->tableName} (id int NOT NULL, somename varchar NOT NULL, somenumber INT NOT NULL IDENTITY(1,1))");

        $result = $this->db->addColumn($this->tableName, $columnDef);
        $this->assertNotEmpty($result);

        $info = $this->getColumnsInfo();

        $this->assertArrayHasKey('id', $info);
        $this->assertArrayHasKey('somename', $info);
        $this->assertArrayHasKey('somenumber', $info);
        $this->assertArrayHasKey('number', $info);

        $this->assertEquals(0, $info['id']['identity_column']);
        $this->assertEquals(0, $info['id']['is_nullable']);
        $this->assertEquals(1, $info['id']['position']);
        $this->assertEquals('int', $info['id']['data_type']);

        $this->assertEquals(0, $info['somename']['identity_column']);
        $this->assertEquals(0, $info['somename']['is_nullable']);
        $this->assertEquals(2, $info['somename']['position']);
        $this->assertEquals('varchar', $info['somename']['data_type']);

        $this->assertEquals(1, $info['somenumber']['identity_column']);
        $this->assertEquals(0, $info['somenumber']['is_nullable']);
        $this->assertEquals(3, $info['somenumber']['position']);
        $this->assertEquals('int', $info['somenumber']['data_type']);

        $this->assertEquals($expected['identity_column'], $info['number']['identity_column']);
        $this->assertEquals($expected['is_nullable'], $info['number']['is_nullable']);
        $this->assertEquals($expected['position'], $info['number']['position']);
        $this->assertEquals($expected['data_type'], $info['number']['data_type']);
    }

    /**
     * Data provider for drop column test
     *
     * @return array
     */
    public function dataProviderDropColumn()
    {
        return array(
            // without identity
            array(
                array(
                    'name' => 'number_nonidentity',
                    'type' => 'int',
                ),
                array(
                    'name' => 'number_identity',
                    'type' => 'int',
                    'is_nullable' => 0,
                    'position' => 3,
                    'identity_column' => 1,
                    'data_type' => 'int',
                ),
            ),
            // with identity
            array(
                array(
                    'name' => 'number_identity',
                    'type' => 'int',
                ),
                array(
                    'name' => 'number_nonidentity',
                    'type' => 'int',
                    'is_nullable' => 0,
                    'position' => 3,
                    'identity_column' => 0,
                    'data_type' => 'int',
                ),
            ),
        );
    }

    /**
     * Test drop column
     *
     * @dataProvider dataProviderDropColumn
     */
    public function testDropColumn($columnDef, $expected)
    {
        $this->db->query("CREATE TABLE {$this->tableName} (id int NOT NULL, somename varchar NOT NULL, number_nonidentity INT NOT NULL, number_identity INT NOT NULL IDENTITY(1,1))");

        $sql = $this->db->dropColumnSQL($this->tableName, $columnDef);
        $result = $this->db->query($sql, true, "Error deleting column(s) on table: {$this->tableName}:");

        $this->assertNotEmpty($result);

        $info = $this->getColumnsInfo();

        $this->assertArrayHasKey('id', $info);
        $this->assertArrayHasKey('somename', $info);
        $this->assertArrayHasKey($expected['name'], $info);
        $this->assertArrayNotHasKey($columnDef['name'], $info);

        $this->assertEquals(0, $info['id']['identity_column']);
        $this->assertEquals(0, $info['id']['is_nullable']);
        $this->assertEquals(1, $info['id']['position']);
        $this->assertEquals('int', $info['id']['data_type']);

        $this->assertEquals(0, $info['somename']['identity_column']);
        $this->assertEquals(0, $info['somename']['is_nullable']);
        $this->assertEquals(2, $info['somename']['position']);
        $this->assertEquals('varchar', $info['somename']['data_type']);

        $this->assertEquals($expected['identity_column'], $info[$expected['name']]['identity_column']);
        $this->assertEquals($expected['is_nullable'], $info[$expected['name']]['is_nullable']);
        $this->assertEquals($expected['position'], $info[$expected['name']]['position']);
        $this->assertEquals($expected['data_type'], $info[$expected['name']]['data_type']);
    }

    /**
     * Test alter column when change only data type.
     */
    public function testAlterColumnWhenChangeOnlyDataTypeWithoutIdentity()
    {
        // create test table
        $this->db->query("CREATE TABLE {$this->tableName} (id int NOT NULL PRIMARY KEY, somename varchar NOT NULL, number INT NOT NULL IDENTITY(1,1))");
        $demoData = array(
            array(
                'id' => 4353253,
                'somename' => 'blabla',
            ),
            array(
                'id' => 76865,
                'somename' => 'sfdgdfgsd',
            ),
            array(
                'id' => 1809897,
                'somename' => 'sfgsfsasd dsaf',
            ),
        );
        // create test data
        foreach($demoData as $demo) {
            $sql = "INSERT INTO {$this->tableName} (id, somename) VALUES ({$demo['id']}, '{$demo['somename']}')";
            $this->db->query($sql);
        }

        $data = array();
        $result = $this->db->query("SELECT * FROM {$this->tableName}");

        while(false !== $row = $this->db->fetchRow($result)) {
            $data[$row['id']] = $row;
        }

        $result = $this->db->alterColumn($this->tableName, array(
            'name' => 'number',
            'type' => 'bigint',
            'auto_increment' => 1,
        ));

        $this->assertNotEmpty($result);

        $info = $this->getColumnsInfo();

        // checking table structure
        $this->assertArrayHasKey('id', $info);
        $this->assertArrayHasKey('somename', $info);
        $this->assertArrayHasKey('number', $info);

        $this->assertEquals(0, $info['id']['identity_column']);
        $this->assertEquals(0, $info['id']['is_nullable']);
        $this->assertEquals(1, $info['id']['position']);
        $this->assertEquals('int', $info['id']['data_type']);

        $this->assertEquals(0, $info['somename']['identity_column']);
        $this->assertEquals(0, $info['somename']['is_nullable']);
        $this->assertEquals(2, $info['somename']['position']);
        $this->assertEquals('varchar', $info['somename']['data_type']);

        $this->assertEquals(1, $info['number']['identity_column']); // Important! Must be identity
        $this->assertEquals(0, $info['number']['is_nullable']);
        $this->assertEquals(3, $info['number']['position']);
        $this->assertEquals('bigint', $info['number']['data_type']);

        // checking data after alter table
        $result = $this->db->query("SELECT * FROM {$this->tableName}");

        while(false !== $row = $this->db->fetchRow($result)) {
            $this->assertArrayHasKey($row['id'], $data);
            $this->assertEquals($data[$row['id']]['somename'], $row['somename']);
            $this->assertEquals($data[$row['id']]['number'], $row['number']);
        }
    }

    /**
     * Test alter column when change only data type.
     */
    public function testAlterColumnWhenDropIdentity()
    {
        // create test table
        $this->db->query("CREATE TABLE {$this->tableName} (id int NOT NULL PRIMARY KEY, somename varchar NOT NULL, number INT NOT NULL IDENTITY(1,1))");
        $demoData = array(
            array(
                'id' => 4353253,
                'somename' => 'blabla',
            ),
            array(
                'id' => 76865,
                'somename' => 'sfdgdfgsd',
            ),
            array(
                'id' => 1809897,
                'somename' => 'sfgsfsasd dsaf',
            ),
        );
        // create test data
        foreach($demoData as $demo) {
            $sql = "INSERT INTO {$this->tableName} (id, somename) VALUES ({$demo['id']}, '{$demo['somename']}')";
            $this->db->query($sql);
        }

        $data = array();
        $result = $this->db->query("SELECT * FROM {$this->tableName}");

        while(false !== $row = $this->db->fetchRow($result)) {
            $data[$row['id']] = $row;
        }

        $result = $this->db->alterColumn($this->tableName, array(
            'name' => 'number',
            'type' => 'bigint',
            'auto_increment' => 0,
            'required' => true,
            'isnull' => false,
        ));

        $this->assertNotEmpty($result);

        $info = $this->getColumnsInfo();

        // checking table structure
        $this->assertArrayHasKey('id', $info);
        $this->assertArrayHasKey('somename', $info);
        $this->assertArrayHasKey('number', $info);

        $this->assertEquals(0, $info['id']['identity_column']);
        $this->assertEquals(0, $info['id']['is_nullable']);
        $this->assertEquals(1, $info['id']['position']);
        $this->assertEquals('int', $info['id']['data_type']);

        $this->assertEquals(0, $info['somename']['identity_column']);
        $this->assertEquals(0, $info['somename']['is_nullable']);
        $this->assertEquals(2, $info['somename']['position']);
        $this->assertEquals('varchar', $info['somename']['data_type']);

        $this->assertEquals(0, $info['number']['identity_column']); // Important! Must not be identity
        $this->assertEquals(0, $info['number']['is_nullable']);
        $this->assertEquals(3, $info['number']['position']);
        $this->assertEquals('bigint', $info['number']['data_type']);

        // checking data after alter table
        $result = $this->db->query("SELECT * FROM {$this->tableName}");

        while(false !== $row = $this->db->fetchRow($result)) {
            $this->assertArrayHasKey($row['id'], $data);
            $this->assertEquals($data[$row['id']]['somename'], $row['somename']);
            $this->assertEquals($data[$row['id']]['number'], $row['number']);
        }
    }

    /**
     * Test alter column when change only data type.
     */
    public function testAlterColumnWhenCreateIdentity()
    {
        // create test table
        $this->db->query("CREATE TABLE {$this->tableName} (id int NOT NULL PRIMARY KEY, somename varchar NOT NULL, number INT NOT NULL)");
        $demoData = array(
            array(
                'id' => 4353253,
                'somename' => 'blabla',
                'number' => 1,
            ),
            array(
                'id' => 76865,
                'somename' => 'sfdgdfgsd',
                'number' => 2,
            ),
            array(
                'id' => 1809897,
                'somename' => 'sfgsfsasd dsaf',
                'number' => 3,
            ),
        );
        // create test data
        foreach($demoData as $demo) {
            $sql = "INSERT INTO {$this->tableName} (id, somename, number)
                VALUES ({$demo['id']}, '{$demo['somename']}', {$demo['number']})";
            $this->db->query($sql);
        }

        $data = array();
        $result = $this->db->query("SELECT * FROM {$this->tableName}");

        while(false !== $row = $this->db->fetchRow($result)) {
            $data[$row['id']] = $row;
        }

        $result = $this->db->alterColumn($this->tableName, array(
            'name' => 'number',
            'type' => 'bigint',
            'auto_increment' => 1,
            'required' => true,
            'isnull' => false,
        ));

        $this->assertNotEmpty($result);

        $info = $this->getColumnsInfo();

        // checking table structure
        $this->assertArrayHasKey('id', $info);
        $this->assertArrayHasKey('somename', $info);
        $this->assertArrayHasKey('number', $info);

        $this->assertEquals(0, $info['id']['identity_column']);
        $this->assertEquals(0, $info['id']['is_nullable']);
        $this->assertEquals(1, $info['id']['position']);
        $this->assertEquals('int', $info['id']['data_type']);

        $this->assertEquals(0, $info['somename']['identity_column']);
        $this->assertEquals(0, $info['somename']['is_nullable']);
        $this->assertEquals(2, $info['somename']['position']);
        $this->assertEquals('varchar', $info['somename']['data_type']);

        $this->assertEquals(1, $info['number']['identity_column']); // Important! Must not be identity
        $this->assertEquals(0, $info['number']['is_nullable']);
        $this->assertEquals(3, $info['number']['position']);
        $this->assertEquals('bigint', $info['number']['data_type']);

        // checking data after alter table
        $result = $this->db->query("SELECT * FROM {$this->tableName}");

        while(false !== $row = $this->db->fetchRow($result)) {
            $this->assertArrayHasKey($row['id'], $data);
            $this->assertEquals($data[$row['id']]['somename'], $row['somename']);
            $this->assertEquals($data[$row['id']]['number'], $row['number']);
        }
    }

    /**
     * Test alter column when create a identity on table where identity already exists on other field.
     */
    public function testAlterColumnWhenCreateIdentityOnTableWithIdentity()
    {
        // create test table
        $this->db->query("CREATE TABLE {$this->tableName} (id int NOT NULL PRIMARY KEY, somename varchar NOT NULL, number INT NOT NULL, number_identity INT NOT NULL IDENTITY(1,1))");
        $demoData = array(
            array(
                'id' => 4353253,
                'somename' => 'blabla',
                'number' => 1,
            ),
            array(
                'id' => 76865,
                'somename' => 'sfdgdfgsd',
                'number' => 3,
            ),
            array(
                'id' => 1809897,
                'somename' => 'sfgsfsasd dsaf',
                'number' => 3,
            ),
        );
        // create test data
        foreach($demoData as $demo) {
            $sql = "INSERT INTO {$this->tableName} (id, somename, number) VALUES ({$demo['id']}, '{$demo['somename']}', {$demo['number']})";
            $this->db->query($sql);
        }

        $data = array();
        $result = $this->db->query("SELECT * FROM {$this->tableName}");

        while(false !== $row = $this->db->fetchRow($result)) {
            $data[$row['id']] = $row;
        }

        $result = $this->db->alterColumn($this->tableName, array(
            'name' => 'number',
            'type' => 'bigint',
            'auto_increment' => 1,
            'required' => true,
            'isnull' => false,
        ));

        $this->assertNotEmpty($result);

        $info = $this->getColumnsInfo();

        // checking table structure
        $this->assertArrayHasKey('id', $info);
        $this->assertArrayHasKey('somename', $info);
        $this->assertArrayHasKey('number', $info);
        $this->assertArrayHasKey('number_identity', $info);

        $this->assertEquals(0, $info['id']['identity_column']);
        $this->assertEquals(0, $info['id']['is_nullable']);
        $this->assertEquals(1, $info['id']['position']);
        $this->assertEquals('int', $info['id']['data_type']);

        $this->assertEquals(0, $info['somename']['identity_column']);
        $this->assertEquals(0, $info['somename']['is_nullable']);
        $this->assertEquals(2, $info['somename']['position']);
        $this->assertEquals('varchar', $info['somename']['data_type']);

        $this->assertEquals(0, $info['number']['identity_column']);
        $this->assertEquals(0, $info['number']['is_nullable']);
        $this->assertEquals(3, $info['number']['position']);
        $this->assertEquals('bigint', $info['number']['data_type']);

        $this->assertEquals(1, $info['number_identity']['identity_column']);
        $this->assertEquals(0, $info['number_identity']['is_nullable']);
        $this->assertEquals(4, $info['number_identity']['position']);
        $this->assertEquals('int', $info['number_identity']['data_type']);

        // checking data after alter table
        $result = $this->db->query("SELECT * FROM {$this->tableName}");

        while(false !== $row = $this->db->fetchRow($result)) {
            $this->assertArrayHasKey($row['id'], $data);
            $this->assertEquals($data[$row['id']]['somename'], $row['somename']);
            $this->assertEquals($data[$row['id']]['number'], $row['number']);
        }
    }

    /**
     * Data provider for testAlterColumnAddDefaultValue
     *
     * @return array
     */
    public function alterColumnAddDefaultValueDataProvider()
    {
        return array(
            // for non-identical column
            array(
                array(
                    'name' => 'number',
                    'type' => 'bigint',
                    'default' => 20,
                    'required' => true,
                    'isnull' => false,
                ),
                array(
                    'number' => array(
                        'identity_column' => 0,
                        'is_nullable' => 0,
                        'data_type' => 'bigint',
                        'column_default' => '((20))',
                    ),
                ),
            ),
            // add/modify default value
            array(
                array(
                    'name' => 'number',
                    'type' => 'bigint',
                    'default' => 70,
                    'required' => true,
                    'isnull' => false,
                ),
                array(
                    'number' => array(
                        'identity_column' => 0,
                        'is_nullable' => 0,
                        'data_type' => 'bigint',
                        'column_default' => '((70))',
                    ),
                ),
            ),
            // when column identity
            array(
                array(
                    'name' => 'number_identity',
                    'type' => 'bigint',
                    'auto_increment' => 1,
                    'default' => 40,
                    'required' => true,
                    'isnull' => false,
                ),
                array(
                    'number_identity' => array(
                        'identity_column' => 1,
                        'is_nullable' => 0,
                        'data_type' => 'bigint',
                        'column_default' => null,
                    ),
                ),
            ),
            // when column was identity
            array(
                array(
                    'name' => 'number_identity',
                    'type' => 'bigint',
                    'auto_increment' => 0,
                    'default' => 40,
                    'required' => true,
                    'isnull' => false,
                ),
                array(
                    'number_identity' => array(
                        'identity_column' => 0,
                        'is_nullable' => 0,
                        'data_type' => 'bigint',
                        'column_default' => '((40))',
                    ),
                ),
            ),
        );
    }

    /**
     * Test alter column when add/change default value.
     *
     * @dataProvider alterColumnAddDefaultValueDataProvider
     */
    public function testAlterColumnAddDefaultValue(array $def, array $expected)
    {
        // create test table
        $this->db->query("CREATE TABLE {$this->tableName} (id int NOT NULL PRIMARY KEY, somename varchar NOT NULL, number INT NOT NULL, number_identity INT NOT NULL IDENTITY(1,1))");

        $result = $this->db->alterColumn($this->tableName, $def);

        $this->assertNotEmpty($result);

        $info = $this->getColumnsInfo();

        foreach($expected as $columnName => $expectedAttrs) {
            $this->assertArrayHasKey($columnName, $info);
            foreach($expectedAttrs as $expectedKey => $expectedValue) {
                $this->assertArrayHasKey($expectedKey, $info[$columnName]);
                $this->assertEquals($expectedValue, $info[$columnName][$expectedKey]);
            }
        }
    }

    /**
     * Data provider for alterColumnDropDefaultValueDataProvider.
     */
    public function alterColumnDropDefaultValueDataProvider()
    {
        return array(
            // number
            array(
                array(
                    'name' => 'number',
                    'type' => 'bigint',
                    'required' => true,
                    'isnull' => false,
                ),
                array(
                    'number' => array(
                        'identity_column' => 0,
                        'is_nullable' => 0,
                        'data_type' => 'bigint',
                        'column_default' => null,
                    ),
                ),
            ),
            // string
            array(
                array(
                    'name' => 'somename',
                    'type' => 'varchar',
                    'required' => true,
                    'isnull' => false,
                ),
                array(
                    'somename' => array(
                        'is_nullable' => 0,
                        'data_type' => 'nvarchar',
                        'column_default' => null,
                    ),
                ),
            ),
        );
    }

    /**
     * Test alter column when drop default value.
     *
     * @dataProvider alterColumnDropDefaultValueDataProvider
     */
    public function testAlterColumnDropDefaultValue(array $def, array $expected)
    {
        // create test table
        $this->db->query("CREATE TABLE {$this->tableName} (id int NOT NULL PRIMARY KEY, somename nvarchar NOT NULL DEFAULT 'abc', number INT NOT NULL DEFAULT 200, number_identity INT NOT NULL IDENTITY(1,1))");

        $result = $this->db->alterColumn($this->tableName, $def);

        $this->assertNotEmpty($result);

        $info = $this->getColumnsInfo();

        foreach($expected as $columnName => $expectedAttrs) {
            $this->assertArrayHasKey($columnName, $info);
            foreach($expectedAttrs as $expectedKey => $expectedValue) {
                $this->assertArrayHasKey($expectedKey, $info[$columnName]);
                $this->assertEquals($expectedValue, $info[$columnName][$expectedKey]);
            }
        }
    }
}
