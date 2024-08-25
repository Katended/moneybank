<?php
  require('../includes/application_top.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML <?php echo HTML_PARAMS; ?>>
<script language="JavaScript" type="text/JavaScript" src="../payments/includes/javascript/commonfunctions.js"></script>
<HEAD>
<TITLE>Glory International School- Academic Reports</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<LINK href="../payments/stylesheet.css" type=text/css rel=stylesheet>
<META content="MSHTML 6.00.2900.3268" name=GENERATOR>
</head>
<body>
<table width="100%" border="0" cellspacing="2" cellpadding="3" style="border:solid 1;border-color:#000000;margin:5px;" bgcolor="#999999">
  <tr>
    <td align="center" colspan="4"><font size="3"><b>ST FRANCIS PRIMARY AND SECONDARY SCHOOLS</b></font><br><hr color="#000000"></td>
    
  </tr>
   <tr>
    <td align="center" colspan="4" styele="padding-top:0;margin:0"></td>    
  </tr>
  <tr height="5">
    <td colspan="4" align="center"><font size="-1">P.O.Box 509, Kampala. Tel. 0772 407027</font></td>
    
  </tr>
  <tr height="5">
    <td colspan="4"></td>    
  </tr>
   <tr height="5">
    <td colspan="4" align="center" ><font size="-1"><b>-------- OFFICIAL RECEIPT --------</b></font></td>
    
  </tr>
  <tr height="5">
    <td colspan="4"></td>    
  </tr>
  <tr>
    <td width="100"><font size="-1"><b><?php echo $_GET['fn']." ".$_GET['ln'];?></b>(<?php echo $_GET['class'];?>)</font></td>
    <td colspan="2">&nbsp;</td>
    
    <td width="150"><font size="-1">Reg No.<?php echo $_GET['regno'];?><br><br>Receipt No.<font color="#FF0000"><?php echo $_GET['r'];?></font><br><br>Date&nbsp;Received.<?php echo date('d/m/Y')?></font></td>
  </tr>
   <tr>
    <td width="60%"><font size="-1"><u><b>Receipt&nbsp;Details</b></u></font></td>
    <td colspan="2" width="150"><font size="-1"><u><b>Paid(UGX)</b></u></font></td>    
    <td width="150"><font size="-1"><u><b>Balance on Item(UGX)</b></u></font></td>
  </tr>
  
  <?php
  		$balance=0;
		$paid=0;
  		$transaction_reciept_query = tep_db_query("SELECT tt.transactiontypes_name,r.credit,r.balance FROM ".TABLE_RECIEPTS." AS r LEFT JOIN " . TABLE_TRANSACTIONTYPES." AS tt ON r.transactiontypes_id = tt.transactiontypes_id");
	 //	echo "SELECT tt.transactiontypes_name,r.credit,r.balance FROM ".TABLE_RECIEPTS." AS r LEFT JOIN " . TABLE_TRANSACTIONTYPES." AS tt ON r.transactiontypes_id = tt.transactiontypes_id WHERE r.tran_recieptno LIKE '".$_GET['r']."'";
	 	while ($reciept = tep_db_fetch_array($transaction_reciept_query)) {?>
		<tr>  
			 <td ><font size="-1"><?php echo $reciept['transactiontypes_name']; ?></font></td>
			<td colspan="2"><font size="-1"><?php echo $reciept['credit']; ?></font></td>    
			<td ><font size="-1"><?php echo $reciept['balance']; ?></font></td>
		</tr> 
	 <?php 
	 	$balance =$balance + $reciept['balance'];
		$paid = $paid + $reciept['credit'];
	 ?> 
   <?php }?>
  
   
   
  <tr>
    <td width="100"></td>
    <td colspan="2" width="60%">&nbsp;</td>
    
    <td width="150"><font size="-1"><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></font></td>
  </tr>
   <tr>
    <td width="100"><font size="-1">Cash</font></td>
    <td><font size="-1">&nbsp;</font></td>
	 <td align="right"><font size="-1" >Total Paid:</font></td>
    
    <td width="150"><font size="-1"><?php echo $paid; ?></font></td>
  </tr>
  <tr>
    <td width="100"><font size="-3"><b>ORIGINAL&nbsp;RECEIPT</b></font></td>
    <td>&nbsp;</td>
	 <td align="right"><font size="-1">Total on Balances :</font></td>
    
    <td width="150"><font size="-1"><?php echo $balance; ?></font></td>
  </tr>
  <tr>
    <td colspan="4" align="center"><font size="-1"><b><?php echo readNumericalValue($_GET['c']);?><br>********** The Amount paid and Balance is only for the specified item(s) **********</b><BR>money once receited is not refundable<br>Thank You!</font></td>     
  </tr>
  
  <tr>
    <td align="center" colspan="4"></font><hr color="#000000"></td>
    
  </tr>
</table>

</body>
</html>
