<?php
/**
 * Project:     KEVIX: PHP MVC Framework
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * For questions, help, comments, discussion, etc.,
 * please send email to kevixli@users.sourceforge.net
 *
 * @link http://kevix.sourceforge.net
 * @copyright(C) 2006  Kevin
 * @author Kevin Lee (Lee Ka Chun)
 * @version 0.5 beta 2 version
 */
 
include_once("tool/Application.php");

/**
 * 	define default return values of action
 */
define("SUCCESS","success");
define("ERROR","error");
define("INPUT","input");

abstract class ActionBase {

	/**
	* 	To store error messages' values
	*/

	private $errors = array ();

	/**
	* 	Execute method is main method & validate method is used to check errors before run
	* 	execute method.
	*/

	abstract public function execute();

	public function prepare() {}

	public final function addError($key, $value = "") {
		$this->errors[$key] = $value;
	}

	public final function hasError() {
		return (count($this->errors) != 0);
	}

	public final function getErrors() {
		return $this->errors;
	}
}
?>