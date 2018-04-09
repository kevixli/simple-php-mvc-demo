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

abstract class MappingFileHandler {
	
	protected $mappingFile;
	protected $action;
	protected $method;
	
	function __construct($mappingFile,$action,$method){
		$this->mappingFile = $mappingFile;
		$this->action = $action;
		$this->method = $method;
	}
	
	/**
	* 	Get related action class PHP file's path
	*/

	abstract function getActionClassPath();
	
	/**
	*	Get class name of action in PHP file
	*/
	
	abstract function getActionClassName();

	/**
	*	Get all related smarty template files' paths 
	*/

	abstract function getAllActionReturnPath();

	/**
	*	Get all related filters' names in action class 
	*/
	
	abstract function getAllFilterNames();
	
	/**
	*	Cache all mapping information
	*/
	
	abstract static function Cache($mappingFile);
	
	/**
	*	Get all type of return views
	*/
	
	abstract function getAllViewTypes();
}
?>