<?php
require_once('../includes/application_top.php');
Common::getlables("761,289,1082,675,1530,1528,1527,1525,1526,297,264,679,1251,1310,316,317,296,289,1208,264,297,299,20,21,301,1523,242,1208,317,1524,306,299,289,297,1251,264,316,1208", "", "",Common::$connObj);
?>
<script language="JavaScript"  type="text/javascript">
        var url ='';
	var iface='';
	url ='../addedit.php';
    $("#btnAdd").click(function () {
        if(document.getElementById('generalledger_description').value===""){
                 displaymessage("POITable2","<?php echo Common::$lablearray['1528']; ?>","WAR.")                     
                return;                    
        }
                
        if (document.getElementById('acc_debit').value != "") {            
            updatGrid('dr');
        }

        if (document.getElementById('acc_credit').value != "") {
            updatGrid('cr');
         }

    });

    var nRow = 0;

    function getRowID() {
        nRow = nRow + 1;
        return nRow;
    }

    function updatGrid(type) {
              
                var table = document.getElementById("POITable2");

                var nDebit = parseFloat(document.getElementById('generalledger_debit').value);
                var nCredt = parseFloat(document.getElementById('generalledger_credit').value);

                var nTotalDebit = 0;
                var nTotalDebit = 0;

                var cRowClass = "";
                if (nDebit == 0 && nCredt == 0) {
                    displaymessage("POITable2","<?php echo Common::$lablearray['1530']; ?>","INFO.")                     
                    return;

                }

                if (cRowClass == "even") {
                    cRowClass = "";
                } else {
                    cRowClass = "even";
                }

                if (type == 'cr') {

                    if (nCredt != 0) {

                        var tableRef = document.getElementById('POITable2').getElementsByTagName('tbody')[0];
                        var row = table.insertRow(tableRef.rows.length + 1)

                        row.setAttribute('id', 'R' + getRowID());
                        row.setAttribute('class', 'sortableTable');

                        var cell0 = row.insertCell(0);
                        var cell1 = row.insertCell(1);
                        var cell2 = row.insertCell(2);
                        var cell3 = row.insertCell(3);
                        var cell4 = row.insertCell(4);
                        var cell5 = row.insertCell(5);
                        var cell6 = row.insertCell(6);
                        var cell7 = row.insertCell(7);
                        var cell8 = row.insertCell(8);
                        var cell9 = row.insertCell(9);
                        var cell10 = row.insertCell(10);
                        var cell11 = row.insertCell(11);
                        var cell12 = row.insertCell(12);
                        
                        cell0.innerHTML = "<input type='checkbox'  value=''>"
                        cell1.innerHTML = document.getElementById('startDate').value;
                        cell2.innerHTML = document.getElementById('tcode').value;
                        cell3.innerHTML = document.getElementById('acc_credit').value;
                        cell4.innerHTML = document.getElementById('generalledger_voucher').value;
                        cell5.innerHTML = "<input type='text' OnKeyUp='balanceTransaction(\"dr\")' name='dr_acc[]' value='" + document.getElementById('generalledger_debit').value + "'><input type='hidden' id='dr_amt[]' name='dr_amt' value='" + document.getElementById('generalledger_debit').value + "'><input type='hidden' id='dr_amt[]' name='dr_amt' value='" + document.getElementById('generalledger_debit').value + "'>";
                        cell6.innerHTML = "<input type='text' OnKeyUp='balanceTransaction(\"cr\")' name='cr_acc[]' value='" + document.getElementById('generalledger_credit').value + "'><input type='hidden' id='cr_amt[]' name='cr_amt' value='" + document.getElementById('generalledger_credit').value + "'><input type='hidden' id='cr_amt[]' name='cr_amt' value='" + document.getElementById('generalledger_credit').value + "'>";
                        cell7.innerHTML = document.getElementById('currencies_code').value;
                        cell8.innerHTML = document.getElementById('generalledger_description').value;
                        cell9.innerHTML = document.getElementById('branchcode').value;
                        cell10.innerHTML = document.getElementById('trancodes_code').value;
                        cell11.innerHTML = document.getElementById('costcenters_code').value; 
                        cell12.innerHTML = "<input type='button' value='X' onclick=deleteRow(this,'" + nCredt + "','cr')>";
                        document.getElementById('txtTotalCr').value = parseFloat(document.getElementById('txtTotalCr').value) + parseFloat(nCredt);
                        
                        var straccdebit = document.getElementById("acc_credit");

//                        for (var i = 0; i < straccdebit.options.length; i++) {
//                            if (straccdebit.options[i].selected == true) {
//                                var selectedstring = straccdebit.options[i].text;
//                                nlength = selectedstring.length;
//                                cell7.innerHTML = selectedstring.substring(nlength - 3, nlength);
//
//                            }
//                        }



                        //  }

                    }
                }

                if (type == 'dr') {

                    if (nDebit != 0) {
//			if(!IsNullEmptyField('acc_debit',"")){
//				return;	
//			}else{
                        //showResult('frmid=frmmanagetransactions&action=getaccount&acccode='+document.getElementById('acc_debit').value,'');				

                        var tableRef = document.getElementById('POITable2').getElementsByTagName('tbody')[0];

                        var row = table.insertRow(tableRef.rows.length + 1);

                        //	row.className ="edit_tr"; 
                        row.setAttribute('id', 'R' + getRowID());
                        row.setAttribute('class', 'sortableTable');
                        
                        var cell0 = row.insertCell(0);
                        var cell1 = row.insertCell(1);
                        var cell2 = row.insertCell(2);
                        var cell3 = row.insertCell(3);
                        var cell4 = row.insertCell(4);
                        var cell5 = row.insertCell(5);
                        var cell6 = row.insertCell(6);
                        var cell7 = row.insertCell(7);
                        var cell8 = row.insertCell(8);
                        var cell9 = row.insertCell(9);
                        var cell10 = row.insertCell(10);
                        var cell11 = row.insertCell(11);
                        var cell12 = row.insertCell(12);
                        
                        cell0.innerHTML = "<input type='checkbox'  value=''>"
                        cell1.innerHTML = document.getElementById('startDate').value;
                        cell2.innerHTML = document.getElementById('tcode').value;
                        cell3.innerHTML = document.getElementById('acc_debit').value;
                        cell4.innerHTML = document.getElementById('generalledger_voucher').value;
                        cell5.innerHTML = "<input type='text' OnKeyUp='balanceTransaction(\"dr\")' name='dr_acc[]' value='" + document.getElementById('generalledger_debit').value + "'><input type='hidden' id='dr_amt[]' name='dr_amt' value='" + document.getElementById('generalledger_debit').value + "'><input type='hidden' id='dr_amt[]' name='dr_amt' value='" + document.getElementById('generalledger_debit').value + "'>";
                        cell6.innerHTML = "<input type='text' OnKeyUp='balanceTransaction(\"cr\")' name='cr_acc[]' value='" + document.getElementById('generalledger_credit').value + "'><input type='hidden' id='cr_amt[]' name='cr_amt' value='" + document.getElementById('generalledger_credit').value + "'><input type='hidden' id='cr_amt[]' name='cr_amt' value='" + document.getElementById('generalledger_credit').value + "'>";
                        cell7.innerHTML = document.getElementById('currencies_code').value;
                        cell8.innerHTML = document.getElementById('generalledger_description').value;
                        cell9.innerHTML = document.getElementById('branchcode').value;
                        cell10.innerHTML = document.getElementById('trancodes_code').value;
                        cell11.innerHTML = document.getElementById('costcenters_code').value;                        
                        cell12.innerHTML = "<input type='button' value='X' onclick=deleteRow(this,'" + nDebit + "','dr')>";
  
                        document.getElementById('txtTotalDr').value = parseFloat(document.getElementById('txtTotalDr').value) + parseFloat(nDebit);

                        var straccdebit = document.getElementById("acc_debit");

//                        for (var i = 0; i < straccdebit.options.length; i++) {
//                            if (straccdebit.options[i].selected == true) {
//                                var selectedstring = straccdebit.options[i].text;
//                                nlength = selectedstring.length;
//                                cell7.innerHTML = selectedstring.substring(nlength - 3, nlength);
//
//                            }
//                        }

                        //}
                    }

                }
            }

            function Save(TXTACTION) {

                var jsonObj = [];
                var nIndex = 0;
                
                
                switch(TXTACTION){
                
                case 'print':
                                      
                    var parameters = JSON.stringify($("#searchterm").serializeArray());

                    var pageinfo = JSON.parse('{"parameters":' + parameters + "}");

                    showValues('frmreportsui', '', '', pageinfo, 'reports/processreport.php').done(function () {

                        openPopupListWindow('reports/reports.php?rtype=' + $('input[name=cfimb_5]:checked').val());
                    });   
                    
                break;
                    
                case 'reverse':
                    // showValues(frm,theid,action,pageparams,urlpage,keyparam)
                     showValues("frmmanagetransactions",$('#searchterm').val(),TXTACTION,"","addedit.php","").done(function(){ 
                        getdata();
                    });
                    
                    break;
                    
                default:
                  
                    $('#POITable2 tr').each(function (row, tr) {

                        // skip first row		
                        //if($(tr).find('td:eq(0)').text()==""){ 

                        if (nIndex > 0) {

                            var nDebit = document.getElementsByName('dr_acc[]').item(nIndex - 1).attributes.value;
                            var nCredit = document.getElementsByName('cr_acc[]').item(nIndex - 1).attributes.value;
                            var obj = {
                                date: $(tr).find('td:eq(1)').text(),
                                tcode: $(tr).find('td:eq(2)').text(),
                                accountcode: $(tr).find('td:eq(3)').text(),
                                voucher: $(tr).find('td:eq(4)').text(),
                                debit: nDebit.value,
                                credit: nCredit.value,
                                currencies: $(tr).find('td:eq(7)').text(),
                                description: $(tr).find('td:eq(8)').text(),
                                bcode: $(tr).find('td:eq(9)').text(),
                                trcode: $(tr).find('td:eq(10)').text(),
                                costcenters: $(tr).find('td:eq(11)').text()
                            };                         
                            jsonObj.push(obj);
                        }
                        nIndex++;
                    });

                    if (nIndex == 0) {
                        displaymessage("frmmanagetransactions", "<?php echo Common::$lablearray['1523']; ?>", "WAR")
                        return;
                    }


                    if (parseFloat(document.getElementById('txtTotalDr').value) != parseFloat(document.getElementById('txtTotalCr').value)) {
                        displaymessage("frmmanagetransactions", "<?php echo Common::$lablearray['679']; ?>", "WAR")
                        return;
                    }


                    var data1 = JSON.stringify(jsonObj);

                   // var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));

                    showValues('frmmanagetransactions', '', 'add', jsonObj, 'addedit.php', $('#theid').val()).done(
                     function () {
                       getdata();
                    });

               
                    // refresh table

                    document.getElementById('txtTotalDr').value = '0.00';
                    document.getElementById('txtTotalCr').value = '0.00';
                    $('#POITable2 tr').has('td').remove();
                    break;
                    
               } 
                //showResult('action='+ document.getElementById('action').value +'&frmid=frmmanagetransactions&generalledger_debit_id='+document.getElementById('generalledger_debit_id').value +'&generalledger_credit_id='+document.getElementById('generalledger_credit_id').value + '&acc_debit='+document.getElementById('acc_debit').value+ '&acc_credit='+document.getElementById('acc_credit').value +'&generalledger_debit='+document.getElementById('generalledger_debit').value +'&generalledger_credit='+document.getElementById('generalledger_credit').value+'&generalledger_datecreated='+document.getElementById('generalledger_datecreated').value +'&generalledger_voucher='+document.getElementById('generalledger_voucher').value+'&generalledger_description='+document.getElementById('generalledger_description').value+'&branchcode='+document.getElementById('branchcode').value,'')
            }


            function deleteRow(row, nAmount, type) {

                var i = row.parentNode.parentNode.rowIndex;
                document.getElementById('POITable2').deleteRow(i);
                if (type == 'dr') {
                    document.getElementById('txtTotalDr').value = parseFloat(document.getElementById('txtTotalDr').value) - parseFloat(nAmount);
                }

                if (type == 'cr') {
                    document.getElementById('txtTotalCr').value = parseFloat(document.getElementById('txtTotalCr').value) - parseFloat(nAmount);
                }
            }


            function balanceTransaction(type) {
                var nValue = 0.00;

                if (type == 'dr') {
                    $('form input[name="dr_acc[]"]').each(function () {
                        nValue = parseFloat(nValue) + parseFloat(this.value);
                    });

                    document.getElementById('txtTotalDr').value = nValue;
                }

                if (type == 'cr') {
                    $('form input[name="cr_acc[]"]').each(function () {
                        nValue = parseFloat(nValue) + parseFloat(this.value);
                    });

                    document.getElementById('txtTotalCr').value = nValue;
                }


            }

</script>
<?php require('../' . DIR_WS_INCLUDES . 'pageheader.php'); ?>					
<form id="frmmanagetransactions" name="frmmanagetransactions">
    <input name="code"  id="code" type="hidden" value="GETTRAN" >
    <input name="action"  id="action" type="hidden" value="" > 
        <div class="general-container">
            <span>
                <?php echo Common::$lablearray['316']; ?><?php echo TEXT_FIELD_REQUIRED;?><br><?php echo generateBranchCombo(); ?>
                <br><?php echo Common::$lablearray['317']; ?><?php echo TEXT_FIELD_REQUIRED;?><br><input type="us-date" id="startDate" name="startDate">
                <br><?php echo Common::$lablearray['1208']; ?><?php echo TEXT_FIELD_REQUIRED;?><br><?php echo Common::DrawComboFromArray(array(), 'trancodes_code', '', 'TRANCODES', '', 'trancodes_code'); ?>
           </span>         
           <span>
             <?php echo Common::$lablearray['299']; ?><br><?php echo tep_draw_input_field('generalledger_voucher', '', '', false, 'text', '', '45'); ?>
             <br><?php echo Common::$lablearray['264']; ?><?php echo TEXT_FIELD_REQUIRED;?><br><?php echo tep_draw_input_field('generalledger_description', '', '', false, 'text', '', '45'); ?>
             <br><?php echo Common::$lablearray['1251']; ?><?php echo TEXT_FIELD_REQUIRED;?><br><?php echo Common::DrawComboFromArray(array(), 'currencies_code',SETTTING_CURRENCY_ID, 'CURRENCIES', '', 'currencies_code'); ?>          
           </span>

            <span>
                <?php echo Common::$lablearray['1310']; ?><br><?php echo Common::generateReportControls('COSTCENTERS', Common::$connObj, 'costcenters_code'); ?>
                <br><input  id="tcode" name="tcode" value="" type="text" readonly="">
            </span>
        </div>
            
    <input name="datefield"  id="datefield" type="hidden" value="" >
 
    <div class="debit-credit">
        <span>        
            <legend style="color:blue"><?php echo Common::$lablearray['289'];?></legend>
            <?php echo Common::$lablearray['296']; ?><br><?php echo Common::DrawComboFromArray(array(), 'acc_debit', '', 'COACOMBO', '', 'COACOMBO'); ?>
            <br><?php echo Common::$lablearray['289']; ?><br><input name="generalledger_debit" id="generalledger_debit" type="text" value='0.0' onKeyPress="return EnterNumericOnly(event, 'generalledger_debit')" onClick="document.getElementById('generalledger_credit').value = '0.00'">
        </span> 	
                
      <span>
          <legend style="color:red"><?php echo Common::$lablearray['297'];?></legend>
          <br><?php echo Common::$lablearray['296']; ?><br><?php echo Common::DrawComboFromArray(array(), 'acc_credit', '', 'COACOMBO', '', 'COACOMBO'); ?>
          <br><?php echo Common::$lablearray['297']; ?><br><input name="generalledger_credit" id="generalledger_credit" type="text" value='0.0' onKeyPress="return EnterNumericOnly(event, 'generalledger_credit')" onClick="document.getElementById('generalledger_debit').value = '0.00'">
       </span>        
    </div>

    				
    <div class="debit-credit">
        <input name="cancel"  type="button" value="<?php echo Common::$lablearray['242']; ?>"  class="actbutton" onClick="
        document.getElementById('save').value = 'Save'
        ;document.getElementById('action').value = 'add';
        document.getElementById('generalledger_description').value = '';document.getElementById('generalledger_credit').value = '';
        document.getElementById('generalledger_debit').value = '';
        document.getElementById('acc_debit').value = '0.00';
        document.getElementById('acc_credit').value = '0.00';
        document.getElementById('startDate').value = '';
        document.getElementById('generalledger_voucher').value = '';
        document.getElementById('txtTotalDr').value = '0.00';
        document.getElementById('txtTotalCr').value = '0.00';
        document.getElementById('generalledger_credit').value = '0';    
        document.getElementById('generalledger_debit').value = '0';   
        $('#POITable2').empty();"><input name="save"  type="button" value="<?php echo Common::$lablearray['1527']; ?>" class="actbutton" id="btnAdd">
    </div>
            	
   
    <fieldset >
        <table   cellpadding="0" id='POITable2' width='100%'>
            <thead>			
                <th class="sortableTable"></th>    
                <th class="sortableTable"><?php echo Common::$lablearray['317']; ?></th>
                <th class="sortableTable"><?php echo Common::$lablearray['1524']; ?></th>
                <th class="sortableTable"><?php echo Common::$lablearray['306']; ?></th>
                <th class="sortableTable"><?php echo Common::$lablearray['299']; ?></th>
                <th class="sortableTable"><?php echo Common::$lablearray['289']; ?></th>
                <th class="sortableTable"><?php echo Common::$lablearray['297']; ?></th>
                <th class="sortableTable"><?php echo Common::$lablearray['1251']; ?></th>
                <th class="sortableTable"><?php echo Common::$lablearray['264']; ?></th>
                <th class="sortableTable"><?php echo Common::$lablearray['316']; ?></th>
                <th class="sortableTable"><?php echo Common::$lablearray['1208']; ?></th>
                <th class="sortableTable"><?php echo Common::$lablearray['1082']; ?></th>
                <th class="sortableTable"></th>
            </thead>
            <tbody id='tablerows'>
            </tbody>
        </table>               
        <table width="100%" border="0" cellpadding="1" >
            <tr>			
                <td></td><td align="right"><b>Debit</b> <input name="txtTotalDr" type="txtTotalDr" id="txtTotalDr" value="0.00" style="background:#FFFF00;" disabled="disabled"></td><td align="right"><b>Credit</b> <input name="txtTotalCr" type="txtTotalCr" id="txtTotalCr" value="0.00" style="background:#FFFF00;" disabled="disabled"></td><td></td>
            </tr>
            <tr>
                <td colspan="4" align="center">						
                    <input name="save"  type="button" value="Save" class="actbutton"  onClick="Save()" id='save'>
                </td>
            </tr>
        </table>
        </fieldset>    
        <fieldset>      
        <table width="100%" border="0" cellspacing="0" cellpadding="2">	
         <tr>
            <td align="center"><?php echo Common::$lablearray['301']; ?> <input name="searchterm" type="text" id="searchterm" value="" size="50"><input name="Search" type="button" class="actbutton" value="<?php echo Common::$lablearray['21']; ?>" onClick="getdata();"><input name="btnReverse"  type="button" value="<?php echo Common::$lablearray['1526']; ?>" class="actbutton" id="btnReverse" onclick="Save('reverse')"></td>
        </tr>
         <tr>
            <td  align="center" id="tab-example">             
                 
              </td>
        </tr>
         <tr>			
              <td align="right">
                    <table width="100%" border="0" cellpadding="1" >

                    <tr>			
                        <td></td><td align="right"><b>Debit</b> <input name="txtTotalDr2" type="numeric" id="txtTotalDr2" value="0.00" style="background:#FFFF00;" disabled="disabled"></td><td align="right"><b>Credit</b> <input name="txtTotalCr2" type="numeric" id="txtTotalCr2" value="0.00" style="background:#FFFF00;" disabled="disabled"></td><td></td>
                    </tr>
                    </table>
           </td>
         </tr>
        <tr>
            <td id='txtHint' align="center"></td>		
        </tr> 
        <tr>
          <td id='txtHint' align="center"><?php echo Common::printOptions();?></td>
        </tr> 
    </table>
    </fieldset>  

</form>
</body>
<script language="JavaScript" type="text/javascript">

  
        
        w2utils.date(new Date());
        
       $('input[type=us-date]').w2field('date',  { format: '<?php echo SETTING_DATE_FORMAT?>'})
        
        $("#btnviewreport").click(function () {            
      
            var parameters = JSON.stringify($("#tcode,#code").serializeArray());
            
            var pageinfo = JSON.parse('{"parameters":' + parameters + "}");   
        
            showValues('frmreportsui', '', '', pageinfo, 'reports/processreport.php').done(function () {
                openPopupListWindow('reports/reports.php?rtype=' + $('input[name=cfimb_5]:checked').val());
            });
            
       });
       
       
         function getdata(){      
       
            if($('#searchterm').val()==""){
                displaymessage("POITable2","<?php echo Common::$lablearray['1525']; ?>","WAR.") 
                return;
            }
            
             
                $('#tcode').val($('#searchterm').val());
                showValues('frmmanagetransactions','tab-example','search','TRANSACTION','load.php?searchterm='+$('#searchterm').val()).done(function(){
                    var nIndex = 0;
                    var nDr =0;
                    var nCr =0;

                    $('#POITable tr').each(function (row, tr) {
                      
                            if (nIndex) {
                                
                                nDr =  parseFloat(nDr) +  parseFloat($(tr).find('td:eq(5)').text());
                               
                                nCr = parseFloat(nCr) +  parseFloat($(tr).find('td:eq(6)').text());
                            }
                            nIndex++;
                    });

                    $('#txtTotalDr2').val(nDr);
                    $('#txtTotalCr2').val(nCr);

                });   


    } 
   
 
        
    function getinfo(frm_id,theid,action,pagedata,urlpage,element){		
	
    

        // if(action==='add'){                    
        //     urlpage='load.php';
           
        // }
        showValues(frm_id,theid,action,'',urlpage,element).done();
    
        // var pageinfo =  JSON.stringify($("#theid, #txtsavaccount, #product_prodid").serializeArray());
      		
        // var data1 = JSON.stringify(JSON.parse('{"pageinfo":'+pageinfo+"}"));
   
      
	
}
</script>
</HTML>
