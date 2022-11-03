<?php

namespace App\Models\Traits;

trait Attributes
{
    protected $attributes;
    protected static $allowed = [];

    public function toArray()
    {
        $array = [];
        foreach (static::$allowed as $property) {
            if (isset($this->attributes[$property])) $array += [$property => $this->attributes[$property]];
            else $array += [$property => null];
        }
        return $array;
    }

    public static function saveAttributes()
    {
        return static::$allowed;
    }

    public function saveAttributesValues()
    {
        return $this->toArray();
    }

    public function setAttributes($values)
    {
        $this->id = $values["id"];
        foreach (static::$allowed as $property) {
            if (isset($values[$property])) $this->attributes[$property] = $values[$property];
            else throw new \Exception("Missing property: $property");
        }
    }

    public static function getAllowed()
    {
        return static::$allowed;
    }
}
