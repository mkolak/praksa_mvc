<?php

require('Connection.php');

class Model
{
    protected $attributes;
    protected $allowed = [];
    protected static $table;

    public function toArray()
    {
        $array = [];
        foreach ($this->allowed as $property) {
            if (isset($this->attributes[$property])) $array += [$property => $this->attributes[$property]];
            else $array += [$property => null];
        }
        return $array;
    }

    /*___________________
        MAGIC METHODS
    _____________________
    */

    public function __get($property)
    {
        if (in_array($property, $this->allowed)) {
            if (isset($this->attributes[$property])) return $this->attributes[$property];
            else return null;
        } else return $this->$property;
    }

    public function __set($property, $value)
    {
        if (in_array($property, $this->allowed)) $this->attributes[$property] = $value;
        else throw new Exception("Unexpected property " . $property);
    }

    public function __toString()
    {
        return json_encode($this->toArray());
    }

    public function __call($name, $arguments)
    {
        $set = explode('set', $name);
        if (isset($set[1]) && !$set[0]) {
            $property = implode(array_slice($set, 1));
            $this->__set($property, $arguments[0]);
            return;
        }
        $get = explode('get', $name);
        if (isset($get[1]) && !$get[0]) {
            $property = implode(array_slice($get, 1));
            return $this->__get($property);
        }
        throw new Exception("Invalid function name " . $name);
    }

    public function __isset($name)
    {
        if (!in_array($name, $this->allowed)) throw new Exception("Invalid property " . $name);
        return isset($this->attributes[$name]);
    }

    public function __unset($name)
    {
        if (!in_array($name, $this->allowed)) throw new Exception("Invalid property " . $name);
        unset($this->attributes[$name]);
    }

    /*___________
    DB Methods
    _____________
    */

    public function save()
    {
        $conn = new Connection();

        // MUTABLE VALUES
        $cols = implode(',', $this->allowed);
        $values = "";
        foreach ($this->allowed as $property) {
            if (isset($this->attributes[$property])) $values = $values . "'" . $this->attributes[$property] . "',";
            else $values = $values . "null,";
        }

        // TIMESTAMPS
        $cols = $cols . ",created_at,updated_at";
        $values = $values . "'" . date('Y-m-d H:i:s') . "','" . date('Y-m-d H:i:s') . "'";
        // $values = rtrim($values, ",");
        $statement = "INSERT INTO " . static::$table . " ($cols) VALUES ($values);";

        echo $statement;

        $conn->pdo->prepare($statement)->execute();

        $this->attributes = [];
    }

    public static function all()
    {
        $conn = new Connection();

        $statement = "SELECT * FROM " . static::$table;
        $query = $conn->pdo->query($statement);
        $models = [];
        while ($row = $query->fetch()) {
            $model = new static();
            foreach ($model->allowed as $property) {
                $model->$property = $row[$property];
            }
            array_push($models, $model);
        }
        return $models;
    }

    public static function queryById(int $id)
    {
        $conn = new Connection();

        $primary_key = $conn->pdo->query("SHOW KEYS FROM " . static::$table . " WHERE Key_name = 'PRIMARY'")->fetch()['Column_name'];

        $statement = "SELECT * FROM " . static::$table . " WHERE " . $primary_key . " = " . $id;
        $query = $conn->pdo->query($statement);

        $model = new static();
        $row = $query->fetch();

        if ($row) {
            foreach ($model->allowed as $property) {
                $model->$property = $row[$property];
            }
        } else throw new Exception("Not found with id: " . $id);

        return $model;
    }

    public static function queryByProperty($property, $value)
    {
        $conn = new Connection();

        $statement = "SELECT * FROM " . static::$table . " WHERE " . $property . " = '" . $value . "'";
        $query = $conn->pdo->query($statement);
        $models = [];
        while ($row = $query->fetch()) {
            $model = new static();
            foreach ($model->allowed as $property) {
                $model->$property = $row[$property];
            }
            array_push($models, $model);
        }
        if (empty($models)) throw new Exception("No rows found with set parameters");
        return $models;
    }
}
