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

class FileUploadFilter extends FilterBase {
	private $allowedTypesErrMsg = "File type is not in allowed list.";
	private $eachMaxSizeErrMsg = "Total files' size is greater than limited size.";
	private $totalMaxSizeErrMsg = "Total files' size is greater than total limited size.";
	private $totalMaxSizeErrField = "totalFiles";
	private $properties;

	function init() {
		$this->properties = $this->getFilterParameters();

		if (isset ($this->properties["allowedTypesErrMsg"]))
			$this->allowedTypesErrMsg = $this->properties["allowedTypesErrMsg"];

		if (isset ($this->properties["eachMaxSizeErrMsg"]))
			$this->eachMaxSizeErrMsg = $this->properties["eachMaxSizeErrMsg"];

		if (isset ($this->properties["totalMaxSizeErrMsg"]))
			$this->totalMaxSizeErrMsg = $this->properties["totalMaxSizeErrMsg"];

		if (isset ($this->properties["totalMaxSizeErrField"]))
			$this->totalMaxSizeErrField = $this->properties["totalMaxSizeErrField"];
	}

	function destroy() {
	}

	function filterIn() {
		if (isset ($_FILES)) {
			$totalFileSize = 0;
			if ($this->properties["allowedTypes"] != "")
				$allowTypes = explode(",", $this->properties["allowedTypes"]);

			foreach ($_FILES as $fieldName => $array) {
				if (is_array($_FILES[$fieldName]['name'])) {
					for ($index = 0; $index < count($_FILES[$fieldName]['name']); $index ++) {

						if (trim($_FILES[$fieldName]['tmp_name'][$index]) == "")
							continue;

						$totalFileSize += $_FILES[$fieldName]['size'][$index];

						if (isset ($this->properties["eachMaxSize"]) && ($_FILES[$fieldName]['size'][$index] > $this->properties["eachMaxSize"]))
							$this->getActionObject()->addError($fieldName, $this->eachMaxSizeErrMsg);

						if (isset ($allowTypes)) {
							$isTypeAllow = false;

							for ($i = 0; $i < count($allowTypes); $i ++) {
								if ($_FILES[$fieldName]['type'][$index] == trim($allowTypes[$i])) {
									$isTypeAllow = true;
									break;
								}
							}

							if (!$isTypeAllow)
								$this->getActionObject()->addError($fieldName, $this->allowedTypesErrMsg);
						}
					}
				} else {
					if (trim($_FILES[$fieldName]['tmp_name']) == "")
						continue;

					$totalFileSize += $_FILES[$fieldName]['size'];

					if (isset ($this->properties["eachMaxSize"]) && ($_FILES[$fieldName]['size'] > $this->properties["eachMaxSize"]))
						$this->getActionObject()->addError($fieldName, $this->eachMaxSizeErrMsg);

					if (isset ($allowTypes)) {
						$isTypeAllow = false;

						for ($i = 0; $i < count($allowTypes); $i ++) {
							if ($_FILES[$fieldName]['type'] == trim($allowTypes[$i])) {
								$isTypeAllow = true;
								break;
							}
						}

						if (!$isTypeAllow)
							$this->getActionObject()->addError($fieldName, $this->allowedTypesErrMsg);
					}
				}
			}
			if (isset ($this->properties["totalMaxSize"]) && ($totalFileSize > $this->properties["totalMaxSize"]))
				$this->getActionObject()->addError($this->totalMaxSizeErrField, $this->totalMaxSizeErrMsg);

			if ($this->getActionObject()->hasError())
				return "form";
		}
	}

	function filterOut() {
	}

}
?>