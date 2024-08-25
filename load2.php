<?php
require_once('includes/application_top.php');
require_once('includes/classes/DataTable.php');
//require_once('includes/classes/productconfig.php');
require_once('includes/classes/common.php');

//$newgrid = new Grid;
error_reporting(E_ALL ^ E_NOTICE);

//$newgrid->Conn = $Conn;
Common::$connObj = $Conn;
//$newgrid->queryoptions = array();
//$newgrid->queryoptions['container'] = '';
//$frmid = filter_input(INPUT_POST, 'frmid');
//
//$newgrid->queryoptions['searchterm'] = $_GET['searchterm'];
//
// if (isset($_GET['page'])) { 
//        
//    $newgrid->queryoptions['setnumber'] = $_GET['setnumber'];
//    $newgrid->queryoptions['start'] = $_GET['start'];
//    $newgrid->queryoptions['page'] = $_GET['page'];
//    $newgrid->queryoptions['sortorder'] = 'ASC';  
//    $newgrid->queryoptions['frmid'] = $frmid;
//    
//} else {    
//    
//    $newgrid->queryoptions['frmid'] = $_POST['frmid'];
//    $newgrid->queryoptions['setnumber'] = 1;
//    $newgrid->queryoptions['start'] = 0;
//    $newgrid->queryoptions['page'] = 1;
//    $newgrid->queryoptions['sortorder'] = 'ASC';
//    $newgrid->queryoptions['element'] = $_POST['theid'];
// 
//}

spl_autoload_register(function ($class_name) {
    include 'includes/classes/' . $class_name . '.php';
});


// DB table to use
// $table = 'datatables_demo';
 
// Table's primary key
$primaryKey = 'client_idno';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
//$columns = array(    
//    array( 'db' => 'checkbox', 'dt' => 0 ),
//    array( 'db' => 'client_surname',  'dt' => 1 ),
//    array( 'db' => 'client_firstname',   'dt' => 2 ),
//    array( 'db' => 'client_middlename',     'dt' => 3 )
//    
//);  
$fieldlist = array('checkbox','client_surname', 'client_firstname',  'client_middlename');
Datatable::prepareFieldList($fieldlist);
$query = "SELECT  *,'<input type=checkbox >' as checkbox FROM clients";

$json = json_encode(Datatable::simple($_POST, $primaryKey,$query));

echo $json;