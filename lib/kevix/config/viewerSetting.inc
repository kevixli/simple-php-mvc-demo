<?php
/**
 * 	Smarty (http://smarty.php.net)
 */

$Smarty["viewerDirPath"] = "lib/Smarty/";
$Smarty["template_dir"] = 'template/';
$Smarty["compile_dir"] = 'template_c/';
$Smarty["config_dir"] = 'lib/Smarty/';
$Smarty["left_delimiter"] = '{';
$Smarty["right_delimiter"] = '}';


/**
 * 	PHPTAL (http://phptal.motion-twin.com)
 */

$PHPTAL["PHPTAL_FORCE_REPARSE"]= 0;
$PHPTAL["PHPTAL_PHP_CODE_DESTINATION"] = "template_c/";
$PHPTAL["PHPTAL_TEMPLATE_REPOSITORY"] = "template/";


/**
 * 	Savant 2 (http://phpsavant.com/yawiki/index.php?area=Savant2)
 */

$Savant2["compileDir"] = "template_c/";
$Savant2["template"] = "template/";
$Savant2["forceCompile"] = false;
$Savant2["prefix"] = "{";
$Savant2["suffix"] = "}";
$Savant2["allowPHP"] = false;

?>