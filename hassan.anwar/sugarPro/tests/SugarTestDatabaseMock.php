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

require_once 'include/database/DBManagerFactory.php';

/**
 * Class SugarTestDatabaseMock
 *
 * This is used for when you want to unit test a specific method, but don't want to interact with the database.
 *
 * Please Use Wisely
 */
class SugarTestDatabaseMock extends DBManager
{
    /**
     * The registered query spies
     *
     * @var array
     */
    protected $query_spies = array();

    /**
     * Any matching rows are placed here
     *
     * @var array
     */
    public $rows = array();
    // not doing prepareds for now
    public $usePreparedStatements = false;

    /**
     * Add a Query Spy
     *
     * When a query is run matching the regex of the $match param, it will return the $rows param or true if the param
     * is left empty
     *
     * @param String $id Id of the Spy
     * @param String $match RegExp for what we want to match on
     * @param array|Closure|boolean $rows What should be returned when we get a match, defaults to `false` which will
     *                                    make the query return boolean `true` if the match is made and not set any
     *                                    rows, if a Closure is passed in, it will call it and return the results
     *                                    if empty, boolean `true` will be returned
     * @return $this
     */
    public function addQuerySpy($id, $match, $rows = false)
    {
        $this->query_spies[$id] = array(
            'match' => $match,
            'rows' => $rows
        );

        return $this;
    }

    /**
     * Remove a Query from the spy list
     *
     * @param string $id Id of the Spy
     * @return $this
     */
    public function deleteQuerySpy($id)
    {
        if (isset($this->query_spies[$id])) {
            unset($this->query_spies[$id]);
        }

        return $this;
    }

    /**
     * Get the Query Run Count for a spy
     *
     * @param String $id Id of the Spy
     * @return bool|integer If the query has been run, return the number of times the spy has been run,
     *                      otherwise return boolean `false`.
     */
    public function getQuerySpyRunCount($id)
    {
        if (isset($this->query_spies[$id]['runCount'])) {
            return $this->query_spies[$id]['runCount'];
        }

        return false;
    }

    /**
     * Parses the query against any registered spies, if one is found, it will parse the associated $rows variable
     * to determine on what it should return, if no spies are matched, it will put an error in the error log and return
     * boolean `false`.
     *
     * @param string $sql SQL Statement to execute
     * @param bool $dieOnError Ignored, True if we want to call die if the query returns errors
     * @param string $msg Ignored, The message to throw when an error happens.
     * @param bool $suppress Ignored, Flag to suppress all error output unless in debug logging mode.
     * @param bool $keepResult Keep query result in the object?
     * @return bool|array If a spy is matched, it will return the contents of the spy's rows, if the rows variable is
     *                    empty, it will return boolean `true`. If no spies are matched, it will return boolean `false`.
     */
    public function query($sql, $dieOnError = false, $msg = '', $suppress = false, $keepResult = false)
    {
        $sql = preg_replace('/\s\s+/', ' ', $sql);
        $matches = array();
        foreach ($this->query_spies as $responseKey => $possibleResponse) {
            if (preg_match($possibleResponse['match'], $sql, $matches)) {
                $response = $possibleResponse;
                break;
            }
        }

        if (!isset($response)) {
            $GLOBALS['log']->fatal(__CLASS__ . " came across a query it wasn't expecting: $sql");
            $this->rows = array();
            return false;
        } else {
            if (isset($this->query_spies[$responseKey]['runCount'])) {
                $this->query_spies[$responseKey]['runCount']++;
            } else {
                $this->query_spies[$responseKey]['runCount'] = 1;
            }
            // if response has rows, return them
            if (isset($response['rows']) && is_array($response['rows'])) {
                $this->rows = $response['rows'];
                return $response['rows'];
            } elseif (is_callable($response['rows'])) {
                $response = $response['rows']();
                if (is_array($response)) {
                    $this->rows = $response;
                }

                return $response;
            }
            return true;
        }

    }

    /**
     * Mocked out to match a query and only return the first column from the first row of the spied query.
     *
     * If the response is not an array, boolean `false` will be returned
     *
     * @param string $sql SQL Statement to execute
     * @param bool $dieOnError Ignored, True if we want to call die if the query returns errors
     * @param string $msg Ignored, The message to throw when an error happens.
     * @return array|bool|mixed
     */
    public function getOne($sql, $dieOnError = false, $msg = '')
    {
        $response = $this->query($sql, $dieOnError, $msg);
        return isset($response[0]) ? array_shift($response[0]) : false;
    }

    /**
     * Mocked out to match a query and only return the first row of the results,
     *
     * If the response is not an array, or the array is empty, boolean `false` will be returned.
     *
     * @param string $sql SQL Statement to execute
     * @param bool $dieOnError Ignored, True if we want to call die if the query returns errors
     * @param string $msg Ignored, The message to throw when an error happens.
     * @param bool $suppress Ignored, Flag to suppress all error output unless in debug logging mode.
     * @return array|bool|mixed
     */
    public function fetchOne($sql, $dieOnError = false, $msg = '', $suppress = false)
    {
        $response = $this->query($sql, $dieOnError, $msg, $suppress);

        // if it's not an array or it's empty, return false
        if (!is_array($response) || empty($response)) {
            return false;
        }
        // return the first record
        return array_shift($response);
    }

    /**
     * Run a Limit Query
     *
     * This will return the sql if $execute is set to `false`, otherwise it will return what is returned from the
     * query() method.
     *
     * This is non-db specific limit query, it adds the MySQL style `LIMIT $start, $offset` params, please keep this
     * in mind when using it to match against.
     *
     * @param string $sql SQL Statement to execute
     * @param int $start Where to start the limit query
     * @param int $count Number to return
     * @param bool $dieOnError  Ignored, True if we want to call die if the query returns errors
     * @param string $msg Ignored, The message to throw when an error happens.
     * @param bool $execute Execute or return SQL?
     * @return bool|array|string
     */
    public function limitQuery($sql, $start, $count, $dieOnError = false, $msg = '', $execute = true)
    {
        $newSQL = $sql." LIMIT ${start},${count}";

        if (!$execute) {
            return $newSQL;
        }

        return $this->query($newSQL, $dieOnError, $msg);
    }

    /**
     * fetchRow Mock
     *
     * Will use the saved rows from the query method and return each row as it's called.  When there are no more rows
     * `false` will be returned
     *
     * @param resource $result Ignored, the resource from the query
     * @return array|mixed
     */
    public function fetchRow($result)
    {
        if (count($this->rows) < 1) {
            return false;
        } else {
            return array_pop($this->rows);
        }
    }

    /**
     * Everything from here on out is just so we are a DBManager, just stubs
     */
    protected function freeDbResult($dbResult) {}
    public function quote($string) {return addslashes($string);}
    public function convert($string, $type, array $additional_parameters = array()) {return $string;}
    public function fromConvert($string, $type) {return $string;}
    public function renameColumnSQL($tablename, $column, $newname) {}
    public function get_indices($tablename) {return array();}
    public function get_columns($tablename) {return array();}
    public function add_drop_constraint($table, $definition, $drop = false) {}
    public function getFieldsArray($result, $make_lower_case = false) {}
    public function getTablesArray() {}
    public function version() {}
    public function tableExists($tableName) {}
    public function connect(array $configOptions = null, $dieOnError = false) {}
    public function createTableSQLParams($tablename, $fieldDefs, $indices) {}
    protected function changeColumnSQL($tablename, $fieldDefs, $action, $ignoreRequired = false) {}
    public function disconnect() {}
    public function lastDbError() {}
    public function validateQuery($query) { return true; }
    public function valid() { return true; }
    public function dbExists($dbname) { return true; }
    public function tablesLike($like) {}
    public function createDatabase($dbname) {}
    public function dropDatabase($dbname) {}
    public function getDbInfo() {}
    public function userExists($username) { return true; }
    public function createDbUser($database_name, $host_name, $user, $password) {}
    public function full_text_indexing_installed() { return true; }
    public function getFulltextQuery($field, $terms, $must_terms = array(), $exclude_terms = array()) {}
    public function installConfig() {}
    public function getFromDummyTable() {}
    public function getGuidSQL() {}
    public function prepareStatement($sql) { return null; }
    public function fetchOneOffset($sql, $offset, $dieOnError = false, $msg = '', $encode = true) {}

    /** {@inheritDoc} */
    protected function get_index_data($table_name = null, $index_name = null)
    {
        return array();
    }
}
