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
 

/**
 *	Define the location of framework folder 
 */
define('FRAMEWORK_RELATIVE_LOCATION', "lib/");

define('PROJECT_ROOT', realpath(dirname(__FILE__))."/");
define('FRAMEWORK_ABSOLUTE_LOCATION', PROJECT_ROOT."lib/");


/**
 * 	Start processing
 */

session_start();
include_once (FRAMEWORK_RELATIVE_LOCATION."kevix/config/mainSetting.inc");
ini_set("include_path", ini_get('include_path').PATH_SEPARATOR.FRAMEWORK_RELATIVE_LOCATION."kevix/class/");
include_once ("mappingFileHandler/MappingFileHandlerFactory.php");
include_once ("view/ViewerFactory.php");
include_once ("view/ViewController.php");
include_once ("action/ActionFactory.php");
include ("filter/FilterFactory.php");

class ActionHandler {

	/**
	 *		$action is value of action name
	 *		$actionClass is action class is called and found by $action value.
	 *		$returnPage is string value for 'return page' and is a keyword for finding return page's path.
	 *		$classname is action class name.
	 */

	private $actionClass, $returnPage, $action, $className, $ActionFilePath, $method;
	private $framework = array ();
	private $views = array ();
	private $requestData = array ();
	private $methodsInActionClass = array();

	function ActionHandler($action, $framework, $requestData,$method = "") {
		$this->action = $action;
		$this->framework = $framework;
		$this->method = $method;
		$this->requestData = $requestData;
	}

	function run() {
		/**
		 *	Read action mapping file & get suitable mapping file handler
		 */

		$mappingFileHandler = MappingFileHandlerFactory :: getMappingFileHandler(FRAMEWORK_RELATIVE_LOCATION."kevix/config/default.actions.xml", $this->action, $this->method, $this->framework["cachingMappingFile"]);

		/**
		 *	Get & check action class path is set or not
		 */

		$actionClassPath = $mappingFileHandler->getActionClassPath();

		if ($actionClassPath == "")
			$this->errorMessage("\"$this->action\" action class's file path is not defined in mapping file.");

		/**
		 * 	Get all return view template file paths
		 */

		$this->views = $mappingFileHandler->getAllActionReturnPath();

		/**
		 * 	Get action class name
		 */

		$className = $mappingFileHandler->getActionClassName();

		/**
		 *	Get all related filters' names in action class 
		 */

		$filterNames = $mappingFileHandler->getAllFilterNames();

		/**
		 * 	Get types of views
		 */

		$viewTypes = $mappingFileHandler->getAllViewTypes();
		
		/**
		 *	Get action class
		 */
		 
		$this->actionClass = ActionFactory::getActionClass($this->framework["actionDIR"]."/".$actionClassPath, $className);
			
		/**
		 * 	get all methods' names in action class
		 */

		$this->methodsInActionClass = get_class_methods($className);

		/**
		 *	set all request value(s) into action class
		 */

		foreach ($this->methodsInActionClass as $method_name) {
			if (substr($method_name, 0, 3) == "set")
				call_user_method($method_name, $this->actionClass, $this->requestData[strtolower(substr($method_name, 3))]);
		}

		/**
		 *	Start Filter
		 */

		$filters = array ();
		$shortCircuit = false;
		$allProperties["parameters"] = $this->requestData;
		$allProperties["actionObject"] = & $this->actionClass;

		for ($i = 0; $i < count($filterNames); $i ++) {
			$filter = FilterFactory :: getFilter($filterNames[$i], $allProperties);

			if (is_null($filter))
				$this->errorMessage("'$filterNames[$i]' filter does not defined in filters.xml!");

			$filters[] = $filter;
			$this->returnPage = $filter->filterIn();

			if ($this->returnPage != "") {
				$shortCircuit = true;
				break;
			}
		}

		if (!$shortCircuit) {
			/**
			 *	execute action
			 */

			$this->actionClass->prepare();

			if ($this->method == "")
				$this->returnPage = $this->actionClass->execute();
			else
				if (method_exists($this->actionClass, $this->method))
					$this->returnPage = call_user_method($this->method, $this->actionClass);
				else
					$this->errorMessage("'$this->method' function does not exists in '$className' Class!");
		}

		/**
		 *	End Filter
		 */

		reset($filters);

		for ($i = count($filters) - 1; 0 <= $i; $i --) {
			$filter = $filters[$i];
			$filter->filterOut();
			unset($filter);
		}

		/**
		 * 	Processing view part connection
		 */

		ViewController::process($this,$viewTypes[$this->returnPage]);
	}

	/**
	 *	print error message
	 */

	private function errorMessage($msg) {
		die("FRAMEWORK ERROR : $msg");
	}
	
	public function getViews(){
		return $this->views;
	}

	public function getMethodsInActionClass(){
		return $this->methodsInActionClass;
	}
	
	public function getFrameworkVar(){
		return $this->framework;
	}
	
	public function getActionClass(){
		return $this->actionClass;
	}
	
	public function getReturnPage(){
		return $this->returnPage;
	}
}

$action = (trim($_GET["__action__"]) != "" ? $_GET["__action__"]:$_POST["__action__"]);
$actionPart = explode("!", $action, 2);
$actionHandler = new ActionHandler($actionPart[0], $framework, array_merge($_GET, $_POST),$actionPart[1]);
$actionHandler->run();
?>