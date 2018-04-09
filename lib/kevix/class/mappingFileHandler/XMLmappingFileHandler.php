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

include_once ("MappingFileHandler.php");
include_once ("objectTool/XMLParser.php");
include_once("tool/StringUtil.php");

class XMLmappingFileHandler extends MappingFileHandler {

	private $actionClassPath;
	private $actionClassName;
	private $views = array ();
	private $filters = array ();
	private $viewTypes = array();

	function XMLmappingFileHandler($mappingFile, $action, $method) {
		parent :: __construct($mappingFile, $action, $method);
		$parser = new XMLParser($mappingFile);
		$data = $parser->getData();

		for ($i = 0; $i < count($data[0]["child"]); $i ++) {
			if ($data[0]["child"][$i]["name"] == "DEFAULT-VIEWS") {
				// Get all default view paths

				for ($j = 0; $j < count($data[0]["child"][$i]["child"]); $j ++) {
					if ($data[0]["child"][$i]["child"][$j]["name"] == "VIEW") {
						$viewName = $data[0]["child"][$i]["child"][$j]["attributes"]["NAME"];
						$viewPath = $data[0]["child"][$i]["child"][$j]["content"];
						$this->views[$viewName] = $viewPath;
					}
				}
			} else
				if ($data[0]["child"][$i]["name"] == "ACTION" && $data[0]["child"][$i]["attributes"]["NAME"] == $action) {
					$this->actionClassPath = $data[0]["child"][$i]["attributes"]["ACTIONPATH"];
					$this->actionClassName = $data[0]["child"][$i]["attributes"]["CLASSNAME"];

					// Get all view paths
					for ($j = 0; $j < count($data[0]["child"][$i]["child"]); $j ++) {
						if ($data[0]["child"][$i]["child"][$j]["name"] == "VIEW") {
							$viewName = $data[0]["child"][$i]["child"][$j]["attributes"]["NAME"];
							$viewPath = $data[0]["child"][$i]["child"][$j]["content"];
							$this->viewTypes[$viewName] = trim($data[0]["child"][$i]["child"][$j]["attributes"]["TYPE"]);
							$this->views[$viewName] = $viewPath;
						}
					}

					// Get all related filter
					for ($j = 0; $j < count($data[0]["child"][$i]["child"]); $j ++) {
						if (($data[0]["child"][$i]["child"][$j]["name"] == "REL-FILTER") && (($data[0]["child"][$i]["child"][$j]["attributes"]["METHOD"] == $this->method) || ($data[0]["child"][$i]["child"][$j]["attributes"]["METHOD"] == ""))) {
							$this->filters[] = $data[0]["child"][$i]["child"][$j]["attributes"]["NAME"];
						}
					}

					break;
				}
		}
	}

	/**
	* 	Get related action class PHP file's path
	*/

	public function getActionClassPath() {
		return $this->actionClassPath;
	}

	/**
	*	Get class name of action in PHP file
	*/

	function getActionClassName() {
		return $this->actionClassName;
	}

	/**
	*	Get all related smarty template files' paths 
	*/

	public function getAllActionReturnPath() {
		return $this->views;
	}

	/**
	*	Get all related filters' names in action class 
	*/

	function getAllFilterNames() {
		return $this->filters;
	}

	/**
	*	Cache all mapping information
	*/

	static function Cache($mappingFile) {
		$parser = new XMLParser($mappingFile);
		$defaultMappingData = $parser->getData();
		$allXMLdata[] = $defaultMappingData;
		$content .= "<?php\n";
		
		for ($i = 0; $i < count($defaultMappingData[0]["child"]); $i ++) {
			if ($defaultMappingData[0]["child"][$i]["name"] == "INCLUDE") {
				$includeFile = PROJECT_ROOT.$defaultMappingData[0]["child"][$i]["attributes"]["FILE"];
				$includeFiles[] = '"'.StringUtil::addEscapeSlashes($includeFile).'"';
				$parser->parse($includeFile);
				$result = $parser->getData();
					$allXMLdata[] = $result;
			}
		}
		
		if(isset($includeFiles))
			$content .= '$mappingFile = array('.implode(",",$includeFiles).");\n";
		
		foreach ($allXMLdata as $data) {
			for ($i = 0; $i < count($data[0]["child"]); $i ++) {
				if ($data[0]["child"][$i]["name"] == "DEFAULT-VIEWS") {
					// Get all default view paths
					for ($j = 0; $j < count($data[0]["child"][$i]["child"]); $j ++) {
						if ($data[0]["child"][$i]["child"][$j]["name"] == "VIEW") {
							$viewName = $data[0]["child"][$i]["child"][$j]["attributes"]["NAME"];
							$viewTypes[$viewName] = trim($data[0]["child"][$i]["child"][$j]["attributes"]["TYPE"]);
							$viewPath = $data[0]["child"][$i]["child"][$j]["content"];
							$viewArray[$viewName] = $viewPath;
						}
					}
				} else
					if ($data[0]["child"][$i]["name"] == "ACTION") {
						$actionName = $data[0]["child"][$i]["attributes"]["NAME"];

						$content .= '$mapping["'.$actionName.'"]["actionPath"] = '."'".$data[0]["child"][$i]["attributes"]["ACTIONPATH"]."';\n";
						$content .= '$mapping["'.$actionName.'"]["className"] = '."'".$data[0]["child"][$i]["attributes"]["CLASSNAME"]."';\n";

						// Get all view paths & filters
						for ($j = 0; $j < count($data[0]["child"][$i]["child"]); $j ++) {
							if ($data[0]["child"][$i]["child"][$j]["name"] == "VIEW") {
								$viewName = $data[0]["child"][$i]["child"][$j]["attributes"]["NAME"];
								$viewPath = $data[0]["child"][$i]["child"][$j]["content"];
								$viewArray[$viewName] = $viewPath;
								
								$viewTypes[$viewName] = trim($data[0]["child"][$i]["child"][$j]["attributes"]["TYPE"]);
							} else
								if ($data[0]["child"][$i]["child"][$j]["name"] == "REL-FILTER") {
									$filterName = $data[0]["child"][$i]["child"][$j]["attributes"]["NAME"];
									$filterRelatedMethod = $data[0]["child"][$i]["child"][$j]["attributes"]["METHOD"];
									$filterRelatedMethod = (trim($filterRelatedMethod) == "" ? "*" : trim($filterRelatedMethod));

									$content .= '$mapping["'.$actionName.'"]["'.$filterRelatedMethod.'"]["filterNames"][] = '."'".$filterName."';\n";
								}
						}
						
						foreach($viewArray as $viewName => $viewPath)
							$content .= '$mapping["'.$actionName.'"]["view"]["'.$viewName.'"] = '."'".$viewPath."';\n";
					
						foreach($viewTypes as $viewName => $viewType)
							$content .= '$mapping["'.$actionName.'"]["viewType"]["'.$viewName.'"] = '."'".$viewType."';\n";
					}
			}
		}
		$content .= "?>";

		return $content;
	}
	
	/**
	*	Get all type of return views
	*/
	
	function getAllViewTypes(){
		return $this->viewTypes;
	}
}
?>