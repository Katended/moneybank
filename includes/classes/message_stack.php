<?php
/*
  $Id: message_stack.php,v 1.6 2003/06/20 16:23:08 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  Example usage:

  $messageStack = new messageStack();
  $messageStack->add('Error: Error 1', 'error');
  $messageStack->add('Error: Error 2', 'warning');
  if ($messageStack->size > 0) echo $messageStack->output();
*/

  class messageStack {
    var $size = 0;
	var $errors = array();
	
    function messageStack() {
	
     	global $messageToStack;
	 
     	$this->errors = array();

    
	  
    }

    function add($message, $type = 'error') {
        
        $this->errors[] = array('params' => '', 'text' => $message);   

     	 $this->size++;
    }

    function add_session($message, $type = 'error') {
      global $messageToStack;

      if (!session_is_registered('messageToStack')) {
        session_register('messageToStack');
        $messageToStack = array();
      }

      $messageToStack[] = array('text' => $message, 'type' => $type);
    }

    function reset() {
      $this->errors = array();
      $this->size = 0;
    }

    function output() {
		
     	
	 	// if (isset( $this->errors[$i][0]) && is_array( $this->errors[$i][0])) {	
			
			reset($this->errors);
			
			//print_r($this->errors);
			$tableBox_string ="";
		 	for ($x=0, $y=sizeof($this->errors[$x]); $x<$y; $x++) {  
	  		 //echo $this->errors[0]['text'];
			 	$tableBox_string .=  $this->errors[$x]['text'];    
			}
			
			//print_r($this->errors);	
			//exit();
			$this->reset();
			
   			return $tableBox_string;
			//exit();
		
    }
  }
?>
