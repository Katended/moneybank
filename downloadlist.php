<?php
require_once('includes/application_top.php');
require_once('includes/functions/password_funcs.php');
//require_once('includes/classes/class.listmanager.php');

switch($_GET['list']){

case 'FEECATEGORIES':
    $query = "select fc.feecategories_id,feecategories_name,DATE_FORMAT(feecategories_dateeffective,'%d/%m/%Y') AS feecategories_dateeffective,schoolsession_name,schoolsessionfeecategories_currentflag FROM " .TABLE_FEE_CATEGORIES." AS fc LEFT JOIN ".TABLE_SCHOOLSESSIONFEECATEGORIES." AS ssfc ON ssfc.feecategories_id=fc.feecategories_id LEFT JOIN ".TABLE_SCHOOLSESSIONS." AS ss on ss.schoolsession_id=ssfc.schoolsession_id GROUP BY fc.feecategories_id DESC";
    $_SESSION['downloadlist'] = $query;
    break;

case 'CLASSES':
    $query = "SELECT * FROM ".TABLE_CLASSES;
    $_SESSION['downloadlist'] = $query;
    break;

case 'CLASSESSUB':
    $query = "SELECT * FROM ".TABLE_CLASSCATEGORIES;
    $_SESSION['downloadlist'] = $query;
    break;

case 'RECON': // Reconciliation report

    $bankaccounts_accno ="'".$_GET['accFrom']."','".$_GET['accTo']."'";

    $query = "SELECT DISTINCT rh.tcode,bankaccounts_accno,IF(rh.tcode='00000000000','Opening Balance',generalledger_description) as generalledger_description,debit,credit,bankstatement_datecreated,generalledger_description FROM ".TABLE_RECONCILIATIONHISTORY." as rh LEFT JOIN ".TABLE_GENERALLEDGER." gl ON rh.tcode=gl.tcode WHERE  bankstatement_datecreated>=".changeDateFromPageToMySQLFormat($_GET['txtFrom'])." AND bankstatement_datecreated<=".changeDateFromPageToMySQLFormat($_GET['txtTo'])."  AND bankaccounts_accno IN (SELECT bankaccounts_accno FROM ".TABLE_BANKACCOUNTS." WHERE chartofaccounts_accountcode IN(".$bankaccounts_accno.")) ORDER BY rh.tcode,bankstatement_datecreated";

    $_SESSION['downloadlist'] = $query;

    break;

default:
    break;
	
	
}


$filename = $_GET['filename'].".xls";
// required for IE, otherwise Content-disposition is ignored
if(ini_get('zlib.output_compression'))
	ini_set('zlib.output_compression', 'Off');

	# This line will stream the file to the user rather than spray it across the screen
	header("Content-type: application/vnd.ms-excel");

	# replace excelfile.xls with whatever you want the filename to default to
	header("Content-Disposition: attachment;filename=".$filename);
	header("Expires: 0");
	header("Cache-Control: private");
	session_cache_limiter("public");

	//include_once '../app/commonfunctions.php';
	session_start(); 
	# printing details
	# priting the lists; The default print option is current page. We select a printing query based on the
	# option chosen by the user
	$COLUMN_STARTING_VARIABLE =0;
	# the coluumns that have numbers, these have to be formatted differently from the rest of the
	# columns
	$number_column_array = getArrayFromCommaDelimitedList($_GET['numbercolumnlist']);
	$printquery = $_SESSION['downloadlist'];
			
?>

<table cellpadding="" cellspacing="2" border="0">
  <tr>
    <td style="font-size:18px; font-weight:bold;"><?php echo str_replace("_"," ", $_GET['filename']); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="4" width="100%" style="background-color: #F7F3EF;">
  <?php //
		$counter = 0;
				
		$result = tep_db_query($printquery);
		$columnCount =  mysql_num_fields($result);
		$i = 0;
		$arraylist = array();
                
		# put all results in an array, and pop the first two
		while ($i < $columnCount) {
			$meta = mysql_fetch_field($result);
			array_push($arraylist, $meta->name);
			$i++;
		}
	
		print "<tr style=\"background-color: #003366;color: #FFFFFF;	font-weight: bold;	text-decoration: none;	position: relative;	height: 20px;\">";
		for ($cols = $COLUMN_STARTING_VARIABLE; $cols < count($arraylist); $cols++) {
			print "<td nowrap align=\"left\">".$arraylist[$cols]."</td>";
		}
		print "</tr>";
		# check if there are any rows returned
		if (mysql_num_rows($result) == 0) {
			print "<tr><td height=\"20\" colspan=\"".$columnCount."\">There are no records to display.</td></tr>";
		}else{
			# print the rows
			while ($line = mysql_fetch_array($result)) {
				
				# open the row
				print "<tr>";
				for ($row = $COLUMN_STARTING_VARIABLE; $row < count($line); $row++){																						
					# Process the row, ignore columns before the column starting variable
					# check if the column is in the number list
					# Note the user of === since the search function may return a key or false
					if (array_search($row, $number_column_array) === false) {
						print "<td nowrap align=\"left\">".$line[$row]."</td>";
					} else {
						print "<td nowrap align=\"right\" style=\"vnd.ms-excel.numberformat:0.00;\">".$line[$row]."</td>";
					}
				 }
				// close the row
				print "</tr>";
			} // end of while
		}
?>
</table>
</td>
  </tr>
</table>