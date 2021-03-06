#AbstractFactory
```php
/**
 * namespace Vehicle;
 * use Utilities\AbstractFactory;
 *
 * class Factory extends \Utilities\AbstractFactory {
 *	
 * 		public static function __callStatic($factory, $parameters) {
 *			return parent::$factory($parameters);
 *		}
 *
 *		public static function register() {
 *			parent::setNamespace("\Vehicle\Type");
 *			parent::addFactories("Car", "Truck", "Motorcycle");
 *		}
 * }
 */

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

require_once($_SERVER['DOCUMENT_ROOT']."/path/to/Utilities/Loader.php");
\Utilities\Loader::register();

\Vehicle\Factory::register();

$car = \Vehicle\Factory::car("Ford", "Mustang", "red");
# $car is instance of \Vehicle\Type\Car

echo $car->getColor(); // red [Method in \Vehicle\Motorized]
```

#Cipher
```php
require_once($_SERVER['DOCUMENT_ROOT']."/path/to/Utilities/Loader.php");
\Utilities\Loader::register();

$cipher = \Utilities\Cipher(MCRYPT_3DES, MCRYPT_MODE_CBC, MCRYPT_RAND);

$cipher->setKey('my secret key')

$encrypted = $cipher->encrypt('my data.');
$decrypted = $cipher->decrypt($encrypted);
```
```php
# ... loader ...

$cipher = \Utilities\Cipher(MCRYPT_3DES, MCRYPT_MODE_CBC, MCRYPT_RAND);

$data1 = $cipher->encrypt('my data.', 'my secret key 1');
$data2 = $cipher->encrypt('my data.', 'my secret key 2'); # you can specify different keys.

$data1 = $cipher->decrypt($data1); # Does not decrypt, using `my secret key 2` still.
$data2 = $cipher->decrypt($data2); # Does decrypt.
$data1 = $cipher->decrypt($data1, 'my secret key 1'); # Does encrypt.
```

#Loader
```php
require_once($_SERVER['DOCUMENT_ROOT']."/path/to/Utilities/Loader.php");
\Utilities\Loader::register();

# Get list of all loaded class files.
foreach (\Utilities\Loader::log() as $filename) {
	echo $filename."\n";
}
```

#Juggernaut
```php
require_once($_SERVER['DOCUMENT_ROOT']."/path/to/Utilities/Loader.php");
\Utilities\Loader::register();

$jugger = new Juggernaut();
# Default iterations set to 100,000.

function test_for_loop() {
	for ($i = 0; $i < 1000; $i++) {
		// Do nothing.
	}
}

$jugger->iterate("test_for_loop");

# key := e63ded35237df6104212beb110810766 
#     [ test := iterate          callback := test_for_loop    time := 4995.49ms ] @   100000 iterations
# 
# /**
#  *   5. function test_for_loop() {
#  *   6. 	for ($i = 0; $i < 1000; $i++) {
#  *   7. 		// Do nothing.
#  *   8. 	}
#  *   9. }
#  */

echo "<pre>".PHP_EOL;
echo $jugger->log();
echo "</pre>".PHP_EOL;

# Or raw array...
print_t($jugger->log(true));

# Array
# (
#     [e63ded35237df6104212beb110810766] => Array
#         (
#             [name] => test_for_loop
#             [test] => iterate
#             [time] => 4.9954919815063
#             [trys] => 100000
#         )
# 
# )
```

# ErrorHandler
```php
namespace MyApp;
use Utilities\ErrorHandler;

class MyClass {
	
	public function __construct() {
		\Utilities\ErrorHandler::register($this);
	}

	public function __destruct() {
		\Utilities\ErrorHandler::unregister();
	}
}
```

# TypeCheck
```php
function error($errno, $errstr = null, $errfile = null, $errline = null) {
	\Utilities\TypeCheck("int", "string", "string" "int");
	if (error_reporting() && $errno) {
		throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
	}
}
```
