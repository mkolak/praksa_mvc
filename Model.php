<?php

use Carbon\Carbon;

require('traits/Timestamps.php');
require('Connection.php');

class Model
{
    use Timestamps;

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

        $cols = implode(',', array_merge($this->allowed, $this->saveTimestamps()));
        $values = ":" . implode(',:', array_merge($this->allowed, $this->saveTimestamps()));

        $statement = "INSERT INTO " . static::$table . " ($cols) VALUES ($values);";

        $insert = [];
        foreach ($this->allowed as $property)
            $insert += [$property => (isset($this->attributes[$property]) ? $this->attributes[$property] : null)];
        $insert += $this->saveTimestampsValues();

        $conn->pdo->prepare($statement)->execute($insert);

        $this->attributes = [];

        return $conn->pdo->lastInsertId();
    }

    public static function all()
    {
        $conn = new Connection();

        $statement = "SELECT * FROM " . static::$table;
        if (self::$softDeletes)  $statement = $statement . " WHERE deleted_at IS NULL;";

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

        $statement = "SELECT * FROM " . static::$table . " WHERE $primary_key = :id";
        if (self::$softDeletes) {
            $statement = $statement . " AND deleted_at IS NULL;";
        } else {
            $statement = $statement . ";";
        }

        $query = $conn->pdo->prepare($statement);
        $query->execute(['id' => $id]);

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

        $statement = "SELECT * FROM " . static::$table . " WHERE $property = :value";
        if (self::$softDeletes) {
            $statement = $statement . " AND deleted_at IS NULL;";
        } else {
            $statement = $statement . ";";
        }

        $query = $conn->pdo->prepare($statement);
        $query->execute(['value' => $value]);

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

    public function update(int $id)
    {
        $conn = new Connection();
        $primary_key = $conn->pdo->query("SHOW KEYS FROM " . static::$table . " WHERE Key_name = 'PRIMARY'")->fetch()['Column_name'];

        $statement = "UPDATE " . static::$table . " SET";
        foreach (array_keys($this->attributes) as $property) {
            $statement = $statement . " $property = :$property,";
        }

        $statement = rtrim($statement, ",");
        if (self::$updated) {
            $statement = $statement . ", " . self::updateTimestamp();
        }

        $statement = $statement . " WHERE $primary_key = :id";

        if (self::$softDeletes) {
            $statement = $statement . " AND deleted_at IS NULL;";
        } else {
            $statement = $statement . ";";
        }

        $conn->pdo->prepare($statement)->execute($this->attributes + ["id" => $id]);

        $this->attributes = [];
    }

    public static function delete(int $id)
    {
        if (!self::$softDeletes) return self::forceDelete($id);

        $conn = new Connection();
        $primary_key = $conn->pdo->query("SHOW KEYS FROM " . static::$table . " WHERE Key_name = 'PRIMARY'")->fetch()['Column_name'];

        $statement = "UPDATE " . static::$table . " SET " . self::deleteTimestamp() . " WHERE $primary_key = :id;";
        $conn->pdo->prepare($statement)->execute(["id" => $id]);
    }

    public static function forceDelete(int $id)
    {
        $conn = new Connection();
        $primary_key = $conn->pdo->query("SHOW KEYS FROM " . static::$table . " WHERE Key_name = 'PRIMARY'")->fetch()['Column_name'];

        $statement = "DELETE FROM " . static::$table . " WHERE $primary_key = :id";
        $conn->pdo->prepare($statement)->execute(["id" => $id]);
    }
}
