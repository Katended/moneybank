<?php
interface Encryption_Interface
{
	public function encrypt($text, $key);
	public function decrypt($text, $key);
}


class SSLCrypt implements Encryption_Interface
{
	protected $cipher_algorithm;
	protected $digest_method;
	protected $ivector_length;

	public function __construct($cipher_algorithm = 'aes-256-ctr', $digest_method = 'sha256')
	{
		$this->cipher_algorithm = $cipher_algorithm;
		$this->digest_method = $digest_method;

		if (!in_array($cipher_algorithm, openssl_get_cipher_methods(TRUE))) {
			throw new \Exception(__METHOD__ . " Unknown cipher $cipher_algorithm");
		}

		if (!in_array($digest_method, openssl_get_md_methods(TRUE))) {
			throw new \Exception(__METHOD__ . " Unknown digest $digest_method");
		}

		$this->ivector_length = openssl_cipher_iv_length($cipher_algorithm);
	}

	public function encrypt($text, $key)
	{

		$keyhash = openssl_digest($key, $this->digest_method, TRUE);
		//$ivector = mcrypt_create_iv($this->ivector_length, MCRYPT_DEV_URANDOM);

		$ivector = bin2hex(openssl_random_pseudo_bytes(8));

		$ivector = '17c736ce5400805e';

		$crypted = openssl_encrypt($text, $this->cipher_algorithm, $keyhash, OPENSSL_RAW_DATA, $ivector);

		if ($crypted === FALSE) {

			throw new \Exception(__METHOD__ . ' Failed: ' . openssl_error_string());
		}

		// RETURN THE IV AND THE ENCRYPTED DATA
		return base64_encode($ivector . $crypted);
	}

	public function decrypt($text, $key)
	{
		$keyhash = openssl_digest($key, $this->digest_method, TRUE);
		$rawdata = base64_decode($text);
		if (strlen($rawdata) < $this->ivector_length) {
			throw new \Exception(__METHOD__ . ' Data is too short');
		}

		// SEPARATE THE IV AND THE ENCRYPTED DATA
		$ivector = substr($rawdata, 0, $this->ivector_length);
		$rawtext = substr($rawdata, $this->ivector_length);
		$decrypt = openssl_decrypt($rawtext, $this->cipher_algorithm, $keyhash, OPENSSL_RAW_DATA, $ivector);

		if ($decrypt === FALSE) {
			throw new \Exception(__METHOD__ . ' Failed: ' . openssl_error_string());
		}

		return $decrypt;
	}
}

class Grid
{

	var $searchterm = "";
	var $searchcolumns = array();
	var $urlparameters = array();
	var $searchparameters = array();
	var $defaultsortfield = "";
	var $defaultsortorder = "DESC";
	var $sortorder;
	var $newsortorder = "";
	var $sortfield = "";
	var $listURL = "";
	var $queryoptions = array();
	var $keyfield;
	var $sp_code;
	var $sp_parameters = array();
	var $Conn;

	# These are parameters that are always added to the List URL when necessary
	var $listURLParameters = "";
	var $start = "";
	var $setnumber = 0;
	var $totalrows = 0;
	var $lastpage = "";
	var $totalsets = 0;
	var $modifier = '';
	var $numberof_rows_on_page = 15;
	var $page = "1";
	var $links = "";
	var $displaygroupheader = false;
	var $cpara = "";
	var $currentheader = "";
	var $checkboxvaluefield = "";
	var $HTMLFooter = "";
	var $addcheckbox = true;
	var $paging = array();
	var $displayPageCount = true;
	var $extraColumnValue = "";
	var $extraFields = array();
	var $lablesarray = array(); // this carried the lables to be used on the grid
	var $fieldsdatarray = array(); // array holding data to be used to pupulate any controls displayed in the grid
	var $ToolTipText = "";
	var $checked = array(); // holding IDs of fro checked checkbox
	var $checkedColor = "";
	var $FormatNumbers = false; // check if numbers should be formated or not
	var $subRows = false;
	var $cPage; // page in which control is being loaded\
	var $formid;
	var $url;
	var $cfunction = '';
	var $checkmultselect = false;

	function getSearchParameter()
	{
		return $this->searchterm;
	}

	function getSearchColumns()
	{
		return $this->searchcolumns;
	}

	function getFilterOption()
	{
		return $this->filteroption;
	}

	function processRequest()
	{

		//$this->Uinter =  trim($arrayvalues['Uinter']);
		//$this->modifier =  trim($arrayvalues['modifier']);
		//$this->yearoptionge =  trim($arrayvalues['year']);
		//$this->monthoption =  getMonthNumberFromString(trim($arrayvalues['month']));

		if (isset($this->queryoptions['searchterm'])) {
			$this->searchterm =  trim($this->queryoptions['searchterm']);
		} else {
			$this->searchterm = '';
		}

		#Obtain the start from the array values else set it to 0
		if (isset($this->queryoptions["start"])) {
			$this->start =  trim($this->queryoptions["start"]);;
		} else {
			$this->start = 0;
		}

		# sorting options
		if (isset($this->queryoptions['sortfield'])) {
			$this->sortfield = trim($this->queryoptions['sortfield']);
		} else {

			$this->sortfield = ($this->sortfield == '') ? $this->defaultsortfield : $this->sortfield;
		}

		if (isset($this->queryoptions['sortorder'])) {
			$this->sortorder =  trim($arrayvalues['sortorder']);
		} else {
			$this->sortorder = ($this->sortorder == '') ? $this->defaultsortorder : $this->sortorder;
			//$this->sortorder = $this->defaultsortorder;
		}

		$this->lastpage =  trim($this->queryoptions["page"]);

		# pagination code
		$this->page =  trim($this->queryoptions["page"]);



		$this->setnumber =  trim($this->queryoptions["setnumber"]);

		# set the default sort parameters if none are specified
		$this->setDefaultSortParameters();
		# set the new sort order
		$this->setNewSortOrder();
		#declare an array of the variables to be ignored
		$ignoredvariables = array("page", "setnumber", "sortorder", "sortfield", "start", "weekendingdate", "year", "modifier", "month");
		//add all other url parameters obtained from the $_GET as list parameters

		foreach ($this->queryoptions as $key => $value) {

			if (!in_array($key, $ignoredvariables)) {
				$this->listURLParameters .= '&' . $key . '=' . $value;
			}
		}
	}

	# Change the sort order, if the current sort order is DESC the new sort order is ASC and vice
	# versa
	function setNewSortOrder()
	{
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
	function getSearchSQL()
	{
		$searchsql = "";
		foreach ($this->searchcolumns as $value) {
			$searchsql .= " OR " . $value . " LIKE '%" . ucfirst($this->searchterm) . "%'";
		}
		$searchsql = substr($searchsql, 3);
		return "(" . $searchsql . ")";
	}


	# Generate sql that searches for the search parameter in columns specified in an array for example if the columns are status and firstname and search parameter is 'liz',
	# the array 'this->searchcolumns' would contain 'status' and 'firstname'. The function loops through the array and generates code for each column e.g for status, the code would be
	# " 0R status LIKE '%Liz'%"
	function getContextSearchSQL()
	{
		$searchsql = "";
		# loop through the columns and generate SQL to search the column

		foreach ($this->contextsearchcolumns as $key => $column) {
			#echo "Key ".$key." Column ".$column;
			# generate SQL only if the search parameter is not empty
			if ($this->contextsearchparameters[$key] != "") {
				$searchsql .= " " . $column . " LIKE '%" . $this->contextsearchparameters[$key] . "%' AND";
			}
		}
		# remove the last three letters, essentially the last AND
		$searchsql = substr($searchsql, 0, -3);
		# return an empty string if there is no context search SQL
		if ($searchsql == "") {
			return "";
		} else {
			return " " . $searchsql . "";
		}
	}


	# set the default sort order and sort parameter
	function setDefaultSortParameters()
	{
		if ($this->sortfield == "") {
			$this->sortfield = $this->defaultsortfield;
		}

		if ($this->sortorder == "") {
			$this->sortorder = $this->defaultsortorder;
		}
	}

	#Generate sql for the sortorder and fields to be sorted by
	function getSortSQL()
	{
		if ($this->sortfield != ""):
			$sortsql = " ORDER BY " . $this->sortfield . " " . $this->sortorder . " ";
		else:
			$sortsql = "";
		endif;

		return $sortsql;
	}

	# build the url based on the url parameter array
	function getURL($urlsortfield)
	{
		# Remove any parameters from the list URL
		//$this->listURL = str_replace(strstr($this->listURL, '?'),"",$this->listURL);
		$this->listURL .= $this->buildURLFromSortByandSortOrder($urlsortfield, $this->newsortorder);
		return $this->listURL;
	}

	# biuld a url from a sort by and sort order
	# this function enables us to change the sort order (as is required by the sort links) or
	# to maintain the sort order as required by the pagination links
	function buildURLFromSortByandSortOrder($urlsortfield, $url_sort_order)
	{
		//return "?sortfield=".$urlsortfield."&sortorder=".$url_sort_order."&setnumber=".$this->setnumber."&start=".$this->start."&page=".$this->page.$this->listURLParameters;
		return "sortfield=" . $urlsortfield . "&sortorder=" . $url_sort_order . "&setnumber=" . $this->setnumber . "&start=" . $this->start . "&page=" . $this->page . $this->listURLParameters;
	}

	# Generate the sort url from the passed sortorder and sortfield values
	function getURLFromSortLink($urlsortfield)
	{
		return $this->getURL($urlsortfield);
	}

	# Function to display arrow based on sort order
	function getArrowFromSortOrder($sortparameter)
	{
		//if($sortparameter == $this->sortfield){
		if ($this->sortorder == "ASC") {
			return "<img src='" . DIR_WS_CATALOG . "../../images/arrow_down.gif' border=\"0\">";
			//return "";
		} else {
			return "<img src='" . DIR_WS_CATALOG . "../../images/arrow_up.gif' border=\"0\">";
		}
		//}
	}

	# Generate the limit clause based on the page and the total number of rows
	function getLimitSQL()
	{


		# display links for the pagination


		$this->lastpage = ceil($this->totalrows / $this->numberof_rows_on_page);

		# Number of link sets
		$this->totalsets = ceil($this->lastpage / 10);

		# This code checks that the value of $page is an integer between 1 and $lastpage

		$this->page = (int)$this->page;


		if ($this->page < 1) {
			$this->page = 1;
		} else if ($this->page > $this->lastpage) {

			if ($this->lastpage >= 1):
				$this->page = $this->lastpage - 1;
			endif;
			//  
		}

		//                if ($this->page = 1) {
		//                    
		//                   $this->page =0 ;
		//                }else{
		//                    
		//                    $this->page --;
		//                }


		# This code will construct the LIMIT clause for the sql SELECT statement

		$limit = 'LIMIT ' . (($this->page > 1 ? (($this->page - 1) * $this->numberof_rows_on_page) : ($this->page == 1 ? -1 : 1)) + 1) . ',' . $this->numberof_rows_on_page;

		return $limit;
	}

	#Generate pagination links
	function getPaginationLinks()
	{

		$currentdisplay = $this->getNumberOfResultsDisplayed();

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
		if ($this->lastpage < 10) {
			$lastsetlink = $this->lastpage;
		} else {

			# Specify the start link and last link for the link to be displayed.
			if ($this->setnumber == 1) {
				$startsetlink = 1;
				# The links setnumber is > 1
			} else {
				$startsetlink = ($this->setnumber - 1) * 10;
			}

			$lastsetlink = $this->setnumber * 10;

			# Whether the remaining links exceed the last set link
			if ($lastsetlink > $this->lastpage
			) {
				$lastsetlink = $this->lastpage;
			}
		}

		if ($this->queryoptions['container'] != "") {
			$data_container = $this->queryoptions['container'];
		} else {
			$data_container = 'griddata';
		}

		# Finally we must construct the hyperlinks which will allow the user to navigate.
		$pagelinks = "<div class='grid-pagination'><ul id='pagination-digg'>";

		if ($this->page > 1) {

			$this->page = $this->page - 1;
			// showValues(frm,theid,action,pageparams,urlpage,keyparam)	
			if (($this->setnumber > 1) && ($this->page == $startsetlink)) {
				# Decrease the set number by 1 if it is the start set link
				$this->setnumber = $this->setnumber - 1;
				$pagelinks = $pagelinks . "<li ><a href=\"#\"  OnClick=\"showValues('" . $this->formid . "','" . $data_container . "','search','" . $this->searchterm . "','" . $this->getURLFromSortLink('') . "','')\"><img src=\"images/icons/page-prev.gif\" border = \"0\" align=\"absmiddle\"></a></li>";
			} else {
				$pagelinks = $pagelinks . "<li ><a href=\"#\" OnClick=\"showValues('" . $this->formid . "','" . $data_container . "','search','" . $this->searchterm . "','" . $this->getURLFromSortLink('') . "','')\"><img src=\"images/icons/page-prev.gif\" border = \"0\" align=\"absmiddle\"></a></li>";
			}
		}

		for ($i = $startsetlink; $i <= $lastsetlink; $i++) {
			# This for loop will add 1 to $i at the end of each pass until $i is greater than the last set link number
			if ($i != $page) {

				$this->page = $i;

				$this->setnumber = $setnumber;

				if ($i != "") {
					$pagelinks_i = $pagelinks_i . "<li><a href='#' OnClick=\"showValues('" . $this->queryoptions['frmid'] . "','" . $data_container . "','search','" . $this->queryoptions['searchterm'] . "','" . $this->getURLFromSortLink('') . "','')\">" . $i . "</a></li>";
				}
			} else {

				if ($page == 1 && $page == $lastpage) {
				} else {
					$pagelinks_i = $pagelinks_i . "<li class='active'>" . $i . "</li>";
				}
			}
		}

		if ($this->queryoptions['container'] != "") {
			$data_container = $this->queryoptions['container'];
		} else {
			$data_container = 'griddata';
		}

		$pagelinks = $pagelinks . $pagelinks_i;
		#if the last set link is set to the last page, do not display links to the next page
		if ($page < $this->lastpage) {
			$this->page = $page + 1;
			# Checks if you are on the last link but not yet on the last results page
			if (($page == $lastsetlink) && ($lastsetlink == ($setnumber * 10))) {
				# Increase the set number by 1 when you get to the last set link
				$this->setnumber = $setnumber + 1;
				$pagelinks = $pagelinks . "<li class='next'><a href='#'  OnClick=\"showValues('" . $this->queryoptions['frmid'] . "','" . $data_container . "','search','" . $this->searchterm . "','" . $this->getURLFromSortLink('') . "','')\"><img src=\"images/icons/page-next.gif\" border = \"0\" align=\"absmiddle\"></a></li>";
			} else {
				$pagelinks = $pagelinks . "<li class='next'><a href='#'   OnClick=\"showValues('" . $this->queryoptions['frmid'] . "','" . $data_container . "','search','" . $this->searchterm . "','" . $this->getURLFromSortLink('') . "','')\"><img src=\"images/icons/page-next.gif\" border = \"0\"  align=\"absmiddle\" ></a></li>";
			}
		}
		Common::getlables("33,34", "", "", $this->Conn);
		return $pagelinks . "</ul><span style='float:left;'>" . $currentdisplay . " " . Common::$lablearray['33'] . " " . $this->totalrows . " " . Common::$lablearray['34'] . "</span></div>";
	}

	# Sets the total number of rows to be used in the pagination functionality
	# The query passed is the one that returns all rows
	function setTotalNumberOfRows($no)
	{
		//openDatabaseConnection();
		#Run query to obtain the number of rows that will be returned
		//$querydata = tep_db_query($query);

		if ($no == 0) {
			$this->totalrows = 1;
		} else {
			$this->totalrows = $no;
		}
	}

	# function to obtain the upper and lower number of resutls displayed in each of the pagination views
	function getNumberOfResultsDisplayed()
	{
		$upperDisplay = 0;

		# Check whether the total results are greater than number of results to be diaplyed on the page
		if ($this->totalrows < $this->numberof_rows_on_page) {
			# check if the total rows are 0
			if ($this->totalrows == 0) {
				$lowerDisplay = 0;
				$upperDisplay = 0;
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

		return ($lowerDisplay . " - " . $upperDisplay);
	}

	// used to add grid controls
	// TODO: Reduce number of lables in each section -get only relevant lables
	function gridControls()
	{

		switch ($this->cPage) {

			case 'LOANAPPROVE':
				common::getlables("1473,1096,1101,1474,1472,1230,20,271,1222,42,1221,1223,1040,317,819,24,1105,1233,1158,1133,1148,1147,1041", "", "", $this->Conn);
				return  '<table cellpading="0" cellspacing="0" border="0" width="100%"><tr><td>' . common::$lablearray['1222'] . ' <SELECT name="lstatus" id="lstatus"><option id="" value="">-----</option><option id="AP" value="AP" selected>' . common::$lablearray['1221'] . '</option><option id="LD" value="LD">' . common::$lablearray['1040'] . '</option><option id="RJ" value="RJ">' . common::$lablearray['1223'] . '</option><option id="DE" value="DE">' . common::$lablearray['1041'] . '</option></SELECT></td><td>' . common::$lablearray['317'] . ' <input type="us-date" id="startDate" name="startDate"></td><td><button class="btn" name="Go"  type="button"   id="btnsearch" onClick="UpdateData(\'LOANAPPROVE\')" style="float:right;margin:5px;">' . common::$lablearray['20'] . '</button></td></tr></table>';
				break;

			case 'LOANDISBURSE':
				common::getlables("1473,1096,1405,1101,1474,1472,1230,20,271,1222,42,1221,1223,1040,317,819,24,1105,1233,1158,1133,1148,1147,1041,271", "", "", $this->Conn);
				return  '<table cellpading="0" cellspacing="0" border="0" width="100%"><tr><td>' . common::$lablearray['24'] . '<br> ' . common::DrawComboFromArray(array(), 'PAYMODES', '', 'PAYMODES', '', 'PAYMODES') . '</td><td>' . common::$lablearray['819'] . '<br><input type="text" id="txtvoucher" name="txtvoucher" value="">' . TEXT_FIELD_REQUIRED . '</td><td>' . common::$lablearray['317'] . "<br><input type='us-date' id='startDate' name='startDate'> </td><td>" . common::$lablearray['1222'] . '<br> <SELECT name="lstatus" id="lstatus"></option><option id="LD" value="LD" selected>' . common::$lablearray['1040'] . '</option><option id="RJ" value="RJ">' . common::$lablearray['1223'] . '</option><option id="DE" value="DE">' . common::$lablearray['1041'] . '</option></SELECT></td><td>' . common::$lablearray['1105'] . '<br><input type="text" id="txtcommission" name="txtcommission" value="0.00"></td><td>' . common::$lablearray['1230'] . '<br><input type="text" id="txtstationery" name="txtstationery" value="0.00"></td></tr></table>'
				. '<table cellpadding="2px;" border="0" width="100%">'
				. '<tr><td>' . common::$lablearray['1405'] . '<br>' . Common::DrawComboFromArray(array(), 'SAVPROD', '', 'SAVPROD', '', 'SAVPROD') . '</td><td>' . common::$lablearray['271'] . '<br><input name="txtAmount" id="txtAmount" type="text" value="" /></td></tr>'
				. '<tr><td>' . common::$lablearray['1147'] . '<br> <input name="adjustDueDatesTo" id="adjustDueDatesTo" type="text" value="0"  maxlength="2" /></td><td id="modes"></td></tr>'
				. '<tr><td><input type="checkbox" id="chkupdateduedates" name="chkupdateduedates" value="Y" checked > ' . common::$lablearray['1233'] . '</td><td><input name="loan_checkNonWorkingDays" id="loan_checkNonWorkingDays" type="checkbox" value="Y" /> ' . common::$lablearray['1133'] . '</td></tr>'
				. '</table>'
				. '<button class="btn" name="Go"  type="button"   id="btnsearch" onClick="UpdateData(\'LOANDISBURSE\')" style="float:right;margin:5px;">' . common::$lablearray['20'] . '</button></p>';
				break;

			case 'SMS':
				// common::getlables("1612","","",$this->Conn);
				// return  '<button class="btn" name="Go"  type="button"   id="btnsearch" onClick="" style="float:right;margin:2px;">'.common::$lablearray['1612'].'</button></p>';
				break;

			case 'LOANDISBURSED':
				common::getlables("21,1473,1096,1101,1474,1472,1230,20,271,1222,42,1221,1223,1040,317,819,24,1105,1233,1158,1133,1148,1147,1041", "", "", $this->Conn);
				return  "<button type='button' id=\"btnSearchGRD\" name=\"btnSearchGRD\" onClick=showValues('" . $this->queryoptions['frmid'] . "','" . $this->queryoptions['container'] . "','search','" . $this->cpara . "','load.php?searchterm='+$('#txtsearchterm').val()) >" . common::$lablearray['21'] . '</button>'
				. '<table><tr><td>' . common::$lablearray['819'] . '<br> <input type="text" id="txtvoucher" name="txtvoucher" value=""></td><td>' . common::$lablearray['317'] . "<br><input type='us-date' id='startDate' name='startDate'> </td><td>" . common::$lablearray['1222'] . '<br> <SELECT name="lstatus" id="lstatus"><option id="DE" value="DE" SELECTED>' . common::$lablearray['1041'] . '</option></SELECT></td></tr></table><span id="modes"></span>'
					. '<button class="btn" name="Go"  type="button"   id="btnsearch" onClick="UpdateData(\'LOANDISBURSED\')" style="float:right;margin:5px;">' . common::$lablearray['20'] . '</button></p>';
				break;

			case 'REFINANCE':
				$products = getProducts();
				common::getlables("9,21,1097,1096,1494,317,24,300,1222,819,1101,1490,1474,20,1472,1473,1100,1489,1097", "", "", Common::$connObj);
				echo   "<span><input type='hidden' id='lstatus' name='lstatus' value='RF'>" . common::$lablearray['21'] . " <input id='txtsearchterm' name='txtsearchterm' value='' size=50 placeholder='Name /Loan Number'></span>"
				. "<fieldset style='width:100%;'>"
				. "<p align='center' class='information'>" . common::$lablearray['1494'] . "</p>"
				. common::$lablearray['1097'] . "<br><input type='text' id='loan_number' name='loan_number' value=''>" . TEXT_FIELD_REQUIRED . "<input type='hidden' id='theid' name='theid' value=''><input type='hidden' id='pageparams' name='pageparams' value='REFINANCE'><table cellpading='5' width='100%' cellpacing='0'>"
				. "<tr><td>" . common::$lablearray['317'] . "<br><input type='us-date' id='startDate' name='startDate' >" . TEXT_FIELD_REQUIRED . "</td><td>" . common::$lablearray['1100'] . "<br><input type='numeric' id='txtintrate' name='txtloan_noofinst' value='0.0'>" . TEXT_FIELD_REQUIRED . "</td><td></td></tr>"
				. '<tr><td >' . common::$lablearray['1489'] . '<br><input type="numeric" id="txttopupperloan" name="txttopupperloan" value="0.0" >' . TEXT_FIELD_REQUIRED . '</td><td>' . common::$lablearray['1101'] . '<br><input type="numeric" id="txtloan_noofinst"  name="txtloan_noofinst" value="0.0">' . TEXT_FIELD_REQUIRED . '</td><td></td></tr>'
				. '</table>'
				. '<div><button class="btn" name="btnscancel" value="" style="float:right;margin:5px;" type="button" onClick="CloseDialog(\'myDialogId1\')"  id="btnscancel">' . common::$lablearray['300'] . '</button><button class="btn" name="Go"  type="button"   id="btnSave" onClick="UpdateData(\'REFINANCE\')" style="float:right;margin:5px;" > ' . common::$lablearray['20'] . '</button></fieldset></div><fieldset id="txtHint"></fieldset>';
				break;



			case 'WRITEOFF':

				// $products = getProducts();

				common::getlables("24,1222,1096,176,20,1490,271", "", "", $this->Conn);
				return  "<fieldset style='float:right;padding:5px;'>" . Common::$lablearray['317'] . " <input type='us-date' id='startDate' name='startDate'>" . Common::$lablearray['1096'] . " " . DrawComboFromArray(array(), 'LOANPROD', '', 'LOANPROD', '', 'LOANPROD') . " <input type='hidden' id='pageparams' name='pageparams' value='WRITEOFF'> "
				. common::$lablearray['1222'] . ' <select name="lstatus" id="lstatus"><option id="WO" value="WO" selected>' . common::$lablearray['176'] . '</option></select> ' . common::$lablearray['271'] . " <input type='numeric' id='txtAmount' name='txtAmount' value='0.0'>" . '<button class="btn" name="Go"  type="button"   id="btnsearch" onClick="UpdateData(\'WRITEOFF\')" > ' . common::$lablearray['20'] . " </button></fieldset>";

				break;

			case 'ALLLOANS':
				break;

			default:
				// common::getlables("21","","",$this->Conn);
				// return  "<button type='button' id=\"btnSearchGRD\" name=\"btnSearchGRD\" onClick=showValues('".$this->queryoptions['frmid']."','".$this->queryoptions['container']."','search','".$this->cpara."','load.php?searchterm='+$('#txtsearchterm').val()) >".common::$lablearray['21'].'</button>';
				break;
		}
	}

	function commonfunction($id, $value, $row, $index = 0)
	{

		switch ($this->cpara) {
			case 'GMEM':
				$nctrol = "<input type='numeric' id='MEM_" . Common::replace_string($id) . "' name='MEM_" . Common::replace_string($id) . "' value='0.0'>";
				break;

			case 'LOANDISBURSE':
				$nctrol = "<input type='numeric' id='txt_dis_amt_" . $id . "' name='txt_dis_amt_" . $id . "' value='" . $value . "'>";
				break;

			case 'INDREPAYLOANS':
				$results = array();
				$results = Common::getAllSavingsDetails($row['client_idno'], $this->Conn);
				$nctrol = "<table width='90%' cellpadding='0' cellspacing='0'>"
				. "<tr>"
					. "<td align='right' colspan='2'>";
				foreach ($results as $key => $value) {
					$nctrol .= "<p><b>Savings Account <b>" . $value['savaccounts_account'] . "</b> Product <b>" . $value['product_prodid'] . "</b> Balance <b>" . $value['balance'] . "</b></p>";
				}
				$nctrol .= "</td></tr>"
				. "<tr>"
					. "<td align='right'>Principal</td><td><input type='hidden'  id='txt_clientcode_:" . $id . "' name='txt_memid_:" . $id . "' value='" . $row['client_idno'] . "'><input type='hidden'  id='txt_lnrno_:" . $id . "' name='txt_memid_:" . $id . "' value='" . $row['members_idno'] . "' ><input type='hidden'  id='txt_lnrno_:" . $id . "' name='txt_lnrno_:" . $id . "' value='" . $row['loan_number'] . "' ><input type='numeric'  id='txt_princ_:" . $id . "' name='txt_princ_:" . $id . "' value='" . $row['dprinc'] . "' placeholder='Principal' class='smallinput'>" . "</td>"
					. "</tr>"
					. "<tr>"
					. "<td align='right'>Interest</td><td><input type='numeric'  id='txt_int_:" . $id . "' name='txt_int_:" . $id . "'  value='" . $row['dint'] . "' placeholder='Interest' class='smallinput'></td>"
					. "</tr>"
					. "<tr>"
					. "<td align='right'>Commission</td><td><input type='numeric'  id='txt_com_:" . $id . "' name='txt_com_:" . $id . "'  value='" . $row['dint'] . "' placeholder='Commission' class='smallinput'></td>"
					. "</tr>"
					. "<tr>"
					. "<td align='right'>Penalty</td><td><input type='numeric'  id='txt_pen_:" . $id . "' name='txt_pen_:" . $id . "'  value='" . $row['dint'] . "' placeholder='Penalty' class='smallinput'></td>"
					. "</tr>"
					. "<tr>"
					. "<td align='right'>VAT</td><td><input type='numeric'  id='txt_vat_:" . $id . "' name='txt_vat_:" . $id . "'  value='" . $row['dint'] . "' placeholder='VAT' class='smallinput'></td>"
					. "</tr>"

				. "<tr>"
				. "<td>Savings Product</td><td><span class='savprod'>" . Common::DrawComboFromArray(array(), 'SAVPROD_:' . $id, '', 'SAVPROD', '', 'SAVPROD') . "</span></td></tr>"
				. "</table>";

				break;

			case 'COLLECTINTEREST':

				$nctrol = "<div class='loan-payments-conainer'><span><span>Principal</span><input type='hidden'  id='txt_member_" . Common::replace_string($id) . "' name='member[]' value='" . $row['member_idno'] . "'><input type='hidden'  id='txt_fund_" . Common::replace_string($id) . "' name='fund[]' value='" . $row['fund_code'] . "'><input type='hidden'  id='txt_prod_" . Common::replace_string($id) . "' name='prodid[]' value='" . $row['product_prodid'] . "'><input type='hidden'  id='txt_donor_" . Common::replace_string($id) . "' name='donor[]' value='" . $row['donor_code'] . "'><input type='hidden'  id='txt_clientcode_" . Common::replace_string($id) . "' name='clientcode[]' value='" . $row['client_idno'] . "'><input type='hidden'  id='txt_lnrno_" . Common::replace_string($id) . "' name='lnrno[]' value='" . $row['loan_number'] . "' ><input type='numeric'  id='txt_princ_" . Common::replace_string($id) . "' name='princ[]' value='" . $row['dprinc'] . "' placeholder='Principal' class='smallinput'></span>"

					. "<span><span>Interest</span><input type='numeric'  id='txt_int_" . Common::replace_string($id) . "' name='int[]' value='" . $row['dint'] . "' placeholder='Interest' class='smallinput'></span>"

					. "<span><span>Commission</span><input type='numeric'  id='txt_com_" . Common::replace_string($id) . "' name='com[]' value='" . $row['dcom'] . "' placeholder='Commission' class='smallinput'></span>"

					. "<span><span>Penalty</span><input type='numeric'  id='txt_pen_" . Common::replace_string($id) . "' name='pen[]' value='" . $row['dpen'] . "' placeholder='Penalty' class='smallinput'></span>"

					. "<span><span>V.A.T</span><input type='numeric'  id='txt_vat_" . Common::replace_string($id) . "' name='vat[]' value='" . $row['dpen'] . "' placeholder='VAT' class='smallinput'></span>"

					. "<span><span>Savings</span><input type='numeric'  id='txt_sav_" . Common::replace_string($id) . "' name='sav[]' value='" . $row['savbal'] . "' placeholder='Savings' class='smallinput'></span></div>";

				break;

			case 'LOANAPPROVE':
				$nctrol = "<input type='numeric' id='" . $id . "' name='txt_dis_amt" . $id . "' value='" . $value . "'>";
				break;

			case 'TRANSFERSAVINGS':
				$nctrol = "<span data-balloon='" . Common::$lablearray['1669'] . " " . $row['name'] . "' data-balloon-pos='left'><input type='numeric' id='txt_acc_amt_to_" . Common::replace_string($row[$this->keyfield]) . "' value='0.0' name='txt_acc_amt_to_" . Common::replace_string($row[$this->keyfield]) . "' size='18'></span>";
				break;

			case 'ROLEPERMISSIONS':
				$modules_query = tep_db_query("SELECT(SELECT modules_description FROM " . TABLE_MODULES . " p  WHERE p.modules_id=rp.modules_id) As modules FROM  " . TABLE_ROLESMODULES . " rp  WHERE  roles_id='" . $id . "'");
				$nctrol = '<ul>';
				while ($modules_array = tep_db_fetch_array($modules_query)) {
					$nctrol = $nctrol . "<li style='text-align:left;margin-left:10px;color:#006600;border:1px solid #EEEEEE;' type='none'><font color='#006600'>" . $modules_array['modules'] . "</font></li>";
				}
				$nctrol = $nctrol . '</ul>';
				break;

			case 'FLAG':
				$nctrol = "<img border='0' src='../" . DIR_WS_FLAG_IMAGES . $value . "' style='margin:0px;' >";
				break;

			case 'MAINSEARCH':
				if ($value != "") {
					$nctrol = "<table cellpadding='5' cellspacing='0' width='50'><tr><td align='right'><img border='1' src='../" . DIR_WS_IMAGES_PHOTOS . $value . "' height='70' width='60'></td></tr></table>";
				} else {
					$nctrol = "<table cellpadding='5' cellspacing='0' width='50'><tr><td align='right'><img border='1' src='../" . DIR_WS_IMAGES_PHOTOS . "nophoto.jpeg' height='70' width='60'></td></tr></table>";
				}
				break;

			case 'FRMWRITEOFF':
				$nctrol = "<input name='txtwriteoff' id='txtwriteoff'  type='text' value='0' style='margin:0px;padding:0px;' disabled>";
				break;

			case 'INVOICES':
				$nctrol = "<div style='width:auto;margin:0px;padding:0px;' id='txtHint2'></div>";
				break;

			case 'VATSECTION1':
				$nctrol = "<input size='8' name='txtvat' id='" . $id . "'  type='text' value='' onKeyPress=\"return EnterNumericOnly(event,'vat_percentage')\">";
				break;

			case 'TAXSECTION1':
				$nctrol = "From <input size='8' name='txttaxes' id='T" . $id . "'  type='text' value='" . $this->extraColumnValue . "'  style='margin:0px;padding:0px;' onKeyPress=\"return EnterNumericOnly(event,'T" . $id . "')\">";

				$nctrol .= "To<input size='8' name='txttaxes' id='T" . $id . "'  type='text' value='" . $this->extraColumnValue . "'  style='margin:0px;padding:0px;' onKeyPress=\"return EnterNumericOnly(event,'T" . $id . "')\">";

				break;

			case 'RECONCILE':
				$glaccounts = getAccountLevels();
				$nctrol = "<table border='0' cellspacing='0' cellpadding='0'><tr><td>" . DrawComboFromArray($glaccounts, 'cmbupdate' . $this->fieldsdatarray['chartofaccounts_accountcode'], $this->fieldsdatarray['chartofaccounts_accountcode']) . "</td><td valign='middle'><b>Debit</b><br><input size='15'  class='pop_panel' name='txtupdateD" . $this->fieldsdatarray['chartofaccounts_accountcode'] . "' id='txtupdateD" . $this->fieldsdatarray['chartofaccounts_accountcode'] . "'  type='text' value='" . $this->fieldsdatarray['generalledger_debit'] . "'  style='margin:0px;padding:0px;'\" width='100' title='Enter debit amount' onKeyUp='balanceTransaction()'></td>";
				$nctrol = $nctrol . "<td align='right' valign='middle'><b>Credit</b><br><input size='15' name='txtupdateC" . $this->fieldsdatarray['chartofaccounts_accountcode'] . "' id='txtupdateC" . $this->fieldsdatarray['chartofaccounts_accountcode'] . "' class='pop_panel' type='text' value='" . $this->fieldsdatarray['generalledger_credit'] . "'  style='margin:0px;padding:0px;'\" width='100' title='Enter credit amount' onKeyUp='balanceTransaction()'></td></tr></table>";

				break;

			case 'PAYROLL':
				$nctrol = '<table cellpadding="2" cellpacing="0"  border="0" width="100%" style="top:0;margin:0px;bgcolor:#eeeeee">
                <tr bgcolor="#eeeeee">
                <td>
                ' . $this->lablesarray['38'] . '<br>
                <input name="date_from' . $id . '"  id="date_from' . $id . '"  type="text" size="15" width="32" value=""  readonly/><img  onclick="displayDatePicker(\'date_from' . $id . '\');"  src="../images/img/cal.gif"></td><td>' . $this->lablesarray['39'] . '<br>
                <input name="date_to' . $id . '"  id="date_to' . $id . '"  type="text" size="15" width="32" value=""  readonly/><img  onclick="displayDatePicker(\'date_to' . $id . '\');"  src="../images/img/cal.gif">
                <td>
                </tr>
                <tr  bgcolor="#eeeeee">
                <td>' . $this->lablesarray['36'] . '<br><input size="25" name="txtmultiplecheques' . $id . '" id="txtmultiplecheques' . $id . '"  type="text" value="' . $this->extraColumnValue . '"  style="margin:0px;padding:0px;" width="100" disabled title="To enter Cheques, enable the Multiple cheque option"></td>
                <td align="left">';

				$banks_query = tep_db_query("SELECT bb.bankbranches_id, banks_name , bankbranches_name,bankaccounts_accno FROM " . TABLE_BANKBRANCHES . " bb, " . TABLE_BANKS . " b, " . TABLE_BANKACCOUNTS . " ba WHERE ba.bankbranches_id = bb.bankbranches_id AND bb.banks_id=b.banks_id GROUP BY bb.bankbranches_id");

				$nctrol = $nctrol . $this->lablearray['35'] . '<br><select id="bankbranches_id' . $id . '" name="bankbranches_id' . $id . '" disabled>
                <option id="" value="">' . $this->lablearray['42'] . '</option>
                ';

				while ($banks_array = tep_db_fetch_array($banks_query)) {
					$nctrol = $nctrol . '<option id="' . $banks_array['bankaccounts_accno'] . '" value="' . $banks_array['bankaccounts_accno'] . '">' . $banks_array['banks_name'] . " | " . $banks_array['bankbranches_name'] . ' | ' . $banks_array['bankaccounts_accno'] . '</option>';
				}

				$nctrol = $nctrol . '</select></td></tr></table>';

				break;

			default:
				break;
		}


		return $nctrol;
	}


	/*================================================================
 * query		: The SQL query to be executed
 * fieldlist	: The list fo fields to be displayed
 * $keyfield		: Identity field
 * headerlist	: list of column names for the table
 * formid		: ID for the HTML form in which your grid will be displayed.
 * paging		: paging parameters
 * addcheckbox	; whether to add a checkbox for each row
 * actionlinks	: ation to be added at the top of the grid
  =================================================================*/
	function getdata($query, $fieldlist, $headerlist, $actionlinks = '', $onclick = '', $chkname = '')
	{


		try {


			Common::getlables("1668,1669,21", "", "", Common::$connObj);

			if ($chkname == "") {
				$chkname = "chkname[]";
			}

			if ($this->sortfield == "") {
				$this->sortfield = $defaultsortfield;
			}

			$this->processRequest();

			if ($query != "" && $this->sp_code == "") {




				// $num_query = tep_db_query($query);

				// $this->setTotalNumberOfRows(tep_db_num_rows($num_query));
			}

			$results_query = array();
			// check see if we are using a stored procedure to retrieve records
			if ($this->sp_code != "") {

				switch ($this->sp_code) {
					case 'LOANDUESSUM':
						break;
					default:
						break;
				}

				$this->sp_parameters[] = array('name' => 'climit', 'value' => $this->getLimitSQL());

				$results_query = Common::common_sp_call(serialize($this->sp_parameters), '', $this->Conn, false);

				if (!is_array($results_query[0])) {
					Common::getlables("310", "", "", $this->Conn);
					echo "MSG." . Common::$lablearray['310'];
					return;
				}
			} else {

				$query =   preg_replace('/SELECT/',
					'SELECT SQL_CALC_FOUND_ROWS ',
					$query,
					1
				);
				//$query = str_replace('SELECT', 'SELECT SQL_CALC_FOUND_ROWS ', $query);  

				$query = $query . " " . $this->getSortSQL() . ' ' . $this->getLimitSQL() . "; SELECT FOUND_ROWS() reccount;";

				$results_query = $this->Conn->SQLSelect($query);
			}


			if (count($results_query) > 1):

				//   $this->processRequest();

				$this->setTotalNumberOfRows($results_query[1][0]['reccount']);

				$results_query1 = $results_query;
				unset($results_query);
				$results_query = $results_query1[0];
				unset($results_query1);

			endif;

			$this->getLimitSQL();

			$data = "<table border='0'  width='100%'    cellspacing='0'  >";
			$data = $data . "<tr><td>";
			$nheader = 0; // is to increment the span of the header so that it does not stop in the middle

			$nheader = $nheader + count($this->extraFields);

			$i = 0;
			// ==================begin data heading
			$idheader = "data_grid_header" . uniqid();
			$idpanel = "data_grid" . uniqid();

			// extra stripts
			$data = "<script>";
			switch ($this->cPage) {

				case 'REFINANCE':
					$data .= "$('#LOANPROD').change(function () {"
					. " var dt =$('#startDate').val(); "
						. " var prod =$('#LOANPROD').val(); "
						. "showValues('frmLoanapp1','tab-example','search','','load.php?page=1&pageparams=REFINANCE&pid='+$('#LOANPROD').val()+'&date='+$('#startDate').val()).done(function(){"
					. "w2utils.date(new Date());	"
					. "$('input[type=us-date]').w2field('date');	"
					. "$('#LOANPROD').val(prod);"
					. "$('#startDate').val(dt);	"
					. "})"
						. "});";

					break;

				default:
					break;
			}



			$data .= "$('#" . $idheader . "').click(function(){
                    $('#" . $idpanel .
				"').slideToggle('slow');                       
                 });
             </script>";



			if ($this->queryoptions['pageparams'] != 'SAVTRAN'):
			// $data.=$this->gridControls();
			endif;

			// 
			// Viewing 1 of 20
			if ($this->displayPageCount == true) {

				if ($this->totalrows != 0) {

					$data .= "<div style='font-size:smaller;'>" . $this->getPaginationLinks($this->formid) . "</div><div id='" . $idheader . "' style='float:right;'><img src='images/expand.png' border=\"0\"></div>";
				}


				$data .= $this->gridControls();
			}

			$data .= "<div  id='" . $idpanel . "' >";
			$data .= "<span class='grid-options'>" . $search . '  ' . $actionlinks . "   <input type='text' class='rounded' value='" . $this->queryoptions['searchterm'] . "' placeholder='Search' maxlength='100' size='25' id='txtsearchterm' name='txtbtnSearch' >  <button class='btn' id='btnSearch' type='button' onClick=showValues('" . $this->queryoptions['frmid'] . "','" . $this->queryoptions['container'] . "','search','" . $this->cPage . "','load.php?searchterm='+$('#txtsearchterm').val())>" . Common::$lablearray['21'] . "</button></span>";
			$data .= "<table class='fancyTable' celpadding='2' border ='0' cellspacing='0' width='100%' id='POITable' style='border-collapse:collapse;'>";
			$data .= " <thead>";

			$i = 0;

			// TABLE HEADER
			$data = $data . "<tr >";
			$data = $data . "<th align='left'><input  title='Check All'  name='hchkall'  id='hchkall' type='checkbox' onClick=\"checkunckeck('hchkall')\"></th>";
			foreach ($headerlist as $value) {
				$data = $data . "<th align='left' nowrap>" . $value . "</th>";
				$i++;
			}
			// is the heading for the extra field 
			foreach ($this->extraFields as $key => $value) {
				if ($value == '') {
					$key = "";
				}
				$data = $data . "<th  nowrap>" . $value . "</th>";
			}

			$data = $data . "</tr>";

			$data = $data . " </thead>";


			$data = $data . " <tbody>";

			$x = 1;

			foreach ($results_query as $key => $row) {


				$i = 1;

				$ncount = count($headerlist) + 1;

				//header				
				if ($this->displaygroupheader == true) {

					if ($this->currentheader != $row[$defaultsortfield]) {
						$data = $data . "<tr>";
						$data = $data . "<td colspan='" . $ncount . "' align='left' ><h2>" . $row[$defaultsortfield] . "</h2></td>";
						$data = $data . "</tr>";

						$fdata = $fdata . "<tr>";
						$fdata = $fdata . "<td colspan='" . $ncount . "' align='left' ><h2>" . $row[$defaultsortfield] . "</h2></td>";
						$fdata = $fdata . "</tr>";
					}

					$this->currentheader = $row[$defaultsortfield];
				}

				# check see if we should add a checkbox
				if ($this->checkboxvaluefield != "") {
					$cvalue = $row[$this->checkboxvaluefield];
				} else {
					$cvalue = $row[$this->keyfield];
				}

				if (in_array($row[$this->keyfield], $this->checked)) {
					$checked = 'checked';
				} else {
					$checked = '';
				}



				$data = $data . "<tr id='RowId_" . Common::replace_string($row[$this->keyfield]) . "'>";

				$id = Common::tep_db_prepare_input($row[$this->keyfield]);


				if ($this->addcheckbox == true) {
					$data = $data . "<td>";
					$data = $data . "<span data-balloon='" . Common::$lablearray['1668'] . "' data-balloon-pos='right'><input class='chkgrd'  name='grid_checkbox_" . Common::replace_string($row[$this->keyfield]) . "' value='" . $id . "' id='grid_checkbox_" . Common::replace_string($row[$this->keyfield]) . "' type='checkbox' " . $checked . " onClick=\"checkunckeck('grid_checkbox_" . Common::replace_string($row[$this->keyfield]) . "'," . $this->checkmultselect . ")\" ></span>";
					$data = $data . "</td>";
				}



				$uniqueid =  $row[$this->keyfield];

				// this array used to populate inline-grid controls
				$this->fieldsdatarray[$this->keyfield] = $row[$this->keyfield];

				foreach ($fieldlist as $sid => $value) {

					if (is_numeric($row[$value]) && $this->FormatNumbers == true) {
						if ($row[$value] < 0) {
							$row[$value] = formatNumber($row[$value]);
						} else {
							$row[$value] = formatNumber($row[$value]);
						}
					}

					$this->fieldsdatarray[$value] = $row[$value];

					if ($addfilelinks == false) {
						if ($onclick != "") {
							$data = $data . "<td><span data-balloon='" . Common::$lablearray['1668'] . "' data-balloon-pos='right'><a href='#' onClick=\"$('#grid_checkbox_" . Common::replace_string($row[$this->keyfield]) . "').trigger('click')\">" . $row[$value] . "</a></span></td>";
						} else {
							$data = $data . "<td><span data-balloon='" . Common::$lablearray['1668'] . "' data-balloon-pos='right'><a href='#' name='' onClick=\"$('#grid_checkbox_" . Common::replace_string($row[$this->keyfield]) . "').trigger('click')\">" . $row[$value] . "</a></span></td>";
						}
						$addfilelinks = true;
					} else {
						$data = $data . "<td  nowrap  >" . $row[$value] . "</td>";
					}
				}

				foreach ($this->extraFields as $key => $svalue) {

					$data = $data . "<td  nowrap >" . $this->commonfunction($row[$this->keyfield], $row[$svalue], $row) . "</td>";
				}


				$data = $data . "</tr>";

				$x++;

				if ($this->subRows == true) {

					$data = $data . "<tr>";
					$data = $data . "<td colspan='" . (count($headerlist) + 1) . "'>";
					$data = $data . $this->commonfunction($row[$this->keyfield], $row[$svalue], $row);
					$data = $data . "</td>";
					$data = $data . "</tr>";
				}

				$addfilelinks = false;

				$i++;
				reset($fieldlist);
			}

			$data = $data . "</table>";

			$data = $data . "</td></tr></table>";

			$json = Common::createResponse('data', '', $data);

			return $json;
		} catch (Exception $e) {
			return Common::createResponse('err', $e->getMessage());
		}
	}
}

// Output a form password field
function tep_draw_password_field($name, $value = '')
{
	$field = tep_draw_input_field($name, $value, 'maxlength="40"', true, 'password', false);
	return $field;
}

// Output a form filefield
function tep_draw_file_field($name, $required = false)
{
	$field = tep_draw_input_field($name, '', '', $required, 'file');
	return $field;
}

// Output a form input field
function tep_draw_input_field($name, $value = '', $parameters = '', $required = false, $type = 'text', $reinsert_value = true, $maxlength = '')
{
	$field = '<input type="' . $type . '" name="' . $name . '"' . ' id="' . $name . '"';

	if (isset($GLOBALS[$name]) && ($reinsert_value == true) && is_string($GLOBALS[$name])) {
		$field .= ' value="' . tep_output_string(stripslashes($GLOBALS[$name])) . '"';
	} elseif (tep_not_null($value)) {
		$field .= ' value="' . tep_output_string($value) . '"';
	}

	if ($maxlength != '') $field .= ' maxlength="' . $maxlength . '"' . ' size="' . $maxlength . '"';

	if ($parameters != '') {
		$field .= $parameters;
	}

	if ($required == true) $field .= " required";

	$field .= '>';

	// if ($required == true) $field .= TEXT_FIELD_REQUIRED;

	return $field;
}

// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
function tep_draw_selection_field($name, $type, $value = '', $checked = false, $compare = '')
{
	$selection = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

	if (tep_not_null($value)) $selection .= ' value="' . tep_output_string($value) . '"';

	if (($checked == true) || (isset($GLOBALS[$name]) && is_string($GLOBALS[$name]) && ($GLOBALS[$name] == 'on')) || (isset($value) && isset($GLOBALS[$name]) && (stripslashes($GLOBALS[$name]) == $value)) || (tep_not_null($value) && tep_not_null($compare) && ($value == $compare))) {
		$selection .= ' CHECKED';
	}

	$selection .= '>';

	return $selection;
}

// Output a form checkbox field
function tep_draw_checkbox_field($name, $value = '', $checked = false, $compare = '')
{
	return tep_draw_selection_field($name, 'checkbox', $value, $checked, $compare);
}

// Output a form radio field
function tep_draw_radio_field($name, $value = '', $checked = false, $compare = '')
{
	return tep_draw_selection_field($name, 'radio', $value, $checked, $compare);
}

// Output a form textarea field
function tep_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true)
{
	$field = '<textarea name="' . tep_output_string($name) . '" wrap="' . tep_output_string($wrap) . '" cols="' . tep_output_string($width) . '" rows="' . tep_output_string($height) . '" id="' . tep_output_string($name) . '"';

	if (tep_not_null($parameters)) $field .= ' ' . $parameters;

	$field .= '>';

	if ((isset($GLOBALS[$name])) && ($reinsert_value == true)) {
		$field .= tep_output_string_protected(stripslashes($GLOBALS[$name]));
	} elseif (tep_not_null($text)) {
		$field .= tep_output_string_protected($text);
	}

	$field .= '</textarea>';

	return $field;
}

////
// Output a form hidden field
function tep_draw_hidden_field($name, $value = '', $parameters = '')
{

	$field = '<input type="hidden" name="' . tep_output_string($name) . '" id="' . $name . '"';

	if (tep_not_null($value)) {
		$field .= ' value="' . tep_output_string($value) . '"';
	} elseif (isset($GLOBALS[$name]) && is_string($GLOBALS[$name])) {
		$field .= ' value="' . tep_output_string(stripslashes($GLOBALS[$name])) . '"';
	}

	if (tep_not_null($parameters)) $field .= ' ' . $parameters;

	$field .= '>';

	return $field;
}

// Output a form pull down menu
function tep_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false)
{
	$field = '<select name="' . tep_output_string($name) . '"';

	if (tep_not_null($parameters)) $field .= ' ' . $parameters;

	$field .= 'id="' . $name . '">';

	if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);

	for ($i = 0, $n = sizeof($values); $i < $n; $i++) {
		$field .= '<option value="' . tep_output_string($values[$i]['id']) . '"';
		if ($default == $values[$i]['id']) {
			$field .= ' SELECTED';
		}

		$field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
	}
	$field .= '</select>';

	if ($required == true) $field .= TEXT_FIELD_REQUIRED;

	return $field;
}
// This funstion validates a plain text password with an
// encrpyted password
function tep_validate_password($plain, $encrypted)
{
	if (tep_not_null($plain) && tep_not_null($encrypted)) {
		// split apart the hash / salt
		$stack = explode(':', $encrypted);

		if (sizeof($stack) != 2) return false;

		if (md5($stack[1] . $plain) == $stack[0]) {
			return true;
		}
	}

	return false;
}
function check_if_string_numeric($str)
{
	$status = true;
	$n = strlen($str) - 1;
	for ($i = 0; $i <= $n; $i++) {
		//echo "the value of i is:".$i."<br>";
		$val = substr($str, $i, 1);
		//echo strlen($password);
		if ($val != 0 && $val != 1 && $val != 2 && $val != 3 && $val != 4 && $val != 5 && $val != 6 && $val != 7 && $val != 8 && $val != 9
		) {
		} else {
			$status = false;
		}
	}
	if ($status == true) {
		return true;
	} else {
		return false;
	}
}

function getMonthNumberFromString($month)
{
	switch ($month) {
		case 'January':
			return '01';
		case 'February':
			return '02';
		case 'March':
			return '03';
		case 'April':
			return '04';
		case 'May':
			return '05';
		case 'June':
			return '06';
		case 'July':
			return '07';
		case 'August':
			return '08';
		case 'September':
			return '09';
		case 'October':
			return '10';
		case 'November':
			return '11';
		case 'December':
			return '12';
	}
}


// returns the month string from number
function getMonthStringFromNumber($month)
{
	switch ($month) {
		case '01':
			return 'January';
		case '02':
			return 'February';
		case '03':
			return 'March';
		case '04':
			return 'April';
		case '05':
			return 'May';
		case '06':
			return 'June';
		case '07':
			return 'July';
		case '08':
			return 'August';
		case '09':
			return 'September';
		case '10':
			return 'October';
		case '11':
			return 'November';
		case '12':
			return 'December';
	}
}



#Function to obtain an array from a comma delimited list
function getArrayFromCommaDelimitedList($string)
{
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
	while (!($pos === false)) {
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



#Function to obtain an values from a comma delimited list
function getValuesFromCommaDelimitedList($string)
{
	# handle empty strings
	# return a blank array
	if (trim($string) == "") {
		return array();
	}
	$i = 0;
	#remove all leading and trailing spaces
	$string = trim($string);
	#find the first ocurrence of a comma
	$pos = strpos($string, "-");
	while (!($pos === false)) {
		#set the new starting position
		$firstpos = $pos + 1;
		#if the string is in the second position, set the first array value
		$lastpos = $pos;
		//$array[$i] = substr($string, 0, $lastpos);
		#reduce the string to start from the new starting position. $firstpos
		$string = substr($string, $firstpos);
		$string = trim($string);
		#locate the first occurence of a comma in the string
		$pos = strpos($string, "-");
		$i++;
	}
	$array[$i] = $string;
	return $array;
}


// funciton that is used to read numerical values
function readNumericalValue($str)
{
	$pos = 1;
	if ($str != "") {
		while (!($pos < strlen($str))) {
			$st = substr($str, $pos, $pos);
			switch ($st) {
				case "1":
					$wordform .=  " One";
					break;
				case "2":
					$wordform .=  " Two";
					break;
				case "3":
					$wordform .=  " Three";
					break;
				case "4":
					$wordform .=  " Four";
					break;
				case "5":
					$wordform .=  " Five";
					break;
				case "6":
					$wordform .=  " Six";
					break;
				case "7":
					$wordform .=  " Seven";
					break;
				case "8":
					$wordform .=  " Eight";
					break;
				case "9":
					$wordform .=  " Nine";
					break;
				case "0":
					$wordform .=  " Zero";
					break;
			}
			$pos++;
		}
	} else {
	}
	return $wordform;
}
function getFileExtentionName($userfile_type)
{
	if ($userfile_type == "image/jpeg" || $userfile_type == "image/pjpeg") {
		return ".jpeg";
	} elseif ($userfile_type == "image/jpg") {
		return ".jpg";
	} elseif ($userfile_type == "image/x-png" || $userfile_type == "image/png") {
		return ".png";
	} elseif ($userfile_type == "image/gif") {
		return ".gif";
	}
}


function generateCheckBoxes($arrayvalues = array(), $currentvalues = array(), $legendlabel = '', $elementid = '', $elementname = '', $bcombine = false)
{

	$checked = "";

	$ncount = 0;
	$checkbox = "<table cellpadding='3' border='0'>";
	foreach ($arrayvalues as $id => $value) {

		if ($elementid == '' && $elementname == '') {
			$elementid  = $id;
			$elementname = $id;
		}

		if ($elementid == '') {
			$elementid  = $id;
		}

		if ($elementname == '') {
			$elementname  = $id;
		}

		if ($bcombine == true) {
			$elementid  = $legendlabel . $id;
		}

		#		check see if checkbox is selected
		if (in_array($id, $currentvalues) || $value == "Tution") {

			$checked = "checked";
		} else {
			$checked = "";
		}
		if ($ncount == 0) {
			$checkbox = $checkbox . "<tr>";
		}
		$checkbox = $checkbox . "<td align='right'><input name='" . $elementname . "' id='" . $elementid . "' type='checkbox' " . $checked . " value='" . $id . "' style='border:0px;margin:0px;background:#FFE28A'></td><td id='a" . $elementid . "'>" . $value . "</td>";

		if ($ncount == 3) {
			$checkbox = $checkbox . "</tr>";
			$ncount = 0;
		}
		$ncount = $ncount + 1;
	}


	//$checkbox= $checkbox."<div style='float:left;width:150px;'>&nbsp;&nbsp;&nbsp;</div><div style='float:left;width:150px;'>&nbsp;</div><div style='float:left;width:150px;'>&nbsp;</div><div style='float:left;width:150px;'>&nbsp;</div>";
	$checkbox = $checkbox . "</table>";
	return $checkbox;
}



function generateCheckBoxList($arrayvalues = array(), $currentvalues = array(), $legendlabel = '', $elementid = '', $elementname = '', $bcombine = false)
{


	$checked = "";

	//print_r($currentvalues);
	//print_r($arrayvalues);
	$ncount = 0;
	$checkbox = "<div class='scrollablecheckboxlist'>";
	foreach ($arrayvalues as $id => $value) {


		$elementid  = $id;




		if ($bcombine == true) {
			$elementid  = $legendlabel . $id;
		}

		#		check see if checkbox is selected
		if (in_array($id, $currentvalues) && $value != '') {

			$checked = "checked";
		} else {
			$checked = "";
		}

		$checkbox = $checkbox . "<input name='" . $elementname . "[]' id='" . $elementid . "' type='checkbox' " . $checked . " value='" . $id . "' style='border:0px;background:#FFE28A;margin-right:10px;'>" . $value . "<br/>";
	}


	//$checkbox= $checkbox."<div style='float:left;width:150px;'>&nbsp;&nbsp;&nbsp;</div><div style='float:left;width:150px;'>&nbsp;</div><div style='float:left;width:150px;'>&nbsp;</div><div style='float:left;width:150px;'>&nbsp;</div>";
	$checkbox = $checkbox . "</div>";
	return $checkbox;
}

#	this function is used to check for user 's user right
#	user_id; USER ID
function getUserRights()
{

	global $user_id;

	$user_roles_modules = array();

	$user_roles = array();

	$roles_query = tep_db_query("SELECT roles_id  FROM " . TABLE_USERROLES . " WHERE user_id='" . $user_id . "'");

	while ($roles = tep_db_fetch_array($roles_query)) {

		$modules_query = tep_db_query("SELECT p.modules_code  FROM " . TABLE_ROLESMODULES . " as rp, " . TABLE_MODULES . " p WHERE rp.modules_id=p.modules_id AND rp.roles_id='" . $roles['roles_id'] . "'");


		$user_roles[] = $roles['roles_id'];

		$user_modules = array();
		$user_role_operations = array();

		while ($modules_array = tep_db_fetch_array($modules_query)) {
			$user_modules[] = $modules_array['modules_code'];
		}


		if (count($user_modules) > 0) {
			$user_roles_modules[$roles['roles_id']] =  array_values($user_modules);
		}

		$operations_query = tep_db_query("SELECT ro.user_id,ro.modules_id, r.roles_id,o.operations_code  FROM " . TABLE_ROLES . " r, " . TABLE_MODULESOPERATIONS . " ro," . TABLE_OPERATIONS . " o WHERE o.operations_id=ro.operations_id AND r.roles_id=ro.roles_id AND r.roles_id='" . $roles['roles_id'] . "'");

		$user_operations = array();
		while ($operations_array = tep_db_fetch_array($operations_query)) {
			$user_operations[] = $operations_array['operations_code'];
		}

		if (count($user_operations) > 0) {
			$user_role_operations[$roles['roles_id']] = array_values($user_operations);
		}

		$branches_query = tep_db_query("SELECT branch_code  FROM " . TABLE_USERBRANCHES . "  WHERE user_accesscode='" . $_SESSION['user_accesscode'] . "'");
		$branch_code = array();
		while ($operations_array = tep_db_fetch_array($branches_query)) {
			$branch_code[] = $operations_array['branch_code'];
		}
	}

	// roles
	if (count($user_roles) > 0) {
		$_SESSION['roles'] = $user_roles;
	} else {
		unset($_SESSION['roles']);
		$_SESSION['roles'] = array('roles' => '');
	}

	// modules
	if (count($user_roles_modules) > 0) {
		$_SESSION['modules'] = $user_roles_modules;
	} else {
		unset($_SESSION['modules']);
		$_SESSION['modules'] = array('modules' => '');
	}

	if (count($user_role_operations) > 0) {
		$_SESSION['operations'] = $user_role_operations;
	} else {
		unset($_SESSION['operations']);
		$_SESSION['operations']	= array('operations', '');
	}

	if (count($branch_code) > 0) {
		$_SESSION['branches'] = $branch_code;
	} else {
		unset($_SESSION['branches']);
		$_SESSION['branches']	= array('branches', '');
	}

	$_SESSION['user_id'] = $user_id;

	session_encode();
}

// Authenticate user
function AuthenticateAccess($pemission_code = '')
{


	$roles  =  $_SESSION['roles'] ?? array();




	if (count($roles) == 0) {
		return 0;
	}

	$modules = isset($_SESSION['modules']) ? $_SESSION['modules'] : array();

	$ncount = 0;

	foreach ($roles as $key => $val) {

		$val = isset($val) ? $val : "";
		// check see if this an array
		//if(is_array($modules[$val])){

		if (isset($modules[$val])) {
			//echo $pemission_code;
			//check see if this permission code exists in this array
			if (in_array($pemission_code, $modules[$val])) {

				return 1;
			}
		}
		//}

		$ncount++;
	}
	$_SESSION['code'] = '01';
	return 0;
}


// Authenticate action for user
function AuthenticateAction($operations_code)
{
	$roles  =  $_SESSION['roles'];

	if (count($roles) == 0) {
		return 0;
	}


	$bstatus = 0;

	foreach ($roles as $key => $val) {

		$operations_query = tep_db_query("SELECT operations_id FROM " . TABLE_MODULESOPERATIONS . " WHERE modules_id='" . $_SESSION['modules_id'] . "' AND roles_id='" . $val . "' AND operations_id IN (SELECT operations_id FROM " . TABLE_OPERATIONS . " WHERE operations_code='" . $operations_code . "')");

		if (tep_db_num_rows($operations_query) > 0) {
			$bstatus = 1;
			break 1;
		}
	}
	//	echo informationUpdate("fail",$lablearray['605'],"");
	return $bstatus;
}

#This functions is used to get a future date given a specified number of dates
function getFutureDate($thedate, $no_of_days)
{
	$thedate = date('d/m/Y', mktime(0, 0, 0, date('m', strtotime($thedate)), date('d', strtotime($thedate)) + $no_of_days, date('Y', strtotime($thedate))));
	return $thedate;
}

#
# function removes slashes from string and replaces them with an underscore
function replace_string($stringvalue)
{

	return reg_replace('/', '_', $stringvalue);
}

# function removes underscores from string and replaces them with a slashes
function replaces_underscores($stringvalue)
{

	return ereg_replace('_', '/', $stringvalue);
}

function PostRepaymentransactions($id, $paymode, $requirementsarray, $voucher, $amountsarray, $trandate, $recfrom, $Obal, $bankaccounts, $cheqno, $checktype, $bankbranch, $branchcode, $cashAccount)
{

	$result  = "";

	if (count($paymode) > 0) {
		$i = 0;

		$lablearray = getlables("362,317,352,362,376,377,298,378,352");

		# generate unique reciept identifier
		$RecID = uniqid();

		$data = '<style>
			h1 {			
				font-family: times;
				font-size: 25pt;
				text-decoration: underline;
			}
			td.underlinecell{
				border-bottom: 0.1px dotted #000000;
			}
			p.first {
				color: #003300;
				font-family: helvetica;
				font-size: 12pt;
			}
			p.first span {
				color: #006600;
				font-style: italic;
			}
			p#second {
				color: rgb(00,63,127);
				font-family: times;
				font-size: 12pt;
				text-align: justify;
			}
			p#second > span {
				background-color: #FFFFAA;
			}
			table.first {
				
				font-family: helvetica;
				font-size: 8pt;
			}
			
			td.second {
				border: 1px dashed #cccccc;
				background-color: #FFFF66;
				border-style: solid solid solid solid;
			}
			div.test {
				color: #CC0000;
				background-color: #FFFF66;
				font-family: helvetica;
				font-size: 10pt;
				border-style: solid solid solid solid;
				text-align: center;
			}
		</style>
		<table border="0" cellspacing="0" cellpadding="2" width="100%" height="100%" class="first" >
		<tr ><td><h2>' . $lablearray['376'] . '</h2><br><h3>' . NAME_OF_INSTITUTION . '</h3><p>' . ADDRESS . '</p></td><td>
		
			<table border="0" cellspacing="0" cellpadding="2">
				<tr><td>' . $lablearray['377'] . '</td><td  class="underlinecell">' . $RecID . '</td></tr>
				<tr><td>' . $lablearray['298'] . '</td><td  class="underlinecell">' . changeMySQLDateToPageFormat($trandate) . '</td></tr>
				<tr><td>' . $lablearray['378'] . '</td><td  class="underlinecell">' . date('d/m/Y') . '</td></tr>
			</table>
		
		</td></tr>';

		$data = $data . '<tr><td>' . $lablearray['352'] . '</td><td class="underlinecell">' . $recfrom . '</td></tr>';

		$pmode = '0';

		foreach ($paymode as $k => $v) {

			$tcode   = concantTcode();

			if (ALLOW_PREPAYMENTS == 0 && (int)$amountsarray[$k] > (int)$Obal[$k]) {
				global $messages_array;
				$lablearray = getlables("363,374,375");
				$messages_array['1'] = '<font color="red">' . $lablearray['363'] . " " . $recname . $lablearray['374'] . $Obal[$k] . ' ' . $i . $lablearray['375'] . '.<br></font>';
			} else {

				if ($Obal[$k] == "") {
					$Obal[$k] = '0';
				}

				if ($v == 'OB' || $v == 'AD') {
					$currentbalance = (int)$Obal[$k] + (int)$amountsarray[$k];
				} else {
					$currentbalance = (int)$Obal[$k] - (int)$amountsarray[$k];
				}


				$curdate = getcurrentDateTime();



				if ($v == "CQ" || $v == "BT" || $v == "BD") {
					$bankbranch_query = tep_db_query("SELECT bankaccounts_accno,chartofaccounts_accountcode FROM " . TABLE_BANKACCOUNTS . " WHERE bankaccounts_accno='" . $bankaccounts[$k] . "'");

					$bankGL = tep_db_fetch_array($bankbranch_query);

					// CHECK EXISTANCE OF BANK ACCOUNT
					if ($bankGL == "") {
						$_SESSION['msg'] = $lablearray['369'] . $bankGL['bankaccounts_accno']; //"Bank Account not configured for "
						return;
					}
				}

				$r_query = tep_db_query("SELECT chartofaccounts_accountcode_fee,chartofaccounts_accountcode_recievable FROM " . TABLE_REQUIREMENTS . " WHERE requirements_id='" . $requirementsarray[$k] . "'");

				$r_array = tep_db_fetch_array($r_query);

				if ($r_array['chartofaccounts_accountcode_recievable'] == "") {
					$lablearray = getlables("365");
					//$_SESSION['msg'] =$lablearray['364'];//"Tution recievable account not configured!";
					return "";
				}

				if ($r_array['chartofaccounts_accountcode_fee'] == "" && ($v == "AD" || $v == "OB")) {
					$lablearray = getlables("364");
					//$_SESSION['msg'] = $lablearray['365'];//Tution Account not configured
					return "";
				}

				$req_query = tep_db_query("SELECT requirements_name FROM " . TABLE_REQUIREMENTS . " WHERE requirements_id='" . $requirementsarray[$k] . "'");
				$req_array = tep_db_fetch_array($req_query);

				switch ($v) {

					case 'CA': // Cash
						$lablearray = getlables("366,367");
						$accDebit = $cashAccount;
						$accCredit = $r_array['chartofaccounts_accountcode_recievable'];
						$generalledger_description = $req_array['requirements_name'] . " " . $id . $lablearray['366']; //": Cash";

						if ($cashAccount == "") {
							$_SESSION['msg'] = $lablearray['367']; //"Cash Account not configured ";
							return "";
						}
						$pmode = '1';
						break;

					case 'CQ':  // Cheque

						// get bank account
						$lablearray = getlables("370,407");
						$accDebit = $bankGL['chartofaccounts_accountcode'];
						$accCredit = $r_array['chartofaccounts_accountcode_recievable'];
						$generalledger_description = $req_array['requirements_name'] . " " . $id . $lablearray['407']; //": Cheque";

						if ($accDebit == "") {
							$_SESSION['msg'] = $lablearray['369'] . $bankGL['bankaccounts_accno']; //"Bank Account not configured for "
							return "";
						}

						$pmode = '2';
						break;

					case 'BT':  // Bank Transfer
						$lablearray = getlables("369,370");
						$accDebit = $bankGL['chartofaccounts_accountcode']; //Recievable
						$accCredit = $r_array['chartofaccounts_accountcode_recievable'];; // Outansding
						$generalledger_description = $req_array['requirements_name'] . " " . $id . $lablearray['369']; //": Bank Transfer";

						if ($accDebit == "") {
							$_SESSION['msg'] = $lablearray['370'] . $bankGL['bankaccounts_accno']; //"Bank Account not configured for 
							return "";
						}
						$pmode = '3';
						break;

					case 'BD': // Student Bank Deposit

						// get bank account
						$lablearray = getlables("370");
						$accDebit = $bankGL['chartofaccounts_accountcode'];
						$accCredit = $r_array['chartofaccounts_accountcode_recievable'];
						$generalledger_description = $req_array['requirements_name'] . " " . $id . $lablearray['370']; //": Bank Deposit Reciept";

						if ($accDebit == "") {
							$lablearray = getlables("371");
							$_SESSION['msg'] = $lablearray['371'] . $bankGL['bankaccounts_accno']; //"Bank Account not configured for "
							return "";
						}
						$pmode = '4';
						break;

					case 'AD': 	// Amount Due - since thre is no money moving from one account to another no need of double entry, we are just puttng money there period.
						$lablearray = getlables("372");
						$accDebit = $r_array['chartofaccounts_accountcode_recievable'];
						$accCredit = $r_array['chartofaccounts_accountcode_fee'];
						$generalledger_description = $req_array['requirements_name'] . " " . $id . $lablearray['372']; //": Amount Due";

						break;

					case 'OB': 	// Opening Balance - since thre is no money moving from one account to another no need of double entry, we are just puttng money there period.
						$lablearray = getlables("373");
						$accDebit = $r_array['chartofaccounts_accountcode_recievable'];
						$accCredit = $r_array['chartofaccounts_accountcode_fee'];
						$generalledger_description = $req_array['requirements_name'] . " " . $id . $lablearray['373']; //": Opening Balance";
						break;

					default:
						break;
				}



				$sql[] = array($amountsarray[$k], '0', $voucher, $accDebit, $requirementsarray[$k], $students_sregno, changeDateFromPageToMySQLFormat($curdate), $_SESSION['user_id'], $generalledger_description, 'R01', '', getCurrencyID($accDebit)); 		// Debit
				$sql[] = array('0', $amountsarray[$k], $voucher, $accCredit, $requirementsarray[$k], $students_sregno, changeDateFromPageToMySQLFormat($curdate), $_SESSION['user_id'], $generalledger_description, 'R01', '', getCurrencyID($accDebit)); 		// Credit

				if ($v == 'CQ') {
					$cheqs[] = array($tcode, $bankaccounts[$k], $cheqno[$k], $checktype[$k], $amountsarray[$k]);
				}

				/*$r_query = tep_db_query("SELECT chartofaccounts_accountcode_fee,chartofaccounts_accountcode_recievable FROM ". TABLE_REQUIREMENTS." WHERE id='".$requirementsarray[$k]."'");				 		  
					
			 		$r_array = tep_db_fetch_array($r_query);						  
			
					$accDebit = $r_array['chartofaccounts_accountcode_fee'];
					$accRecievable = $r_array['chartofaccounts_accountcode_recievable'];
					*/

				tep_db_query("INSERT INTO " . TABLE_STUDENTSPAYMENTS . " (students_sregno,requirements_id,studentspayments_amount,transactiontypes_code,studentspayments_voucher,user_id,studentspayments_recievedfrom,reciepts_code,studentspayments_datecreated,branchcode,tcode) values ('" . tep_db_input($id) . "','" . tep_db_input($requirementsarray[$k]) . "','" . tep_db_input($amountsarray[$k]) . "','" . tep_db_input($v) . "','" . tep_db_input($voucher) . "','" . $_SESSION['user_id'] . "','" . tep_db_input($recievedfrom) . "','" . $RecID . "','" . $curdate . "','" . BRANCHCODE . "','" . $tcode . "')");

				$lastinsetid = tep_db_insert_id();

				// check see if VAT is enabled
				$nvat = 0.00000;

				if (VAT_ON_FEES_ENABLED == 'Y' && ($v == 'CA' || $v == 'CQ' || $v == 'BD' || $v == 'BT')) {

					$vat_query = tep_db_query("SELECT vat_percentage FROM " . TABLE_VAT . " WHERE vat_itemcode='R" . tep_db_input($requirementsarray[$k]) . "'");

					$vat_array = tep_db_fetch_array($vat_query);

					$nVat = calulateVAT($vat_array['vat_percentage'], $amounts_array['feecategoriesamount_amount']);

					// check see if we have any VAT to post
					if ($nVat > 0) {
						$lablearray = getlables("708");
						$sql[] = array($nVat, '0', '', ACC_VAT_ON_FEES, $amounts_array['requirements_id'], $students_sregno, changeDateFromPageToMySQLFormat($curdate), $_SESSION['user_id'], $students_sregno . ': ' . $lablearray['708'], 'V01', getCurrencyID(ACC_VAT_ON_FEES)); 		// Debit
						$sql[] = array('0', $nVat, '', $accDebit, $amounts_array['requirements_id'], $students_sregno, changeDateFromPageToMySQLFormat($curdate), $_SESSION['user_id'], $students_sregno . ': ' . $lablearray['708'], 'V01', getCurrencyID($accDebit)); 		// Credit
					}
				}

				if ($v == 'BD' || $v == 'BT') {
					tep_db_query("INSERT INTO " . TABLE_BANKTRANSACTIONS . " (bankaccounts_id,tcode) VALUES ('" . $bankGL['bankaccounts_id'] . "','" . $tcode . "')");
				}
			}


			$lablearray = getlables("374,270,379,380,381");
			$recname = getRequirementName($requirementsarray[$k]);

			$data = $data . '<tr><td>' . $lablearray['270'] . $_SESSION['user_name'] . '</td><td align="right" class="underlinecell">' . $recname . '</td></tr>';
			$data = $data . '<tr>
					<td></td>
					<td >
						<table border="1" width="140px" cellspacing="0" cellpadding="1">
							<tr><td>' . $lablearray['379'] . '</td><td align="right">' . $Obal[$k] . '</td></tr>
							<tr><td >' . $lablearray['380'] . '</td><td align="right">' . $amountsarray[$k] . '</td></tr>
							<tr><td>' . $lablearray['381'] . '</td><td align="right">' . ((int)$Obal[$k] - (int)$amountsarray[$k]) . '</td></tr>
						</table>		
					</td>
				</tr>';

			$i++;
		}


		PostTransactionsGeneral($sql, $tcode);

		// see if we have got cheqs to post
		if (count($cheqs) > 0) {
			submitCheq($cheqs, 'C', $tcode);
		}
	}

	$lablearray = getlables("311,382,383,371,384,385");
	$data = $data . '<tr><td colspan="2">		
		<b>' . $lablearray['385'] . '</b><br>
			<table border="0" cellspacing="0" width="170px" cellpadding="1">
				<tr><td>' . $lablearray['311'] . '</td><td><table width="15px"  style="border-width: 1px 1px 1px 1px;" bgcolor="' . ($pmode == '1' ? 'bgcolor:#000000;' : "") . '"><tr><td></td></tr></table></td></tr>
				<tr><td>' . $lablearray['382'] . '</td><td><table width="15px" style="border-width: 1px 1px 1px 1px;" bgcolor="' . ($pmode == '2' ? 'bgcolor:#000000;' : "") . '"><tr><td></td></tr></table></td></tr>
				<tr><td>' . $lablearray['383'] . '</td><td><table width="15px" style="border-width: 1px 1px 1px 1px;" bgcolor="' . ($pmode == '3' ? 'bgcolor:#000000;' : "") . '"><tr><td></td></tr></table></td></tr>
				<tr><td>' . $lablearray['371'] . '</td><td><table width="15px" style="border-width: 1px 1px 1px 1px;" bgcolor="' . ($pmode == '4' ? 'bgcolor:#000000;' : "") . '"><tr><td></td></tr></table></td></tr>
			</table>			
		</td></tr>';

	$data = $data . '<tr ><td align="right" >' . $lablearray['384'] . '</td><td align="right" class="underlinecell" >' . $_SESSION['user_name'] . '</td></tr></table>';

	# insert reciept into reciept table
	tep_db_query("INSERT INTO " . TABLE_RECIEPTS . " (reciepts_code,reciepts,reciepts_datecreated) VALUES ('" . $RecID . "','" . tep_db_input($data) . "',NOW())");

	$_SESSION['reciepts_code'] = $RecID;
}

// this funciton is ued top calculate VAT
function calulateVAT($nvat_percentage, $Amount)
{

	if ($nvat_percentage == 0 || $Amount == 0) {
		$nVat = 0;
	} else {
		$nVat = round((float)$amounts_array['feecategoriesamount_amount'] * (float)$vat_array['vat_percentage'] / 100, SETTTING_ROUND_TO);
	}

	return $nVat;
}



# This functin is used to post transaction to the general ledger
function PostTransactionsGeneral($sql, $tcode, $branchcode = BRANCHCODE)
{

	global $messageToStack;

	if (SETTTING_CURRENCY_CODE == "") {
		$lablearray = getlables("695");
		$messageToStack->add($lablearray['695']);
		$_SESSION['msg'] = $lablearray['695'];
		return false;
	}

	reset($sql);

	foreach ($sql as $key => $value) {
		$currency[] = $value[11];
	}

	if (count(array_unique($currency)) > 1) {
		$lablearray = getlables("681");
		//   $messageToStack->add($lablearray['681']);
		echo informationUpdate('fail', $lablearray['681']);
		$_SESSION['msg'] = $lablearray['681'];
		unset($sql);
		return false;
	}



	tep_db_BeginTransaction();

	reset($sql);

	foreach ($sql as $key => $value) {

		// check see if transaction type is added
		if ($value[4] == "") {
			$value[4] = 0;
		}

		if ($value[6] == "") {
			$value[6] = getcurrentDateTime();
		}


		// check if transaction are of local currency
		if (SETTTING_CURRENCY_CODE == $value[11]) {

			$value[0] = round((float)$value[0], SETTTING_ROUND_TO);
			$value[1] = round((float)$value[1], SETTTING_ROUND_TO);
		} else {

			//getexchnage rate 

			$ex_rate_array = getExchangeRate($value[11], $value[6]);


			$forexrates_id_array = array_keys($ex_rate_array);

			if ($ex_rate_array[$forexrates_id_array[0]] == 0) {

				$lablearray = getlables("696");

				unset($sql);

				tep_db_query("ROLLBACK");

				echo informationUpdate('fail', $value[11] . ' ' . $lablearray['696']);

				// for post pages
				$_SESSION['msg'] = $value[11] . ' ' . $lablearray['696'];

				return 0;
			}

			// foreign currency amount
			$fcamount = 0;

			if ($value[0] > 0) {
				$fcamount = $value[0];
			}

			if ($value[1] > 0) {
				$fcamount = $value[1];
			}


			// set foreight currency amount
			if ($value[1] <> 0) {
				$value[12] = $value[1];
			}

			if ($value[2] <> 0) {
				$value[12] = $value[2];
			}

			$value[0] = round((float)$ex_rate_array[$forexrates_id_array[0]] * (float)$value[0], SETTTING_ROUND_TO);
			$value[1] = round((float)$ex_rate_array[$forexrates_id_array[0]] * (float)$value[1], SETTTING_ROUND_TO);
		}

		if ($value[10] != "") {
			$branchcode = $value[10];
		}

		tep_db_query("INSERT INTO " . TABLE_GENERALLEDGER . " (tcode,generalledger_debit,generalledger_credit,generalledger_voucher,chartofaccounts_accountcode,transactiontypes_id,students_sregno,generalledger_datecreated,users_id,generalledger_description,trancode,branchcode,entrydate,forexrates_id,generalledger_fcamount) VALUES ('" . $tcode . "','" . $value[0] . "','" . $value[1] . "','" . $value[2] . "','" . $value[3] . "','" . $value[4] . "','" . $value[5] . "'," . $value[6] . ",'" . $value[7] . "','" . $value[8] . "','" . $value[9] . "','" . $branchcode . "',NOW(),'" . $forexrates_id_array[0] . "','" . $fcamount . "')");
	}


	tep_db_Commit();
	$lablearray = getlables("345");
	$_SESSION['msg'] = $lablearray['345'];
	// removes all elements from array
	unset($sql);

	return true;
}

function getExchangeRate($currencies_id, $dtDate)
{

	$currency_query = tep_db_query("SELECT forexrates_id,forexrates_midrate FROM " . TABLE_FOREXRATES . "  WHERE forexrates_date<=" . $dtDate . " AND $currencies_id='" . $currencies_id . "' ORDER BY forexrates_date DESC LIMIT 1");
	$currency_array = tep_db_fetch_array($currency_query);

	return 	array(trim($currency_array['forexrates_id']) => trim($currency_array['forexrates_midrate']));
}

// used to get the gl Account currency
function getCurrencyID($GlAccount)
{
	$Accounts_query = tep_db_query("SELECT currencies_id FROM " . TABLE_CHARTOFACCOUNTS . " WHERE chartofaccounts_accountcode='" . $GlAccount . "'");
	$cCurrency = tep_db_fetch_array($Accounts_query);
	return $cCurrency['currencies_id'];
}
# This functin is used to post cheques to the cheqs table
# parameters cheqs array fro transactions, action(action to be take again the chech transaction
# (C) clear (B) bouunce cheque (P) Post Cheque
function submitCheq($cheqs, $action = 'C', $tcode = '')
{

	reset($cheqs);

	foreach ($cheqs as $key => $value) {

		// get bank branch
		$bankbranch_query = tep_db_query("SELECT bankbranches_id FROM " . TABLE_BANKACCOUNTS . " WHERE bankaccounts_accno='" . $value[1] . "'");

		$bankbranch = tep_db_fetch_array($bankbranch_query);

		tep_db_query("INSERT INTO " . TABLE_CHEQS . " (tcode,bankaccounts_accno,cheqs_no,cheqs_type,cheqs_amount,cheqs_status,cheqs_datecreated,bankbranches_id) VALUES ('" . $tcode . "','" . $value[1] . "','" . $value[2] . "','" . $value[3] . "','" . $value[4] . "','" . $action . "',NOW(),'" . $bankbranch['bankbranches_id'] . "')");
	}

	return true;
}



function getAllOutstandingBalances($students_sregno)
{
	$oBals = array();

	#	get balances
	# 	get all charges since students join school for items
	$charges_query = tep_db_query("SELECT r.requirements_id,r.requirements_name, SUM(IFNULL(fca.feecategoriesamount_amount,0)) AS amt FROM " . TABLE_FEECATEGORIESAMOUNT . "  AS fca, " . TABLE_REQUIREMENTS . " AS r, " . TABLE_STUDENTFEECATEGORIES . " as sfc WHERE fca.feecategories_id=sfc.feecategories_id  AND sfc.students_sregno='" . $students_sregno . "' AND fca.requirements_id=r.requirements_id  GROUP BY fca.requirements_id");
	$i = 0;
	while ($charges_array = tep_db_fetch_array($charges_query)) {

		# get all payments since student joined school for this item
		$payments_query = tep_db_query("SELECT requirements_id,Max(studentspayments_datecreated),studentspayments_amount AS paid FROM " . TABLE_STUDENTSPAYMENTS . " WHERE requirements_id='" . $charges_array['requirements_id'] . "' AND students_sregno='" . $students_sregno . "' GROUP BY requirements_id");

		$payments_array = tep_db_fetch_array($payments_query);
		$oBals[$i]  = array($charges_array['requirements_id'], $charges_array['requirements_name'], (int)$charges_array['amt'] - (int)$payments_array['paid']);
		$i++;
	}
	return $oBals;
}


function UpdateTransactions($students_sregno, $requirements_id, $tcode, $theAmount)
{
	#	get transactions of this requirement


	$amounts_query = tep_db_query("SELECT transactiontypes_code,studentspayments_id,studentspayments_amount,studentspayments_balance FROM " . TABLE_STUDENTSPAYMENTS . "  WHERE  students_sregno='" . $students_sregno . "' AND requirements_id='" . $requirements_id . "' ORDER BY studentspayments_id ASC");

	$balance = 0;



	while ($amounts = tep_db_fetch_array($amounts_query)) {

		// check see this is a bill
		if ($amounts['transactiontypes_code'] == "AD") {
			$balance = $balance + $amounts['studentspayments_amount'];
		} else {
			$balance = $balance - $amounts['studentspayments_amount'];
		}

		tep_db_query("UPDATE " . TABLE_STUDENTSPAYMENTS . " SET studentspayments_balance='" . $balance . "' WHERE studentspayments_id = '" . $amounts['studentspayments_id'] . "'");
	}

	// get the debit
	$debit_query = tep_db_query("SELECT sum(generalledger_debit) as amt,chartofaccounts_accountcode as acc,generalledger_description,chartofaccounts_accountcode FROM " . TABLE_GENERALLEDGER . " WHERE tcode='" . $tcode . "' AND generalledger_debit > 0 GROUP BY tcode");
	$modify_debit = tep_db_fetch_array($debit_query);

	// get the credit
	$credit_query = tep_db_query("SELECT sum(generalledger_credit) as amt,chartofaccounts_accountcode as acc,generalledger_description,chartofaccounts_accountcode FROM " . TABLE_GENERALLEDGER . " WHERE tcode='" . $tcode . "' AND generalledger_credit > 0 GROUP BY tcode");
	$modify_credit = tep_db_fetch_array($credit_query);

	$curdate = getcurrentDateTime();

	// reversal
	$sql[] = array('0', $modify_debit['amt'], '', $modify_debit['chartofaccounts_accountcode'], $requirements_id, $students_sregno, $curdate, $_SESSION['user_id'], $modify_debit['generalledger_description'] . ' Correction', 'R01', getCurrencyID($modify_debit['chartofaccounts_accountcode'])); 		// Debit
	$sql[] = array($modify_credit['amt'], '0', '', $modify_credit['chartofaccounts_accountcode'], $requirements_id, $students_sregno, $curdate, $_SESSION['user_id'], $modify_debit['generalledger_description'] . ' Correction', 'R01', getCurrencyID($modify_credit['chartofaccounts_accountcode'])); 		// Credit

	PostTransactionsGeneral($sql, $tcode);

	// actual
	$sql[] = array($theAmount, '0', '', $modify_debit['chartofaccounts_accountcode'], $requirements_id, $students_sregno, $curdate, $_SESSION['user_id'], $modify_debit['generalledger_description'], 'R01', getCurrencyID($modify_debit['chartofaccounts_accountcode'])); 		// Debit
	$sql[] = array('0', $theAmount, '', $modify_credit['chartofaccounts_accountcode'], $requirements_id, $students_sregno, $curdate, $_SESSION['user_id'], $modify_debit['generalledger_description'], 'R01', getCurrencyID($modify_credit['chartofaccounts_accountcode'])); 		// Credit

	PostTransactionsGeneral($sql, $tcode);

	return true;
}

function getAllPayments($students_sregno)
{
	$oBals = array();

	#	get balances
	# 	get all charges since students join school for items
	$charges_query = tep_db_query("SELECT r.requirements_id,r.requirements_name, SUM(IFNULL(fca.feecategoriesamount_amount,0)) AS amt FROM " . TABLE_FEECATEGORIESAMOUNT . "  AS fca, " . TABLE_REQUIREMENTS . " AS r LEFT JOIN " . TABLE_STUDENTFEECATEGORIES . " as sfc ON fca.feecategories_id=sfc.feecategories_id WHERE sfc.students_sregno='" . $students_sregno . "' AND fca.requirements_id=r.requirements_id  GROUP BY fca.requirements_id");
	$i = 0;
	while ($charges_array = tep_db_fetch_array($charges_query)) {

		# get all payments since student joined school for this item
		$payments_query = tep_db_query("SELECT requirements_id,SUM(IFNULL(studentspayments_amount,0)) AS paid FROM " . TABLE_STUDENTSPAYMENTS . " WHERE requirements_id='" . $charges_array['requirements_id'] . "' AND students_sregno='" . $students_sregno . "' GROUP BY requirements_id");

		$payments_array = tep_db_fetch_array($payments_query);
		$oBals[$i]  = array($charges_array['requirements_id'], $charges_array['requirements_name'], (int)$charges_array['amt'] - (int)$payments_array['paid']);
		$i++;
	}
	return $oBals;
}

function drawDowloansearchBtn($buttons)
{

	switch ($buttons) {

		case 'EXPDF': // PDF and EXCEL


			break;

		case 'SH': //Search

			break;

		case 'ALL': //Search

			break;

		default:

			break;
	}
}

function DrawCoA($glaccounts = array())
{

	if (count($glaccounts) == 0) {
		$glaccounts = getAccountLevels('0', 'coa');
	}


	$rowcolor = '#FFFFFF';

	$html_out = "<table width='100%' cellpading='5' cellspacing='2' bgcolor='white'>";

	foreach ($glaccounts as $key => $value) {

		$html_out = $html_out . '<tr  onmouseout="mouseOut(this);" onmouseOver="mouseIn(this);" onClick="ChangeColor(this, true,\'' . $key . '\');" style=\"cursor:default;\">';

		if ($rowcolor == '#FFFFFF') {

			$rowcolor = '';
		} else {

			$rowcolor = '#FFFFFF';
		}

		$html_out = $html_out . "<td width='50px;'></td><td title='Click to edit account'><input type='hidden' id='" . $key . "' value='" . $key . "'><span id='icon" . $key . "' ></span>" . $value . "</td>";

		$html_out = $html_out . "</tr>";
	}

	$html_out = $html_out . "</table>";

	return $html_out;
}
function DrawComboFromArray($allaccounts = array(), $fieldname = '', $selected_id = '', $type = 'combo', $onChange = "", $ctype = "", $frmid = "")
{

	switch ($type) {

		case 'combo':
			$html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "' onChange='" . $onChange . "' " . $ctype . ">";
			//$lablearray = getlables("1069");
			// $html_out .= "<option id='' value=''></option>";
			if (count($allaccounts) > 0) {

				foreach ($allaccounts as $key => $value) {

					if ($key == trim($selected_id)) {
						$html_out .= "<option id='" . trim($key) . "' value='" . trim($key) . "' selected >" . $value . "</option>";
					} else {
						$html_out .= "<option id='" . trim($key) . "'  value='" . trim($key) . "'>" . $value . "</option>";
					}
				}
			}

			$html_out .=  '</select>';

			break;


		case 'banks':

			$cwhere = "";
			$lablearray = getlables("998,999");

			//if(isset($_SESSION['licence_build'])){
			//$banks_query = tep_db_query("SELECT licence_build,licence_organisationname FROM ".TABLE_LICENCE."  WHERE licence_build='".$_SESSION['licence_build']."' ".$cwhere." ORDER BY licence_organisationname ASC");
			//	$selected_id = $_SESSION['licence_build'];


			//	}else{

			//return ' <b>'.$lablearray['998'].'</b>';
			//}
			$banks_query = tep_db_query("SELECT licence_build,licence_organisationname FROM " . TABLE_LICENCE . " ORDER BY licence_organisationname ASC");
			$html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";
			$html_out .= "<option id='' value=''>-----</option>";
			while ($banks = tep_db_fetch_array($banks_query)) {
				if ($banks['licence_build'] == $selected_id) {
					$html_out = $html_out . "<option id='" . $banks['licence_build'] . "' value='" . $banks['licence_build'] . "' selected >" . $banks['licence_organisationname'] . "</option>";
				} else {
					$html_out = $html_out . "<option id='" . $banks['licence_build'] . "'  value='" . $banks['licence_build'] . "'>" . $banks['licence_organisationname'] . "</option>";
				}
			}
			$html_out = $html_out . '</select>';

			break;

		case 'operatorbranches':

			if ($_SESSION['user_accesscode'] != "") {
				$cwhere = "  AND user_accesscode='" . $_SESSION['user_accesscode'] . "'"; // AND branch_code='".$_SESSION['branch_code']."'";
			}

			$banks_query = tep_db_query("SELECT bankbranches_id,ob.branch_code,bankbranches_name FROM " . TABLE_USERBRANCHES . " ub, " . TABLE_OPERATORBRANCHES . " ob  WHERE ub.branch_code=ob.branch_code " . $cwhere . " ORDER BY bankbranches_name ASC");

			$html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "' " . $ctype . ">";

			$html_out = $html_out . "<option value='' id=''>-----</option>";

			if ($selected_id == ''):
				$selected_id = BRANCHCODE;
			endif;

			while ($banks = tep_db_fetch_array($banks_query)) {

				if ($banks['branch_code'] == $selected_id) {
					$html_out = $html_out . "<option id='" . $banks['branch_code'] . "' value='" . $banks['branch_code'] . "' selected >" . $banks['bankbranches_name'] . "</option>";
				} else {
					$html_out = $html_out . "<option id='" . $banks['branch_code'] . "'  value='" . $banks['branch_code'] . "'>" . $banks['bankbranches_name'] . "</option>";
				}
			}
			$html_out = $html_out . '</select>';

			break;

		case 'USERS':

			if ($selected_id != "") {
				$cwhere = "WHERE user_id='" . $selected_id . "'";
			}

			$banks_query = tep_db_query("SELECT user_id,user_firstname,user_lastname FROM " . TABLE_USERS . " " . $cwhere . " ORDER BY user_id DESC");

			$html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

			$html_out = $html_out . "<option value='' id='' selected>-----</option>";

			while ($user = tep_db_fetch_array($banks_query)) {
				if ($key == $selected_id) {
					$html_out = $html_out . "<option id='" . $user['user_id'] . "' value='" . $user['user_id'] . "' >" . $user['user_firstname'] . " " . $user['user_lastname'] . "</option>";
				} else {
					$html_out = $html_out . "<option id='" . $user['user_id'] . "'  value='" . $user['user_id'] . "'>" . $user['user_firstname'] . " " . $user['user_lastname'] . "</option>";
				}
			}
			$html_out = $html_out . '</select>';

			break;

		case 'roles':

			if ($selected_id != "") {
				$cwhere = "WHERE roles_id='" . $selected_id . "'";
			}

			$banks_query = tep_db_query("SELECT roles_id,roles_name FROM " . TABLE_ROLES);

			$html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

			$html_out = $html_out . "<option value='' id='' selected>-----</option>";

			while ($roles = tep_db_fetch_array($banks_query)) {
				if ($key == $selected_id) {
					$html_out = $html_out . "<option id='" . $roles['roles_id'] . "' value='" . $roles['roles_id'] . "'  >" . $roles['roles_name'] . "</option>";
				} else {
					$html_out = $html_out . "<option id='" . $roles['roles_id'] . "'  value='" . $roles['roles_id'] . "'>" . $roles['roles_name'] . "</option>";
				}
			}
			$html_out = $html_out . '</select>';

			break;

		case 'modules':

			if ($selected_id != "") {
				$cwhere = "WHERE modules_id='" . $selected_id . "'";
			}

			$banks_query = tep_db_query("SELECT modules_id,modules_description FROM " . TABLE_MODULES);


			$html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

			$html_out = $html_out . "<option value='' id='' selected>-----</option>";

			while ($modules = tep_db_fetch_array($banks_query)) {
				if ($key == $selected_id) {
					$html_out = $html_out . "<option id='" . $modules['modules_id'] . "' value='" . $modules['modules_id'] . "'  >" . $modules['modules_description'] . "</option>";
				} else {
					$html_out = $html_out . "<option id='" . $modules['modules_id'] . "'  value='" . $modules['modules_id'] . "'>" . $modules['modules_description'] . "</option>";
				}
			}
			$html_out = $html_out . '</select>';


			break;

		case 'CASHACCOUNTS':

			if ($selected_id != "") {
				//$cwhere = " WHERE roles_id='".$_sselected_id."'";
			}

			$banks_query = tep_db_query("SELECT  chartofaccounts_accountcode,cashaccounts_name FROM " . TABLE_ROLESCASHACCOUNTS . " rcc INNER JOIN " . TABLE_CASHACCOUNTS . " cc ON cc.chartofaccounts_accountcode=rcc.chartofaccounts_accountcode " . $cwhere . " GROUP BY cc.chartofaccounts_accountcode");


			$html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

			$html_out = $html_out . "<option value='' id='' >-----</option>";

			while ($modules = tep_db_fetch_array($banks_query)) {
				if ($key == $selected_id) {
					$html_out = $html_out . "<option id='" . $modules['chartofaccounts_accountcode'] . "' value='" . $modules['chartofaccounts_accountcode'] . "'  selected>" . $modules['chartofaccounts_accountcode'] . " " . $modules['cashaccounts_name'] . "</option>";
				} else {
					$html_out = $html_out . "<option id='" . $modules['chartofaccounts_accountcode'] . "'  value='" . $modules['chartofaccounts_accountcode'] . "'>" . $modules['chartofaccounts_accountcode'] . " " . $modules['cashaccounts_name'] . "</option>";
				}
			}
			$html_out = $html_out . '</select>';


			break;

		case 'INSTYPE':
		case 'FREQUENCY':
			$lablearray1 = getlables("869,44,1117,1118,1119,45,1121,1122,1165");

			$html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";
			$html_out = $html_out . "<option value='' id='' " . ($selected_id == '' ? 'selected' : '') . ">-----</option>";
			$html_out = $html_out . "<option value='D' id='D'" . ($selected_id == 'D' ? 'selected' : '') . ">" . $lablearray1['869'] . "</option>";
			$html_out = $html_out . "<option value='W' id='W'" . ($selected_id == 'W' ? 'selected' : '') . ">" . $lablearray1['1117'] . "</option>";
			$html_out = $html_out . "<option value='HM' id='HM' " . ($selected_id == 'HM' ? 'selected' : '') . ">" . $lablearray1['1118'] . "</option>";
			$html_out = $html_out . "<option value='FW' id='FW' " . ($selected_id == 'FW' ? 'selected' : '') . ">" . $lablearray1['1119'] . "</option>";
			$html_out = $html_out . "<option value='M' id='M' " . ($selected_id == 'M' ? 'selected' : '') . ">" . $lablearray1['45'] . "</option>";
			$html_out = $html_out . "<option value='TM' id='TM'" . ($selected_id == 'TM' ? 'selected' : '') . ">" . $lablearray1['1121'] . "</option>";
			$html_out = $html_out . "<option value='Q' id='Q' " . ($selected_id == 'Q' ? 'selected' : '') . ">" . $lablearray1['1122'] . "</option>";
			$html_out = $html_out . "<option value='A' id='A' " . ($selected_id == 'A' ? 'selected' : '') . ">" . $lablearray1['1165'] . "</option>";
			$html_out = $html_out . '</select>';



			break;

		case 'INTTYPE':
			$lablearray1 = getlables("1124,1125,1126,1123");
			$html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";
			$html_out = $html_out . "<option value='' id='' >-----</option>";
			$html_out = $html_out . "<option value='FR' id='FR'>" . $lablearray1['1124'] . "</option>";
			$html_out = $html_out . "<option value='DA' id='DA' >" . $lablearray1['1125'] . "</option>";
			$html_out = $html_out . "<option value='DD' id='DD' selected> " . $lablearray1['1126'] . "</option>";
			$html_out = $html_out . '</select>';
			break;

		case 'FUNDCODE':
			if ($selected_id != "") {
				$cwhere = "WHERE fund_code='" . $selected_id . "'";
			}

			$fund_query = tep_db_query("SELECT fund_code,fund_name FROM " . TABLE_FUND);


			$html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

			$html_out = $html_out . "<option value='' id='' selected>-----</option>";

			while ($fund = tep_db_fetch_array($fund_query)) {
				if ($key == $selected_id) {
					$html_out = $html_out . "<option id='" . $fund['fund_code'] . "' value='" . $fund['fund_code'] . "'  >" . $fund['fund_name'] . "</option>";
				} else {
					$html_out = $html_out . "<option id='" . $fund['fund_code'] . "'  value='" . $fund['fund_code'] . "'>" . $fund['fund_name'] . "</option>";
				}
			}
			$html_out = $html_out . '</select>';
			break;

		case 'CAT1':
			if ($selected_id != "") {
				$cwhere = "WHERE category1_code='" . $selected_id . "'";
			}

			$cat1_query = tep_db_query("SELECT category1_code,category1_name FROM " . TABLE_CATEGORY1);


			$html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

			$html_out = $html_out . "<option value='' id='' selected>-----</option>";

			while ($cat = tep_db_fetch_array($cat1_query)) {
				if ($key == $selected_id) {
					$html_out = $html_out . "<option id='" . $cat['category1_code'] . "' value='" . $cat['category1_code'] . "'  >" . $cat['category1_name'] . "</option>";
				} else {
					$html_out = $html_out . "<option id='" . $cat['category1_code'] . "'  value='" . $cat['category1_code'] . "'>" . $cat['category1_name'] . "</option>";
				}
			}
			$html_out = $html_out . '</select>';
			break;

		case 'CAT2':
			if ($selected_id != "") {
				$cwhere = "WHERE category2_code='" . $selected_id . "'";
			}

			$cat2_query = tep_db_query("SELECT category2_code,category2_name FROM " . TABLE_CATEGORY2);


			$html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

			$html_out = $html_out . "<option value='' id='' selected>-----</option>";

			while ($cat = tep_db_fetch_array($cat2_query)) {
				if ($cat['category3_code'] == $selected_id) {
					$html_out = $html_out . "<option id='" . $cat['category2_code'] . "' value='" . $cat['category2_code'] . "'  >" . $cat['category2_name'] . "</option>";
				} else {
					$html_out = $html_out . "<option id='" . $cat['category2_code'] . "'  value='" . $cat['category2_code'] . "'>" . $cat['category2_name'] . "</option>";
				}
			}
			$html_out = $html_out . '</select>';
			break;

		case 'CAT3':
			if ($selected_id != "") {
				$cwhere = "WHERE category3_code='" . $selected_id . "'";
			}

			$cat3_query = tep_db_query("SELECT category3_code,category3_name FROM " . TABLE_CATEGORY3);


			$html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

			$html_out = $html_out . "<option value='' id='' selected>-----</option>";

			while ($cat = tep_db_fetch_array($cat3_query)) {
				if ($cat['category3_code'] == $selected_id) {
					$html_out = $html_out . "<option id='" . $cat['category3_code'] . "' value='" . $cat['category3_code'] . "'  >" . $cat['category3_name'] . "</option>";
				} else {
					$html_out = $html_out . "<option id='" . $cat['category3_code'] . "'  value='" . $cat['category3_code'] . "'>" . $cat['category3_name'] . "</option>";
				}
			}
			$html_out = $html_out . '</select>';
			break;

		case 'PRODUCTS':
			if ($selected_id != "") {
				$cwhere = "WHERE product_prodid='" . $selected_id . "'";
			}

			$product_query = tep_db_query("SELECT product_name,product_prodid FROM " . TABLE_PRODUCT);


			$html_out = "<select name='product_prodid' id='product_prodid'  onChange='" . $onChange . "'>";

			$html_out = $html_out . "<option value='' id='' selected>-----</option>";

			while ($product = tep_db_fetch_array($product_query)) {
				if ($product['product_prodid'] == $selected_id) {
					$html_out = $html_out . "<option id='" . $product['product_prodid'] . "' value='" . $product['product_prodid'] . "'  >" . $product['product_prodid'] . " " . $product['product_name'] . "</option>";
				} else {
					$html_out = $html_out . "<option id='" . $product['product_prodid'] . "'  value='" . $product['product_prodid'] . "'>" . $product['product_prodid'] . " " . $product['product_name'] . "</option>";
				}
			}
			$html_out = $html_out . '</select>';

			break;

		case 'LOANPROCESSLEVELS':

			$html_out = "<SELECT name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>
                    <option id='NA' value='NA'>-------</option>
                    <option id='FEEATLA' value='FEEATLA'>Loan Application</option>
                    <option id='FEEATAP' value='FEEATAP'>Loan Approval</option>
                    <option id='FEEATLD' value='FEEATLD'>Loan Disbursement</option>
            </SELECT>";

			break;

		case 'PAYMODES':
			$lablearray1 = getlables("42,311,382,1213,1202");
			$html_out = "<SELECT name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>
                   <option id='NA' value=''>-------</option>
                   <option id='CA' value='CA'>" . $lablearray1['311'] . "</option>
                   <option id='CQ' value='CQ'>" . $lablearray1['382'] . "</option>
                   <option id='SA' value='SA'>" . $lablearray1['1213'] . "</option>
                   <option id='DB' value='DB'>" . $lablearray1['1202'] . "</option>
           </SELECT>";

			break;

		case 'SAVTTYPES':
			$lablearray1 = getlables("42,1027,1027,1212");
			$html_out = "<select id='" . $fieldname . "' name='" . $fieldname . "'>
            <option id='' value='' >-------</option>
            <option id='SD' value='SD'>" .  $lablearray1['1027'] . "</option>
            <option id='SW' value='SW'>" .  $lablearray1['1028'] . "</option>
            <option id='SW' value='IT'>" .  $lablearray1['1212'] . "</option>
            </select>
            <script>            
            $( '#PAYMODES' ).change(function() {
                showValues('" . $frmid . "','modes','search','PAYMODES','load.php',$('#PAYMODES').val());
            });
            </script>
            ";

			break;

		case 'FEES':

			$fees_query = tep_db_query("SELECT fees_id,fees_name FROM " . TABLE_FEES);

			$html_out = "<SELECT name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

			$html_out = $html_out . "<option value='' id='' selected>-------</option>";

			while ($product = tep_db_fetch_array($fees_query)) {

				$html_out = $html_out . "<option id='" . $product['fees_id'] . "' value='" . $product['fees_id'] . "'  >" . $product['fees_name'] . "</option>";
			}
			$html_out = $html_out . '</SELECT>';

			break;

		case 'CURRENCIES':

			$currencies_query = tep_db_query("SELECT currencies_id,name,currencies_code FROM " . TABLE_CURRENCIES);


			$html_out = "<SELECT name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

			$html_out = $html_out . "<option value='' id='' selected>-------</option>";

			while ($product = tep_db_fetch_array($currencies_query)) {
				if (in_array($product['currencies_id'], $allaccounts)) {
					$html_out = $html_out . "<option id='" . $product['currencies_id'] . "' value='" . $product['currencies_id'] . "' selected >" . $product['name'] . " " . $product['currencies_code'] . "</option>";
				} else {

					$html_out = $html_out . "<option id='" . $product['currencies_id'] . "' value='" . $product['currencies_id'] . "'  >" . $product['name'] . " " . $product['currencies_code'] . "</option>";
				}
			}
			$html_out = $html_out . '</SELECT>';
			break;

		case 'LOANPROD':

			$results_query = tep_db_query("SELECT product_prodid,product_name FROM " . TABLE_PRODUCT . " WHERE LEFT(product_prodid,1)='L'");


			$html_out = "<SELECT name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

			// $html_out = $html_out."<option value='' id=''>-------</option>";

			while ($product = tep_db_fetch_array($results_query)) {
				if (in_array($product['product_prodid'], $allaccounts)) {
					$html_out = $html_out . "<option id='" . $product['product_prodid'] . "' value='" . $product['product_prodid'] . "' selected >" . $product['product_name'] . " " . $product['product_prodid'] . "</option>";
				} else {

					$html_out = $html_out . "<option id='" . $product['product_prodid'] . "' value='" . $product['product_prodid'] . "'  >" . $product['product_name'] . " " . $product['product_prodid'] . "</option>";
				}
			}
			$html_out = $html_out . '</SELECT>';
			break;

		case 'SAVPROD':

			$currencies_query = tep_db_query("SELECT product_prodid,product_name FROM " . TABLE_PRODUCT . " WHERE LEFT(product_prodid,1)='S'");


			$html_out = "<SELECT name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

			$html_out = $html_out . "<option value='' id=''>-------</option>";

			while ($product = tep_db_fetch_array($currencies_query)) {
				if (in_array($product['product_prodid'], $allaccounts) || $product['product_prodid'] == $selected_id) {
					$html_out = $html_out . "<option id='" . $product['product_prodid'] . "' value='" . $product['product_prodid'] . "' selected >" . $product['product_name'] . " " . $product['product_prodid'] . "</option>";
				} else {

					$html_out = $html_out . "<option id='" . $product['product_prodid'] . "' value='" . $product['product_prodid'] . "'  >" . $product['product_name'] . " " . $product['product_prodid'] . "</option>";
				}
			}
			$html_out = $html_out . '</SELECT>';
			break;
		case 'PRODGLACCS':
		case 'PRODGLSAV':
		case 'SAVINTCAL':
		case 'PRODGLTDACC':

			switch ($type) {

				case 'SAVINTCAL':
					$glitems = array('METHOD_1' => '(Rate X Amount Saved X Period)-(Rate X Amount Withdrawn X Period)');
					break;

				case 'PRODGLACCS':
					$glitems = array(
						'PRINCIPAL_OUTSTANDING_ACC' => 'Principal Outstanding',
						'PROV_BAD_DEBTS_ACC' => 'Provision for Bad Debts',
						'PROV_COST_ACC' => 'Provision Cost',
						'INT_RECEIVED_ACC' => 'Interest Received',
						'COMM_RECEIVED_ACC' => 'Commision Received',
						'PEN_RECEIVED_ACC' => 'Penalty Received',
						'LOANS_WRITTEN_OFF_ACC' => 'Loans Written Off',
						'ACCRUED_INTEREST_ACC' => 'Accrued Interest',
						'LOANS_RECOVERED_ACC' => 'Loan Recovered',
						'CHEQUE_ACC' => 'Cheque',
						'ACCRUED_PENALTIES_ACC' => 'Accrued Penalties',
						'CURRENCY_DIFF_ACC' => 'Currency Differences',
						'LOAN_COMMISSION_ACC' => 'Loan Commission',
						'LOAN_PRINCIPAL_OVERPAYMENT_ACC' => 'Principal Overpayments',
						'LOAN_INTEREST_OVERPAYMENT_ACC' => 'Interest Overpayments',
						'LOAN_COMMISION_OVERPAYMENT_ACC' => 'Commission Overpayments'
					);
					break;
				case 'PRODGLSAV':
					$glitems = array(
						'SAVINGS_ACC' => 'Savings',
						'STAT_RECEIVED_ACC' => 'Stationery Received',
						'INT_SAV_ACC' => 'Interest on Savings',
						'INT_OD_ACC' => 'Interest on Overdrafts',
						'COMM_SAV_ACC' => 'Commision Received on Savings',
						'WITHHOLDING_TAX_ACC' => 'Withholding Tax',
						'INT_ON_CLOSED_SAVACC' => 'Interest on Closed Accounts'
					);
					break;
				case 'PRODGLTDACC':
					$glitems = array(
						'TIMEDEPOSIT_ACC' => 'Time Deposit Account',
						'INT_TD_ACC' => 'Interest Paid on Time Deposits'
					);
					break;
			}


			asort($glitems);

			$html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

			$html_out = $html_out . "<option value='' id='' selected>-------</option>";

			foreach ($glitems as $key => $val) {

				$html_out = $html_out . "<option id='" . $key . "' value='" . $key . "'  >" . $val . "</option>";
			}
			$html_out = $html_out . '</select>';

			break;

		case 'COACOMBO':

			$glitems = getAccountLevels($id = 0, $type = '');

			$html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

			foreach ($glitems as $key => $val) {
				$html_out = $html_out . $val;
			}
			$html_out = $html_out . "</select>";

			break;
	}

	return $html_out;
}

# This function is used to draw the transaction type combo
# i.e Cash,Bank,Direct Bank Transafer, Bank Direct Deposits
function DrawAccComboTranType($allaccounts)
{

	$combo = "<select name='" . $fieldname . "' id='" . $fieldname . "'>";
	$combo = $combo . "<option value=''>Please select GL account</option>";
	//$allaccounts = getAccountLevels();

	foreach ($allaccounts as $key => $value) {
		if ($key == $selected_id) {
			$combo = $combo . "<option id='" . $key . "' value='" . $key . "' selected >" . $value . "</option>";
		} else {
			$combo = $combo . "<option id='" . $key . "' value='" . $key . "'>" . $value . "</option>";
		}
	}
	$combo = $combo . '</select>';

	return $combo;
}

# This is a recursive function that generates the chart of accounts
# we make combo and times static because we donot want to loose that data popupulated therein when we recall the function
function getAccountLevels($id = 0, $type = '')
{

	static $combo = array();

	static $times = 0;

	$times++;

	//$accounts_query = tep_db_query("SELECT id,name,chartofaccounts_accountcode,OwnerEl FROM " . TABLE_CHARTOFACCOUNTS." WHERE OwnerEl='".$id."'");
	if ($type == 'coa') {

		$accounts_query = tep_db_query("SELECT chartofaccounts_level,chartofaccounts_header,chartofaccounts_accountcode,chartofaccounts_name,chartofaccounts_parent FROM " . TABLE_CHARTOFACCOUNTS . " WHERE chartofaccounts_parent='" . $id . "' order by chartofaccounts_level,chartofaccounts_parent,chartofaccounts_accountcode ASC");

		if ($id != 0):
			$acc_query = tep_db_query("SELECT chartofaccounts_groupcode,chartofaccounts_level,chartofaccounts_accountcode FROM " . TABLE_CHARTOFACCOUNTS . " WHERE chartofaccounts_accountcode IN (SELECT chartofaccounts_parent FROM " . TABLE_CHARTOFACCOUNTS . " WHERE chartofaccounts_accountcode='" . $id . "')");

			$acc_array = tep_db_fetch_array($acc_query);

			if (count($acc_array) > 0):
				//  if($id=='131000'){
				$chartofaccounts_groupcode = generategroupcode($acc_array['chartofaccounts_groupcode'], ($acc_array['chartofaccounts_level'] + 1), $acc_array['chartofaccounts_accountcode']);
				//  }  
				tep_db_query("UPDATE " . TABLE_CHARTOFACCOUNTS . " SET chartofaccounts_level ='" . ($acc_array['chartofaccounts_level'] + 1) . "',chartofaccounts_groupcode='" . $chartofaccounts_groupcode . "' WHERE chartofaccounts_accountcode='" . $id . "'");
			endif;
		else:

			tep_db_query("UPDATE " . TABLE_CHARTOFACCOUNTS . " SET chartofaccounts_level ='1' WHERE chartofaccounts_parent='0'");

		endif;

		while ($accounts_array = tep_db_fetch_array($accounts_query)) {

			// check see if account is a header account=make it bold
			if ($accounts_array['chartofaccounts_header'] == 'Y') {
				$combo[$accounts_array['chartofaccounts_accountcode']] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $times - 1) . '<span style="color:#3186e7;">' . $accounts_array['chartofaccounts_accountcode'] . " : " . $accounts_array['chartofaccounts_name'] . '</span>';
			} else {
				$combo[$accounts_array['chartofaccounts_accountcode']] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $times - 1) . $accounts_array['chartofaccounts_accountcode'] . " : " . $accounts_array['chartofaccounts_name'];
			}

			getAccountLevels($accounts_array['chartofaccounts_accountcode'], 'coa');
		}
	} else {

		$accounts_query = tep_db_query("SELECT chartofaccounts_header,chartofaccounts_accountcode,chartofaccounts_name,chartofaccounts_parent FROM " . TABLE_CHARTOFACCOUNTS . " WHERE chartofaccounts_parent='" . $id . "'");

		while ($accounts_array = tep_db_fetch_array($accounts_query)) {

			if ($accounts_array['chartofaccounts_header'] != 'Y') {

				$combo[] = "<option id='" . $accounts_array['chartofaccounts_accountcode'] . "' value='" . $accounts_array['chartofaccounts_accountcode'] . "'>" . $accounts_array['chartofaccounts_accountcode'] . " : " . $accounts_array['chartofaccounts_name'] . "</option>";
				//$combo[$accounts_array['chartofaccounts_accountcode']] = str_repeat("----",$times-1).">".$accounts_array['chartofaccounts_accountcode']." : ".$accounts_array['chartofaccounts_name'];
				getAccountLevels($accounts_array['chartofaccounts_accountcode'], '');
			} else {
				$combo[] = "<optgroup label='" . $accounts_array['chartofaccounts_accountcode'] . " : " . $accounts_array['chartofaccounts_name'] . "'>";
				getAccountLevels($accounts_array['chartofaccounts_accountcode'], '');
				$combo[] = "</optgroup>";
			}
		}
	}

	$times--;

	return $combo;
}

# This is a recursive function that generates the chart of accounts
# we make combo and times static because we donot want to loose that data popupulated therein when we recall the function
function getAccountNoHeaders()
{

	static $combo = array();


	//$accounts_query = tep_db_query("SELECT id,name,chartofaccounts_accountcode,OwnerEl FROM " . TABLE_CHARTOFACCOUNTS." WHERE OwnerEl='".$id."'");

	$accounts_query = tep_db_query("SELECT coa.chartofaccounts_accountcode,coa.chartofaccounts_name,coa.chartofaccounts_parent,name,coa.currencies_id, c.currencies_code FROM " . TABLE_CHARTOFACCOUNTS . " coa LEFT JOIN " . TABLE_CURRENCIES . " c ON c.currencies_id=coa.currencies_id WHERE coa.chartofaccounts_header='N' ORDER BY coa.chartofaccounts_accountcode ASC");

	while ($accounts_array = tep_db_fetch_array($accounts_query)) {
		$combo[$accounts_array['chartofaccounts_accountcode']] = $accounts_array['chartofaccounts_accountcode'] . " :   " . $accounts_array['chartofaccounts_name'] . " -(" . $accounts_array['name'] . " " . $accounts_array['currencies_code'] . ")";
	}

	return 	$combo;
}


//	function concantTcode(){
//		//$sql ="START TRANSACTION; ";
//	
//		echo 'here';
//		tep_db_query("UPDATE ".TABLE_USERS." SET user_lasttcode1 =(user_lasttcode1 + 1) WHERE user_id = '".$_SESSION['user_id']."'");
//		//$sql =$sql." COMMIT; ";
//		$tcode_query = tep_db_query("SELECT user_lasttcode1 AS tcode,user_usercode FROM " . TABLE_USERS." WHERE user_id = '".$_SESSION['user_id']."'");
//		
//		
//		//$tcode_query = tep_db_query("SELECT configuration_value AS tcode FROM " . TABLE_CONFIGURATION." WHERE configuration_key = 'NEXTTCODE'");
//		$tcode = tep_db_fetch_array($tcode_query);
//
//				
//		// please remove date('Y') in future so that you use a configurable code
//		return  date('Y').$tcode['tcode'].$tcode['user_usercode'];
//	}




# This function is used to draw the cheqs  and Banks control
function DrawCashAccounts($roles_id)
{

	$html_out = "<span id='divcashaccounts'>";

	// change array values to string
	$roles_id2 = implode(" OR rc.roles_id=", $roles_id);
		
	$cashaccounts_query = tep_db_query("SELECT cc.chartofaccounts_accountcode, cashaccounts_name FROM " . TABLE_ROLESCASHACCOUNTS . " as rc, " . TABLE_CASHACCOUNTS . " cc  WHERE cc.chartofaccounts_accountcode=rc.chartofaccounts_accountcode  AND rc.roles_id=" . $roles_id2 . ' GROUP BY cc.chartofaccounts_accountcode,cashaccounts_name');

	$html_out .= "Cash Accounts <br><select id='cashaccounts_code' name='cashaccounts_code'>";

	while ($cashaccounts_array = tep_db_fetch_array($cashaccounts_query)) {

		$html_out .= "<option id='" . $cashaccounts_array['chartofaccounts_accountcode'] . "' value='" . $cashaccounts_array['chartofaccounts_accountcode'] . "'>" . $cashaccounts_array['chartofaccounts_accountcode'] . " | " . $cashaccounts_array['cashaccounts_name'] . "</option>";
	}

	$html_out .= "</select>";

	$html_out .= "</span>";

	return $html_out;
}


# This function is used to draw the cheqs  and Banks control
function drawPrintOptions()
{

	$html_out .= '<table cellpadding="5" border="0"><tr><td align="center"><fieldset>';
	$html_out .= '<ul style=" padding: 2px; width: 250px;margin:0px;float:left;text-align:left;padding:5px;">';
	$html_out .= '<li style="list-style-type: none;padding:2px;"><input name="printoption" type="radio" value="PDF" id="" onClick="document.getElementById(\'txtprintoptions\').value=\'../downloadlistpdf.php?\'" style="margin:0px;float:left;margin-right:2px;">Portable Document Format(PDF)</li>';
	$html_out .= '<li style="list-style-type: none;padding:2px;"><input name="printoption" type="radio" value="HTML" id="" onClick=""  style="margin:0px;float:left;margin-right:2px;" disabled>HTML</li>';
	$html_out .= '<li style="list-style-type: none;padding:2px;"><input name="printoption"  type="radio" value="EXCEL" id="" onClick=""  style="margin:0px;float:left;margin-right:2px;" disabled>Excel</li>';
	$html_out .= '</ul>';
	$html_out .= "</fieldset></td></tr>";
	$html_out .= '<tr><td  align="center"><input  type="reset" value="  Clear  " id="reset" class="actbutton"><input name="btnok" type="button" value="  Ok  " id="reset" class="actbutton" onClick="printOptions()"></td></tr></table>';


	return $html_out;
}


# This function i used to get the currect date and time
function getcurrentDateTime()
{
	return Date('Y-m-d H:i:s');
}

function randColor()
{
	$letters = "1234567890ABCDEF";
	for ($i = 0; $i < 6; $i++) {
		$pos = rand(0, 15);
		$str .= $letters[$pos];
	}
	return $str;
}

function formatNumber($str)
{
	return number_format($str);
}

// this function is used to set the date fort=mat set by the user
function getUserDatetimeFormat($pagedate)
{

	if (DATE_FORMAT == "") {
		return date(DATE_FORMAT, strtotime($pagedate));
	} else {
		return date('d/m/Y', strtotime($pagedate));
	}
}

function ConvertToDatetime($date)
{
	$date = new DateTime(changeDateFromPageToMySQLFormat($date));
	return date_format($date, 'Y-m-d H:i:s');
}

// function is used to generate a trial balanace
function generateTrialBalance($dtm_from, $dtm_to, $rpt = '', $rpt_id = '')
{

	generateTransactionsForYear($dtm_from, $dtm_to);

	$datefrom = changeDateFromPageToMySQLFormat($dtm_from);

	$dateto = changeDateFromPageToMySQLFormat($dtm_to);

	if (date("d", strtotime($dtm_from)) == date("d", strtotime(STARTFINYEAR)) && date("m", strtotime($dtm_from)) == date("m", strtotime(STARTFINYEAR)) && date("Y", strtotime($dtm_from)) >= date("Y", strtotime(STARTFINYEAR))) {
		// get the oldest opening balance
		//dcrip = getlab("002693") 	&& "Opening Balance"

		$db_query = tep_db_query("SELECT MIN(cAllYears.tday) AS tday FROM cAllYears WHERE cAllYears.trancode = 'A00'");



		//if there are no openng balances get the balances for each account
		if (tep_db_num_rows($db_query) > 0) {

			tep_db_query("DROP TABLE IF EXISTS cTrialB1");
			tep_db_query("CREATE TEMPORARY TABLE cTrialB1 AS SELECT cAllYears.chartofaccounts_accountcode as account, SUM(generalledger_debit)-SUM(generalledger_credit) AS start FROM cAllYears GROUP BY cAllYears.chartofaccounts_accountcode");
		} else {

			// get all opening balances
			// note that if we are viewing balance sheet at the begining of the
			// financila year we dont need to recalculates balances from transaction
			// we just nee the opening balances only

			tep_db_query("DROP TABLE IF EXISTS cAllYrs");

			tep_db_query("CREATE TEMPORARY TABLE cAllYrs AS SELECT * FROM cAllYears WHERE TDAY=" . $cStDay . "  AND  cAllYears.trancod='A00'");


			tep_db_query("DROP TABLE IF EXISTS cTrialB1");

			tep_db_query("CREATE TEMPORARY TABLE cTrialB1 AS SELECT cAllYears.chartofaccounts_accountcode as account, SUM(generalledger_debit)-SUM(generalledger_credit) AS start FROM cAllYrs GROUP BY chartofaccounts_accountcode");
		}
	} else {

		tep_db_query("DROP TABLE IF EXISTS cTrialB1");
		// Include opening balances posted by year closure and any transactions before "From Date"
		tep_db_query("CREATE TEMPORARY TABLE cTrialB1 AS SELECT cAllYears.chartofaccounts_accountcode as account, SUM(generalledger_debit)-SUM(generalledger_credit) AS start FROM cAllYears WHERE DATE(tday) < " . $datefrom . "  OR  cAllYears.trancode='A00' GROUP BY cAllYears.chartofaccounts_accountcode ");
	}



	switch ($rpt) {

		case 'CF': // Cashflow statement
			tep_db_query("DROP TABLE IF EXISTS cOpeningbals");

			tep_db_query("CREATE TEMPORARY TABLE cOpeningbals AS SELECT account,IF(Start < 0,Start*-1,Start) as Startbal  FROM cTrialB1");

			tep_db_query("DROP TABLE IF EXISTS cCashflow");

			tep_db_query("DROP TABLE IF EXISTS cGL");

			tep_db_query("CREATE TEMPORARY TABLE cGL AS SELECT * FROM " . TABLE_GENERALLEDGER . " WHERE generalledger_datecreated BETWEEN " . $datefrom . " AND " . $dateto);

			tep_db_query("CREATE TEMPORARY TABLE cCashflow AS SELECT cfheader_cfincrease,chartofaccounts_accountcode_from,chartofaccounts_accountcode_to,cflabel_isdebit,000000000000000000000.0 as Total,000000000000000000000.0 as Balance,h.cfheader_id,l.cflabel_id,l.cflabel_en FROM " . TABLE_CFLABEL . " as l," . TABLE_CFHEADER . " as h WHERE l.cfheader_id=h.cfheader_id AND h.cfReports_id='" . $rpt_id . "' ORDER BY h.cfheader_id,l.cflabel_id");

			break;

		default: // Balance sheet/Trial Balance/ Income Statement
			//check see if we are printing this report at the begining of the financial year

			// get also the accounts that had no operations
			tep_db_query("DROP TABLE IF EXISTS cTrialB2");

			$query = "CREATE TEMPORARY TABLE cTrialB2 AS SELECT chartofaccounts_accountcode AS account,'0' as start FROM " . TABLE_CHARTOFACCOUNTS . " WHERE chartofaccounts_accountcode NOT IN (SELECT account FROM cTrialB1)";


			tep_db_query($query);



			//*-- and join them
			tep_db_query("DROP TABLE IF EXISTS cTrialB3");

			$query = "CREATE TEMPORARY TABLE cTrialB3
							SELECT * FROM cTrialB1
							UNION
							SELECT * FROM cTrialB2";

			tep_db_query($query);



			//now get the debit/credit transactions in the requested period
			tep_db_query("DROP TABLE IF EXISTS cTrialB4");

			$query = "CREATE TEMPORARY TABLE cTrialB4 AS SELECT cAllYears.chartofaccounts_accountcode as account, SUM(generalledger_debit) AS debit, SUM(generalledger_credit) AS credit
								FROM cAllYears," . TABLE_CHARTOFACCOUNTS . " as ca
								WHERE cAllYears.chartofaccounts_accountcode = ca.chartofaccounts_accountcode AND cAllYears.trancode !='A00' AND ca.chartofaccounts_header='N' AND DATE(tday) BETWEEN " . $datefrom . " AND " . $dateto . "
								GROUP BY cAllYears.chartofaccounts_accountcode";

			tep_db_query($query);


			// add the accounts that had no transactions in this period
			tep_db_query("DROP TABLE IF EXISTS cTrialB5");

			tep_db_query("CREATE TEMPORARY TABLE cTrialB5 AS SELECT chartofaccounts_accountcode AS account, 0 as debit,0 as credit FROM " . TABLE_CHARTOFACCOUNTS . " WHERE chartofaccounts_accountcode NOT IN (SELECT account FROM cTrialB4) AND chartofaccounts_header='N'");

			tep_db_query("DROP TABLE IF EXISTS cTrialB6");

			$query = "CREATE TEMPORARY TABLE cTrialB6 AS SELECT * FROM cTrialB4
							UNION
							SELECT * FROM cTrialB5";

			tep_db_query($query);

			tep_db_query("DROP TABLE IF EXISTS cTrialB7");

			//now get starting date cursor and debit/credit operations cursor together
			$query = "CREATE TEMPORARY TABLE cTrialB7 AS SELECT cTrialB3.account, cTrialB3.start, debit, credit,(COALESCE(start,0) + COALESCE(debit,0) ) - ABS(COALESCE(credit,0)) as end FROM cTrialB3, cTrialB6 WHERE cTrialB3.account = cTrialB6.account ";

			tep_db_query($query);


			// OMMITED cTrialB
			tep_db_query("DROP TABLE IF EXISTS cTrialB8");

			$query = "CREATE TEMPORARY TABLE cTrialB8 AS SELECT account,  IF(COALESCE(start,0)>0,COALESCE(start,0),0) AS startdeb,  IF(COALESCE(start,0)<0,-ABS(COALESCE(start,0)),0) AS startcred,
							COALESCE(debit,0) AS debit, COALESCE(credit,0) AS credit, IF(COALESCE(end,0)>0,end,0) AS enddeb,  IF(end<0,-end,0) AS endcred
						FROM cTrialB7";

			tep_db_query($query);



			// check see if we are generating Income and Expenditre report
			if ($rpt == 'INCEXP') {
				tep_db_query("DROP TABLE IF EXISTS cTrialBa");

				$query = "CREATE TEMPORARY TABLE cTrialBa AS SELECT cTrialB8.* FROM cTrialB8," . TABLE_CHARTOFACCOUNTS . " as coa
								WHERE cTrialB8.account=coa.chartofaccounts_accountcode AND chartofaccounts_tgroup > 2";

				tep_db_query($query);


				tep_db_query("DROP TABLE IF EXISTS cTrialB8");

				tep_db_query("CREATE TEMPORARY TABLE cTrialB8 AS SELECT * FROM cTrialBa");

				tep_db_query("DROP TABLE IF EXISTS cTrialBa");
			}

			// profit and loss
			tep_db_query("DROP TABLE IF EXISTS cTrials");

			$query = "CREATE TEMPORARY TABLE cTrials AS SELECT *  FROM cTrialB8";

			tep_db_query($query);


			tep_db_query("DROP TABLE IF EXISTS cTrialB");

			$query = "CREATE TEMPORARY TABLE cTrialB AS
							SELECT cTrialB8.account, chartofaccounts_groupcode as groupcode,
							startdeb, startcred, debit, credit, enddeb,endcred,
							coa.chartofaccounts_name AS label,
							IF(coa.chartofaccounts_parent=0,1,coa.chartofaccounts_level) AS hlevel
							FROM cTrialB8," . TABLE_CHARTOFACCOUNTS . " as coa
							WHERE cTrialB8.account = coa.chartofaccounts_accountcode
							ORDER BY cTrialB8.account,chartofaccounts_groupcode";

			tep_db_query($query);




			//create the groupings here
			// 1st grouping
			tep_db_query("DROP TABLE IF EXISTS cTrialh1");
			$query = "CREATE TEMPORARY TABLE cTrialh1 AS  SELECT cTrialB.*,	coa.chartofaccounts_name AS header1
						FROM cTrialB," . TABLE_CHARTOFACCOUNTS . " as coa
						WHERE  CONCAT(LEFT(cTrialB.groupcode,3),'000000000000000000') = coa.chartofaccounts_groupcode
						GROUP BY cTrialB.ACCOUNT";
			tep_db_query($query);




			// 2st grouping
			tep_db_query("DROP TABLE IF EXISTS cTrialh2");

			$query = "CREATE TEMPORARY TABLE cTrialh2 AS  SELECT cTrialh1.*,coa.chartofaccounts_name AS header2
						FROM cTrialh1," . TABLE_CHARTOFACCOUNTS . " as coa
						WHERE CONCAT(LEFT(cTrialh1.groupcode,6),'000000000000000') = coa.chartofaccounts_groupcode
						GROUP BY cTrialh1.ACCOUNT";

			tep_db_query($query);




			// 2st grouping
			tep_db_query("DROP TABLE IF EXISTS cTrialh3");
			$query = "CREATE TEMPORARY TABLE cTrialh3 AS  SELECT cTrialh2.*,coa.chartofaccounts_name AS header3
						FROM cTrialh2," . TABLE_CHARTOFACCOUNTS . " as coa
						WHERE CONCAT(LEFT(cTrialh2.groupcode,9),'000000000000') = coa.chartofaccounts_groupcode
						GROUP BY cTrialh2.ACCOUNT";
			tep_db_query($query);



			// 2st grouping
			tep_db_query("DROP TABLE IF EXISTS cTrialh4");
			$query = "CREATE TEMPORARY TABLE cTrialh4 AS  SELECT cTrialh3.*,coa.chartofaccounts_name AS header4
						FROM cTrialh3," . TABLE_CHARTOFACCOUNTS . " as coa
						WHERE CONCAT(LEFT(cTrialh3.groupcode,12),'000000000') = coa.chartofaccounts_groupcode
						GROUP BY cTrialh3.ACCOUNT";
			tep_db_query($query);



			// 2st grouping
			tep_db_query("DROP TABLE IF EXISTS cTrialh5");

			$query = "CREATE TEMPORARY TABLE cTrialh5 AS SELECT cTrialh4.*,coa.chartofaccounts_name AS header5
						FROM cTrialh4," . TABLE_CHARTOFACCOUNTS . " as coa
						WHERE CONCAT(LEFT(cTrialh4.groupcode,15),'000000') = coa.chartofaccounts_groupcode
						GROUP BY cTrialh4.ACCOUNT";


			tep_db_query($query);


			tep_db_query("DROP TABLE IF EXISTS cTrialBxx");

			$query = "CREATE TEMPORARY TABLE cTrialBxx AS SELECT Groupcode, account, label , startdeb,startcred, debit, credit,
							enddeb, endcred,
							IF(hlevel=1,'',header1) AS header1,
							IF(hlevel<=2,'',header2) AS header2,
							IF(hlevel<=3,'',header3) AS header3,
							IF(hlevel<=4,'',header4) AS header4,
							IF(hlevel<=5,'',header5) AS header5
							FROM cTrialh5";

			tep_db_query($query);
			break;
	}

	//=================

}

function generateTransactionsForYear($dtm_from, $dtm_to)
{

	// get the start of the financial year
	$startFinYear = date("Y", strtotime(STARTFINYEAR));
	$endFinYear =  date("Y", strtotime(STARTFINYEAR));

	tep_db_query("DROP TEMPORARY  TABLE IF EXISTS cDebtTrans");

	// debtros
	$query = "CREATE TEMPORARY TABLE cDebtTrans AS SELECT debtors_id, tcode
			FROM " . TABLE_PAYMENTSDEBTORS . " UNION
			SELECT debtors_id, invoices_no as tcode
			FROM " . TABLE_INVOICESDEBTORS;

	tep_db_query($query);

	tep_db_query("DROP TEMPORARY  TABLE IF EXISTS cAllYears");

	// merge to generalledger
	$query = "CREATE TEMPORARY TABLE cAllYears AS SELECT gl.*,debtors_id
			FROM " . TABLE_GENERALLEDGER . " as gl LEFT OUTER JOIN cDebtTrans
			ON gl.tcode=cDebtTrans.tcode";


	tep_db_query($query);


	tep_db_query("DROP TEMPORARY  TABLE IF EXISTS cCredTrans");

	//creditors
	$query = "CREATE TEMPORARY TABLE cCredTrans AS SELECT creditors_id, tcode
			FROM " . TABLE_PAYMENTSCREDITORS . " UNION
			SELECT creditors_id, invoices_no as tcode
			FROM " . TABLE_INVOICESCREDITORS;

	tep_db_query($query);

	tep_db_query("DROP TEMPORARY  TABLE IF EXISTS cAllYears1");

	// merge to generalledger
	$query = "CREATE TEMPORARY TABLE cAllYears1 AS SELECT cAllYears.*,creditors_id
			FROM cAllYears LEFT OUTER JOIN cCredTrans
			ON cAllYears.tcode=cCredTrans.tcode";


	tep_db_query($query);

	tep_db_query("DROP TABLE IF EXISTS cCredTrans");

	tep_db_query("DROP TABLE IF EXISTS cDebtTrans");



	//tep_db_query("CREATE TEMPORARY TABLE cAllYears AS SELECT gl.*,'' as debtors_id,'' as creditors_id FROM ".TABLE_GENERALLEDGER." as gl WHERE trancode!='A00'");


	if ($dtm_from < STARTFINYEAR) {



		$openbal = "Opening Balance";

		tep_db_query("DROP TABLE IF EXISTS cAllYears");

		tep_db_query("CREATE TEMPORARY TABLE cAllYears AS SELECT gl.*,'' as debtors_id,'' as creditors_id,generalledger_datecreated as tday FROM " . TABLE_GENERALLEDGER . " as gl WHERE trancode!='A00'");



		FindFinYear($dtm_from, $dtm_to, $startFinYear, $EndFinYear);

		for ($t = $startFinYear; $t <= $EndFinYear; $t++) {
			$yearfilexists = false;

			//$db_exists = tep_db_query("SELECT COUNT(*) FROM information_schema.tables WHERE table_name = 'yr".$t."'");

			$db_exists = tep_db_query("SHOW TABLES LIKE 'Yr" . $t . "'");

			if (tep_db_num_rows($db_exists) > 0) {
				$yearfilexists = true;
			}

			if ($yearfilexists == true) {
				$trancodeexists = false;

				if ($t == $startFinYear) {
					tep_db_query("DROP TABLE IF EXISTS cAllYears");
					$query = "INSERT INTO cAllYears  SELECT *,'' AS debtors_id, '' as creditors_id,generalledger_datecreated as tday FROM yr" . $t . " WHERE trancode='A00'";
				}

				if ($t != STARTFINYEAR) {
					tep_db_query("DROP TABLE IF EXISTS cAllYears");
					$query = "INSERT INTO cAllYears SELECT *,'' AS debtors_id, '' as creditors_id,generalledger_datecreated as tday FROM yr" . $t . " WHERE trancode='A00'";
				}

				tep_db_query($query);
			}
		}
	} else {

		tep_db_query("DROP TABLE IF EXISTS cAllYears1");
		tep_db_query("CREATE TEMPORARY TABLE cAllYears1 AS  SELECT * from cAllYears");
		tep_db_query("DROP TABLE IF EXISTS cAllYears");
		tep_db_query("CREATE TEMPORARY TABLE cAllYears AS SELECT cAllYears1.*, '' as creditors_id,generalledger_datecreated as tday from cAllYears1");
		tep_db_query("DROP TABLE IF EXISTS cAllYears1");
	}
}


function FindFinYear($dStart, $dEnd, $StartYear, $EndYear)
{

	$lContinue = true;

	$i = date("Y", strtotime(STARTFINYEAR));

	do {
		$dDate1  = date("m/d/Y", strtotime(STARTFINYEAR));
		//dDate1 = CTODFormat(STR(DAY(STARTFINYEAR),2)+"/"+STR(MONTH(STARTFINYEAR),2)+"/"+STR(i-1,4));

		$dDate2 = strtotime('-1 year', strtotime(STARTFINYEAR));

		//dDate2 = CTODFormat(STR(DAY(STARTFINYEAR),2)+"/"+STR(MONTH(STARTFINYEAR),2)+"/"+STR(i,4))-1;

		$i = $i - 1;

		if ($i <= 1900) {

			$StartYear = date("Y", strtotime(STARTFINYEAR));;

			$lContinue = false;
		}

		if ($dStart >= $dDate1 && $dStart <= $dDate1) {
			$StartYear = $i;
			$lContinue = false;
		}
	} while ($lContinue == true);


	$lContinue = true;

	$i = date("Y", strtotime(STARTFINYEAR));
	+1;

	do {
		$dDate1  = date("m/d/Y", strtotime(STARTFINYEAR));
		$dDate2 = strtotime('-1 year', strtotime(STARTFINYEAR));

		//dDate1 = CTODFormat(STR(DAY(STARTFINYEAR),2)+"/"+STR(MONTH(STARTFINYEAR),2)+"/"+STR(i-1,4))
		//dDate2 = CTODFormat(STR(DAY(STARTFINYEAR),2)+"/"+STR(MONTH(STARTFINYEAR),2)+"/"+STR(i,4))-1

		$i = $i - 1;
		if ($dEnd >= $dDate1 && $dStart <= $dDate1) {
			$EndYear = i;
			$lContinue = false;
		}

		if ($i <= 1900) {
			$EndYear  = date("Y", strtotime(STARTFINYEAR));;
			$lContinue = false;
		}
	} while ($lContinue == true);
}

function generategroupcode($currentlevel, $headerlevel, $paccountcode)
{

	$charlength = "";

	switch ($headerlevel) {
		case '1':
			$charlength = "0,3";
			$char = "18";
			$nchar = "3";
			break;
		case '2':
			$charlength = "4,3";
			$char = "3";
			$nchar = "6";
			break;
		case '3':
			$charlength = "7,3";
			$char = "6";
			$nchar = "9";
			break;
		case '4':
			$charlength = "10,3";
			$char = "9";
			$nchar = "12";
			break;
		case '5':
			$charlength = "13,3";
			$nchar = "15";
			break;

		case '6':
			$charlength = "16,3";
			$char = "12";
			$nchar = "18";
			break;

		default:
			//$messageStack->add("Sorry, sub Accounts allowed to maximum level 5.", 'error');
			return "";
			break;
	}
	if ($paccountcode != ''):
		$query = "SELECT MAX(SUBSTRING(chartofaccounts_groupcode," . $charlength . ")) maxcode FROM " . TABLE_CHARTOFACCOUNTS . " WHERE chartofaccounts_parent = '" . $paccountcode . "'";

	else:
		$query = "SELECT MAX(SUBSTRING(chartofaccounts_groupcode," . $charlength . ")) maxcode FROM " . TABLE_CHARTOFACCOUNTS . " WHERE LEFT(chartofaccounts_groupcode," . $nchar . ") = LEFT('" . $currentlevel . "'," . $nchar . ") AND chartofaccounts_level='" . $headerlevel . "'";

	endif;


	$maxgroup_query = tep_db_query($query);

	$maxcode = tep_db_fetch_array($maxgroup_query);

	$maxgrpcode = (int)$maxcode['maxcode'];

	if ($maxgrpcode == "") {
		$maxgrpcode  =	0;
	}
	$cseed = (string)($maxgrpcode + 1);
	$newgcode = str_repeat('0', 3 - strlen($cseed)) . $cseed;

	switch ($headerlevel) {
		case '1':
			$newgcode = substr($currentlevel, 0, $char) . $newgcode . str_repeat('0', 18);
		case '2':
			$newgcode = substr($currentlevel, 0, $char) . $newgcode . str_repeat('0', 15);
			break;
		case '3':
			$newgcode = substr($currentlevel, 0, $char) . $newgcode . str_repeat('0', 12);
			break;
		case '4':
			$newgcode = substr($currentlevel, 0, $char) . $newgcode . str_repeat('0', 9);
			break;
		case '5':
			$newgcode = substr($currentlevel, 0, $char) . $newgcode . str_repeat('0', 6);
			break;
		case '6':
			$newgcode = substr($currentlevel, 0, $char) . $newgcode . str_repeat('0', 3);
			break;
		case '7':
			$newgcode = $currentlevel;
			break;
	}

	return $newgcode;
}

function getArrears($RDay, $amount, $classes_id = '', $requirements_id = '')
{


	if ($classes_id != "") {
		$classes = "classes_id =" . $classes_id;
	} else {
		$classes = "classes_id LIKE '%%'";
	}

	if ($requirements_id != "") {
		$requirements = " requirements_id =" . $requirements_id;
	} else {
		$requirements = " requirements_id LIKE '%%'";
	}

	tep_db_query("DROP TABLE IF EXISTS cWoff");

	tep_db_query("CREATE TEMPORARY TABLE cWoff AS SELECT requirements_id,students_sregno,SUM(writtenoff_amount) AS writtenoff_amount  FROM " . TABLE_WRITTENOFF . " WHERE CAST(writtenoff_datecreated AS date) <=" . changeDateFromPageToMySQLFormat($RDay) . "  GROUP BY students_sregno,requirements_id");

	// get dues
	tep_db_query("DROP TABLE IF EXISTS cDues");
	tep_db_query("CREATE TEMPORARY TABLE cDues AS SELECT sd.students_sregno ,sd.requirements_id,SUM(studentsdues_amount) AS studentsdues_amount from " . TABLE_STUDENTSDUES . " as sd WHERE students_sregno IN (SELECT students_sregno FROM " . TABLE_STUDENTCLASSES . " WHERE " . $classes . ") AND CAST(sd.studentsdues_datecreated AS date) <=" . changeDateFromPageToMySQLFormat($RDay) . " AND requirements_id IN (select requirements_id FROM " . TABLE_REQUIREMENTS . " WHERE " . $requirements . ") GROUP BY sd.students_sregno,sd.requirements_id");

	/*echo "SELECT sd.students_sregno ,sd.requirements_id,SUM(studentsdues_amount) AS studentsdues_amount from " .TABLE_STUDENTSDUES." as sd WHERE students_sregno IN (SELECT students_sregno FROM ".TABLE_STUDENTCLASSES." WHERE ".$classes.") AND sd.studentsdues_datecreated <=".changeDateFromPageToMySQLFormat($_POST['txtRDay'])." AND requirements_id IN (select requirements_id FROM ".TABLE_REQUIREMENTS." WHERE ".$requirements.")".$whereSQL." GROUP BY sd.students_sregno,sd.requirements_id";

	$requirements_types_query = tep_db_query('select * from cDues');

	 while ($requirements = tep_db_fetch_array($requirements_types_query)) {
		echo $requirements['requirements_id'].'-'.$requirements['studentsdues_amount'].'<BR>';
	}
	exit;*/
	// remove written off from dues
	tep_db_query("DROP TABLE IF EXISTS cDues2");
	tep_db_query("CREATE TEMPORARY TABLE cDues2 AS SELECT d.students_sregno as students_sregno ,d.requirements_id,(studentsdues_amount - IF(ISNULL(writtenoff_amount),0,writtenoff_amount)) AS studentsdues_amount from cDues as d  LEFT OUTER JOIN cWoff as w ON w.students_sregno=d.students_sregno and w.requirements_id=d.requirements_id");

	// get payments
	tep_db_query("DROP TABLE IF EXISTS cPaid");
	tep_db_query("CREATE TEMPORARY TABLE cPaid AS SELECT sp.students_sregno ,sp.requirements_id,SUM(studentspayments_amount) AS studentspayments_amount FROM " . TABLE_STUDENTSPAYMENTS . " as sp WHERE students_sregno IN (SELECT students_sregno FROM " . TABLE_STUDENTCLASSES . " WHERE " . $classes . ") and requirements_id IN (select requirements_id FROM " . TABLE_REQUIREMENTS . " WHERE " . $requirements . ") AND CAST(sp.studentspayments_datecreated AS date)<=" . changeDateFromPageToMySQLFormat($RDay) . " GROUP BY sp.students_sregno,sp.requirements_id");

	// get arrears
	tep_db_query("DROP TABLE IF EXISTS cArr1");
	tep_db_query("CREATE TEMPORARY TABLE cArr1 AS SELECT cDues2.students_sregno as students_sregno,cDues2.requirements_id,ROUND(studentsdues_amount-IF(ISNULL(studentspayments_amount),0,studentspayments_amount)," . SETTTING_ROUND_TO . ") as studentsdues_amount,0 as ndays FROM cDues2 LEFT OUTER JOIN cPaid ON cDues2.students_sregno=cPaid.students_sregno AND cDues2.requirements_id=cPaid.requirements_id ");
}


function generateProvisions($RDay, $from, $to, $per)
{

	$query_result = tep_db_query("SELECT students_sregno,requirements_id FROM cArrPro2");

	while ($Arrears = tep_db_fetch_array($query_result)) {


		$ndays = getDaysInArrears($RDay, $Arrears['students_sregno'], $Arrears['requirements_id']);


		// update cursor
		tep_db_query("UPDATE  cArrPro2 SET ndays =" . $ndays . " WHERE requirements_id='" . $Arrears['requirements_id'] . "' AND students_sregno='" . $Arrears['students_sregno'] . "'");
	}


	tep_db_query("DROP TABLE IF EXISTS cArrPro");

	tep_db_query("CREATE TEMPORARY TABLE cArrPro AS SELECT cArrPro2.*,0000000000000000000000000." . str_repeat('0', SETTTING_ROUND_TO) . " as provision FROM cArrPro2");

	$query_result = tep_db_query("SELECT students_sregno, requirements_id, studentsdues_amount, provision, ndays FROM cArrPro");

	while ($provision = tep_db_fetch_array($query_result)) {

		// calculate provision
		switch (true) {

			case ($provision['ndays'] <= $to[0]):
				$nProvision = roundoffFigure($per[0] / 100 * $provision['studentsdues_amount']);
				break;

			case ($provision['ndays'] <= $to[1]):
				$nProvision = roundoffFigure($per[1] / 100 * $provision['studentsdues_amount']);
				break;

			case ($provision['ndays'] <= $to[2]):
				$nProvision = roundoffFigure($per[2] / 100 * $provision['studentsdues_amount']);
				break;

			case ($provision['ndays'] <= $to[3]):
				$nProvision = roundoffFigure($per[3] / 100 * $provision['studentsdues_amount']);
				break;

			case ($provision['ndays'] > $to[4]):
				$nProvision = roundoffFigure($per[4] / 100 * $provision['studentsdues_amount']);
				break;

			default:

				break;
		}
		//echo $nProvision;

		if ($nProvision == "") {
			$nProvision = $provision['studentsdues_amount'];
		}

		// update cursor
		tep_db_query("UPDATE cArrPro SET provision ='" . $nProvision . "' WHERE requirements_id='" . $provision['requirements_id'] . "' AND students_sregno='" . $provision['students_sregno'] . "'");
	}
}

function roundoffFigure($nNo)
{

	return round($nNo, SETTTING_ROUND_TO);
}


function getDaysInArrears($RDay, $students_sregno, $requirements_id)
{

	// get the total paymentment so far
	$query_result = tep_db_query("SELECT SUM(studentspayments_amount) AS totpaid FROM " . TABLE_STUDENTSPAYMENTS . " WHERE students_sregno='" . $students_sregno . "' AND requirements_id='" . $requirements_id . "' AND studentspayments_datecreated <" . changeDateFromPageToMySQLFormat($RDay));

	$totalpaid = tep_db_fetch_array($query_result);

	// get the dues
	$query_result = tep_db_query("SELECT students_sregno,studentsdues_amount,studentsdues_datecreated FROM " . TABLE_STUDENTSDUES . " WHERE students_sregno='" . $students_sregno . "' AND requirements_id='" . $requirements_id . "' AND studentsdues_datecreated <" . changeDateFromPageToMySQLFormat($RDay));
	//echo "SELECT students_sregno,studentsdues_amount,studentsdues_datecreated FROM ". TABLE_STUDENTSDUES." WHERE students_sregno='".$students_sregno."' AND requirements_id='".$requirements_id."' AND studentsdues_datecreated <".changeDateFromPageToMySQLFormat($RDay);
	while ($thedues = tep_db_fetch_array($query_result)) {

		if ($thedues['studentsdues_amount'] < $totalpaid['totpaid']) {
			$totalpaid['totpaid'] = $totalpaid['totpaid'] - $thedues['studentsdues_amount'];
		} else {


			$nArrdays = dateDifference(changeMySQLDateToPageFormat($thedues['studentsdues_datecreated']), $RDay);
			break;
		}
	}
	return $nArrdays;
}

/*// this function is ued to calculate provision
function getCalculateProvisions(ndays1,ndays2,ndays3,ndays4,ndays4){

	tep_db_query("CREATE TEMPORARY TABLE cArrPro2 AS SELECT cArrPro.*,0 AS Provision FROM cArrPro");

	$query_result = tep_db_query("SELECT studentsdues_amount,ndays FROM cArrPro2");

	while($thedues = tep_db_fetch_array($query_result)){




	}



}*/

function getWorkingPayablePeriod($start_date, $end_date, $holidays, $returnIn)
{

	//$thedays = array();
	if ($end_date < $start_date) {
		$nPeriod = array();
	} else {
		$nPeriod  = dateDifference($start_date, $end_date, $returnIn, $holidays);
	}


	// # uncomment this form actual days
	//return $thedays;

	return $nPeriod;
}

function get_working_days($start_date, $end_date, $holidays, $returnIn)
{
	$workdays = array();
	$workdays['frequency'] = "0";
	$workdays['holidays'] = 0;
	$nPeriod = 0;


	$curdate = strtotime($start_date);
	$bnotworkday = true;
	$bworkday = true;
	$nPeriod = 0;
	$nDays = 0;

	while (date("Y-m-d", $curdate) != date("Y-m-d", strtotime($end_date))) {

		$day_index = date("w", $curdate);

		if (SETTING_EXCLUDE_WEEKENDS == "checked") {
			#check see if day is a weekend
			if ($day_index == 0 || $day_index == 6) {
				//	echo date("Y-m-d", $curdate)."<BR>";
				//	echo $day_index ."<br>";
				$nPeriod = $nPeriod + 1;
				$bworkday = false;
			}
		}

		if (SETTING_EXCLUDE_HOLIDAYS == "checked") {
			# check see if this is a public holiday		
			if (in_array(date('Y-m-d', $curdate), $holidays)) {
				$nPeriod = $nPeriod + 1;
				$bworkday = false;
			}
		}

		if ($bworkday == true) {

			// this adds the end date of each pay-period
			switch ($returnIn) {

				case 'DAYS':
					break;

				case 'WEEKS':
					if (SETTING_DAYS_WEEK == $nDays) {
						$workdays[] = $curdate;
						$nDays = -1;
					}
					break;

				case 'MONTHS':
					// check see if its full payperiod and add it to array
					if (SETTING_DAYS_MONTH == $nDays) {
						$workdays[] = $curdate;
						$nDays = -1;
					}
					//print_r($curdate."------");
					break;

				case 'YEARS':

					if (SETTING_DAYS_YEAR == $nDays) {
						$workdays[] = $curdate;
						$nDays = -1;
					}
					break;

				default;
					break;
			}
		} else {
			$workdays['holidays'] = $workdays['holidays'] + 1;
		}

		$curdate = strtotime(date("Y-m-d", $curdate) . "+1 day");

		$bworkday = true;

		$nDays++;
	}

	return $workdays;
}

function dateDifference($start, $end, $returnIn, $holidays)
{


	$workdays = get_working_days($start, $end, $holidays, $returnIn);

	$nDaysToReduce = $workdays['holidays'];

	$sdate = strtotime($start);

	$edate = strtotime($end);

	$time = $edate - $sdate;

	switch ($returnIn) {

		case 'SECONDS':
			$timefinal = $time;
			break;

		case 'MINUTES':
			$pmin = ($edate - $sdate) / 60;
			$timefinal =  explode('.', $pmin);
			break;

		case 'HOURS':
			$phour = ($edate - $sdate) / 3600;
			$timefinal = explode('.', $phour);
			break;

		case 'DAYS':
			$time = 	$time - ($nDaysToReduce * 86400);
			$pday = $time  / 86400;
			$timefinal = explode('.', $pday);

			break;

		case 'WEEKS':
			$time = 	$time - ($nDaysToReduce * 86400);
			$pweek = $time  / (86400 * SETTING_DAYS_WEEK);
			$timefinal = explode('.', $pweek);
			break;

		case 'MONTHS':

			$time = $time - ($nDaysToReduce * 86400);
			$pmonth = $time / (86400 * SETTING_DAYS_MONTH);
			$timefinal = explode('.', $pmonth);
			break;

		case 'YEARS':
			$time = $time - ($nDaysToReduce * 86400);
			$pyears = $time  / (86400 * SETTING_DAYS_YEAR);
			$timefinal = explode('.', $pyears);

			break;

		default:
			$time = 	$time - ($nDaysToReduce * 86400);
			$pday = $time  / 86400;
			$timefinal = explode('.', $pday);
			break;
	}

	$workdays['frequency'] = $timefinal[0];


	return $workdays;
}

// this function used yo find the number of monthe between dates
function yearMonthDifference($start_date, $end_date)
{
	// 31556926 seconds in year
	// $years = floor(($end_date - $start_date) / 31556926);
	// takes remaning seconds to find months  2629743.83 seconds each month
	$months = round((($end_date - $start_date) % 31556926) / 2629743.83, 0);

	// if($years > 0){
	////     if($years > 1){$year_s = 's';} // adds "s" if more than one year
	//     $years_display = $years.' year'.$year_s;
	// }
	//if($months > 0){
	//  if($months > 1){$month_s = 's';} // adds "s" if more than one month
	//   $months_display = $months.' month'.$month_s;
	// }

	//return trim($years_display.' '.$months_display);

	return  $months;
}

function stripRelaceLinefeedswithBreaks($string)
{
	return strtr($string, array("\r\n" => ' ', "\r" => '<br />', "\n" => ' '));
}

//comma delimited lists
//comma delimited lists
function getlables($tlansationsids, $From = "", $To = "")
{

	global $lablearray;


	if ($tlansationsids != "") {
		$translations_query = tep_db_query("select translations_id,translations_eng, translations_fr,translations_sp,translations_swa,translations_lug,translations_runya,translations_ja FROM " . TABLE_TRANSLATIONS . " WHERE translations_id IN (" . $tlansationsids . ")");
	}
	//echo "select translations_id,translations_eng, translations_fr,translations_sp,translations_swa,translations_lug,translations_runya,translations_ja FROM " . TABLE_TRANSLATIONS . " WHERE translations_id IN (".$tlansationsids.")";
	if ($From != "" && $To != "") {
		$translations_query = tep_db_query("select translations_id,translations_eng, translations_fr,translations_sp,translations_swa,translations_lug,translations_runya,translations_ja FROM " . TABLE_TRANSLATIONS . " WHERE translations_id BETWEEN " . $From . " AND " . $To);
	}

	while ($lable = tep_db_fetch_array($translations_query)) {

		switch ($_SESSION['P_LANG']) {

			case 'EN':
				$lablearray[$lable['translations_id']] = $lable['translations_eng'];
				break;

			case 'LUG':
				$lablearray[$lable['translations_id']] = $lable['translations_lug'];
				break;

			case 'FR':
				$lablearray[$lable['translations_id']] = $lable['translations_fr'];
				break;

			case 'SP':
				$lablearray[$lable['translations_id']] = $lable['translations_sp'];
				break;

			case 'JA':
				$lablearray[$lable['translations_id']] = $lable['translations_ja'];
				break;

			case 'RUNYA':
				$lablearray[$lable['translations_id']] = $lable['translations_runya'];
				break;

			default:
				$lablearray[$lable['translations_id']] = $lable['translations_eng'];
				break;
		}
	}

	return $lablearray;
}

// This function is used to find the number of days between two dates
function getNumberOFDays($start_date, $end_date)
{

	$sDate = strtotime($start_date);

	$lDate = strtotime($end_date);

	$datediff = strtotime('+1 day', $lDate) - $sDate;
	//echo floor($datediff/(60*60*24));
	//exit();
	return floor($datediff / (60 * 60 * 24));
}

function getBranchCodeList()
{

	global $branchcodelist;

	$branchcodelist = array();

	$branchcodelist_query = tep_db_query("select licence_organisationname,branchcode FROM licence ORDER BY branchcode");

	while ($array_values = tep_db_fetch_array($branchcodelist_query)) {
		$branchcodelist[$array_values['branchcode']] = $array_values['branchcode'] . ":" . $array_values['licence_organisationname'];
	}
}



function fnEncrypt($sValue, $sSecretKey)
{


	// INSTANTIATE ENCRYPTION OBJECTS FROM THE CLASS
	$sobj = new SSLCrypt;

	$s_encoded = $sobj->encrypt($sValue, $sSecretKey);



	//    if(strlen(trim($sSecretKey))<16){
	//		$sSecretKey = str_pad(trim($sSecretKey),16,"0");
	//	}
	//	
	//
	//	return rtrim(
	//        base64_encode(
	//            mcrypt_encrypt(
	//                MCRYPT_RIJNDAEL_256,
	//                $sSecretKey, $sValue, 
	//                MCRYPT_MODE_ECB, 
	//                mcrypt_create_iv(
	//                    mcrypt_get_iv_size(
	//                        MCRYPT_RIJNDAEL_256, 
	//                        MCRYPT_MODE_ECB
	//                    ), 
	//                    MCRYPT_RAND)
	//                )
	//            ), "\0"
	//        );

	return $s_encoded;
}

function fnDecrypt($sValue, $sSecretKey)
{
	// INSTANTIATE ENCRYPTION OBJECTS FROM THE CLASS
	$sobj = new SSLCrypt;



	//	 if(strlen(trim($sSecretKey))<16){
	//		$sSecretKey = str_pad($sSecretKey,16,"0");
	//	}
	//    return rtrim(
	//        mcrypt_decrypt(
	//            MCRYPT_RIJNDAEL_256, 
	//            $sSecretKey, 
	//            base64_decode($sValue), 
	//            MCRYPT_MODE_ECB,
	//            mcrypt_create_iv(
	//                mcrypt_get_iv_size(
	//                    MCRYPT_RIJNDAEL_256,
	//                    MCRYPT_MODE_ECB
	//                ), 
	//                MCRYPT_RAND
	//            )
	//        ), "\0"
	//    );

	$s_decoded = $sobj->decrypt($sValue, $sSecretKey);

	return $s_decoded;
}


function getMachineID()
{

	ob_start(); // Turn on output buffering
	system('ipconfig /all'); //Execute external program to display output
	$mycom = ob_get_contents(); // Capture the output into a variable
	ob_clean(); // Clean (erase) the output buffer

	$findme = "Physical";
	$pmac = strpos($mycom, $findme); // Find the position of Physical text
	return  substr($mycom, ($pmac + 36), 17); // Get Physical Address

}

function  getLicenceCounts()
{

	/*$count_query = tep_db_query("select students_sregno from " . TABLE_STUDENTS);
	
		if(tep_db_num_rows($count_query) > 5){
		
			tep_db_query("DELETE FROM " . TABLE_STUDENTS);
	
			echo "<span class='invalidLicense'><p><b>Demo database.</b></p>
				<p>A demo database can only support limited information.</p>
				<p>Please purchase Licence from Sync Technologies Tel:(256)773397960 Email.<a href='mailto:billing@thebursar.com'>billing@thebursar.com</a><a href='http://www.thebursar.com'>www.thebursar.com</a></p>
				</span>";
		}*/
}

// get last build number
function get_licenceInfo()
{

	$build_query = tep_db_query("select licence_build from " . TABLE_LICENCE);

	$build_values = tep_db_fetch_array($build_query);

	return $build_values['licence_build'];
}

function generateBranchCombo($branchcode = BRANCHCODE)
{

	$query_results = tep_db_query("SELECT branch_code, licence_organisationname FROM " . TABLE_LICENCE . " WHERE licence_build='" . $_SESSION['licence_build'] . "'");

	$combo = "<SELECT id='branchcode' name='branchcode'>" .
		"<option id='' value=''>All Branches</option>";
	while ($arrayresults = tep_db_fetch_array($query_results)) {
		if ($branchcode == $arrayresults['branch_code']) {
			$combo .= "<option id='" . $arrayresults['branch_code'] . "' value='" . $arrayresults['branch_code'] . "' selected>" . $arrayresults['branch_code'] . ':' . $arrayresults['licence_organisationname'] . "</option>";
		} else {
			$combo .= "<option id='" . $arrayresults['branch_code'] . "' value='" . $arrayresults['branch_code'] . "'>" . $arrayresults['branch_code'] . ':' . $arrayresults['licence_organisationname'] . "</option>";
		}
	}
	$combo .= "</SELECT>";

	return $combo;
}

function generateCurrencyCombo($cLabel)
{

	$combo = "<table cellpadding='0' cellspacing='0'><tr><td>" . $cLabel;

	$currencies_query = tep_db_query("SELECT currencies_id,currencies_name,currencies_code FROM " . TABLE_CURRENCIES . " ORDER BY currencies_name,currencies_code");

	$combo .= "<select name='currencies_id'id='currencies_id'>";
	$combo .= "<option id='' value=''></option>";

	while ($currencies_array = tep_db_fetch_array($currencies_query)) {
		$combo .= "<option id='" . $currencies_array['currencies_id'] . "' value='" . $currencies_array['currencies_id'] . "'>" . $currencies_array['currencies_name'] . " (" . $currencies_array['currencies_code'] . ")</option>";
	}

	$combo .= "</select></td>";
	$combo .= "<td align='left' id='flag'></td>";
	$combo .= "</tr>";
	$combo .= " </table>";

	return  $combo;
}

function getGLAccountcurrency($cAccount)
{

	$currencies_query = tep_db_query("SELECT currencies_code FROM " . TABLE_CHARTOFACCOUNTS . " WHERE chartofaccounts_accountcode='" . $cAccount . "'");

	$currencies_array = tep_db_fetch_array($currencies_query);

	return  $currencies_array['currencies_code'];
}



function json_decodeData($cString, $bassoc = false)
{

	$jason =  preg_replace("{\\\}", "", $cString);

	return json_decode($jason, $bassoc);
}

function color_inverse($color)
{
	/*$color = str_replace('#', '', $color);
		if (strlen($color) != 6){ return '000000'; }
		$rgb = '';
		for ($x=0;$x<3;$x++){
			$c = 255 - hexdec(substr($color,(2*$x),2));
			$c = ($c < 0) ? 0 : dechex($c);
			$rgb .= (strlen($c) < 2) ? '0'.$c : $c;
		}*/

	if ($color == "0" || $color == "") {
		return 'black';
	} else {
		return (hexdec($color) > 0xffffff / 3) ? 'black' : 'white';
	}

	//return '#'.$rgb;
}

// this functio is used to set 
function number_format_locale_display($number, $decimals = 2)
{
	$locale = (isset($_COOKIE['locale']) ?
		$_COOKIE['locale'] :
		$_SERVER['HTTP_ACCEPT_LANGUAGE']
	);
	switch ($locale) {
		case 'en-us':
		case 'en-ca':
			$decimal = '.';
			$thousands = ',';
			break;
		case 'fr':
		case 'ca':
		case 'de':
		case 'en-gb':
			$decimal = ',';
			$thousands = ' ';
			break;
		case 'es':
		case 'es-mx':
		default:
			$decimal = '.';
			$thousands = ',';
	}
	return number_format($number, $decimals, $decimal, $thousands);
}


// Function to get the client IP address
function get_client_ip()
{
	$ipaddress = '';
	if ($_SERVER['HTTP_CLIENT_IP'])
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	else if ($_SERVER['HTTP_X_FORWARDED_FOR'])
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else if ($_SERVER['HTTP_X_FORWARDED'])
		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	else if ($_SERVER['HTTP_FORWARDED_FOR'])
		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	else if ($_SERVER['HTTP_FORWARDED'])
		$ipaddress = $_SERVER['HTTP_FORWARDED'];
	else if ($_SERVER['REMOTE_ADDR'])
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	else
		$ipaddress = 'UNKNOWN';
	return $ipaddress;
}







// function to get funds
function getFunds()
{


	$fund_query = tep_db_query("SELECT fund_code,fund_name FROM " . TABLE_FUND);

	while ($fund_array = tep_db_fetch_array($fund_query)) {

		$funds[$fund_array['fund_code']] = $fund_array['fund_name'];
	}

	return $funds;
}


// function to donors
function getDonors()
{


	$fund_query = tep_db_query("SELECT donor_code,donor_name FROM " . TABLE_DONOR);

	while ($fund_array = tep_db_fetch_array($fund_query)) {

		$donors[$fund_array['donor_code']] = $fund_array['donor_name'];
	}

	return $donors;
}

// function to get funds
function getLoanCategory()
{


	$loancat_query = tep_db_query("SELECT loancategory_code,loancategory_name FROM " . TABLE_LOANCATEGORY);

	while ($loancat_array = tep_db_fetch_array($loancat_query)) {

		$loancat[$loancat_array['loancategory_code']] = $loancat_array['loancategory_name'];
	}

	return $loancat;
}

// function to get funds
function getProducts()
{


	$product_query = tep_db_query("SELECT product_name,product_prodid FROM " . TABLE_PRODUCT);

	while ($product_array = tep_db_fetch_array($product_query)) {

		$product[$product_array['product_prodid']] = $product_array['product_name'];
	}

	return $product;
}

function informationUpdate($status, $msg, $callback = '') {}

function printOptions($data = '', $param = '')
{

	$printctr = '<div class="print-options-container"><div><input type="radio" name="cfimb_5" id="id_cfimb_5_4" value="PDF">
            PDF<input type="radio" name="cfimb_5" id="id_cfimb_5_5" value="HTML" checked="checked">
            HTML<input type="radio" name="cfimb_5" id="id_cfimb_5_6" value="EXCEL">EXCEL</div>
            
         ';
	if ($param != '') {

		$printctr .= '<button class="btn" name="Go"  type="button"   id="btnviewreport" onclick="openPopupListWindow(\'reports/reports.php?rtype=\'+$(\'input[name=cfimb_5]:checked\').val()+\'' . $param . ')">View & Print</button>';
	} else {
		$printctr .= '<button class="btn" name="Go"  type="button"   id="btnviewreport" onclick="openPopupListWindow(\'reports/reports.php?rtype=\'+$(\'input[name=cfimb_5]:checked\').val())">View & Print</button></div>';
	}

	echo $printctr;
}
