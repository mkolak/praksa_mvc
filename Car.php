<?php

require('Model.php');

class Car extends Model
{
    protected $allowed = ['name', 'manufacturer', 'year', 'engine'];
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


foreach (Car::all() as $value) {
    echo $value;
    echo "<br>";
};

echo "<br>";

echo Car::queryById(4);

echo "<br><br>";

foreach (Car::queryByProperty("year", 2005) as $value) {
    echo $value;
    echo "<br>";
}
