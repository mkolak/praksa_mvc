<?php

namespace App\Controllers;

use App\Models\Car;

require_once("QueryCarValidator.php");
require_once("StoreCarValidator.php");
require_once("DeleteCarValidator.php");
require_once(realpath(dirname(__FILE__) . '/../models/Car.php'));

class CarController
{
    public static function index($request)
    {
        if (empty($request)) return Car::all();

        try {
            QueryCarValidator::validate($request);
        } catch (\Exception $e) {
            echo $e->getMessage();
            die();
        }

        if (isset($request->id)) return Car::queryById($request->id);

        $property = array_keys($_GET)[0];
        $value = array_values($_GET)[0];
        return Car::queryByProperty($property, $value);
    }

    public static function store($request)
    {
        try {
            StoreCarValidator::validate($request);
        } catch (\Exception $e) {
            echo $e->getMessage();
            die();
        }

        $car = new Car();
        $car->setAttributes($request);
        $car->save();
    }

    public static function delete($id)
    {
        DeleteCarValidator::validate($id);

        Car::delete($id);
    }
}
