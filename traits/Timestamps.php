<?php

require 'vendor/autoload.php';

use Carbon\Carbon;

trait Timestamps
{
    protected static $created = true;
    protected static $updated = true;
    protected static $softDeletes = false;

    public static function saveTimestamps()
    {
        $cols = [];
        if (self::$created) array_push($cols, "created_at");
        if (self::$updated) array_push($cols, "updated_at");
        return $cols;
    }

    public static function saveTimestampsValues()
    {
        $values = [];
        if (self::$created) $values += ["created_at" => Carbon::now()];
        if (self::$updated) $values += ["updated_at" => Carbon::now()];
        return $values;
    }

    public static function updateTimestamp()
    {
        if (self::$updated) return "updated_at = '" . Carbon::now() . "'";
        else return "";
    }

    public static function deleteTimestamp()
    {
        if (self::$softDeletes) return "deleted_at = '" . Carbon::now() . "'";
        else return "";
    }
}
