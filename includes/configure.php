<?php
switch ($_SERVER['SERVER_NAME']):
case 'localhost':
    require_once('local.config.php');
    break;

case 'thebursar.atwebpages.com':
    require_once('production.config.php');
    break;

case 'development-url':
    require_once('development.config.php');
    break;

default:
endswitch;

define('DIR_FS_CATALOG', $_SERVER['DOCUMENT_ROOT'].DIR_WS_CATALOG); // absolute path required
define('DIR_WS_IMAGES', 'images/');
define('DIR_WS_IMAGES_PHOTOS',DIR_WS_IMAGES.'photos/');
define('DIR_WS_TEMP_FILES','tempfiles/');
define('DIR_WS_FLAG_IMAGES', DIR_WS_IMAGES.'flags/');
define('DIR_WS_ICONS', DIR_WS_CATALOG.DIR_WS_IMAGES . 'icons/');
define('DIR_WS_CATALOG_IMAGES', DIR_WS_CATALOG . 'images/');
define('__ROOT__', dirname(dirname(__FILE__))); 
define('TEXT_FIELD_REQUIRED','<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAYAAAAGCAYAAADgzO9IAAAAE0lEQVR42mP8z8DQzoAFMA6kBAAP/AkrzkAwtQAAAABJRU5ErkJggg==">');
// check if branch code is defined 
// check if branch code is defined 

if(isset($_SESSION['BRANCHCODE'])){  
  if(!defined('BRANCHCODE')){
    define('BRANCHCODE',$_SESSION['BRANCHCODE']);
  }
}
define('DIR_WS_INCLUDES', 'includes/');

define('DIR_WS_FUNCTIONS', 'functions/');
define('DIR_WS_CLASSES',  'classes/');

define('DIR_WS_LANGUAGES', 'languages/');
define('DIR_WS_CATALOG_LANGUAGES', DIR_WS_CATALOG . 'includes/languages/');
define('DIR_FS_CATALOG_LANGUAGES', DIR_FS_CATALOG . 'includes/languages/');
define('DIR_FS_CATALOG_IMAGES', DIR_FS_CATALOG . 'images/');
define('DIR_FS_CATALOG_MODULES', DIR_FS_CATALOG . 'includes/modules/');
define('DIR_FS_BACKUP', DIR_FS_ADMIN . 'backups/');
define("IN_PHP", true);  
