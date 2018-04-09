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
include_once ("tool/ComponentManager.php");

class IoCcomponentFilter extends FilterBase {
	function init() {
		$param =$this->getFilterParameters();
		
		if(isset($param["XMLFile"]))
			ComponentManager::$COMPONENT_XML_FILE = PROJECT_ROOT.$param["XMLFile"];
			
		if(isset($param["cacheFile"]))
			ComponentManager::$COMPONENT_CACHE_FILE = PROJECT_ROOT.$param["cacheFile"];
		
	}

	function destroy() {
	}

	function filterIn() {
		$implementNames = class_implements($this->getActionObject());

		foreach ($implementNames as $enabler) {
			$object = ComponentManager :: getComponent(trim($enabler));
			
			if (!is_null($object)) {
				$methodName = "set".ComponentManager :: getComponentClassName(trim($enabler));
				$this->getActionObject()-> $methodName (& $object);
			}
		}
	}

	function filterOut() {
	}
}
?>