<?php

namespace App\Controllers;

require_once(realpath(dirname(__FILE__) . '/../models/Car.php'));

use App\Models\Car;

class QueryCarValidator
{
    public static function validate($request)
    {
        foreach ($request as $param => $value) {
            if (!in_array($param, array_merge(Car::getAllowed(), ["id"]))) {
                throw new \Exception("Invalid parameter: " . $param);
            }
        }

        if (isset($request["name"]) && strlen($request["name"]) > 20) throw new \Exception("Name too long.");
        if (isset($request["manufacturer"]) && strlen($request["manufacturer"]) > 20) throw new \Exception("Manufacturer too long.");
        if (isset($request["engine"]) && strlen($request["engine"]) > 20) throw new \Exception("Engine too long.");
        if (isset($request["year"])) {
            if (!is_numeric($request["year"]) || ($request["year"] < 1850 || $request["year"] > 3000)) throw new \Exception("Invalid year");
        }
    }
}
