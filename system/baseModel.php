<?php

namespace system;

abstract class baseModel {

    protected ?PDO $cursor;
    public string $table;
    public array $field;

    public function __construct(){
        $this->cursor = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    }

    public function setTable(string $table): baseModel
    {
        $this->table = $table;
        return $this;
    }
    public function setField(Array $field): baseModel
    {
        $this->field = $field;
        return $this;
    }

    public function findAll(?String $where = null, String $select = '*',?String $from = null,String $orderBy = 'id DESC'){
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

    public function delete(Int $id) {
        $reqs = 'DELETE FROM '.$this->table.' WHERE id = '.$id.';';
        return $this->cursor->exec($reqs);
    }

    public function savePost(Array $field){
        foreach ($field as $field_item => $field_value) {
            if(isset($this->field[$field_item])) {
                $this->field[$field_item] = $field_value;
            }
        }
    }

}

