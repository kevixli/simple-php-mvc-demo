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

include_once ("FilterBase.php");
include_once ("tool/Validator.php");
include_once ("objectTool/XMLParser.php");

class ValidationFilter extends FilterBase {
	private $data;

	function init() {
		$validatorDir = FRAMEWORK_RELATIVE_LOCATION."kevix/config/validator/";
		$validatorFileName = get_class($this->getActionObject())."-validator.xml";
		$properties = $this->getFilterParameters();
		if(isset($properties["validatorDir"]))
			$validatorDir = PROJECT_ROOT.$properties["validatorDir"]."/";
		
		if (file_exists($validatorDir.$validatorFileName)) {
			$parse = new XMLParser($validatorDir.$validatorFileName);
			$this->data = $parse->getData();
			$parse = null;
		} else
			die("'".$validatorFileName."' validation file does not exist in '$validatorDir'!");
	}

	function destroy() {
	}

	function filterIn() {
		$parameters = $this->getParameters();
		$data = $this->data;

		// handle field / validator tag
		for ($i = 0; $i < count($data[0]["child"]); $i ++) {
			if ($data[0]["child"][$i]["name"] == "FIELD") {
				$fieldName = $data[0]["child"][$i]["attributes"]["NAME"];

				// handle field-validator tag
				for ($j = 0; $j < count($data[0]["child"][$i]["child"]); $j ++) {
					$type = $data[0]["child"][$i]["child"][$j]["attributes"]["TYPE"];

					for ($k = 0; $k < count($data[0]["child"][$i]["child"][$j]["child"]); $k ++) {
						if ($data[0]["child"][$i]["child"][$j]["child"][$k]["name"] == "PARAM") {
							$paraName = $data[0]["child"][$i]["child"][$j]["child"][$k]["attributes"]["NAME"];
							$para[$paraName] = $data[0]["child"][$i]["child"][$j]["child"][$k]["content"];
						} else
							if ($data[0]["child"][$i]["child"][$j]["child"][$k]["name"] == "MESSAGE")
								$errorMsg = trim($data[0]["child"][$i]["child"][$j]["child"][$k]["attributes"]["KEY"]);
					}

					$para["string"] = $parameters[$fieldName];

					if (!Validator :: $type ($para)) {
						$this->getActionObject()->addError($fieldName, $errorMsg);
					}
				}
			} else
				if ($data[0]["child"][$i]["name"] == "EXPRESSION") {
					
					for ($j = 0; $j < count($data[0]["child"][$i]["child"]); $j ++) {
						if ($data[0]["child"][$i]["child"][$j]["name"] == "PARAM") {
							
							if ($data[0]["child"][$i]["child"][$j]["attributes"]["NAME"] == "expression")
								$expression = trim($data[0]["child"][$i]["child"][$j]["content"]);

							else
								if ($data[0]["child"][$i]["child"][$j]["attributes"]["NAME"] == "errorVar")
									$fieldName = trim($data[0]["child"][$i]["child"][$j]["content"]);

						} else
							if ($data[0]["child"][$i]["child"][$j]["name"] == "MESSAGE")
								$errorMsg = trim($data[0]["child"][$i]["child"][$j]["attributes"]["KEY"]);
					}

 					eval('$expression =('.$expression.');');
 					
					if (!$expression)
						$this->getActionObject()->addError($fieldName, $errorMsg);
				}
		}

		if ($this->getActionObject()->hasError())
			return "form";
	}

	function filterOut() {

	}
	
}
?>