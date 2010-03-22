<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

define('APPLICATION_ENVIRONMENT', 'testing');
define('BASE_PATH', realpath(dirname(__FILE__) . '/../'));
define('TESTS_PATH', BASE_PATH . '/tests/');

$pathes = array();
$pathes[] = BASE_PATH . '/library/';
$pathes[] = TESTS_PATH;
$pathes[] = get_include_path();

// Include path
set_include_path(implode($pathes, PATH_SEPARATOR));

// Zend_Loader
require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->setFallbackAutoloader(true);

define('TESTS_CONFIG_ROBO47_EXIFTOOL_EXIFTOOLPATH', '/usr/bin/exiftool');