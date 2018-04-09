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

include_once("objectTool/XMLParser.php");

class FilterFactory {
	private static $result = "";
	
	public static function getFilter($filterName,$allProperties){
		if(self::$result ==""){
			$parser = new XMLParser(FRAMEWORK_ABSOLUTE_LOCATION."kevix/config/filters.xml");
			self::$result = $parser->getData();
		}
		
		for($i=0;$i<count(self::$result[0]["child"]);$i++){			
			if(self::$result[0]["child"][$i]["attributes"]["NAME"] == $filterName){
				foreach(self::$result[0]["child"][$i]["attributes"] as $attribName =>$content)
					${$attribName} = $content;
				
				for($j=0;$j<count(self::$result[0]["child"][$i]["child"]);$j++){
					if(self::$result[0]["child"][$i]["child"][$j]["name"]=="PARAM"){
						$filterParameters[self::$result[0]["child"][$i]["child"][$j]["attributes"]["NAME"]] = trim(self::$result[0]["child"][$i]["child"][$j]["content"]);
					}
				}
					
				include_once($FILTERPATH);
				$allProperties["filterParameters"] = $filterParameters;
				return new $CLASSNAME($allProperties);
			}
		}
		
		return null;
	}
}
?>