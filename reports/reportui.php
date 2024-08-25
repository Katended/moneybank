<?php
require_once('../includes/application_top.php');
//require_once("../simple-php-captcha-master/simple-php-captcha.php");
//$_SESSION['captcha'] = simple_php_captcha(array(),'TRANSFER');

spl_autoload_register(function ($class_name) {
    require_once(__ROOT__. '/includes/classes/' . $class_name . '.php');
});
$_parent = basename(__FILE__);
getlables("20,1097,300,1544,1161,1258,186,1736,21,24,300,1161,1024,373,299,317,1197,1096,373,9,654,1395,1396,1553,186");
?>
<style> 
    #panel, #flip {
        padding:0px;
        text-align: center;
        border: solid 1px #c3c3c3;
    }

    #panel {
        padding:0px;
        display: none;
        margin:0px;
        height:250px; 

    }
</style>

<form   id="frmreportsui" name="frmreportsui" action="#"> 
    <div id="status"></div>
    <table  width="100%" cellpadding="0" cellspacing="0" >
        <tr>
            <td align="center"> 
                <?php  echo Common::generateReportControls($_GET['rcode'], $Conn); ?> 

            </td>             

        </tr>


    </table>
</form>	
</BODY>
<script type="text/javascript">
   $( document ).ready(function() { 
       
     w2utils.date(new Date());
      $('#endDate').w2field('date',  { format: '<?php echo Common::convertDateJSFormat()?>'})    
	 $('#startDate').w2field('date',  { format: '<?php echo Common::convertDateJSFormat();?>'})
// constraints="{datePattern:'<?php echo Common::convertDateJSFormat()?>', strict:true}"

    });
    
     function enabledisable(){
   
        var ckbox = $('#ckhareacodes');     
        if (ckbox.is(':checked')) {
            $('#product_prodid,#endDate,#startDate,#currencies_id,#fund_code,#costcenters_code,#user_id,#bussinesssector_code,#client1_code,#client2_code,#client3_code,#loancategory1_code,#loancategory2_code,#client_regstatus').prop('disabled',true);
        } else {
            $('#product_prodid,#endDate,#startDate,#currencies_id,#fund_code,#costcenters_code,#user_id,#bussinesssector_code,#client1_code,#client2_code,#client3_code,#loancategory1_code,#loancategory2_code,#client_regstatus').prop('disabled',false);
        }
   

    }  
 
    
    $("#code").change(function () {
       
        switch ($(this).val()) {
        case 'PORTRSK':
        case 'ARRERPT':
        case 'OUTBAL':
            $( "#startDate" ).prop( "disabled", true );
            break;

        case 'LLEDGER':
            $('#product_prodid,#currencies_id,#fund_code,#costcenters_code,#user_id,#bussinesssector_code,#client1_code,#client2_code,#client3_code,#loancategory1_code,#loancategory2_code,#client_regstatus').prop('disabled',false);
            $('fieldset').hide('slow');
            break;

        case 'PLEDGERMULTIPLE':
        case 'PROVISION':    
            $('#product_prodid,#currencies_id,#fund_code,#costcenters_code,#user_id,#bussinesssector_code,#client1_code,#client2_code,#client3_code,#loancategory1_code,#loancategory2_code,#client_regstatus').prop('disabled',true);
            $('fieldset').hide('slow');  
            break;

         case 'DUESLN': 
         case 'SAVBALRPT': 
             $('#product_prodid,#currencies_id,#fund_code,#costcenters_code,#user_id,#bussinesssector_code,#client1_code,#client2_code,#client3_code,#loancategory1_code,#loancategory2_code,#client_regstatus').prop('disabled',false);
             $( "#startDate" ).prop( "disabled", true );
             $( "#startDate" ).prop( "value","");
             $('fieldset').show('slow');
             break;

         default:
            $('#product_prodid,#currencies_id,#fund_code,#costcenters_code,#user_id,#bussinesssector_code,#client1_code,#client2_code,#client3_code,#loancategory1_code,#loancategory2_code,#client_regstatus').prop('disabled',false);
            $( "#startDate" ).prop( "disabled", false );
            $('#branch_codefr,#branch_codeto').prop('disabled',false);
            $('fieldset').show('slow');
            break;
        }
                  
         
    });
    
    $("#btnviewreport").click(function () {


        switch ($('#code').val()) {


            
        case 'SAVSTAT':
            if ($('#startDate').val()===''|| $('#endDate').val()==='') {
                displaymessage('frmreportsui', "<?php echo $lablearray['186']; ?>",'INFO'); 
                 return;
            }

            if ($('#savaccounts_account').val()==='') {
                displaymessage('frmreportsui', "<?php echo $lablearray['1736']; ?>",'INFO'); 
                 return;
            }
            break    

        case 'LOANREP':
            if ($('#startDate').val()===''|| $('#endDate').val()==='') {
                displaymessage('frmreportsui', "<?php echo $lablearray['186']; ?>",'INFO'); 
                 return;
            }
            break;

        case 'ARRERPT':       
            if ($('#endDate').val()==='') {
                displaymessage('frmreportsui', "<?php echo $lablearray['186']; ?>",'INFO'); 
                 return;
            }
            break;

         case 'PROVISION':
            if($('#product_prodid').val()=="" || $('#pDate').val()=="" || $('#branch_code').val()==""){
                displaymessage('frmreportsui', "<?php echo $lablearray['1553'] ?>", 'success');                
                return;
            }
            
            if($('#class1a').val()=="" || $('#class1b').val()=="" || $('#class1per').val()==""){
                displaymessage('frmreportsui', "<?php echo $lablearray['1553'] ?>", 'success');                
                return;
            }
            
            if($('#class2a').val()=="" || $('#class2b').val()=="" || $('#class2per').val()==""){
                displaymessage('frmreportsui', "<?php echo $lablearray['1553'] ?>", 'success');                
                return;
            }
            
            if($('#class3a').val()=="" || $('#class3b').val()=="" || $('#class3per').val()==""){
                displaymessage('frmreportsui', "<?php echo $lablearray['1553'] ?>", 'success');                
                return;
            }
            
            if($('#class5a').val()=="" || $('#class5per').val()==""){
                displaymessage('frmreportsui', "<?php echo $lablearray['1553'] ?>", 'success');                
                return;
            }            
            break;

        case 'PORTRSK':
            if( $('#endDate').val()==""){
                displaymessage('frmreportsui', "<?php echo $lablearray['186'] ?>", 'success');                
                return;
            }
            
            if($('#class1a').val()=="" || $('#class1b').val()=="" ){
                displaymessage('frmreportsui', "<?php echo $lablearray['1553'] ?>", 'success');                
                return;
            }
            
            if($('#class2a').val()=="" || $('#class2b').val()==""){
                displaymessage('frmreportsui', "<?php echo $lablearray['1553'] ?>", 'success');                
                return;
            }
            
            if($('#class3a').val()=="" || $('#class3b').val()==""){
                displaymessage('frmreportsui', "<?php echo $lablearray['1553'] ?>", 'success');                
                return;
            }
            
            if($('#class5a').val()=="" || $('#class5b').val()==""){
                displaymessage('frmreportsui', "<?php echo $lablearray['1553'] ?>", 'success');                
                return;
            }
            if($('#class6a').val()=="" || $('#class6b').val()==""){
                displaymessage('frmreportsui', "<?php echo $lablearray['1553'] ?>", 'success');                
                return;
            } 
                  
            var data3 = JSON.parse(JSON.stringify($("#panel :input").serializeArray()));
            console.log(JSON.stringify($("#panel :input").serializeArray()));
            break;
            
        case 'PLEDGER':
            if($('#client_idno').val()=="" && !$('#client2_code').is('[disabled=disabled]')){
               displaymessage('frmreportsui', "<?php echo $lablearray['1396'] ?>", 'success');                
              return;
            }
            
          if ($('#startDate').val() === '' || $('#endDate').val()==='') {
                displaymessage('frmreportsui', "<?php echo $lablearray['186']; ?>",'INFO'); 
                 return;
            }
            break;
            
        case 'SAVTILL':
        case 'PROFITPERPERIOD':
            if ($('#startDate').val() === '' || $('#endDate').val()==='') {
                displaymessage('frmreportsui', "<?php echo $lablearray['186']; ?>",'INFO'); 
                 return;
            }
            break;  
            
        case 'PLEDGERMULTIPLE':
        case 'TRIALB':
             if($('#areacode_code').val()==""){
              displaymessage('frmreportsui', "<?php echo $lablearray['1544'] ?>", 'success');                
              return;
            }
            
             if ($('#startDate').val() === '' || $('#endDate').val()==='') {
                displaymessage('frmreportsui', "<?php echo $lablearray['186']; ?>",'INFO'); 
                 return;
            }
            break;

        }

        // check see if we are not generating the multiple loan ledgercard- we don need to send selected fields for this report
       
        if ($('#code').val() != 'MLLCARD') {
           
            // make sure all elements in the selected list are selected for serialisation
            var listbox = document.getElementById('selected_columns');


            if (listbox.options.length <= 0) {
                displaymessage("status", "<?php echo $lablearray['1258']; ?>", 'warning');
               
                return;
            } else {
                for (var count = 0; count < listbox.options.length; count++) {
                    listbox.options[count].selected = true;
                }
            }
            
        }
        // serialise selected fields
        var fields = JSON.stringify($("#selected_columns").serializeArray());

        // serialise form data and exclude elements which we do not want to be included
        var parameters = JSON.stringify($("#frmreportsui :input[name!='selected_columns']:input[name!='cfimb_5']:input[name!='fieldlist']").serializeArray());
        
        var data1 = JSON.parse('{"parameters":' + parameters + "}");
        var data2 = JSON.parse('{"fields":' + fields + "}");
        var pageinfo = $.extend({}, data1, data2, data3 || '')
         showValues('frmreportsui', '', '', pageinfo, 'reports/processreport.php','').done(function () {
               
                openPopupListWindow('reports/reports.php?rtype=' + $('input[name=cfimb_5]:checked').val());
            });

    });

    function listbox_moveacross(sourceID, destID) {
        var src = document.getElementById(sourceID);
        var dest = document.getElementById(destID);

        for (var count = 0; count < src.options.length; count++) {

            if (src.options[count].selected == true) {
                var option = src.options[count];

                var newOption = document.createElement("option");
                newOption.value = option.value;
                newOption.id = option.id;
                newOption.text = option.text;
                newOption.selected = true;
                try {
                    dest.add(newOption, null); //Standard
                    src.remove(count, null);
                } catch (error) {
                    dest.add(newOption); // IE only
                    src.remove(count);
                }
                count--;
            }
        }
    }



    // select all and move all 
    function listbox_selectall(listID, destID) {
        var listbox = document.getElementById(listID);
        for (var count = 0; count < listbox.options.length; count++) {

            listbox.options[count].selected = true;

        }
        listbox_moveacross(listID, destID)
    }

    function LoadClients(rpt) {

        switch (rpt) {

            case 'SAVSTAT':
                searchtext = returnClientType($('input[name=client_type]:checked').val());
                if (searchtext === '') {
                    displaymessage('frmreportsui', "<?php echo $lablearray['1395']; ?>",'INFO');             
                }
             
                showValues('frmreportsui', 'panel', 'search', searchtext, 'load.php?act=edit', 'savaccounts_account');

                break;

            case 'PLEDGER':
                searchtext =returnClientType($('input[name=client_type]:checked').val());
                if (searchtext === '') {
                    displaymessage('frmreportsui', "<?php echo $lablearray['1395']; ?>",'INFO'); 
                  
                }
                
                switch (searchtext) {

                    case 'INDSAVACC':
                        searchtext = 'IND';
                        break;
                    default:
                        searchtext = '';
                }
                showValues('frmreportsui', 'panel', 'search', searchtext, 'load.php?act=edit&searchterm='+$('#client_idno').val(), '').done(function () {
                    $("#txtsearchterm").focus();
                });
                break;

            case 'PORTRSK':
                showValues('frmreportsuiportrsk', 'panel', 'loadelement', '', 'load.php', '').done(function () {
                    $("#panel").tooltip({show: {duration: 800}});
                });
                break;
                
            case 'MLLCARD':    
             showValues('frmreportsui', 'panel', 'search', 'ALLLOANS', 'load.php').done(function () {
                //     w2utils.date(new Date());
                 //   $('input[type=us-date]').w2field('date');
                });
                
              break;  
            default:

                break;
        }


        $("#panel").slideToggle();
    }

    function getinfo(frm_id, theid, action, pagedata, urlpage, element) {

        $("#action").val(action);

        if (action === 'add') {
            urlpage = 'load.php';
        }
        
        if($('#code').val()=='MLLCARD'){
            $('#loan_number_fr').val(theid);
            $('#loan_number_to').val(theid);
            return;
        }
       
        showValues('frmreportsui', theid, 'edit', pagedata, urlpage, element).done(
        function () {
            populateForm('frmSave', jsonObj['data']);
            
            if($('#code').val()!='SAVSTAT'){
              $('#client_idno').val(theid);
            }
            
        }
        );


    }

</script>

  <script>
  $( function() {
    // run the currently selected effect
    function runEffect() {
      // get effect type from
      var selectedEffect ='puff';
 
      // Most effect types need no options passed by default
      var options = {};
      // some effects have required parameters
      if ( selectedEffect === "scale" ) {
        options = { percent: 50 };
      } else if ( selectedEffect === "transfer" ) {
        options = { to: "#button", className: "ui-effects-transfer" };
      } else if ( selectedEffect === "size" ) {
        options = { to: { width: 200, height: 60 } };
      }
 
      // Run the effect
      $( "#myDialogId1" ).effect( selectedEffect, options, 500, callback );
    };
 
    // Callback function to bring a hidden box back
    function callback() {
      setTimeout(function() {
        $( "#myDialogId1" ).removeAttr( "style" ).hide();
        CloseDialog("myDialogId1");
      }, 1000 );
    };
 
    // Set effect from select menu value
    $( "#btnscancel" ).click(function() {
      runEffect();
    //  
      return false;
    });
  } );
  </script>
</HTML>