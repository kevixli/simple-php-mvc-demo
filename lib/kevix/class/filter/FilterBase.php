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

abstract class FilterBase {
	private $parameters;
	private $actionObject;
	private $filterParameters;
	
	/**
	*	Constructor
	*/
	
	function __construct($allProperties){
		$this->parameters = $allProperties["parameters"];
		$this->actionObject = $allProperties["actionObject"];
		$this->filterParameters = $allProperties["filterParameters"];
		$this->init();
	}
	
	protected final function getFilterParameters(){
		return $this->filterParameters;
	}
	
	protected final function getParameters(){
		return $this->parameters;
	}
	
	protected final function getActionObject(){
		return $this->actionObject;
	}
	
	/**
	* 	Method is run when the filter object is created.
	*/

	abstract function init();
	
	/**
	*	Method is run when the filter object is closed.
	*/
	
	abstract function destroy();

	/**
	*	Filter In action 
	*/

	abstract function filterIn();
	
	/**
	*	Filter Out action 
	*/

	abstract function filterOut();

	/**
	*	Destructor
	*/
	 
	function __destruct() {
		$this->destroy();
	}
}
?>