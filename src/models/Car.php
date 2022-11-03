<?php

namespace App\Models;

require('Model.php');


class Car extends Model
{

    protected static $allowed = ['name', 'manufacturer', 'year', 'engine'];
    protected static $table = "cars";
}

/*

Tablica za model

CREATE TABLE cars (
	id INTEGER AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20) NOT NULL,
    manufacturer VARCHAR(30) NOT NULL,
    engine VARCHAR(40) NOT NULL,
    year INT NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    deleted_at DATETIME
);

*/

/* ______________________________________________
INSERT STATEMENTI POMOCU MODEL FUNKCIJE ZA INSERT
*/

// $auto = new Car();
// $auto->name = "XK-E";
// $auto->manufacturer = "Jaguar";
// $auto->year = 1976;
// $auto->engine = "Engine 2";

// $auto->save();

// $auto->name = "X6";
// $auto->manufacturer = "BMW";
// $auto->year = 2005;
// $auto->engine = "Engine 6";

// $auto->save();

// $auto->name = "A8";
// $auto->manufacturer = "Audi";
// $auto->year = 2005;
// $auto->engine = "Engine 3";

// $auto->save();

// $auto->name = "A3";
// $auto->manufacturer = "Audi";
// $auto->year = 2002;
// $auto->engine = "Engine 3";

// $auto->save();


// foreach (Car::all() as $value) {
//     echo $value;
//     echo "<br>";
// };

// echo "<br>";

// echo Car::queryById(4);

// echo "<br><br>";

// foreach (Car::queryByProperty("year", 2005) as $value) {
//     echo $value;
//     echo "<br>";
// }

// $auto = new Car();
// $auto->name = "Golf 7";
// $auto->manufacturer = "Volkswagen";
// $auto->year = 2013;
// $auto->engine = "Engine 33";
// $auto->save();

// Car::delete(27);

// $auto->name = "Quattroporte";
// $auto->manufacturer = "Maserati";
// $auto->year = 2001;
// $auto->engine = "Engine 11";

// $auto->save();

// foreach (Car::queryByProperty('manufacturer', 'Audi') as $value) {
//     echo $value . "<br>";
// }

// foreach (Car::all() as $car) {
//     echo $car . "<br>";
// }

// $car2 = new Car();
// $car2->name = "Punto";
// $car2->manufacturer = "Fiat";
// $car2->year = 2010;
// $car2->engine = "Engine 20";
// $car2->save();

// $car2 = new Car();
// $car2->name = "Uno";
// $car2->manufacturer = "Fiat";
// $car2->year = 1996;
// $car2->engine = "Engine 20";
// $car2->update(30);

// $car3 = new Car();
// $car3->setAttributes([
//     "name" => "Tico",
//     "manufacturer" => "Daewoo",
//     "year" => 2000,
//     "engine" => "Engine 21"
// ]);
// echo $car3;
