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

class Validator {

	public static function notEmpty($parameters) {
		return (trim($parameters["string"]) != "");
	}

	public static function requiredString($parameters) {
		return !ereg("[0-9]+", $parameters["string"]);
	}

	public static function requiredNumber($parameters) {
		return ereg("[0-9]+", $parameters["string"]);
	}

	public static function email($parameters) {
		if ($parameters["string"] == "")
			return true;

		return ereg("([a-zA-Z0-9_]+)@([a-zA-Z0-9_]+)([.]+)([a-zA-Z]+)", $parameters["string"]);
	}

	public static function strLen($parameters) {
		if (trim($parameters["min"]) != "" && trim($parameters["max"]) != "")
			return (strlen($parameters["string"]) >= $parameters["min"]) && (strlen($parameters["string"]) <= $parameters["max"]);
		else
			if (trim($parameters["min"]) != "")
				return (strlen($parameters["string"]) >= $parameters["min"]);
			else
				return (strlen($parameters["string"]) <= $parameters["max"]);
	}

	public static function numRange($parameters) {
		if (trim($parameters["min"]) != "" && trim($parameters["max"]) != "")
			return ($parameters["string"] >= $parameters["min"]) && ($parameters["string"] <= $parameters["max"]);
		else
			if (trim($parameters["min"]) != "")
				return ($parameters["string"] >= $parameters["min"]);
			else
				return ($parameters["string"] <= $parameters["max"]);
	}

	public static function dateRange($parameters) {
		$stringPart = explode("/", trim($parameters["string"]));
		if(count($stringPart)!= 3)
			die("Error in 'Validator::dateRange( )' : Input string does not in date format(d/m/yyyy)!");
			
		$stringTimestamp = mktime(0, 0, 0, $stringPart[1], $stringPart[0], $stringPart[2]);

		if (trim($parameters["min"]) != "") {
			$minPart = explode("/", trim($parameters["min"]));
			if(count($minPart)!= 3)
			die("Error in 'Validator::dateRange( )' : Min value does not in date format(d/m/yyyy)!");
		
			$minTimestamp = mktime(0, 0, 0, $minPart[1], $minPart[0], $minPart[2]);
		}

		if (trim($parameters["max"])!="") {
			$maxPart = explode("/", trim($parameters["max"]));
			if(count($maxPart)!= 3)
			die("Error in 'Validator::dateRange( )' : Max value does not in date format(d/m/yyyy)!");
		
			$maxTimestamp = mktime(0, 0, 0, $maxPart[1], $maxPart[0], $maxPart[2]);
		}
		
		if (trim($parameters["min"]) != "" && trim($parameters["max"]) != "")
			return ($stringTimestamp >= $minTimestamp) && ($stringTimestamp <= $maxTimestamp);
		else
			if (trim($parameters["min"]) != "")
				return ($stringTimestamp >= $minTimestamp);
			else
				return ($stringTimestamp <= $maxTimestamp);
	}
}
?>