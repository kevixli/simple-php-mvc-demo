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

include_once ("objectTool/XMLParser.php");
include_once ("tool/StringUtil.php");

/**
 * Init variables
 */
 
ComponentManager::$COMPONENT_CACHE_FILE = FRAMEWORK_ABSOLUTE_LOCATION."kevix/temp/component.tmp";
ComponentManager::$COMPONENT_XML_FILE = FRAMEWORK_ABSOLUTE_LOCATION."kevix/config/components.xml";

class ComponentManager {

	public static $COMPONENT_CACHE_FILE;
	public static $COMPONENT_XML_FILE;

	public static function & getComponent($enabler) {
		self :: cache();
		include (self :: $COMPONENT_CACHE_FILE);

		if (!is_array($component[$enabler]))
			return null;

		switch (trim($component[$enabler]["scope"])) {
			case "request" :
				include_once ($component[$enabler]["classPath"]);
				self :: checkClassExist($component[$enabler]["className"]);

				if (is_array($component[$enabler]["constructor-arg"])) {
					$initPara = '"'.implode('","', StringUtil::addEscapeSlashes($component[$enabler]["constructor-arg"])).'"';
					eval ('$object = new '.$component[$enabler]["className"].'('.$initPara.');');
				} else
					$object = new $component[$enabler]["className"] ();

				if (is_array($component[$enabler]["property"])) {
					foreach ($component[$enabler]["property"] as $propertyName => $value) {
						$method = "set".ucfirst($propertyName);
						$object-> $method ($value);
					}
				}
				return $object;
				break;

			case "session" :
				session_write_close();
				include_once ($component[$enabler]["classPath"]);
				self :: checkClassExist($component[$enabler]["className"]);
				session_start();
				$sessionName = "KEVIX_COMPONENT_".$component[$enabler]["className"];

				if (!isset ($_SESSION[$sessionName]))
					$_SESSION[$sessionName] = new $component[$enabler]["className"];

				return $_SESSION[$sessionName];
				break;
/*
			case "application" :
				include_once ($component[$enabler]["classPath"]);
				self :: checkClassExist($component[$enabler]["className"]);
				include_once("tool/Application.php");
				
				Application::start();
				Application::end();
				break;
*/
		}
	}

	public static function getComponentClassName($enabler) {
		self :: cache();
		include (self :: $COMPONENT_CACHE_FILE);
		return $component[$enabler]["className"];
	}

	/**
	 *	
	 */

	public static function getComponentScope($enabler) {
		self :: cache();
		include (self :: $COMPONENT_CACHE_FILE);
		return $component[$enabler]["scope"];
	}

	private static function checkClassExist($className) {
		if (!class_exists($className))
			die("Error in IOC component. '".$className."' does not exist! Please check components.xml.");
	}

	private static function cache() {
		if (!file_exists(self :: $COMPONENT_CACHE_FILE) || (filemtime(self :: $COMPONENT_XML_FILE) - filemtime(self :: $COMPONENT_CACHE_FILE)) > 2) {
			$parser = new XMLParser(self :: $COMPONENT_XML_FILE);
			$data = $parser->getData();
			$content .= "<?php\n";

			// Component tag looping
			for ($i = 0; $i < count($data[0]["child"]); $i ++) {
				// scope,classPath,className,enabler tag
				$enabler = trim($data[0]["child"][$i]["attributes"]["ENABLER"]);
				$content .= '$component["'.$enabler.'"]["scope"]="'.trim($data[0]["child"][$i]["attributes"]["SCOPE"])."\";\n";
				$content .= '$component["'.$enabler.'"]["classPath"]="'.trim(PROJECT_ROOT.$data[0]["child"][$i]["attributes"]["CLASSPATH"])."\";\n";
				$content .= '$component["'.$enabler.'"]["className"]="'.trim($data[0]["child"][$i]["attributes"]["CLASSNAME"])."\";\n";

				//property, constructor-arg tag
				for ($j = 0; $j < count($data[0]["child"][$i]["child"]); $j ++) {
					switch ($data[0]["child"][$i]["child"][$j]["name"]) {
						case "PROPERTY" :
							$content .= '$component["'.$enabler.'"]["property"]["'.$data[0]["child"][$i]["child"][$j]["attributes"]["NAME"].'"]="'.StringUtil::addEscapeSlashes(trim($data[0]["child"][$i]["child"][$j]["content"]))."\";\n";
							break;

						case "CONSTRUCTOR-ARG" :
							$content .= '$component["'.$enabler.'"]["constructor-arg"][]="'.StringUtil::addEscapeSlashes(trim($data[0]["child"][$i]["child"][$j]["content"]))."\";\n";
							break;

					}
				}
			}

			$content .= "?>";

			$file = fopen(self :: $COMPONENT_CACHE_FILE, "w");
			if ($file) {
				fwrite($file, $content);
				fclose($file);
			}
		}
	}
}
?>