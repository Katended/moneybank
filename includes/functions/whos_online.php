<?php

  function tep_update_whos_online() {
   
    global $user_id;

    if (tep_session_is_registered('user_id')) {
      $wo_user_id = $user_id;

      $user_query = tep_db_query("select user_firstname, user_lastname from " . TABLE_USERS . " where user_id = '" .$_SESSION['user_id'] . "'");
   
      $user = tep_db_fetch_array($user_query);

      $wo_full_name = $user['user_firstname'] . ' ' . $user['user_lastname'];
	  
    } else {
	
      $wo_user_id = '';
      $wo_full_name = 'Guest';
	  
    }


	$wo_session_id = tep_session_id();
	
    $wo_ip_address = getenv('REMOTE_ADDR');
    $wo_last_page_url = getenv('REQUEST_URI');

    $current_time = time();
    $xx_mins_ago = ($current_time - 900);

	// remove entries that have expired
    tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where time_last_click < '" . $xx_mins_ago . "'");

    $stored_customer_query = tep_db_query("select count(*) as count from " . TABLE_WHOS_ONLINE . " where session_id = '" . tep_db_input($wo_session_id) . "'");
   
    $stored_customer = tep_db_fetch_array($stored_customer_query);

    if ($stored_customer['count'] > 0) {
	
		$wo_customer_id = isset($wo_customer_id) ? $wo_customer_id : "" ;
		$wo_ip_address = isset($wo_ip_address) ? $wo_ip_address : "" ;
		$wo_ip_address = isset($wo_customer_id) ? $wo_customer_id : "" ;
		$wo_full_name = isset($wo_full_name) ? $wo_full_name : "" ;
		$current_time = isset($current_time) ? $current_time : "" ;
		$current_time = isset($current_time) ? $current_time : "" ;
		$wo_last_page_url = isset($wo_last_page_url) ? $wo_last_page_url : "" ;
		
     tep_db_query("update " . TABLE_WHOS_ONLINE . " set user_id = '" . (int)$wo_customer_id . "', full_name = '" . tep_db_input($wo_full_name) . "', ip_address = '" . tep_db_input($wo_ip_address) . "', time_last_click = '" . tep_db_input($current_time) . "', last_page_url = '" . tep_db_input($wo_last_page_url) . "' where session_id = '" . tep_db_input($wo_session_id) . "'");
    } else {
      tep_db_query("insert into " . TABLE_WHOS_ONLINE . " (user_id, full_name, session_id, ip_address, time_entry, time_last_click, last_page_url) values ('" . (int)$wo_user_id . "', '" . tep_db_input($wo_full_name) . "', '" . tep_db_input($wo_session_id) . "', '" . tep_db_input($wo_ip_address) . "', '" . tep_db_input($current_time) . "', '" . tep_db_input($current_time) . "', '" . tep_db_input($wo_last_page_url) . "')");
    }
  }
?>
