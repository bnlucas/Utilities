<?php
/**
 * Juggernaut is a simple benchmarking utility.
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
use LogicException;
use ReflectionFunction;

class Juggernaut {
	
	/**
	 * @access  protected
	 * @var     int
	 */
	protected $iterations;

	/**
	 * @access  protected
	 * @var     array
	 */
	protected $log = array();

	/**
	 * Juggernaut constructor. Default sets 100,000 iterations.
	 *
	 * @access  public
	 * @param   int $iterations
	 * @return  \Utilities\Juggernaut
	 */
	public function __construct($iterations = 100000) {
		\Utilities\TypeCheck::check("int");
		\Utilities\ErrorHandler::register($this);
		$this->iterations = $iterations;
	}

	/**
	 * Unregisters \Utilities\ErrorHandler error and exception handling.
	 *
	 * @access  public
	 * @return  void
	 */
	public function __destruct() {
		\Utilities\ErrorHandler::unregister();
	}

	/**
	 * Runs $callback function x number of times. Setting $iterations
	 * overrides \Utilities\Juggernaut::iterations;
	 *
	 * @access  public
	 * @param   string $callback
	 * @param   int $iterations
	 * @return  void
	 */
	public function iterate($callback, $iterations = null) {
		\Utilities\TypeCheck::check("string", "int");
		if (is_null($iterations)) {
			$iterations = $this->iterations;
		}
		$start = microtime(true);
		$i = 0;
		do {
			call_user_func($callback);
			$i++;
		} while ($i < $iterations);
		$stop = microtime(true);
		$this->log[md5($callback.$start.$stop)] = array(
			"name" => $callback,
			"test" => "iterate",
			"time" => ($stop - $start),
			"trys" => $iterations
		);
	}

	/**
	 * Returns Juggernaut log. Setting $raw to true provides raw array.
	 * 
	 * @access  public
	 * @param   bool $raw
	 * @return  string|array
	 */
	public function log($raw = false) {
		\Utilities\TypeCheck::check("bool");
		if ($raw) {
			return $this->log;
		}
		$out = "";
		foreach ($this->log as $key => $test) {
			$out .= sprintf("key := %s ".PHP_EOL."    [ test := %-16s callback := %-16s time := %6.2fms ] @ %8.0d iterations", $key, $test['test'], $test['name'], ($test['time'] * 1000), $test['trys']).PHP_EOL;
			$out .= $this->source($test['name']);
			
		}
		return $out;
	}

	/**
	 * Runs a single call of $callback.
	 * 
	 * @access  public
	 * @param   string $callback
	 * @return  void
	 */
	public function single($callback) {
		\Utilities\TypeCheck::check("string");
		$start = microtime(true);
		call_user_func($callback);
		$stop = microtime(true);
		$this->log[md5($callback.$start.$stop)] = array(
			"name" => $callback,
			"test" => "simple",
			"time" => ($stop - $start),
			"trys" => 1
		);
	}

	/**
	 * Loads source code for $callback function, or any user defined function.
	 *
	 * @access  public
	 * @param   string $function
	 * @return  string
	 */
	public function source($function) {
		\Utilities\TypeCheck::check("string");
		$reflect = new ReflectionFunction($function);
		if (!$reflect->isUserDefined()) {
			throw new LogicException($function." must be user defined in order to view the source.");
		}
		$file = file($reflect->getFileName());
		$source = PHP_EOL."/**".PHP_EOL;
		$i = ($reflect->getStartLine() - 1);
		do {
			$source .= " * ".sprintf("%3.0d. %s", $i, htmlspecialchars($file[$i]));
			$i++;
		} while ($i < $reflect->getEndLine());
		return $source." */".str_repeat(PHP_EOL, 3);
	}
}
?>