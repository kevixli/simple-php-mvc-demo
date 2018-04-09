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
 * @version 0.5 beta version
 */

Application::$APPLICATION_DATA_FILE = FRAMEWORK_RELATIVE_LOCATION."kevix/temp/application.data";
class Application {
	
	public static $APPLICATION_DATA_FILE;
	
	/**
	*	To store all application variable
	*/

	private static $appVariable = array();
	
	public static function set($key,$value){
		self::$appVariable[$key] = $value;
	}
	
	public static function remove($key){
		unset(self::$appVariable[$key]);
	}
	
	public static function get($key){
		return self::$appVariable[$key];
	}
	
	public static function start() {
		if (file_exists(self::$APPLICATION_DATA_FILE) && empty(self::$appVariable)) {
			$file = fopen(self::$APPLICATION_DATA_FILE, "r");
			if ($file) {
				$data = fread($file, filesize(self::$APPLICATION_DATA_FILE));
				self::$appVariable = unserialize($data);
				fclose($file);
			}
		}
	}

	public static function end() {
		$data = serialize(self::$appVariable);
		$file = fopen(self::$APPLICATION_DATA_FILE, "w");

		if ($file) {
			fwrite($file, $data);
			fclose($file);
		}
	}
}
?>