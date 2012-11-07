<?php
/**
 * AbstractFactory is a for fun factory engine in complete abstraction.
 *
 * @author      Nathan Lucas <nathan@plainwreck.com>
 * @copyright   2012 Nathan Lucas
 * @link        http://github.com/bnlucas/Utilities
 * @license     http://githut.com/bnlucas/Utilities
 * @version     1.0.0
 * @package     Utilities
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Utilities;
use InvalidArgumentException;
use LogicException;
use ReflectionClass;

class AbstractFactory {
	
	/**
	 * @access  private
	 * @static
	 * @var     array
	 */
	private static $factories = array();

	/**
	 * @access  private
	 * @static
	 * @var     string
	 */
	private static $namespace;

	/**
	 * Loads factory.
	 *
	 * \Utilities\AbstractFactory::factory(mixed $param [, mixed $param [, mixed $... ]]);
	 *
	 * @access  public
	 * @static
	 * @param   string $class
	 * @param   mixed [mixed $param [, mixed $param [, mixed $... ]]]
	 * @return  object
	 */
	public static function __callStatic($factory, $parameters) {
		$factory = ucfirst($factory);
		if (!in_array($factory, self::$factories)) {
			throw new LogicException("There is no factory called '".$factory."'.");
		}
		$factory = (!self::$namespace) ? $factory : self::$namespace."\\".$factory;
		array_shift($parameters);
		$reflect = new ReflectionClass($factory);
		if (!$reflect->hasMethod("__construct")) {
			throw new LogicException($factory." must have a constructor.");
		}
		$method = $reflect->getMethod("__construct");
		$i = 0;
		$_parameters = array();
		foreach ($method->getParameters() as $parameter) {
			if (isset($parameters[$i])) {
				$_parameters[] = $parameters[$i];
				continue;
			}
			if (!$parameter->isOptional()) {
					throw new InvalidArgumentException("$".$parameter->getName()." is a required parameter in method '".$method->getName()."'.");
			} else {
				$_parameters[] = $parameter->getDefaultValue();
			}
			$i++;
		}
		return $reflect->newInstanceArgs($_parameters);
	}
	
	/**
	 * Set factory list.
	 * 
	 * \Utilities\AbstractFactory::addFactories("factory1", "factory2", "factory3");
	 * \Utilities\AbstractFactory::addFactories(array("factory1", "factory2", "factory3"));
	 *
	 * @access  public
	 * @static
	 * @param   array|string [array $factories] | [string $factory [, string $... ]]
	 * @return  void
	 */
	public static function addFactories(/** [array $factories] | [, string $factory [, string $... ]] **/) {
		$factories = func_get_args();
		if (count($factories) == 1) {
			$factories = (is_array($factories[0])) ? $factories[0] : array($factories);
		}
		self::$factories = $factories;
	}

	/**
	 * Set namespace of factories.
	 * @access  public
	 * @static
	 * @param   string $namespace
	 * @return  void
	 */
	public static function addNamespace($namespace) {
		self::$namespace = $namespace;
	}

	/**
	 * Get factory list.
	 * @access  public
	 * @static
	 * @return  array
	 */
	public static function getFactories() {
		return self::$factories;
	}

	/**
	 * Get namespace.
	 * @access  public
	 * @static
	 * @return  string
	 */
	public static function getNamespace() {
		return self::$namespace;
	}
}
?>