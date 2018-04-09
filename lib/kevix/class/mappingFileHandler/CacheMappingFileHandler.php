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
include_once ("XMLmappingFileHandler.php");

class CacheMappingFileHandler extends MappingFileHandler {
	private $tempMappingFileName;

	function __construct($mappingFile, $action, $method) {
		$this->tempMappingFileName = FRAMEWORK_RELATIVE_LOCATION."kevix/temp/mapping.tmp";
		parent :: __construct($this->tempMappingFileName, $action, $method);

		if (!file_exists($this->tempMappingFileName) || $this->hasSomeMappingFileUpdated($mappingFile)) {
			$parameters["mappingFile"] = $mappingFile;
			$parameters["MAPPING_TEMP_FILE"] = $this->tempMappingFileName;
			self :: Cache($parameters);
		}
	}

	private function hasSomeMappingFileUpdated($defaultMappingFile){
		include ($this->tempMappingFileName);
		$mappingFile[] = $defaultMappingFile;
		
		for ($i=0;$i < count($mappingFile);$i++){
			if((filemtime($mappingFile[$i]) - filemtime($this->tempMappingFileName)) > 2){
				return true;
				break;
			}
		}
		
		return false;
	}
	
	/**
	* 	Get related action class PHP file's path
	*/

	function getActionClassPath() {
		include ($this->tempMappingFileName);
		return $mapping[$this->action]["actionPath"];
	}

	/**
	*	Get class name of action in PHP file
	*/

	function getActionClassName() {
		include ($this->tempMappingFileName);
		return $mapping[$this->action]["className"];
	}

	/**
	*	Get all related smarty template files' paths 
	*/

	function getAllActionReturnPath() {
		include ($this->tempMappingFileName);
		return $mapping[$this->action]["view"];
	}

	/**
	*	Get all related filters' names in action class 
	*/

	function getAllFilterNames() {
		include ($this->tempMappingFileName);

		if (is_array($mapping[$this->action][$this->method]["filterNames"]) && is_array($mapping[$this->action]["*"]["filterNames"]))
			return array_merge($mapping[$this->action][$this->method]["filterNames"], $mapping[$this->action]["*"]["filterNames"]);
		else
			if (is_array($mapping[$this->action][$this->method]["filterNames"]))
				return $mapping[$this->action][$this->method]["filterNames"];
			else
				return $mapping[$this->action]["*"]["filterNames"];

	}

	/**
	*	Cache all mapping information into "kevix/temp/mapping.tmp"
	*/

	static function Cache($parameters) {
		$data = XMLmappingFileHandler :: Cache($parameters["mappingFile"]);
		$file = fopen($parameters["MAPPING_TEMP_FILE"], "w");

		if ($file) {
			fwrite($file, $data);
			fclose($file);
		}
	}
	
	/**
	*	Get all type of return views
	*/
	
	function getAllViewTypes(){
		include ($this->tempMappingFileName);
		return $mapping[$this->action]["viewType"];
	}
}
?>