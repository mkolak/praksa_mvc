<?php

require 'vendor/autoload.php';

use Carbon\Carbon;

trait Timestamps
{
    public function save()
    {
        $id = parent::save();

        // $conn = new Connection();

        // echo "SHOW KEYS FROM " . parent::$table . " WHERE Key_name = 'PRIMARY'";
        // $primary_key = $conn->pdo->query("SHOW KEYS FROM " . parent::$table . " WHERE Key_name = 'PRIMARY'")->fetch()['Column_name'];
        // $statement = "UPDATE " . parent::$table . " SET created_at = '" . Carbon::now() . "', updated_at = '" . Carbon::now() . "' WHERE $primary_key = $id";
        // $conn->pdo->prepare($statement)->execute();
        // return $id;
    }
}
