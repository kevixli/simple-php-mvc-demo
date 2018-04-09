<?php
/**
 * 	Framework setting :
 * 
 * 	$framework["cachingMappingFile"] : whether all mapping files cached into one temp file.
 * 	$framework["actionDIR"]			 : the relative location of root directory of all action classes
 * 	$framework["viewerType"] 		 : The type of software which is chosen for view part processing (Smarty/PHPTAL/Savant2)
 */

$framework["cachingMappingFile"] = true;
$framework["actionDIR"] = "";
$framework["viewerType"] = "Smarty";
?>