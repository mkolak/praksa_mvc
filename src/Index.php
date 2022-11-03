<?php

require '../vendor/autoload.php';
require_once("./router/Router.php");
require_once("./views/View.php");
require("./controllers/CarController.php");

use App\View;
use App\Routes\Router;
use App\Controllers\CarController;

$twig = View::loadTwig();

$router = new Router();

$router->addRoute('GET', '/', function () {
    echo "Get started on route /cars";
});

$router->addRoute('GET', '/cars', function () use ($twig) {
    $cars = CarController::index($_GET);
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
    CarController::store($_POST);
    View::redirect();
});

$router->addRoute("POST", '/cars/delete', function () {
    CarController::delete($_POST["id"]);
    View::redirect();
});

$router->run();
