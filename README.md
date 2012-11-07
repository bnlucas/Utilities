#AbstractFactory
```php

// Global class loader.
require_once($_SERVER['DOCUMENT_ROOT']."/path/to/Utilities/Loader.php");
\Utilities\Loader::register();

\Utilities\AbstractFactory::addFactories("Car", "Truck", "Motorcycle");
\Utilities\AbstractFactory::addNamespace("\\Vehicle\\Type");

/**
 * namespace Vehicle\Type;
 * 
 * class Car extends \Vehicle\Motorized {
 *
 *     public function __construct($make, $model, $color) {
 *         $this->setMake($make);
 *         $this->setModel($model);
 *         $this->setColor($color);
 *     }
 * }
 */

$car = \Utilities\AbstractFactory::car("Ford", "Mustang", "red");
/** $car is instance of \Vehicle\Type\Car **/

echo $car->getColor(); // red
```

------------------------------------------------------------------------

#Loader
```php
require_once($_SERVER['DOCUMENT_ROOT']."/path/to/Utilities/Loader.php");
\Utilities\Loader::register();

/** Get list of all loaded class files. **/
foreach (\Utilities\Loader::log() as $filename) {
	echo $filename."\n";
}
```