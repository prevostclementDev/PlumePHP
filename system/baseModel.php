<?php

namespace system;

use PDO;

/**
 * This abstract base model class provides a foundation for performing database operations within the PlumePHP framework.
 * It facilitates database connections, table management, and common CRUD operations for project models.
 */
abstract class baseModel {

    protected ?PDO $cursor;
    public string $table;
    public array $field;

    /**
     * Constructor for the baseModel class.
     *
     * Initializes the database connection using PDO.
     */
    public function __construct(){
        $this->cursor = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    }

    /**
     * Set the table name for the model.
     *
     * @param string $table The name of the database table.
     * @return baseModel The current instance of the baseModel class.
     */
    public function setTable(string $table): baseModel
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Set the fields for the model.
     *
     * @param array $field An array of fields for the database table.
     * @return baseModel The current instance of the baseModel class.
     */
    public function setField(Array $field): baseModel
    {
        $this->field = $field;
        return $this;
    }

    /**
     * Find all records in the database table.
     *
     * @param string|null $where Optional WHERE clause.
     * @param string $select Columns to select (default is '*').
     * @param string|null $from Optional table name.
     * @param string $orderBy Optional ORDER BY clause (default is 'id DESC').
     * @return bool|array Returns an array of records or false if no records are found.
     */
    public function findAll(?String $where = null, String $select = '*',?String $from = null,String $orderBy = 'id DESC'): bool|array {
        if($from === null) {
            $from = $this->table;
        }

        $reqs = 'SELECT '.$select.' FROM '.$from;

        if($where != null ) {
            $reqs.=' WHERE '.$where;
        }

        $reqs.=' ORDER BY '.$orderBy.' ;';

        $finder = $this->cursor->query($reqs);
        return $finder->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find a record by its ID or using a custom WHERE clause.
     *
     * @param int $id The ID of the record (or null to use WHERE clause).
     * @param string|null $where Optional WHERE clause.
     * @param string $select Columns to select (default is '*').
     * @param string|null $from Optional table name.
     * @return array|null Returns a single record as an array or null if not found.
     */
    public function find(Int $id, ?String $where = null,String $select = '*',?String $from = null) {

        if($from === null) {
            $from = $this->table;
        }

        $reqs = 'SELECT '.$select.' FROM '.$from.' WHERE ';
        if($where != null) {
            $reqs.=$where;
        } else {
            $reqs.='id = '.$id;
        }
        $reqs .= ';';

        $finder = $this->cursor->query($reqs);
        return $finder->fetch(PDO::FETCH_ASSOC);
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

                    if(is_string($value)) {
                        $strValue.='"'.$value.'",';
                    } else {
                        $strValue.=$value.',';
                    }
                }
            }

            $reqs = 'INSERT INTO '.$this->table.' ('.substr($strInsert,0,-1).') VALUES ('.substr($strValue,0,-1).')';
        }
        else {
            $setStr = '';
            foreach ($this->field as $item => $value) {
                if($value != '') {

                    if(is_string($value)) {
                        $setStr .= $item.' = "'.$value.'",';
                    } else {
                        $setStr .= $item.' = '.$value.',';
                    }
                }
            }
            $reqs = 'UPDATE '.$this->table.' SET '.substr($setStr,0,-1).' WHERE id = '.$id;
        }

        $this->cursor->exec($reqs);
        return intval($this->cursor->lastInsertId());

    }

    /**
     * Delete a record from the database.
     *
     * @param int $id The ID of the record to delete.
     * @return bool|int Returns true for a successful delete or false otherwise.
     */
    public function delete(Int $id): bool|int {
        $reqs = 'DELETE FROM '.$this->table.' WHERE id = '.$id.';';
        return $this->cursor->exec($reqs);
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

}

