<?php
/**
 * Cipher
 *
 * Simple mcrypt interface.
 *
 * Cipher is a simple class for working with mcrypt.
 *
 * @author      Nathan Lucas <nathan@bnlucas.com>
 * @copyright   2013 Nathan Lucas
 * @version     3.0.0
 * @package		Utilities
 *
 * Copyright 2013 Nathan Lucas
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

namespace Utilities;
use LogicException;

class Cipher {

	/**
	 * Encryption algorithm to use.
	 *
	 * @access  private
	 * @var     string
	 */
	private $algo;

	/**
	 * Encryption mode.
	 *
	 * @access  private
	 * @var     string
	 */
	private $mode;

	/**
	 * Randomization source.
	 *
	 * @access  private
	 * @var     int
	 */
	private $rand;

	/**
	 * Initialization vector size.
	 *
	 * @access  private
	 * @var     int
	 */
	private $iv_size;

	/**
	 * Encryption key.
	 *
	 * @access  private
	 * @var     string
	 */
	private $key;

	/**
	 * Cipher constructor. Sets the encryption algorithm and mode, randomization
	 * source, and initialization vector size.
	 *
	 * @access  public
	 * @param   string $algo
	 * @param   string $mode
	 * @param   int $rand
	 * @return  void
	 */
	public function __construct($algo = MCRYPT_3DES, $mode = MCRYPT_MODE_CBC, $rand = MCRYPT_RAND) {
		$this->algo = $algo;
		$this->mode = $mode;
		$this->rand = $rand;

		$this->iv_size = mcrypt_get_iv_size($this->algo, $this->mode);
	}

	/**
	 * Get the current encryption key being used.
	 *
	 * @access  public
	 * @return  string
	 */
	public function getKey() {
		if ($this->key) {
			return $this->key;
		}
		return null;
	}

	/**
	 * Set the key used for the encrypt and decrypt methods until another $key
	 * is specified with Cipher::encrypt, Cipher::decrypt, or Cipher::setKey.
	 *
	 * @access  public
	 * @param   mixed $key
	 * @return  void
	 */
	public function setKey($key) {
		if (!is_null($key)) {
			$key = hash('sha256', $key, true);
			$key_size = mcrypt_get_key_size($this->algo, $this->mode);
			$this->key = substr($key, 0, $key_size);
		}
		if (is_null($this->key)) {
			throw new LogicException('You must specify a key at least once in '.
				'either Cipher::encrypt or Cipher::decrypt or Cipher::setKey');
		}
	}

	/**
	 * Returns encrpyted iv and data, base64 encoded. $key must be specified at
	 * least once, it can be changed at any point.
	 *
	 * @access  public
	 * @param   string $data
	 * @param   mixed $key
	 * @return  string
	 */
	public function encrypt($data, $key = null) {
		$key = (strlen($key) == 0) ? $key = null : $key;

		$this->setKey($key);
		
		$iv = mcrypt_create_iv($$this->iv_size, $this->rand);

		$out = mcrypt_encrypt($this->algo, $this->key, $data, $this->mode, $iv);
		return base64_encode($iv.$out);
	}

	/**
	 * Returns decrypted data. $key must be specified at least once, it can be 
	 * changed at any point.
	 *
	 * @access  public
	 * @param   mixed $data
	 * @param   mixed $key
	 * @return  mixed
	 */
	public function decrypt($data, $key = null) {
		$key = (strlen($key) == 0) ? $key = null : $key;

		$this->setKey($key);
		
		$data = base64_decode($data);

		$iv   = substr($data, 0, $this->iv_size);
		$data = substr($data, $this->iv_size);

		return mcrypt_decrypt($this->algo, $this->key, $data, $this->mode, $iv);
	}
}
?>
