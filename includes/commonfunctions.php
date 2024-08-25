<?php 	
	/*
	 * Display current user. If no user is logged in, display Global as
	 * the username.
	 */
	 function displayCurrentUser() {
	 	if (!isset($_SESSION['names'])) {
			return "Global";	
		} else {
			 return $_SESSION['names'];
		}
	 }
	
	
	/*
	 * Return the current date/time to be displayed on all pages.
	 * The time is Eastern Standard Time which is GMT - 5hrs.
	 */
	function getCurrentTime() {
            echo date("m/d H:i", timeCaringDayLightSaving(-18000))." EST";
	}

	
	# Function to transform a date from MySQL database Format (yyyy-mm-dd) to the format displayed on pages(mm/dd/yyyy).
	# If the date from the database is NULL, it is transformed to an empty string for display on the pages 
	function changeMySQLDateToPageFormat($mysqldate) {	
		
		if($mysqldate == NULL) {
			$pagedate = "";
		} else {

                    //$pagedate = date("m/d/Y", strtotime($mysqldate));
                    $pagedate = date(SETTING_DATE_FORMAT, strtotime($mysqldate));    
                    
		}
		return $pagedate;	
	}
	
	# Function to transform a date from the format displayed on pages(mm/dd/yyyy) to the MySQL database date format (yyyy-mm-dd).
	# If the date from the database is an empty string, it is transformed to a NULL value. Note that single quotation marks are added to
	# the non-empty date. 
	function changeDateFromPageToMySQLFormat($pagedate) {	
		if (trim($pagedate)== "") {
			$mysqldate = "NULL";
		} else {
 //                     Dates in the m/d/y or d-m-y formats are disambiguated by looking at the separator 
//                      between the various components: if the separator is a slash (/), 
//                      then the American m/d/y is assumed; whereas if the separator is a dash (-) or a dot (.),
//                      then the European d-m-y format is assumed.
                        $mysqldate = str_replace('/', '-', $mysqldate);
			$mysqldate = "'".date("Y-m-d", strtotime($pagedate))."'";
		}		
		return $mysqldate;	
	}
	
	
	



	#Function to generate a random alphanumeric string of a specified length
	function get_rand_id($length) {
	  if($length>0) 
	  { 
	  $rand_id="";
		for($i=1; $i<=$length; $i++)
		{
		mt_srand((double)microtime() * 1000000);
		$num = mt_rand(0,35);
		$rand_id .= assign_rand_value($num);
		}
	  }
	return $rand_id;
	} 
	
	
	# Function to obtain a comma delimited string from an array
	function getCommaDelimitedListFromArray($array){
		$arraystring = "";
		# check if the array is empty and return an empty string
		if (count($array) == 0) {
			return "";
		}
		foreach($array as $value) {
			$arraystring .= ",".$value;
		}
		
		$arraystring = substr($arraystring,1);
		return $arraystring;
	}



	#Function to obtain an array from a comma delimited list
	function getArrayFromCommaDelimitedList($string) {
		# handle empty strings
		# return a blank array
		if (trim($string) == "") {
			return array();
		}
		$i = 0;
		#remove all leading and trailing spaces
		$string = trim($string);
		#find the first ocurrence of a comma
		$pos = strpos($string, ",");
		while(!($pos === false)) {
                    #set the new starting position
                    $firstpos = $pos + 1;
                    #if the string is in the second position, set the first array value
                    $lastpos = $pos;
                    $array[$i] = substr($string, 0, $lastpos);		
                    #reduce the string to start from the new starting position. $firstpos
                    $string = substr($string, $firstpos);		
                    $string = trim($string);
                    #locate the first occurence of a comma in the string
                    $pos = strpos($string, ",");
                    $i++;
	
		}
		$array[$i] = $string;
		return $array;
	}

	
    # returns an arrow pointing upwards for ASC (ascending order) and downwards 
    # for DESC (descending order) when sorting a list.
    function getArrowFromSortOrder($sortorder){
         if ($sortorder == "ASC") {
                return "<img src=\"../images/arrowdown.gif\" border=\"0\">";
         }else if ($sortorder == "DESC") { 
                        return "<img src=\"../images/arrowup.gif\" border=\"0\">";
         }
    }
	
		// Function to cast a value of type date to a string
    function getStringFromDate($date) {		
        if($date == NULL) {
            $dateString = "";
        } else {
            $datestring = date("m/d/Y", strtotime($date));
        }
        return $datestring;	
    }
    // Function to cast a string value to a date
    function getDateFromString($string) {	
        if ($string == "") {
                $date = "NULL";
        } else {
                $date = "'".date("Y-m-d", strtotime($string))."'";
        }
        return $date;
    }
	

    # Function that highlights a link of the current file the user is browsinghighlights the link to the current page 
    function highlightLink($filename, $stylesheetclass) {			
        # get the name of the current file
        # This code obtains the file name of the current page
        $currentFile = $_SERVER["SCRIPT_NAME" ]; 
        $parts = Explode( '/', $currentFile); 
        $currentFile =  $parts[count($parts) - 1 ]; 


        # check whether user is browsing the current page
        if ($currentFile == $filename) {

                $linkstyle = 'in'.$stylesheetclass;

        } else {
                $linkstyle = $stylesheetclass;

        }

        return $linkstyle;

    } 
	

    # function to format a number with two decimal places and a comma for 1000th separator
    function formatNumber($number) {
        //return sprintf("%01.2f", number_format($number, 2, '.', ''));
        return number_format($number, 2);
    }

    # function to format a number with one decimal place and a comma for 1000th separator
    function formatNumberWithOneDecimalPlace($number) {
        return number_format($number, 1);
    }

    # function to format a number with two decimal places without a comma for the 1000th separator
    function formatTotalForInvoice($number) {
        # check if the total is 0.00
        if ($number == "0.00") {
                return "0";
        }
        # return the total with two decimal places and no thousandth separator
        # we use sprintf to add a zero since the total is rounded off to two decimal places
        # 123.4 becomes 123.40
        # 123.45 remains 123.45
        return sprintf("%s.2f",$number);
    }


    # get the code for a field with fixed value, these are values that are obtained from 
    # a select box. When the text is empty or has a value All, a LIKE comparison is used
    # and an equlas is used for all other values
    function getSearchCriteriaForFieldWithFixedValue($text) {
        if (trim($text) == "") {
                return " LIKE '%' ";
        } else if (trim($text) == "All") {
                return " LIKE '%' ";
        } else {
                return " = '".$text."'";
        }

    }

    function getBadgeNumberForEmployee($employeeid) {
            $badge = getRowAsArray("SELECT tsgbadgenumber FROM employee WHERE employee.id = '".$employeeid."'");
            return $badge['tsgbadgenumber'];
    }


    # Generates HTML select options from data in an array. This function assumes that the
    # array key is the value of the option and the array key value is the text to be displayed
    # If the current value is an empty sting, a <Select One> option is displayed
    # as the first option
    function generateCheckboxOptions2($optionvalues, $name, $currentvalues, $columns, $idprefix = "",$jscriptfucntion="") {
        //print_r(array_keys($optionvalues));
        //print_r($currentvalues);
        #$columns = NUM_OF_COLUMNS;
        $checkboxHTML = "";
        $counter = 0;
        $checkboxHTML .= "<table cellpading=\"4\" cellspacing=\"0\" border=\"0\"><tr>";
        foreach($optionvalues as $key => $value) {
                $counter++;
                $col = $counter%$columns;
                $checkboxHTML .= "<td nowrap=\"nowrap\" align=\"left\"><input type=\"checkbox\" "; 
                if (in_array($key, $currentvalues)) {
                        $checkboxHTML .= "checked "; 
                 } 
                $checkboxHTML .= "name=\"".$name."[]\" id=\"".$idprefix.$key."\" class=\"checkbox\" value=\"".$key."\">&nbsp;&nbsp;".$value."</td>";
                 if($col==0) {			 	
                        $checkboxHTML .= "</tr><tr>";
                 }
        }

        $checkboxHTML .= "<td></td><td></td><td></td><td></td></tr></table>";
        return $checkboxHTML;
    }
	
	function generateCheckboxOptions($optionvalues, $name, $currentvalues, $columns, $idprefix = "",$jscriptfucntion="") {
		//print_r(array_keys($optionvalues));
		//print_r($currentvalues);
		#$columns = NUM_OF_COLUMNS;
		$checkboxHTML = "";
		
		$counter = 0;
		
		$checkboxHTML .= "<div>";
		
		foreach($optionvalues as $key => $value) {
			$counter++;
			$col = $counter%$columns;
			$checkboxHTML .= "<label for='o'".$counter."><input type=\"checkbox\" "; 
			if (in_array($key, $currentvalues)) {
				$checkboxHTML .= "checked "; 
			 } 
		 	$checkboxHTML .= "name=\"".$name."[]\" id=\"".$idprefix.$key."\" class=\"checkbox\" value=\"".$key."\">&nbsp;&nbsp;".$value;
			 if($col==0) {			 	
			 	$checkboxHTML .= "</label>";
			 }
		}
		
		$checkboxHTML .= " </div>";
		return $checkboxHTML;
	}
	
	
	function generateBranchCombo($branchcode="") {
		$query_results = tep_db_query("SELECT branch_code, licence_organisationname FROM " . TABLE_LICENCE . " WHERE licence_build='" . $_SESSION['licence_build'] . "'");
		
		$combo ="<SELECT id='branchcode' name='branchcode'>".		
		"<option id='' value=''>All Branches</option>";		
		while($arrayresults = tep_db_fetch_array($query_results)){
			if($branchcode==$arrayresults['branch_code']){
				$combo .="<option id='".$arrayresults['branch_code']."' value='".$arrayresults['branch_code']."' selected>".$arrayresults['branch_code'].':'.$arrayresults['licence_organisationname']."</option>";
			}else{
				$combo .="<option id='".$arrayresults['branch_code']."' value='".$arrayresults['branch_code']."'>".$arrayresults['branch_code'].':'.$arrayresults['licence_organisationname']."</option>";
			}
		
		}
		$combo .="</SELECT>";
		
		return $combo;
	
	}
	
	
	function generateCurrencyCombo() {
		
			 $combo ="<table cellpadding='0' cellspacing='0'><tr><td>".$cLabel;		
		
			 $currencies_query = tep_db_query("SELECT currencies_id,currencies_name,currencies_code FROM " . TABLE_CURRENCIES." ORDER BY currencies_name,currencies_code");				 		  
			  
			 $combo.="<select name='currencies_id'id='currencies_id'>";
			 $combo.="<option id='' value=''></option>";
			
			 while ($currencies_array = tep_db_fetch_array($currencies_query)) {
				 $combo.="<option id='".$currencies_array['currencies_id']."' value='".$currencies_array['currencies_id']."'>".$currencies_array['currencies_name']." (".$currencies_array['currencies_code'].")</option>";
			  }				  
			  
			  $combo.="</select></td>";
			  $combo.="<td align='left' id='flag'></td>";
			  $combo.="</tr>";
			  $combo.=" </table>";	
			  
			  return  $combo;		
	}
?>