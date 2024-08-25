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

<script>
	
// create the BorderContainer and attach it to our appLayout div
var appLayout = new BorderContainer({
    design: "headline"
}, "appLayout");
 
 
// create the TabContainer
var contentTabs = new TabContainer({
    region: "center",
    id: "contentTabs",
    tabPosition: "bottom",
    "class": "centerPanel",
    href: "contentCenter.html"
})
 
// add the TabContainer as a child of the BorderContainer
appLayout.addChild( contentTabs );
 
// create and add the BorderContainer edge regions
appLayout.addChild(
    new ContentPane({
        region: "top",
        "class": "edgePanel",
        content: "Header content (top)"
    })
)
appLayout.addChild(
    new ContentPane({
        region: "left",
        id: "leftCol", "class": "edgePanel",
        content: "Sidebar content (left)",
        splitter: true
    })
);
 
// Add initial content to the TabContainer
contentTabs.addChild(
    new ContentPane({
        href: "contentGroup1.html",
        title: "Group 1"
    })
)
 
// start up and do layout
appLayout.startup();
</script>
<META http-equiv=Content-Type content="text/html; charset=iso-8859-1">


<META content="MSHTML 6.00.2900.3268" name=GENERATOR>
<link href="styles/collapsiblepanel.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="includes/javascript/gridsearch.js" language="javascript"></script>

<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="excanvas.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="includes/javascript/jquery-1.6.2.min.js"></script>



<script language="javascript" type="text/javascript" src="includes/javascript/jquery.jqplot.min.js"></script>
<link rel="stylesheet" type="text/css" href="includes/javascript/jquery.jqplot.css" />
<script type="text/javascript" src="includes/javascript/PLUGINS/jqplot.barRenderer.min.js"></script>
<script type="text/javascript" src="includes/javascript/PLUGINS/jqplot.categoryAxisRenderer.min.js"></script>
<script type="text/javascript" src="includes/javascript/PLUGINS/jqplot.pointLabels.min.js"></script>


<script type="text/javascript" src="includes/javascript/PLUGINS/jqplot.dateAxisRenderer.min.js"></script>
<script type="text/javascript" src="includes/javascript/plugins/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="includes/javascript/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>




<script type="text/javascript" src="includes/javascript/PLUGINS/jqplot.pieRenderer.min.js"></script>
<script type="text/javascript" src="includes/javascript/PLUGINS/jqplot.donutRenderer.min.js"></script>
<style type="text/css">
#chart3 .jqplot-point-label {
  padding: 1px 3px;
  color:#FFFFFF;
  
}  
 .divsection {
  	height:250px;
	width:250px;
	float:left;
	margin:2px;
	font-size:13px;
	color:#000099;
	padding:2px;
  
  }
.divsection p {
	font-size:30px;
	color:#000099;
	margin:0px;
	padding:0px;	
	font-family:sans-serif, Arial, Helvetica;	
	
}
</style>

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
<?php require(DIR_WS_INCLUDES . 'initmenu.php'); 

getlables("171,257,258");
?>

  <TABLE cellSpacing=0 cellPadding=0 width="100%">
    <TBODY>
     
      <TR> 
        
      <TD> 
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
      </TD>
      </TR>
	  <tr>
	  	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		  <tr>
			<td id="memonline" class="divsection" valign="top"></td>
			<td>
			<div id="sidebar" class="home-top-right">
			<h4 style="background:#000000; color: #FFFFFF;padding:5px;">Student Enrollment</h4>
			<?php
			
			$students_query=tep_db_query("SELECT count(students_sregno) as thecount FROM " . TABLE_STUDENTS);
		  $students_array= tep_db_fetch_array($students_query); 
			
		  $students_boarders_query=tep_db_query("SELECT count(students_sregno) as thecount FROM " . TABLE_STUDENTS." WHERE students_isborder='Y'");
		  $students_boarders_array= tep_db_fetch_array($students_boarders_query);
		  
		  $students_male_query=tep_db_query("SELECT count(students_sregno) as thecount FROM " . TABLE_STUDENTS." WHERE students_gender='M'");
		  $students_male_array= tep_db_fetch_array($students_male_query);
		  
		  $students_female_query=tep_db_query("SELECT count(students_sregno) as thecount FROM " . TABLE_STUDENTS." WHERE students_gender='F'");
		  $students_female_array= tep_db_fetch_array($students_female_query);
		  
		   
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
			

			
			?>
			<table width="100%" border="0" cellspacing="2" cellpadding="2">
            <tr >
              <td >Students</td>
              <td align="right">&nbsp;<?php echo $students_array['thecount'];?></td>
            </tr>
            <tr>
              <td >Male Students</td>
              <td align="right">&nbsp;<?php echo $students_male_array['thecount'];?></td>
            </tr>
            <tr >
              <td >Female Students</td>
              <td align="right">&nbsp;<?php echo $students_female_array['thecount'];?></td>
            </tr>
            <tr>
              <td >Boarding Students</td>
              <td align="right">&nbsp;<?php echo $students_boarders_array['thecount'];?></td>
            </tr>
            <tr class="alternateRow">
              <td >Enrolled this Year</td>
              <td align="right">&nbsp;		  
			  <?php
			   
			  // tep_db_free_result($students_query);
			  // tep_db_free_result($students_boarders_query);
			  // tep_db_free_result($students_male_query);
			 //  tep_db_free_result($students_female_query);
			   
			   $enrollment_query = tep_db_query("SELECT COUNT(*) AS thecount FROM ".TABLE_STUDENTS." WHERE YEAR(students_dateenrolled) ='".date('Y')."'");
			   $enrollment_array = tep_db_fetch_array($enrollment_query);
			   echo (int)$enrollment_array['thecount'];
			   tep_db_free_result($enrollment_query);
			   ?></td>
            </tr>
             <tr>
              <td >Left School</td>
              <td align="right"><?php echo ($students_array['thecount']-$students_active_array['thecount']);?></td>
            </tr>
			<tr>
				<td>
				<?php
				echo renderChart("FusionCharts/FCF_Pie3D.swf", "", $strXML, "FactorySum", 200, 160);
				//name="WMode" value="Transparent"
				?>
				</td>
			</tr>
			
          </table>
			</div>
			</td>
			<td>
			<div id="sidebar" class="home-top-right">
			<h4 style="background:#000000; color: #FFFFFF;padding:5px;matgin:0px;">BDO Target</h4>
				
				
			<table cellpadding="0" cellspacing="5" border="0" width="100%">
			
			<tr>
				<th class="listingColumn">Class</th>
				<th class="bdoColumn">Collected</th>
				<th class="bdoLightsColumn"></th>
				<th class="teamColumn">Target</th>
				<th class="teamLightsColumn"></th>
			</tr>
			<?php 
			$class ="";
			$nTarget =0;
			 
			
			while($targets_array = tep_db_fetch_array($targets_query)) {
			// get all payment made since the start of this seesion 
			// do not include balances brought forward/opening balances
			$col_query = tep_db_query("SELECT SUM(studentspayments_amount) as amt FROM ".TABLE_STUDENTSPAYMENTS. " AS sp,".TABLE_STUDENTCLASSES." AS s WHERE sp.studentspayments_datecreated >= '".$targets_array['schoolsessionfeecategories_datecreated']."' AND sp.transactiontypes_code <> 'OB' AND transactiontypes_code<>'AD' AND s.classes_id='".$targets_array['classes_id']."' GROUP BY s.classes_id");
			$col_array = tep_db_fetch_array($col_query);
			$nCol  = $nCol + $col_array['amt'];
			$nTarget = $nTarget + $targets_array['target'];
			
			if($class==''){
				$class="alternateRow";
			}else{
				$class='';				
			}				
			?>
			 <tr class="<?php echo $class;?>">
				<td class="listingColumn"><?php echo $targets_array['classes_name'];?></td>
				<td class="bdoColumn" align="right"><?php echo formatNumber((int)$col_array['amt']);?></td>
				<td class="bdoLightsColumn"></td>
				<td class="teamColumn" align="right"><?php echo formatNumber((int)$targets_array['target']);?></td>
				<td class="teamLightsColumn"></td>
			</tr>				 
			 <?php }?>	

			<tr class="alternateRow">
				<td class="listingColumn" align="right">Totals</td>
				<td class="bdoColumn" align="right"><?php echo formatNumber($nCol);?></td>
				<td class="bdoLightsColumn"><img src="images/REDLIGHT.GIF" /></td>
				<td class="teamColumn" align="right"><?php echo formatNumber($nTarget);?></td>
				<td class="teamLightsColumn"><img src="images/REDLIGHT.GIF" /></td>
			</tr>
			
			</table>
		
			</div>
			</td>
			
			<td>
			<div id="sidebar" class="home-top-right">
			<h4 style="background:#000000; color: #FFFFFF;padding:5px;">Recent Activity</h4>
		<div style='overflow:auto;height:300px'>
		<div class="widget-wrap">
		
			<div id="vert-related"><div class="odd"><div class="number">1.</div><a href="http://yro.slashdot.org/story/12/08/29/0329219/dont-build-a-database-of-ruin">Don't Build a Database of Ruin</a><div class="clear"></div></div><div class="even"><div class="number">2.</div><a href="http://politics.slashdot.org/story/12/08/28/1248219/can-data-mining-win-a-presidential-campaign">Can Data Mining Win a Presidential Campaign?</a><div class="clear"></div></div><div class="odd"><div class="number">3.</div><a href="http://tech.slashdot.org/story/12/08/17/2312203/gartner-buzzword-tracker-says-cloud-computing-still-on-hype-wave">Gartner Buzzword Tracker Says "Cloud Computing" Still on Hype Wave</a><div class="clear"></div></div><div class="even"><div class="number">4.</div><a href="http://tech.slashdot.org/story/12/08/16/2343249/dremel-based-project-accepted-as-apache-incubator">Dremel-Based Project Accepted As Apache Incubator</a><div class="clear"></div></div><div class="odd"><div class="number">5.</div><a href="http://science.slashdot.org/story/12/08/12/2356231/how-big-data-became-so-big">How Big Data Became So Big</a><div class="clear"></div></div><div class="even"><div class="number">6.</div><a href="http://games.slashdot.org/story/12/07/27/2310252/predicting-color-blindness-add-or-learning-disorders-from-game-data">Predicting Color Blindness, ADD, or Learning Disorders From Game Data</a><div class="clear"></div></div><div class="odd"><div class="number">7.</div><a href="http://yro.slashdot.org/story/12/07/20/1241223/facebook-and-wal-mart-join-forces">Facebook and Wal-Mart Join Forces</a><div class="clear"></div></div><div class="even"><div class="number">8.</div><a href="http://apple.slashdot.org/story/12/07/19/1834249/apple-expanding-nc-green-data-center">Apple Expanding NC Green Data Center</a><div class="clear"></div></div><div class="odd"><div class="number">9.</div><a href="http://yro.slashdot.org/story/12/06/30/1111201/when-your-e-books-read-you">When Your e-Books Read You</a><div class="clear"></div></div><div class="even"><div class="number">10.</div><a href="http://developers.slashdot.org/story/12/06/12/220232/ios-tops-android-for-number-of-new-app-projects-from-developers">iOS Tops Android For Number of New App Projects From Developers</a><div class="clear"></div></div></div>
			</div>
			</div>
			
			</td>
		  </tr>
		</table>

	  </tr>
    </TBODY>
  </TABLE>
 
  <?php require(DIR_WS_INCLUDES . 'userfooter.php'); ?>
 <script>
 	var t=setTimeout("showResult('frmid=frmdashboard','memonline')",5000);
 </script>

