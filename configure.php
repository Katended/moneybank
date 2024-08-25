<?php
  define('HTTP_SERVER', 'http://localhost'); // eg, http://localhost - should not be empty for productive servers
  define('HTTP_CATALOG_SERVER','http://localhost');
  define('HTTPS_CATALOG_SERVER','https://localhost');
  define('ENABLE_SSL_CATALOG','false'); // secure webserver for catalog module
  define('DIR_FS_DOCUMENT_ROOT','D:/xampp/htdocs/moneybankonline/'); // where the pages are located on the server
  define('DIR_WS_ADMIN', '/moneybankonline/'); // absolute path required
  define('DIR_FS_ADMIN', 'D:/xampp/htdocs/moneybankonline/'); // absolute pate required
  define('DIR_WS_CATALOG', '/moneybankonline/'); // absolute path required
  define('DIR_FS_CATALOG', $_SERVER['DOCUMENT_ROOT'].DIR_WS_CATALOG); // absolute path required
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_IMAGES_PHOTOS',DIR_WS_IMAGES.'photos/');
  define('DIR_WS_TEMP_FILES','tempfiles/');
  define('DIR_WS_FLAG_IMAGES', DIR_WS_IMAGES.'flags/');
  define('DIR_WS_ICONS', DIR_WS_CATALOG.DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_CATALOG_IMAGES', DIR_WS_CATALOG . 'images/');
  define('__ROOT__', dirname(dirname(__FILE__))); 

 //  define('TEXT_FIELD_REQUIRED','/thebursar.atwebpages.com/images/required.gif');

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
  

  

// define our database connection
 define('DB_SERVER', '127.0.0.1'); // eg, localhost - should not be empty for productive servers
  define('DB_DATABASE', 'moneybankonline');
  define('DB_SERVER_USERNAME', 'root');
  define('DB_SERVER_PASSWORD', '');

  define('USE_PCONNECT', 'true'); // use persisstent connections?
  define('STORE_SESSIONS', 'mysql'); // leave empty '' for default handler or set to 'mysql'
  
?>