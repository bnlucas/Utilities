<?php
/**
 * TypeCheck to ease the lack of PHP's type hinting.
 *
 * function error($errno, $errstr = null, $errfile = null, $errline = null) {
 *     \Utilities\TypeCheck("int", "string", "string" "int");
 *     if (error_reporting() && $errno) {
 *         throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
 *     }
 * }
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

class TypeCheck {
	
	/**
	 * Type aliases.
	 * @access  public
	 * @static
	 * @var     array
	 */
	public static $aliases = array(
		"array"		=> "array",
		"bool"		=> "boolean",
		"boolean"	=> "boolean",
		"double"	=> "double",
		"float"		=> "double",
		"real"		=> "double",
		"int"		=> "integer",
		"null"		=> "NULL",
		"NULL"		=> "NULL",
		"unset"		=> "NULL",
		"object"	=> "object",
		"resource"	=> "resource",
		"str"		=> "string",
		"string"	=> "string"
	);

	/**
	 * Checks the calling function's arguments against set types.
	 * Will throw an InvalidArgumentException if type does not match.
	 *
	 * @access  public
	 * @static
	 * @param   string $type [, string $type [, string $... ]]
	 * @return  void
	 */
	public static function check(/** string $type [, string $type [, string $... ]] **/) {
		$args = func_get_args();
		$trace = debug_backtrace();
		$fn = $trace[1];
		$stop = (count($fn['args']) > count($args)) ? count($args) : count($fn['args']);
		$i = 0;
		do {
			if (($args[$i] != "mixed") && (gettype($fn['args'][$i]) != self::$aliases[$args[$i]])) {
				throw new InvalidArgumentException(sprintf("Invalid type. Given %s as %s", gettype($fn['args'][$i]), self::$aliases[$args[$i]]));
			}
			$i++;
		} while ($i < $stop);
	}
}
?>