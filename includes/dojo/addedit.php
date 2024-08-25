<?php	
require('includes/application_top.php');
require('includes/functions/password_funcs.php');
include("includes/FusionCharts.php");

  if ($error == true) {
    $messageStack->add('login', TEXT_LOGIN_ERROR);
  }

	//$strXML will be used to store the entire XML document generated
   //Generate the graph element
   $strXML = "<graph caption='Students' decimalPrecision='0' showNames='1' pieSliceDepth='8' formatNumberScale='0' chartTopMargin='0' chartBottomMargin='0' chartRightMargin='0' chartLeftMargin='0' bgAlpha='0'>";

   //Fetch all  records
   $strQuery = tep_db_query("SELECT sum(s.classes_id) AS thecount,classes_name as name FROM ".TABLE_STUDENTCLASSES." as s, ".TABLE_CLASSES." as c where c.classes_id=s.classes_id group by s.classes_id");
  //echo "SELECT sum(s.classes_id) AS thecount,classes_name as name FROM ".TABLE_STUDENTS." as s, ".TABLE_CLASSES." as c where c.classes_id=s.classes_id group by s.classes_id"; 
   //Iterate through each
   while($results_array = tep_db_fetch_array($strQuery)) {
 //  echo "here";
	 //Generate <set name='..' value='..'/>
	$strXML = $strXML."<set name='" . $results_array['name'] . "' value='" . $results_array['thecount'] . "'  color='".randColor()."' />";
  //	echo "<set name='" . $results_array['name'] . "' value='" . $results_array['thecount'] . "'  color ='".randColor()."' />";
  }
  // $strXML .="<set name='Jan' value='11' color='AFD8F8' />";
 // echo $strXML;
 // exit();
 
  // $strXML .="<set name='Feb' value='24' color='F6BD0F' />";
 //  $strXML .="<set name='Mar' value='671' color='8BBA00' />";
  
   //Finally, close <graph> element
   $strXML .= "</graph>";	
   
   
   getlables('110');


	$targets_query = tep_db_query("SELECT fca.classes_id,classes_name,SUM(feecategoriesamount_amount) AS target,schoolsessionfeecategories_datecreated FROM ". TABLE_SCHOOLSESSIONFEECATEGORIES." as ssfc,".TABLE_STUDENTFEECATEGORIES." AS sfc,".TABLE_FEECATEGORIESAMOUNT." AS fca, ".TABLE_CLASSES." AS c,".TABLE_REQUIREMENTS." AS r WHERE ssfc.feecategories_id=sfc.feecategories_id AND fca.feecategories_id=sfc.feecategories_id  AND ssfc.schoolsessionfeecategories_currentflag='Y' AND c.classes_id=fca.classes_id AND r.requirements_id=fca.requirements_id AND r.requirements_ismonetary='Y' GROUP BY fca.classes_id");
	
   
?>



<script language="javascript">

var url ='';
var iface = '';

url="addedit.php";

$(document).ready(function(){

 <?php 		
	$students_query = tep_db_query("select count(*) as cnt,c.classes_name from ".TABLE_STUDENTS." s,".TABLE_STUDENTCLASSES." sc,". TABLE_CLASSES." c where s.students_sregno=sc.students_sregno AND sc.classes_id=c.classes_id  group by c.classes_id");
	
	$num = tep_db_num_rows($students_query);
	
	$i =1;
	
	$mydata = "var data = [";	
		
	while($results_array = tep_db_fetch_array($students_query)) {
		$mydata = $mydata."['".$results_array['classes_name']."',".$results_array['cnt']."]";
		if($num!=$i){
			$mydata = $mydata.",";
		}			
		$i++;		
	}
	$mydata = $mydata."];";
	echo $mydata;	
?>

 // var data = [['Senior 1',1],['Senior 2',1],['Senior 5',2],['Primary 7',1]]; 
  var plot1 = jQuery.jqplot ('Piechart1', [data],
    {
      seriesDefaults: {
        // Make this a pie chart.
        renderer: jQuery.jqplot.PieRenderer,
		
        rendererOptions: {
          // Put data labels on the pie slices.
          // By default, labels show the percentage of the slice.
          showDataLabels: true
        }
      },
	  // grid settings
	 grid: {
		
		borderColor: '#000000',
		gridLineColor: '#000000',
		 borderWidth: 0,
		 shadowAngle: 0,
		 shadowWidth: 0,
		 shadow: false
	 },
      legend: { show:true, location: 'e', xoffset: 0,yoffset: 0 }
    }
  );
});


$(document).ready(function(){
   // var budgeted = [200];
   // var AmountSpent = [460];
   // var s3 = [-260, -440, 320, 200];
	
<?php 

//get all budgets for this year
tep_db_query("CREATE TEMPORARY TABLE cBudgets AS SELECT budgets_id,chartofaccounts_accountcode,budgetdefinition_name,budgets_item,budgets_amount FROM ".TABLE_BUDGETS." as b, ".TABLE_BUDGETDEFINITION." bd WHERE bd.budgetdefinition_id=b.budgetdefinition_id GROUP BY b.budgets_id");

//get all budgets for this year
tep_db_query("CREATE TEMPORARY TABLE cAmounts AS SELECT chartofaccounts_accountcode, SUM(generalledger_debit)-SUM(generalledger_credit) AS Amt FROM ".TABLE_GENERALLEDGER." WHERE chartofaccounts_accountcode IN (SELECT chartofaccounts_accountcode FROM cBudgets) GROUP BY chartofaccounts_accountcode");

tep_db_query("CREATE TEMPORARY TABLE cComparison AS SELECT budgets_item,budgetdefinition_name,IFNULL(budgets_amount,0) AS budgets_amount,IFNULL(Amt,0) AS Amt FROM cBudgets LEFT JOIN cAmounts ON cAmounts.chartofaccounts_accountcode=cBudgets.chartofaccounts_accountcode");

$budgets_query  = tep_db_query("SELECT * FROM cComparison");

$num = tep_db_num_rows($budgets_query);

$budgeted = "var s1 = [";

$AmountSpent = "var s2 = [";

$ticks = "var ticks = [";

$i =1;

$series_array = array();

while($budgets_array = tep_db_fetch_array($budgets_query)) {
	
	$budgeted = $budgeted.$budgets_array['budgets_amount'];
	
	$AmountSpent = $AmountSpent.$budgets_array['Amt'];
	
	$ticks = $ticks."'".$budgets_array['budgetdefinition_name']." ".$budgets_array['budgets_item']."'";
			
	if($num!=$i){
		$budgeted = $budgeted.",";
		$AmountSpent = $AmountSpent.",";
		$ticks = $ticks.",";
	}else{
		$budgeted = $budgeted."];";
		$AmountSpent = $AmountSpent."];";
		$ticks = $ticks."];";
	}				
				
	$i++;		
}

echo $budgeted;	

echo $AmountSpent;
?>
// Can specify a custom tick Array.
// Ticks should match up one for each y value (category) in the series.
//var ticks = ['May', 'June', 'July', 'August'];
     
<?php 
echo $ticks;

?>
 
    var plot1 = $.jqplot('Comp1', [s1,s2], {
        // The "seriesDefaults" option is an options object that will
        // be applied to all series in the chart.
        seriesDefaults:{
            renderer:$.jqplot.BarRenderer,
            rendererOptions: {
			fillToZero: true,
			barWidth:10}			
        },
		
		 // grid settings
		 grid: {
		
			borderColor: '#000000',
			gridLineColor: '#CCCCCC',
			 borderWidth: 0,
			 shadowAngle: 0,
			 shadowWidth: 0,
			 shadow: false
		 },
        // Custom labels for the series are specified with the "label"
        // option on the series option.  Here a series option object
        // is specified for each series.

        series:[
			{label:'Bugeted'},
           {label:'Spent'}           
        ],
        // Show the legend and put it outside the grid, but inside the
        // plot container, shrinking the grid to accomodate the legend.
        // A value of "outside" would not shrink the grid and allow
        // the legend to overflow the container.
        legend: {
            show: true,
            placement: 'outsideGrid'
        },
			
        axes: {
            // Use a category axis on the x axis and use our custom ticks.
            xaxis: {
                renderer: $.jqplot.CategoryAxisRenderer,
                ticks: ticks
            },
            // Pad the y axis just a little so bars can get close to, but
            // not touch, the grid boundaries.  1.2 is the default padding.
            yaxis: {
                pad: 0,
                tickOptions: {formatString: '<?php echo SETTTING_CURRENCY_CODE;?>% d'}
            }
        }
    });
});


//point line graph
$(document).ready(function(){
  var line1 = [14, 32, 41, 44, 40, 47, 53, 67];
  var line2 = [6, 7, 41, 60, 40, 56, 53, 99];
 
  var plot5 = $.jqplot('chart5', [line1,line2], {
      title:'Budgets',
      seriesDefaults: {
        showMarker:false,
        pointLabels: {
          show: true,
          edgeTolerance: 5
        }},
		 // grid settings
	
      axes:{
        xaxis:{min:3}
      }
  });
}); 



</script>	
<?php // require(DIR_WS_INCLUDES . 'initmenu.php'); 

getlables("171,257,258");
?>
 		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
		<td  valign="top">	  
	   	<div id="Piechart1" class="divsection"></div>
	   </td>
	   <td>
			<div class="divsection">
			<br>
			<?php 
			$students_query=tep_db_query("SELECT count(students_sregno) as thecount FROM " . TABLE_STUDENTS." WHERE YEAR(students_dateenrolled)=YEAR(NOW())");
			$students_array= tep_db_fetch_array($students_query);
			tep_db_free_result($students_query);
			?>
			<p align="center" style="font-size:12px;font-family:Arial, Helvetica;"><?php echo $lablearray['257'];?></p>
			<p align="center"><?php echo $students_array['thecount'];?> <?php echo $lablearray['171'];?></p>
			<?php 
			$students_query=tep_db_query("SELECT count(students_sregno) as thecount FROM " . TABLE_STUDENTS." WHERE YEAR(students_dateenrolled)=YEAR(NOW())-1");
			$students_array= tep_db_fetch_array($students_query);
			tep_db_free_result($students_query);
			?>
			<br>
			<p align="center" style="font-size:12px;font-family:Arial, Helvetica;"><?php echo $lablearray['258'];?></p>
			<p align="center"><?php echo $students_array['thecount'];?> <?php echo $lablearray['171'];?></p>
			</div>
		</td>
		<td>
			<div id="Comp1" class="divsection">
			
			</div>
		</td>
		<td>
		<?php 
		//$students_query =tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where time_last_click < '" . $xx_mins_ago . "'");;
		//$students_array= tep_db_fetch_array($students_query);
		//tep_db_free_result($students_query);
		?>
		<div id="chart5" class="divsection"></div>
		</td>
		</tr>
		</table>	
 
