<?php

require '../vendor/autoload.php';
require_once("./router/Router.php");
require_once("./models/Car.php");
require_once '../vendor/autoload.php';

use App\Models\Car;
use Twig\Environment;
use App\Routes\Router;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader('views');

$twig = new Environment($loader);

$router = new Router();

$router->addRoute('GET', '/home', function () {
    echo "Home page";
});

$router->addRoute('GET', '/cars', function () use($twig){
    if(!empty($_GET)){
        $property = array_keys($_GET)[0];
        $value = array_values($_GET)[0];
        $cars = Car::queryByProperty($property, $value);
    }
    else $cars = Car::all();
    echo $twig->render('list.twig', ['cars' => $cars]);
});

$router->addRoute('GET', '/cars/create', function() use ($twig){
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

// $router->addRoute("POST", "/cars/create", function(){
//     var_dump($_POST);
// });

$router->addRoute('POST', '/cars', function () {
    $car = new Car();
    $car->setAttributes($_POST);
    $car->save();
});

$router->run();
