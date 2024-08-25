<?php
//include_once "commonfunctions.php";
class ListManager {
	var $searchterm = "";
	var $searchcolumns = array();
	var $urlparameters = array();
	var $searchparameters = array();
	var $defaultsortfield = "";
	var $defaultsortorder = "ASC";
	var $sortorder = "ASC";
	var $newsortorder = "";
	var $sortfield = "";
	var $listURL = "";
	var $filtercolumn = "";
	
	# These are parameters that are always added to the List URL when necessary 
	var $listURLParameters = "";
	var $filteroption = "";	
	var $yearoption = "";//date("Y");
	var $monthoption = ""; // date("n");
	var $start = "";
	var $setnumber = 0;
	var $totalrows = 0;
	var $lastpage = "";
	var $totalsets = 0;
	var $weekendingdate ='';
	var $modifier = '';
	var $numberof_rows_on_page = 15;
	var $page = "";

		
	function getSearchParameter() {
		return $this->searchterm;
	}

	function getSearchColumns() {
		return $this->searchcolumns;
	}
	
	function getFilterOption() {
		return $this->filteroption;
	}
	
	function processRequest($arrayvalues){
		$this->modifier =  trim($arrayvalues['modifier']);
		$this->yearoption =  trim($arrayvalues['year']);
		$this->monthoption =  getMonthNumberFromString( trim($arrayvalues['month']));
		if(isset($arrayvalues['searchterm'])) {
			$this->searchterm =  trim($arrayvalues['searchterm']);;
		} else {
			$this->searchterm = '';
		}

		
		$this->weekendingdate=  trim($arrayvalues['weekendingdate']);
		
		#Obtain the start from the array values else set it to 0
		if (isset($arrayvalues["start"]) ) {
			$this->start =  trim($arrayvalues["start"]);;
		} else {
			$this->start = 0;
		}	 

		$this->setnumber =  trim($arrayvalues["setnumber"]);
		# sorting options
		if ( isset($arrayvalues['sortfield']) ) {
			$this->sortfield = trim($arrayvalues['sortfield']);
		} else {
			$this->sortfield = $this->defaultsortfield;
		}	
		
		if ( isset($arrayvalues['sortorder']) ) {
			$this->sortorder =  trim($arrayvalues['sortorder']);
		} else {
			$this->sortorder = $this->defaultsortorder;
		}			
		
		# pagination code
		$this->page =  trim($arrayvalues["page"]);
		$this->setnumber =  trim($arrayvalues["setnumber"]);
		
		# set the default sort parameters if none are specified
		$this->setDefaultSortParameters();
		# set the new sort order
		$this->setNewSortOrder();
		#declare an array of the variables to be ignored
		$ignoredvariables = array("page", "setnumber", "sortorder", "sortfield", "start", "weekendingdate", "searchterm", "year", "modifier", "month");
		//add all other url parameters obtained from the $_GET as list parameters
		foreach($arrayvalues as $k=>$v) {
			if(!in_array($k, $ignoredvariables)) {
				$this->listURLParameters .= "&$k=$v";				
			}
		}
		
	}
	
	# Change the sort order, if the current sort order is DESC the new sort order is ASC and vice
	# versa
	function setNewSortOrder() {
		# set the new sort order
		if ($this->sortorder == "DESC") {
			$this->newsortorder = "ASC";
		} else {
			$this->newsortorder = "DESC";
		} 
	}
	
	# Generate sql that searches for the search parameter in columns specified in an array for example if the columns are status and firstname and search parameter is 'liz',
	# the array 'this->searchcolumns' would contain 'status' and 'firstname'. The function loops through the array and generates code for each column e.g for status, the code would be
	# " 0R status LIKE '%Liz'%"
	function getSearchSQL() {
		$searchsql = "";
		foreach ($this->searchcolumns as $value) {
			$searchsql.= " OR ".$value." LIKE '%".ucfirst($this->searchterm)."%'";
		}
		$searchsql =substr($searchsql, 3);
		return "(".$searchsql.")";
	}
	
	/** This function caters for the context sensitive searches like search for employee and
		search for timesheets, it uses the search columns and search values arrays
	*/
	# Generate sql that searches for the search parameter in columns specified in an array for example if the columns are status and firstname and search parameter is 'liz',
	# the array 'this->searchcolumns' would contain 'status' and 'firstname'. The function loops through the array and generates code for each column e.g for status, the code would be
	# " 0R status LIKE '%Liz'%"
	function getContextSearchSQL() {
		$searchsql = "";
		# loop through the columns and generate SQL to search the column
		
		foreach ($this->contextsearchcolumns as $key => $column){
			#echo "Key ".$key." Column ".$column;
			# generate SQL only if the search parameter is not empty
			if ($this->contextsearchparameters[$key] != "") {
				$searchsql .= " ".$column." LIKE '%".$this->contextsearchparameters[$key]."%' AND";
			}
		}
		# remove the last three letters, essentially the last AND
		$searchsql =substr($searchsql,0, -3);
		# return an empty string if there is no context search SQL
		if ($searchsql == "") {
			return "";
		} else {
			return " ".$searchsql."";
		}
		
	}

	
	# set the default sort order and sort parameter
	function setDefaultSortParameters(){
		if ($this->sortfield == "") {
			$this->sortfield = $this->defaultsortfield;
		}
		
		if ($this->sortorder == "") {
			$this->sortorder = $this->defaultsortorder;
		}
	}
	
	#Generate sql for the sortorder and fields to be sorted by
	function getSortSQL() {		
		$sortsql = " ORDER BY ".$this->sortfield." ".$this->sortorder." ";
		return $sortsql;
	}
	
	
	
	# build the url based on the url parameter array
	function getURL($urlsortfield){
		# Remove any parameters from the list URL 
		$this->listURL = str_replace(strstr($this->listURL, '?'),"",$this->listURL);
		$this->listURL .= $this->buildURLFromSortByandSortOrder($urlsortfield, $this->newsortorder);
		return $this->listURL;
	}
	
	# biuld a url from a sort by and sort order
	# this function enables us to change the sort order (as is required by the sort links) or
	# to maintain the sort order as required by the pagination links
	function buildURLFromSortByandSortOrder($urlsortfield, $url_sort_order) {
		return "?sortfield=".$urlsortfield."&sortorder=".$url_sort_order."&setnumber=".$this->setnumber."&start=".$this->start."&page=".$this->page.$this->listURLParameters; 
	}
	
	# Generate the sort url from the passed sortorder and sortfield values
	function getURLFromSortLink($urlsortfield) {
		return $this->getURL($urlsortfield);
	}
	
	# Function to display arrow based on sort order
	function getArrowFromSortOrder($sortparameter){
		if($sortparameter == $this->sortfield){
			 if ($this->sortorder == "ASC") {
				//return "<img src=\"".XOOPS_URL."/content/images/arrowdown.gif\" border=\"0\">";
				return "";
			 }else if ($this->sortorder == "DESC") { 
				//return "<img src=\"".XOOPS_URL."/content/images/arrowup.gif\" border=\"0\">";
			 	return "";
			 }
		}
	}	
	
	# Generate the limit clause based on the page and the total number of rows
	function getLimitSQL() {
	 
		# display links for the pagination
		$this->lastpage = ceil($this->totalrows/$this->numberof_rows_on_page);
		# Number of link sets
		$this->totalsets = ceil($this->lastpage/15);
							
		# This code checks that the value of $page is an integer between 1 and $lastpage
		$this->page = (int)$this->page;
		if ($this->page < 1) {
   			$this->page = 1;
		} else if ($this->page > $this->lastpage) {
   			$this->page = $this->lastpage;
		} 
		
		#echo "page: ".$this->page." lastpage: ".$this->lastpage." totalrows: ".$this->totalrows;
		# This code will construct the LIMIT clause for the sql SELECT statement
		return 'LIMIT ' .(($this->page - 1) * $this->numberof_rows_on_page).',' .$this->numberof_rows_on_page;

	}
	
	#Generate pagination links
	function getPaginationLinks() {

		# This code checks that the value of $setnumber is an integer between 1 and $totalsets
		$this->setnumber = (int)$this->setnumber;
		if ($this->setnumber < 1) {
   			$this->setnumber = 1;
		} elseif ($this->setnumber > $this->totalsets) {
			$this->setnumber = $this->totalsets;
		} 
		//echo "totalrows: ".$this->totalrows." page: ".$this->page." setnumber: ".$this->setnumber." totalsets: ".$this->totalsets." lastpage: ".$this->lastpage;
		#set the page number and setnumber to function variables to reserve their values
		$page = $this->page;
		$setnumber = $this->setnumber;
		# Display only the results links if they are less than the number of links per page
		if($this->lastpage < 15) {
				$lastsetlink = $this->lastpage;
		} else {
			# Specify the start link and last link for the link to be displayed.
			if ($this->setnumber == 1) {
				$startsetlink = 1;
			# The links setnumber is > 1
			} else {
				$startsetlink = ($this->setnumber - 1) * 15;
			}
								

			$lastsetlink = $this->setnumber * 15;
			# Whether the remaining links exceed the last set link
			if($lastsetlink > $this->lastpage) {
				$lastsetlink = $this->lastpage;
			}
		}
							
		# Finally we must construct the hyperlinks which will allow the user to navigate.
		if($this->page > 1){ 
        	$this->page = $this->page - 1; 
        	# Fancy way of subtracting 1 from $page	
			if(($this->setnumber > 1) && ($this->page == $startsetlink)) {
				# Decrease the set number by 1 if it is the start set link
				$this->setnumber = $this->setnumber - 1;
				echo ("<a  href=\"".$this->buildURLFromSortByandSortOrder($this->sortby, $this->sortorder)."\">Previous <img src=\"/stfrancis/images/prev_arrow.gif\" border = \"0\" align=\"absmiddle\"></a>&nbsp;");
			} else {							        
        		echo ("<a  href=\"".$this->buildURLFromSortByandSortOrder($this->sortby, $this->sortorder)."\">Previous <img src=\"/stfrancis/images/prev_arrow.gif\" border = \"0\" align=\"absmiddle\"></a>&nbsp;");		   
			}
    	}
							
		for($i = $startsetlink; $i <= $lastsetlink; $i++){ 
    		# This for loop will add 1 to $i at the end of each pass until $i is greater than the last set link number
        	if($i != $page){ 
				$this->page = $i;
				 $this->setnumber = $setnumber;
				echo ("<a   class=\"pagination\" href=\"".$this->buildURLFromSortByandSortOrder($this->sortby, $this->sortorder)."\">$i</a>&nbsp;"); 
			}else{  
				if($page == 1 && $page==$lastpage) {
				} else {           								
					echo ("<span class=\"pagination\">".$i."&nbsp;</span>"); 
				}
        	} 
        }
							
		#if the last set link is set to the last page, do not display links to the next page
		if ($page < $this->lastpage) {
			$this->page = $page + 1;
			# Checks if you are on the last link but not yet on the last results page
			if(($page == $lastsetlink) && ($lastsetlink == ($setnumber * 15))) {
				# Increase the set number by 1 when you get to the last set link
				$this->setnumber = $setnumber + 1;
				echo ("<a  class=\"pagination\" href=\"".$this->buildURLFromSortByandSortOrder($this->sortby, $this->sortorder)."\"><img src=\"/stfrancis/images/arrow_right.gif\" border = \"0\" align=\"absmiddle\"> Next</a><br>");
			} else {
				echo ("<a class=\"pagination\" href=\"".$this->buildURLFromSortByandSortOrder($this->sortby, $this->sortorder)."\"><img src=\"/stfrancis/images/arrow_right.gif\" border = \"0\"  align=\"absmiddle\"> Next</a><br>");
        	}
		}
		
		# call function that set upper and lower dispays for the results returned
		//$this->getNumberOfResultsDisplayed();
	}
	
	# Sets the total number of rows to be used in the pagination functionality
	# The query passed is the one that returns all rows
	function setTotalNumberOfRows($no) {
		//openDatabaseConnection();
		#Run query to obtain the number of rows that will be returned
		//$querydata = tep_db_query($query);
		$this->totalrows = $no;
	
	}
	
	# function to obtain the upper and lower number of resutls displayed in each of the pagination views
	function getNumberOfResultsDisplayed() {
		
		# Check whether the total results are greater than number of results to be diaplyed on the page
		if ($this->totalrows < $this->numberof_rows_on_page) {		
			# check if the total rows are 0
			if ($this->totalrows == 0) {
				$lowerDisplay = 0;			
				$upperDisplay =0;
			} else {			
				$lowerDisplay = 1;			
				$upperDisplay = $this->totalrows;
			}
			
		} else {
			
			# While the upper display is still less than the total number of rows 
			if ($upperDisplay < $this->totalrows) {		
				//echo "Upper display greater than total rows";
				$lowerDisplay	= (($this->page - 1) * $this->numberof_rows_on_page) + 1;	
			
				$upperDisplay = (($this->page - 1) * $this->numberof_rows_on_page) + $this->numberof_rows_on_page;
			
			}			
			# Check whether the upperdisplay is still less than the total number of rows

			if ($upperDisplay > $this->totalrows) {
				$upperDisplay = $this->totalrows;
			}
		}	
		
		return 	($lowerDisplay." - ".$upperDisplay);
	
	}
}
?>