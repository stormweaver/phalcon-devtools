<?php

namespace Phalcon\Db;

abstract class Adapter implements \Phalcon\Events\EventsAwareInterface
{
    /**
     * Event Manager
     *
     * @var Phalcon\Events\Manager
     */
    protected $_eventsManager;

    /**
     * Descriptor used to connect to a database
     *
     * @var \stdClass
     */
    protected $_descriptor;

    /**
     * Name of the dialect used
     */
    protected $_dialectType;

    /**
     * Type of database system the adapter is used for
     */
    protected $_type;

    /**
     * Dialect instance
     */
    protected $_dialect;

    /**
     * Active connection ID
     *
     * @var long
     */
    protected $_connectionId;

    /**
     * Active SQL Statement
     *
     * @var string
     */
    protected $_sqlStatement;

    /**
     * Active SQL bound parameter variables
     *
     * @var string
     */
    protected $_sqlVariables;

    /**
     * Active SQL Bind Types
     *
     * @var string
     */
    protected $_sqlBindTypes;

    /**
     * Current transaction level
     */
    protected $_transactionLevel = 0;

    /**
     * Whether the database supports transactions with save points
     */
    protected $_transactionsWithSavepoints = false;

    /**
     * Connection ID
     */
    static protected $_connectionConsecutive = 0;


    /**
     * Name of the dialect used
     */
	public function getDialectType() {}

    /**
     * Type of database system the adapter is used for
     */
	public function getType() {}

    /**
     * Active SQL bound parameter variables
     *
     * @return string 
     */
	public function getSqlVariables() {}

    /**
     * Phalcon\Db\Adapter constructor
     *
     * @param array $descriptor 
     */
	public function __construct(array $descriptor) {}

    /**
     * Sets the event manager
     *
     * @param mixed $eventsManager 
     */
	public function setEventsManager(\Phalcon\Events\ManagerInterface $eventsManager) {}

    /**
     * Returns the internal event manager
     *
     * @return \Phalcon\Events\ManagerInterface 
     */
	public function getEventsManager() {}

    /**
     * Sets the dialect used to produce the SQL
     *
     * @param mixed $dialect 
     */
	public function setDialect(\Phalcon\Db\DialectInterface $dialect) {}

    /**
     * Returns internal dialect instance
     *
     * @return \Phalcon\Db\DialectInterface 
     */
	public function getDialect() {}

    /**
     * Returns the first row in a SQL query result
     * <code>
     * //Getting first robot
     * $robot = $connection->fecthOne("SELECTFROM robots");
     * print_r($robot);
     * //Getting first robot with associative indexes only
     * $robot = $connection->fecthOne("SELECTFROM robots", Phalcon\Db::FETCH_ASSOC);
     * print_r($robot);
     * </code>
     *
     * @param string $sqlQuery 
     * @param int $fetchMode 
     * @param array $bindParams 
     * @param array $bindTypes 
     * @return array 
     */
	public function fetchOne($sqlQuery, $fetchMode = 2, $bindParams = null, $bindTypes = null) {}

    /**
     * Dumps the complete result of a query into an array
     * <code>
     * //Getting all robots with associative indexes only
     * $robots = $connection->fetchAll("SELECTFROM robots", Phalcon\Db::FETCH_ASSOC);
     * foreach ($robots as $robot) {
     * print_r($robot);
     * }
     * //Getting all robots that contains word "robot" withing the name
     * $robots = $connection->fetchAll("SELECTFROM robots WHERE name LIKE :name",
     * Phalcon\Db::FETCH_ASSOC,
     * array('name' => '%robot%')
     * );
     * foreach($robots as $robot){
     * print_r($robot);
     * }
     * </code>
     *
     * @param string $sqlQuery 
     * @param int $fetchMode 
     * @param array $bindParams 
     * @param array $bindTypes 
     * @return array 
     */
	public function fetchAll($sqlQuery, $fetchMode = 2, $bindParams = null, $bindTypes = null) {}

    /**
     * Returns the n'th field of first row in a SQL query result
     * <code>
     * //Getting count of robots
     * $robotsCount = $connection->fetchColumn("SELECT count(*) FROM robots");
     * print_r($robotsCount);
     * //Getting name of last edited robot
     * $robot = $connection->fetchColumn("SELECT id, name FROM robots order by modified desc");
     * print_r($robot);
     * </code>
     *
     * @param string $sqlQuery 
     * @param array $placeholders 
     * @param int|string $column 
     * @return string| 
     */
	public function fetchColumn($sqlQuery, $placeholders = null, $column = 0) {}

    /**
     * Inserts data into a table using custom RBDM SQL syntax
     * <code>
     * //Inserting a new robot
     * $success = $connection->insert(
     * "robots",
     * array("Astro Boy", 1952),
     * array("name", "year")
     * );
     * //Next SQL sentence is sent to the database system
     * INSERT INTO `robots` (`name`, `year`) VALUES ("Astro boy", 1952);
     * </code>
     *
     * @param string|array $table 
     * @param array $values 
     * @param mixed $fields 
     * @param mixed $dataTypes 
     * @param  $array dataTypes
     * @return  
     */
	public function insert($table, $values, $fields = null, $dataTypes = null) {}

    /**
     * Inserts data into a table using custom RBDM SQL syntax
     * Another, more convenient syntax
     * <code>
     * //Inserting a new robot
     * $success = $connection->insert(
     * "robots",
     * array(
     * "name" => "Astro Boy",
     * "year" => 1952
     * )
     * );
     * //Next SQL sentence is sent to the database system
     * INSERT INTO `robots` (`name`, `year`) VALUES ("Astro boy", 1952);
     * </code>
     *
     * @param mixed $table 
     * @param mixed $data 
     * @param mixed $dataTypes 
     * @param  $string table
     * @param  $array dataTypes
     * @return  
     */
	public function insertAsDict($table, $data, $dataTypes = null) {}

    /**
     * Updates data on a table using custom RBDM SQL syntax
     * <code>
     * //Updating existing robot
     * $success = $connection->update(
     * "robots",
     * array("name"),
     * array("New Astro Boy"),
     * "id = 101"
     * );
     * //Next SQL sentence is sent to the database system
     * UPDATE `robots` SET `name` = "Astro boy" WHERE id = 101
     * //Updating existing robot with array condition and $dataTypes
     * $success = $connection->update(
     * "robots",
     * array("name"),
     * array("New Astro Boy"),
     * array(
     * 'conditions' => "id = ?",
     * 'bind' => array($some_unsafe_id),
     * 'bindTypes' => array(PDO::PARAM_INT) //use only if you use $dataTypes param
     * ),
     * array(PDO::PARAM_STR)
     * );
     * </code>
     * Warning! If $whereCondition is string it not escaped.
     *
     * @param string|array $table 
     * @param mixed $fields 
     * @param mixed $values 
     * @param mixed $whereCondition 
     * @param mixed $dataTypes 
     * @param  $array dataTypes
     * @param  $string|array whereCondition
     * @return  
     */
	public function update($table, $fields, $values, $whereCondition = null, $dataTypes = null) {}

    /**
     * Updates data on a table using custom RBDM SQL syntax
     * Another, more convenient syntax
     * <code>
     * //Updating existing robot
     * $success = $connection->update(
     * "robots",
     * array(
     * "name" => "New Astro Boy"
     * ),
     * "id = 101"
     * );
     * //Next SQL sentence is sent to the database system
     * UPDATE `robots` SET `name` = "Astro boy" WHERE id = 101
     * </code>
     *
     * @param mixed $table 
     * @param mixed $data 
     * @param mixed $whereCondition 
     * @param mixed $dataTypes 
     * @param  $string whereCondition
     * @param  $array dataTypes
     * @return  
     */
	public function updateAsDict($table, $data, $whereCondition = null, $dataTypes = null) {}

    /**
     * Deletes data from a table using custom RBDM SQL syntax
     * <code>
     * //Deleting existing robot
     * $success = $connection->delete(
     * "robots",
     * "id = 101"
     * );
     * //Next SQL sentence is generated
     * DELETE FROM `robots` WHERE `id` = 101
     * </code>
     *
     * @param string|array $table 
     * @param string $whereCondition 
     * @param array $placeholders 
     * @param array $dataTypes 
     * @return boolean 
     */
	public function delete($table, $whereCondition = null, $placeholders = null, $dataTypes = null) {}

    /**
     * Gets a list of columns
     *
     * @param	array columnList
     * @return	string
     * @param mixed $columnList 
     * @return string 
     */
	public function getColumnList($columnList) {}

    /**
     * Appends a LIMIT clause to $sqlQuery argument
     * <code>
     * echo $connection->limit("SELECTFROM robots", 5);
     * </code>
     *
     * @param string $sqlQuery 
     * @param int $number 
     * @return string 
     */
	public function limit($sqlQuery, $number) {}

    /**
     * Generates SQL checking for the existence of a schema.table
     * <code>
     * var_dump($connection->tableExists("blog", "posts"));
     * </code>
     *
     * @param string $tableName 
     * @param string $schemaName 
     * @return bool 
     */
	public function tableExists($tableName, $schemaName = null) {}

    /**
     * Generates SQL checking for the existence of a schema.view
     * <code>
     * var_dump($connection->viewExists("active_users", "posts"));
     * </code>
     *
     * @param string $viewName 
     * @param string $schemaName 
     * @return boolean 
     */
	public function viewExists($viewName, $schemaName = null) {}

    /**
     * Returns a SQL modified with a FOR UPDATE clause
     *
     * @param string $sqlQuery 
     * @return string 
     */
	public function forUpdate($sqlQuery) {}

    /**
     * Returns a SQL modified with a LOCK IN SHARE MODE clause
     *
     * @param string $sqlQuery 
     * @return string 
     */
	public function sharedLock($sqlQuery) {}

    /**
     * Creates a table
     *
     * @param	string tableName
     * @param	string schemaName
     * @param	array definition
     * @return	boolean
     * @param string $tableName 
     * @param string $schemaName 
     * @param mixed $definition 
     * @return bool 
     */
	public function createTable($tableName, $schemaName, $definition) {}

    /**
     * Drops a table from a schema/database
     *
     * @param	string tableName
     * @param	boolean ifExists
     * @return	boolean
     * @param string $tableName 
     * @param string $schemaName 
     * @param mixed $ifExists 
     * @return bool 
     */
	public function dropTable($tableName, $schemaName = null, $ifExists = true) {}

    /**
     * Creates a view
     *
     * @param	string tableName
     * @param	array definition
     * @param	string schemaName
     * @return	boolean
     * @param string $viewName 
     * @param mixed $definition 
     * @param mixed $schemaName 
     * @return bool 
     */
	public function createView($viewName, $definition, $schemaName = null) {}

    /**
     * Drops a view
     *
     * @param	string viewName
     * @param	boolean ifExists
     * @return	boolean
     * @param string $viewName 
     * @param string $schemaName 
     * @param mixed $ifExists 
     * @return bool 
     */
	public function dropView($viewName, $schemaName = null, $ifExists = true) {}

    /**
     * Adds a column to a table
     *
     * @param string $tableName 
     * @param string $schemaName 
     * @param mixed $column 
     * @return bool 
     */
	public function addColumn($tableName, $schemaName, \Phalcon\Db\ColumnInterface $column) {}

    /**
     * Modifies a table column based on a definition
     *
     * @param string $tableName 
     * @param string $schemaName 
     * @param mixed $column 
     * @return bool 
     */
	public function modifyColumn($tableName, $schemaName, \Phalcon\Db\ColumnInterface $column) {}

    /**
     * Drops a column from a table
     *
     * @param string $tableName 
     * @param string $schemaName 
     * @param string $columnName 
     * @return bool 
     */
	public function dropColumn($tableName, $schemaName, $columnName) {}

    /**
     * Adds an index to a table
     *
     * @param string $tableName 
     * @param string $schemaName 
     * @param mixed $index 
     * @return bool 
     */
	public function addIndex($tableName, $schemaName, \Phalcon\Db\IndexInterface $index) {}

    /**
     * Drop an index from a table
     *
     * @param string $tableName 
     * @param string $schemaName 
     * @param mixed $indexName 
     * @return bool 
     */
	public function dropIndex($tableName, $schemaName, $indexName) {}

    /**
     * Adds a primary key to a table
     *
     * @param string $tableName 
     * @param string $schemaName 
     * @param mixed $index 
     * @return bool 
     */
	public function addPrimaryKey($tableName, $schemaName, \Phalcon\Db\IndexInterface $index) {}

    /**
     * Drops a table's primary key
     *
     * @param string $tableName 
     * @param string $schemaName 
     * @return bool 
     */
	public function dropPrimaryKey($tableName, $schemaName) {}

    /**
     * Adds a foreign key to a table
     *
     * @param string $tableName 
     * @param string $schemaName 
     * @param mixed $reference 
     * @return bool 
     */
	public function addForeignKey($tableName, $schemaName, \Phalcon\Db\ReferenceInterface $reference) {}

    /**
     * Drops a foreign key from a table
     *
     * @param string $tableName 
     * @param string $schemaName 
     * @param string $referenceName 
     * @return bool 
     */
	public function dropForeignKey($tableName, $schemaName, $referenceName) {}

    /**
     * Returns the SQL column definition from a column
     *
     * @param mixed $column 
     * @return string 
     */
	public function getColumnDefinition(\Phalcon\Db\ColumnInterface $column) {}

    /**
     * List all tables on a database
     * <code>
     * print_r($connection->listTables("blog"));
     * </code>
     *
     * @param string $schemaName 
     * @return array 
     */
	public function listTables($schemaName = null) {}

    /**
     * List all views on a database
     * <code>
     * print_r($connection->listViews("blog"));
     * </code>
     *
     * @param string $schemaName 
     * @return array 
     */
	public function listViews($schemaName = null) {}

    /**
     * Lists table indexes
     * <code>
     * print_r($connection->describeIndexes('robots_parts'));
     * </code>
     *
     * @param	string table
     * @param	string schema
     * @return	Phalcon\Db\Index[]
     * @param string $table 
     * @param mixed $schema 
     */
	public function describeIndexes($table, $schema = null) {}

    /**
     * Lists table references
     * <code>
     * print_r($connection->describeReferences('robots_parts'));
     * </code>
     *
     * @param	string table
     * @param	string schema
     * @return	Phalcon\Db\Reference[]
     * @param string $table 
     * @param string $schema 
     */
	public function describeReferences($table, $schema = null) {}

    /**
     * Gets creation options from a table
     * <code>
     * print_r($connection->tableOptions('robots'));
     * </code>
     *
     * @param	string tableName
     * @param	string schemaName
     * @return	array
     * @param mixed $tableName 
     * @param mixed $schemaName 
     */
	public function tableOptions($tableName, $schemaName = null) {}

    /**
     * Creates a new savepoint
     *
     * @param string $name 
     * @return bool 
     */
	public function createSavepoint($name) {}

    /**
     * Releases given savepoint
     *
     * @param string $name 
     * @return bool 
     */
	public function releaseSavepoint($name) {}

    /**
     * Rollbacks given savepoint
     *
     * @param string $name 
     * @return bool 
     */
	public function rollbackSavepoint($name) {}

    /**
     * Set if nested transactions should use savepoints
     *
     * @param bool $nestedTransactionsWithSavepoints 
     * @return \Phalcon\Db\AdapterInterface 
     */
	public function setNestedTransactionsWithSavepoints($nestedTransactionsWithSavepoints) {}

    /**
     * Returns if nested transactions should use savepoints
     *
     * @return bool 
     */
	public function isNestedTransactionsWithSavepoints() {}

    /**
     * Returns the savepoint name to use for nested transactions
     *
     * @return string 
     */
	public function getNestedTransactionSavepointName() {}

    /**
     * Returns the default identity value to be inserted in an identity column
     * <code>
     * //Inserting a new robot with a valid default value for the column 'id'
     * $success = $connection->insert(
     * "robots",
     * array($connection->getDefaultIdValue(), "Astro Boy", 1952),
     * array("id", "name", "year")
     * );
     * </code>
     *
     * @return \Phalcon\Db\RawValue 
     */
	public function getDefaultIdValue() {}

    /**
     * Check whether the database system requires a sequence to produce auto-numeric values
     *
     * @return bool 
     */
	public function supportSequences() {}

    /**
     * Check whether the database system requires an explicit value for identity columns
     *
     * @return bool 
     */
	public function useExplicitIdValue() {}

    /**
     * Return descriptor used to connect to the active database
     *
     * @return array 
     */
	public function getDescriptor() {}

    /**
     * Gets the active connection unique identifier
     *
     * @return string 
     */
	public function getConnectionId() {}

    /**
     * Active SQL statement in the object
     *
     * @return string 
     */
	public function getSQLStatement() {}

    /**
     * Active SQL statement in the object without replace bound paramters
     *
     * @return string 
     */
	public function getRealSQLStatement() {}

    /**
     * Active SQL statement in the object
     *
     * @return array 
     */
	public function getSQLBindTypes() {}

}
