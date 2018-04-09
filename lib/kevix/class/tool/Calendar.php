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

class Calendar {
	
	/**
	 *	Define all constant variables for all weekday
	 */
	
	const SUNDAY = 0;
	const MONDAY = 1;
	const TUESDAY = 2;
	const WEDNESDAY = 3;
	const THURSDAY = 4;
	const FRIDAY = 5;
	const SATURDAY = 6;

	/**
	 *	
	 */
	 
	public static function getDatesOfWeekdayInMonth($weekdayName,$timestamp=""){
		$result = array();
		
		if ($timestamp=="")
			$timestamp = mktime();
			
		$year = date("Y",$timestamp);
		$month = date("n",$timestamp);

		for($i=1 ; $i <= self::getTotalDaysInMonth($timestamp) ; $i++){
			$ts = mktime(0,0,0,$month,$i,$year);
			$weekday = date("w",$ts);
			
			if($weekday==$weekdayName)
				$result[] = $ts;

		}
		
		return result;
	}
	
	/**
	 *	
	 */
	 
	public static function getTotalDaysInMonth($timestamp=""){
		if ($timestamp=="")
			$timestamp = mktime();
			
		return date("t",$timestamp);
	}
	
	/**
	 *	
	 */
	 
	public static function getPreviousDay($timestamp=""){
		if ($timestamp=="")
			$timestamp = mktime();
			
		$year = date("Y",$timestamp);
		$month = date("n",$timestamp);
		$day = date("j",$timestamp) -1;
		$hour = date("G",$timestamp);
		$minute = (substr(date("i",$timestamp),0,1)=="0" && strlen(date("i",$timestamp))==2 ? substr(date("i",$timestamp),1,1): date("i",$timestamp));
		$second = (substr(date("s",$timestamp),0,1)=="0" && strlen(date("s",$timestamp))==2 ? substr(date("s",$timestamp),1,1): date("s",$timestamp));
	
		return mktime($hour,$minute,$second,$month,$day,$year);
	}
	
	/**
	 *	
	 */
	 
	public static function getNextDay($timestamp=""){
		if ($timestamp=="")
			$timestamp = mktime();
			
		$year = date("Y",$timestamp);
		$month = date("n",$timestamp);
		$day = date("j",$timestamp) + 1;
		$hour = date("G",$timestamp);
		$minute = (substr(date("i",$timestamp),0,1)=="0" && strlen(date("i",$timestamp))==2 ? substr(date("i",$timestamp),1,1): date("i",$timestamp));
		$second = (substr(date("s",$timestamp),0,1)=="0" && strlen(date("s",$timestamp))==2 ? substr(date("s",$timestamp),1,1): date("s",$timestamp));
	
		return mktime($hour,$minute,$second,$month,$day,$year);
	}
	
}
?>