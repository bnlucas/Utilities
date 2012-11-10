<?php
/**
 * ErrorHandler is a utilitiy class to hand errors and exceptions.
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
use Exception;
use ErrorException;
use ReflectionClass;

class ErrorHandler {
	
	/**
	 * Application which called \Utilities\ErrorHandler::register()
	 * @access  protected
	 * @static
	 * @var     string
	 */
	protected static $application;

	/**
	 * Conver PHP errors into ErrorExceptions.
	 *
	 * @access  public
	 * @static
	 * @param   int $errno
	 * @param   string $errstr
	 * @param   string $errfile
	 * @param   int $errline
	 * @return  throws ErrorException
	 */
	public static function handleError($errno, $errstr = null, $errfile = null, $errline = null) {
		if (error_reporting() && $errno) {
			throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
		}
	}

	/**
	 * Formats Exceptions to display Exception information.
	 *
	 * @access  public
	 * @static
	 * @param   Exception $exception
	 * @return  void
	 */
	public static function handleException(Exception $exception) {
		$title = sprintf("%s Error", self::$application);
		$detail = "<pre>%-10s %s</pre>";
		$error = sprintf("<h1>%s</h1>", $title);
		$error .= "<h2>Details</h2>";
		if ($exception->getCode()) {
			$error .= sprintf($detail, "Code:", $exception->getCode());
		}
		if ($exception->getMessage()) {
			$error .= sprintf($detail, "Message:", $exception->getMessage());
		}
		if ($exception->getFile()) {
			$error .= sprintf($detail, "File:", $exception->getFile());
		}
		if ($exception->getLine()) {
			$error .= sprintf($detail, "Line:", $exception->getLine());
		}
		if ($exception->getTraceAsString()) {
			$error .= "<h2>Trace</h2>";
			$error .= sprintf("<pre>%s</pre>", $exception->getTraceAsString());
		}
		echo sprintf("<html><head><title>%s</title><style>body{margin:0;padding:30px;font:12px/1.5 Helvetica,Arial,Verdana,sans-serif;}h1{margin:0;font-size:48px;font-weight:normal;line-height:48px;}</style></head><body>%s</body></html>", $title, $error);
	}

	/**
	 * Restores error and exception handlers.
	 *
	 * @access  public
	 * @static
	 * @return  void
	 */
	public static function unregister() {
		restore_error_handler();
		restore_exception_handler();
	}

	/**
	 * Registers \Utilities\ErrorHandler
	 *
	 * @access  public
	 * @static
	 * @param   object $application
	 * @param   bool $exceptions
	 * @return  void
	 */
	public static function register($application, $exceptions = true) {
		set_error_handler(array("\Utilities\ErrorHandler", "handleError"));
		if ($exceptions) {
			set_exception_handler(array("\Utilities\ErrorHandler", "handleException"));
		}
		$app = new ReflectionClass($application);
		self::$application = $app->getShortName();
	}
}
?>