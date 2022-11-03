<?php

namespace App\Controllers;

require_once(realpath(dirname(__FILE__) . '/../models/Car.php'));

use App\Models\Car;

class DeleteCarValidator
{
    public static function validate($id)
    {
        if (!is_numeric($id)) throw new \Exception("Id is not a numeric value");
    }
}
