<?php
  define('HTTP_SERVER', 'http://thebursar.atwebpages.com'); // eg, http://localhost - should not be empty for productive servers
  define('HTTP_CATALOG_SERVER','http://thebursar.atwebpages.com');
  define('HTTPS_CATALOG_SERVER','https://thebursar.atwebpages.com');
  define('ENABLE_SSL_CATALOG','false'); // secure webserver for catalog module
  define('DIR_FS_DOCUMENT_ROOT','/home/www/thebursar.atwebpages.com/'); // where the pages are located on the server
  define('DIR_WS_ADMIN', '/thebursar.atwebpages.com/'); // absolute path required
  define('DIR_FS_ADMIN', '/home/www/thebursar.atwebpages.com/'); // absolute pate required
  define('DIR_WS_CATALOG', '/thebursar.atwebpages.com/'); // absolute path required
 
  define('DB_SERVER','fdb1027.runhosting.com'); // eg, localhost - should not be empty for productive servers
  define('DB_SERVER_USERNAME', '4514587_thebursar');
  define('DB_SERVER_PASSWORD', 'Kate1Davis2000');
  define('DB_DATABASE', '4514587_thebursar');
  define('USE_PCONNECT', 'true'); // use persisstent connections?
  define('STORE_SESSIONS', 'mysql'); // leave empty '' for default handler or set to 'mysql'
?>