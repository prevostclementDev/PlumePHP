<?php

namespace system;

use PDO;
use PDOStatement;

/**
 * This abstract base model class provides a foundation for performing database operations within the PlumePHP framework.
 * It facilitates database connections, table management, and common CRUD operations for project models.
 */
abstract class baseModel {

    protected ?PDO $cursor;

    private string $bindWhere = '';
    private array $whereBindValue = [];
    private string $whereOperator = 'AND';

    public string $table;

    public array $field;

    public array $errorInfo = [];

    /**
     * Constructor for the baseModel class.
     *
     * Initializes the database connection using PDO.
     */
    public function __construct(){
        $this->cursor = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    }

    /**
     * Find all records in the database table.
     *
     * @param string $select Columns to select (default is '*').
     * @param string $orderBy Optional ORDER BY clause (default is 'id DESC').
     * @return bool|array Returns an array of records or false if no records are found.
     */
    public function findAll(String $select = '*',String $orderBy = 'id DESC'): bool|array {

        $reqs = $this->includeWhere(
            'SELECT '.$select.' FROM '.$this->table,
            ' ORDER BY '.$orderBy.' ;'
        );

        if(is_string($reqs)) {
            $reqs = $this->cursor->prepare($reqs);
        }

        return $this->exec($reqs);
    }

    /**
     * Find a record by its ID or using a custom WHERE clause.
     *
     * @param int $id The ID of the record (or null to use WHERE clause).
     * @param string $select Columns to select (default is '*').
     * @return array|null Returns a single record as an array or null if not found.
     */
    public function find(Int $id,String $select = '*'): ?array {

        $reqs = $this->includeWhere('SELECT '.$select.' FROM '.$this->table);

        if(is_string($reqs) ) {
            $reqs.= ' WHERE id = :id ;';

            $reqs = $this->cursor->prepare($reqs);

            $reqs->bindValue(':id',$id);

        }

        return $this->exec($reqs);
    }

    /**
     * Save a record in the database.
     *
     * @param int|null $id The ID of the record to update (or null to insert a new record).
     * @return int Returns the ID of the inserted or updated record.
     */
    public function save(Int $id = null): int {

        if($id === null) {
            $strInsert = '';
            $strValue = '';

            foreach ($this->field as $item => $value) {
                if($value != '') {
                    $strInsert.=$item.',';
                    $strValue.=':'.$item.',';
                }
            }

            $reqs = 'INSERT INTO '.$this->table.' ('.substr($strInsert,0,-1).') VALUES ('.substr($strValue,0,-1).')';
        }
        else {
            $setStr = '';
            foreach ($this->field as $item => $value) {
                if($value != '') {
                    $setStr .= $item.' = :'.$item.',';
                }
            }
            $reqs = 'UPDATE '.$this->table.' SET '.substr($setStr,0,-1).' WHERE id = '.$id;
        }

        $reqs = $this->cursor->prepare($reqs);

        foreach ($this->field as $item => $value) {
            if($value != '') {
                $reqs->bindValue(':'.$item,$value);
            }
        }

        $reqs->execute();
        return $this->cursor->lastInsertId($this->table);

    }

    /**
     * Delete a record from the database.
     *
     * @param int $id The ID of the record to delete.
     * @return bool|int Returns true for a successful delete or false otherwise.
     */
    public function delete(Int $id): bool|int {

        $reqs = $this->includeWhere('DELETE FROM '.$this->table);

        if(is_string($reqs) ) {
            $reqs.= ' WHERE id = :id ;';

            $reqs = $this->cursor->prepare($reqs);

            $reqs->bindValue(':id',$id);

        }

        return $reqs->execute();
    }

    /**
     * Update the model's field values with a given array.
     *
     * @param array $field An array of field values to update.
     */
    public function savePost(Array $field): void {
        foreach ($field as $field_item => $field_value) {
            if(isset($this->field[$field_item])) {
                $this->field[$field_item] = $field_value;
            }
        }
    }

    /**
     * Construct and add a conditional clause to the SQL query using a specified field, value, and operator.
     *
     * @param string $key The name of the field to compare.
     * @param mixed $value The value to compare with the field.
     * @param string $operator The comparison operator (e.g., '=', '<', '>', etc.).
     * @return self Returns the current object instance, allowing method chaining.
     */
    public function where(string $key,mixed $value,string $operator = '=') : self {

        // Check if a previous WHERE clause exists and add the logical operator if needed
        if ($this->bindWhere != '') {
            $this->bindWhere .= ' ' . $this->whereOperator . ' ';
        }

        // Build the condition by appending the field name, operator, and a bound parameter (e.g., key = :key)
        $this->bindWhere .= $key . $operator . ':' . $key;

        // Store the parameter value in the whereBindValue array
        $this->whereBindValue[':' . $key] = $value;

        // Return the object instance to allow method chaining
        return $this;
    }

    /**
     * Set the logical operator (AND or OR) to combine conditions in a WHERE clause.
     *
     * @param string $operator The logical operator to use (e.g., 'AND' or 'OR').
     * @return self Returns the current object instance, allowing method chaining.
     */
    public function whereOperator(string $operator) : self {
        $this->whereOperator = $operator;
        return $this;
    }

    /**
     * Set the table name for the query or specify the table from which data will be selected.
     *
     * @param string|null $from The name of the table or null to keep the existing table name.
     * @return self Returns the current object instance, allowing method chaining.
     */
    public function from(?string $from = null) : self {
        if($from != null) {
            $this->table = $from;
        }
        return $this;
    }

    /**
     * Construct and include a WHERE clause in the SQL query.
     *
     * @param string $reqs The SQL query to which the WHERE clause is added.
     * @param string|null $includeAfter Additional clauses or conditions to append after the WHERE clause.
     * @return string|PDOStatement The modified SQL query or prepared PDOStatement.
     */
    protected function includeWhere(string $reqs,?string $includeAfter = null): string|PDOStatement {

        $returnToInclude = '';

        // Check if a WHERE clause has been constructed
        if ($this->bindWhere != '') {
            // Append the WHERE clause to the query
            $reqs .= ' WHERE ' . $this->bindWhere;

            // Add any additional clauses or conditions specified after the WHERE clause
            if (is_string($includeAfter)) {
                $reqs .= $includeAfter;
            }

            // Prepare the query for execution
            $reqs = $this->cursor->prepare($reqs);

            // Bind values to the query's parameters
            if (!empty($this->whereBindValue)) {
                foreach ($this->whereBindValue as $binder => $value) {
                    $reqs->bindValue($binder, $value);
                }
            }
        }

        // Append any additional clauses specified after the WHERE clause, if necessary
        if (is_string($reqs) && is_string($includeAfter)) {
            $reqs .= $includeAfter;
        }

        // Return the prepared query or query string
        return $reqs;


    }

    /**
     * Execute a prepared SQL query and retrieve results.
     *
     * @param PDOStatement $PDOStatement The prepared SQL query to execute.
     * @param bool $all If true, returns query results as an array of associative arrays; if false, returns a single associative array.
     * @return bool|array The query results or false in case of an error.
     */
    protected function exec(PDOStatement $PDOStatement, bool $all = false): bool|array {
        // Execute the prepared query
        $PDOStatement->execute();

        // Store error information
        $this->errorInfo = $PDOStatement->errorInfo();

        // Return the query results as an array of associative arrays (if $all is true)
        // or as a single associative array (if $all is false)
        if ($all) {
            return $PDOStatement->fetchAll(PDO::FETCH_ASSOC);
        }

        return $PDOStatement->fetch(PDO::FETCH_ASSOC);
    }

}

