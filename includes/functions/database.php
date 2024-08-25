<?php
   
      
  
 # check this function later david 
  function tep_db_connect($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link') {
 	global $link;
/*	Right after I upgraded to PHP 5.3 I started getting "MySQL server has gone away" errors 
	on almost every other page request from the apache server. I tried a few ideas that didn't work. 
	Here is the solution: I changed all my mysql_pconnect() statements to mysql_connect(). It fixed the problem. 
	For some reason PHP 5.3 does not like persistent connections. 
    if (USE_PCONNECT == 'true')  {		
     	$$link = mysql_pconnect($server, $username, $password);	

    } else {
      	$$link = mysql_connect($server, $username, $password);
    }
*/


	$link = mysqli_connect($server, $username, $password,$database);
	
  	return $link;
	
  }


  function tep_db_close($link = 'db_link') {
   	global $link;
    return mysqli_close($link);
  }

  function tep_db_error($query, $errno, $error) { 
    die('<font color="#000000"><b> Database query error:' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
  }

  function tep_db_query($query, $link = 'db_link') {
   global $link, $logger;

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      if (!is_object($logger)) $logger = new logger;
      $logger->write($query, 'QUERY');
    }
	
	
	mysqli_query($link,'SET NAMES utf8');
	global $link;
    $result = mysqli_query($link,$query) or tep_db_error($query, mysqli_errno($link), mysqli_error($link));

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      if (mysqli_error($link)) {
	  		echo mysqli_error($link);
			exit();
	  	
	  }//$logger->write(mysqli_error($link), 'ERROR');
    }

    return $result;
  }

  function tep_db_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link') {
    reset($data);
    if ($action == 'insert') {
      $query = 'insert into ' . $table . ' (';
      while (list($columns, ) = each($data)) {
        $query .= $columns . ', ';
      }
      $query = substr($query, 0, -2) . ') values (';
      reset($data);
      while (list(, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= 'now(), ';
            break;
          case 'null':
            $query .= 'null, ';
            break;
          default:
            $query .= '\''. tep_db_input($value).'\',';
            break;
        }
      }
      $query = substr($query, 0, -2) . ')';
    } elseif ($action == 'update') {
      $query = 'update ' . $table . ' set ';
      while (list($columns, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= $columns . ' = now(), ';
            break;
          case 'null':
            $query .= $columns .= ' = null, ';
            break;
          default:
            $query .= $columns . ' = \'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ' where ' . $parameters;
    }

    return tep_db_query($query, $link);
  }

  function tep_db_fetch_array($db_query) {
    return mysqli_fetch_array($db_query);
  }

  function tep_db_result($result, $row, $field = '') {
    return mysql_result($result, $row, $field);
  }

  function tep_db_num_rows($db_query) {
    return mysqli_num_rows($db_query);
  }

  function tep_db_data_seek($db_query, $row_number) {
    return mysqli_data_seek($db_query, $row_number);
  }

  function tep_db_insert_id() {
  	 global $link;
    return mysqli_insert_id($link);
  }

  function tep_db_free_result($db_query) {
  	return mysqli_free_result($db_query);
  }

  function tep_db_fetch_fields($db_query) {
   	return mysql_fetch_field($db_query);
  }

  function tep_db_output($string) {
   	return htmlspecialchars($string);
  }

  function tep_db_input($string, $link = 'db_link') {
    
	global $link;

    if (function_exists('mysql_real_escape_string')) {
    	
		return mysqli_real_escape_string($link,$string);
    
	} elseif (function_exists('mysql_escape_string')) {
	
     	return mysqli_escape_string($link,$string);
    }

    return addslashes($string);
  }

 function tep_db_Commit(){ 
 		 global $link;
  		mysqli_commit($link);		
  }
  
  
  function tep_db_Rollback(){ 
  		 global $link;	  
  		mysqli_rollback($link);	
  }
  
  function tep_db_BeginTransaction(){    		
	
	//	$$link = mysqli_autocommit(tep_db_connect($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link'), FALSE);
		tep_db_query('START TRANSACTION;');		
  } 
  
  function tep_db_prepare_input($string) {

    if (is_string($string)) {
      return trim(stripslashes($string));
    } elseif (is_array($string)) {
      reset($string);
      while (list($key, $value) = each($string)) {
        $string[$key] = tep_db_prepare_input($value);
      }
      return $string;
    } else {
      return $string;
    }
  }
  
  // this isgona be a fure function for querying databases
  function getResultsFromQuery($queryString){
   
   		$result = tep_db_query($queryString);
		
		//$data =array();
		//while($fields[] = tep_db_fetch_fields($results)){}
		 
        //while($row[] = mysql_fetch_row($results)){}
		      	 
		//$data = array_flip($row);
		//list($data) = $row;
       // return $fields;
	   
	   $i=0;
		while ($i < mysqli_num_fields($result)) {           
			$fields[]=mysqli_fetch_field($result, $i);
			$i++;
		}
	   
		while ($row = mysqli_fetch_row($result)) {               
			
			$new_row = array();
			
			for($i=0;$i<count($row); $i++) {								
				$new_row[ $fields[$i]->table][$fields[$i]->name]= $row[$i];
			}
			
			$resultdata[]= $new_row;;
		} 
	
		//print_r($resultdata);
		return $resultdata;
    }
	
	
	
	

?>
