<?php
    require_once('../includes/application_top.php');
    spl_autoload_register(function ($class_name) {
        include '../includes/classes/'.$class_name . '.php';
    });
    $_parent = basename(__FILE__); 

    require('../'.DIR_WS_INCLUDES . 'pageheader.php'); 
   Common::getlables("1651,1652,1653,1607,300,20,1608", "", "", Common::$connObj);
?>
<form id="frmmodemsettings" name="frmmodemsettings">   

<span id='status' ></span>
<input name="theid" type="hidden" id="theid" value=""> 	
<input name="action" type="hidden" id="action" value="add"> 	
<table cellpadding="2" cellspacing="2" border="0" width="100%">            

<tr>
    <td ><?php echo Common::$lablearray['1651'];?></td><td ><input type="text" id="txtDevice" name="txtDevice" value='' maxlength="100" size="70"></td>
</tr>


<tr>
    <td ><?php echo Common::$lablearray['1652'];?></td><td>
        <select id="cmbBitsPerSecond" name="cmbBitsPerSecond">
            <option value="75" >75</option>
            <option value="110" >110</option>
            <option value="134" >134</option>
            <option value="150" >150</option>
            <option value="300" >300</option>
            <option value="600" >600</option>
            <option value="1200" >1200</option>
            <option value="1800" >1800</option>
            <option value="2400" >2400</option>
            <option value="4800" >4800</option>
            <option value="7200" >7200</option>
            <option value="9600" selected>9600</option>
            <option value="14400" >14400</option>
            <option value="19200" >19200</option>
            <option value="38400" >38400</option>
            <option value="57600" >57600</option>
            <option value="115200" >115200</option>
            <option value="12800" >12800</option>    
        </select>
    </td>
</tr>


 <tr>
    <td ><?php echo Common::$lablearray['1653'];?></td><td ><input type="text" id="txtPort" name="txtPort" value='' maxlength="100" size="5"> e.g COM2</td>
</tr>
<tr>
<td colspan="2">
<button class="btn" name="btn300"  type="button" style="float:right;margin:4px;" id="btn300" onClick="CloseDialog(vFloatingPane.id);"><?php echo Common::$lablearray['300'];?></button><button class="btn" name="btn20"  type="button" style="float:right;margin:4px;" id="btn20"> <?php echo Common::$lablearray['20'];?></button>
</TD>
</tr> 
<tr>
    <td colspan="2" id="txtHint"></td>
</tr>
    </table>
	    
</form>
	
</body>
<script language="JavaScript"  type="text/javascript">

$( document ).ready(function() {
    
    showValues('frmmodemsettings', 'txtHint', 'search','MODEMS', 'load.php','');
    
    $( "#btn20" ).click(function() {			
       var pageinfo =  JSON.stringify($("#frmmodemsettings").serializeArray());	
       
       var data1 = JSON.stringify(JSON.parse('{"pageinfo":'+pageinfo+"}"));			
       
        showValues('frmmodemsettings','',$("#action").val(),data1,'addedit.php','').done(function () {
          $("#action").val('add');
          showValues('frmmodemsettings', 'txtHint', 'search','MODEMS', 'load.php','');
        });
        
    });
    
    
    
});

 function getinfo(frm_id,theid,action,pagedata,urlpage,element){	
      showValues('frmmodemsettings',theid,'edit','',urlpage,element);	
  }
</script>
</HTML>