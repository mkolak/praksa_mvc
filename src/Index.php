<?php

require '../vendor/autoload.php';
require_once("./router/Router.php");
require_once("./models/Car.php");
require_once '../vendor/autoload.php';

use App\Models\Car;
use App\Routes\Router;

$loader = new Twig\Loader\FilesystemLoader('views');

$twig = new Twig\Environment($loader);

$filter = new Twig\TwigFilter('snake_case', function ($str) {
    return implode('_', explode(' ', strtolower($str)));
});

$twig->addFilter($filter);

$router = new Router();

$router->addRoute('GET', '/home', function () {
    echo "Home page";
});

$router->addRoute('GET', '/cars', function () use ($twig) {
    if (!empty($_GET)) {
        $property = array_keys($_GET)[0];
        $value = array_values($_GET)[0];
        $cars = Car::queryByProperty($property, $value);
    } else $cars = Car::all();
    echo $twig->render('cars_list.twig', ['cars' => $cars]);
});

$router->addRoute('GET', '/cars/create', function () use ($twig) {
    $fields = [
        [
            "name" => "Name",
            "inputName" => "name",
            "inputType" => "Text"
        ],
        [
            "name" => "Manufacturer",
            "inputName" => "manufacturer",
            "inputType" => "Text"
        ],
        [
            "name" => "Year",
            "inputName" => "year",
            "inputType" => "number"
        ],
        [
            "name" => "Engine",
            "inputName" => "engine",
            "inputType" => "Text"
        ]
    ];
    echo $twig->render('create.twig', ["fields" => $fields]);
});

$router->addRoute("POST", "/cars/create", function () {
    $car = new Car();
    $car->setAttributes($_POST);
    $car->save();
    $url = "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'];
    header('Location: ' . $url . '/cars');
    die();
});

$router->addRoute('POST', '/cars', function () {
    $car = new Car();
    $car->setAttributes($_POST);
    $car->save();
});

$router->addRoute("POST", '/cars/delete', function () {
    Car::delete($_POST['id']);
    $url = "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'];
    header('Location: ' . $url . '/cars');
    die();
});

$router->run();
