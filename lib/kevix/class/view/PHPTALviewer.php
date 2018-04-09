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

include_once ("ViewerBase.php");

class PHPTALviewer extends ViewerBase {
	
	function PHPTALviewer($parameters) {
		define('PHPTAL_TEMPLATE_REPOSITORY', $parameters["PHPTAL_TEMPLATE_REPOSITORY"]);
		define('PHPTAL_PHP_CODE_DESTINATION', $parameters["PHPTAL_PHP_CODE_DESTINATION"]);
		define('PHPTAL_FORCE_REPARSE', $parameters["PHPTAL_FORCE_REPARSE"]);
		
		include_once ("PHPTAL.php");

		$templateObject = new PHPTAL();
		$this->setViewObject($templateObject);
	}

	public function display($viewPage) {
		$this->getViewObject()->setTemplate($this->templateRoot.$viewPage);
		echo $this->getViewObject()->execute();
	}

	public function assign($index, $value) {
		$this->getViewObject()->set($index, $value);
	}
}
?>