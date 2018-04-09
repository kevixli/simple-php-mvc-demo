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
include_once ("ViewerFactory.php");

Class ViewController {
	public function process(&$actionHandler,$type) {
		$framework = $actionHandler->getFrameworkVar();
		$actionClass = $actionHandler->getActionClass();
		$methodsInActionClass = $actionHandler->getMethodsInActionClass();
		$returnPage = $actionHandler->getReturnPage();
		$views = $actionHandler->getViews();
		
		switch ($type) {
			case "" :
			case "template" :
				$pageLayout = ViewerFactory :: getViewer($framework["viewerType"]);
				$pageLayout->assign("errors", $actionClass->getErrors());

				foreach ($methodsInActionClass as $method_name) {
					if (substr($method_name, 0, 3) == 'get')
						$pageLayout->assign(substr(strtolower($method_name), 3), call_user_method($method_name, $actionClass));
				}

				if ($views[$returnPage] != "")
					$pageLayout->display($views[$returnPage]);
				else
					die("No view for '$returnPage'");

				break;

			case "php" :
			
				$errors = $actionClass->getErrors();

				foreach ($methodsInActionClass as $method_name) {
					if (substr($method_name, 0, 3) == 'get')
						${ substr(strtolower($method_name), 3) } = call_user_method($method_name, $actionClass);
				}

				include ($views[$returnPage]);
				break;

			case "xml" :
				break;

			case "redirect" :
				header("Location: $views[$returnPage]");
				break;
			
			case "action" :
				$actionPart = explode("!", $views[$returnPage], 2);
				
				foreach ($methodsInActionClass as $method_name) {
					if (substr($method_name, 0, 3) == 'get')
						$requestData[substr(strtolower($method_name), 3)] = call_user_method($method_name, $actionClass);
				}
				
				$actionHandler->ActionHandler($actionPart[0], $framework, $requestData,$actionPart[1]);
				$actionHandler->run();
				break;
				
			default :
				die("The type of view must be 'php','template','xml','action' or 'redirect'!");
				break;
		}
	}
}
?>