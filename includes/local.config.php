<?php
 
  define('HTTP_SERVER', 'http://localhost'); // eg, http://localhost - should not be empty for productive servers
  define('HTTP_CATALOG_SERVER','http://localhost');
  define('HTTPS_CATALOG_SERVER','https://localhost');
  define('ENABLE_SSL_CATALOG','false'); // secure webserver for catalog module
  define('DIR_FS_DOCUMENT_ROOT','D:/xampp/htdocs/moneybankonline/'); // where the pages are located on the server
  define('DIR_WS_ADMIN', '/moneybankonline/'); // absolute path required
  define('DIR_FS_ADMIN', 'D:/xampp/htdocs/moneybankonline/'); // absolute pate required
  define('DIR_WS_CATALOG', '/moneybankonline/'); // absolute path required
  
// define our database connection
  define('DB_SERVER', '127.0.0.1'); // eg, localhost - should not be empty for productive servers
  define('DB_DATABASE', 'moneybankonline');
  define('DB_SERVER_USERNAME', 'root');
  define('DB_SERVER_PASSWORD', '');

  define('USE_PCONNECT', 'true'); // use persisstent connections?
  define('STORE_SESSIONS', 'mysql'); // leave empty '' for default handler or set to 'mysql' 
?>