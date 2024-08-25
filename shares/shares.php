<?php
require_once('../includes/application_top.php');
//require_once("../simple-php-captcha-master/simple-php-captcha.php");
//$_SESSION['captcha'] = simple_php_captcha(array(),'TRANSFER');

spl_autoload_register(function ($class_name) {
    include '../includes/classes/'.$class_name . '.php';
});

$_parent = basename(__FILE__);

getlables("21,1097,300,1161,20,1199,1608");

// TO DO: Add receipts Printing
?>
<script type="text/javascript">
P_LANG = "<?php echo P_LANG;?>";

var searchtext  ='';
var act  ='';

function getClients() {
    //        searchtext = $('input[name=radiosclient]:checked').val()+'SAVACC';
    //       if($("#ttype").val()=="SA" && $("#txtsavaccount").val()!=""){          
    //            
    //            if(searchtext=='GRPSAVACC'){
    //                searchtext ='MEMSAVACC';
    //            }
    //        
    //            $( "#product_prodidto" ).trigger( "change" );   
    //            return;
    //        }
        
       act ='EDIT';
       $('#action').val('edit');              
       
       searchtext = $('input[name=radiosclient]:checked').val();	
       showValues('frmShares','toppanel','search',searchtext,'load.php?act='+act).done(
        function(){  
             $('#toppanel').show();
       });
       
       
}


$( "#radiotran" ).click(function() {       
            
    showValues('frmShares','savdata','search','SHATRAN','load.php?act=edit&account='+$("#txtsavaccount").val()+'&product_prodid='+$("#product_prodid").val()+'&memid='+$("#memids").val()).done(
        function(){    
          
       })
});

function getinfo(frm_id,theid,action,pagedata,urlpage,element){		
	
    $( "#action").val(action);

        if(action==='add'){                    
            urlpage='load.php';
            action ='edit';
        }
        
        $("#toppanel").hide();
        var pageinfo =  JSON.stringify($("#theid, #txtsavaccount, #product_prodid").serializeArray());
      		
        var data1 = JSON.stringify(JSON.parse('{"pageinfo":'+pageinfo+"}"));
   
        showValues('frmShares',theid,action,data1,urlpage,element).done(     
         function(){
        
             if(action=='reverse')
             $( "#radiotran" ).trigger( "click" );
        });
	
    }

//    $(document).ready( function () {
//        
//        var table = $('#example').DataTable( {
//        fixedHeader: true,
//        buttons: [ 'copy', 'excel', 'pdf'],
//        responsive: true,
//        "dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
//        "sDom": "<'dt-toolbar'<'col-xs-6'f><'col-xs-6'<'toolbar'>>r>t<'dt-toolbar-footer'<'col-xs-6'i><'col-xs-6'p>>", 
//        
//        "ajax": {
//                "url": "./load2.php",
//                "type": "POST"
//                }
//                
//    } );
//    
//    table.on( 'init', function () {
//        table.buttons().container()
//        .insertBefore( '#example_filter' );
//    } );
//    
//  
//      $("div.toolbar").html('<b>Custom tool bar! Text/images etc.</b>');
//    } );


</script>

   <style>
    /**
    STYLES ARE NESSESARY FOR THE TABS TO DISPLAY WELL
    */
    .tab {
        width: auto;
        height:auto;
        border: 1px solid silver;
        display: none;
        padding: 1px;
        overflow: hidden;
        
    }
  
    #gridata {
        width: 40%;
        height:auto;  
        float:right;
        position: relative;	
        z-index: 5000 ;
        padding:2px;
        position: relative;
        display:none;

    }


    .loader{
        margin:200px;
        padding:10px;
    }
    h1{
        font-family: 'Actor', sans-serif;
        color:#616161;
        width:auto;
        float:left;
        font-size:18px;
        margin-right:5px;
        letter-spacing:1px;
        font-weight:200;        
        text-shadow: 0 1px 0 #FFFFFF;
    }
    .loader span{
        width:16px;
        height:16px;
        border-radius:50%;
        display:inline-block;
        position:absolute;
        left:50%;
        margin-left:-10px;
        -webkit-animation:3s infinite linear;
        -moz-animation:3s infinite linear;
        -o-animation:3s infinite linear;

    }


    .loader span:nth-child(2){
        background:#E84C3D;
        -webkit-animation:kiri 1.2s infinite linear;
        -moz-animation:kiri 1.2s infinite linear;
        -o-animation:kiri 1.2s infinite linear;

    }
    .loader span:nth-child(3){
        background:#F1C40F;
        z-index:100;
    }
    .loader span:nth-child(4){
        background:#2FCC71;
        -webkit-animation:kanan 1.2s infinite linear;
        -moz-animation:kanan 1.2s infinite linear;
        -o-animation:kanan 1.2s infinite linear;
    }

.tab:focus {
    background-color:red;
}
    @-webkit-keyframes kanan {
        0% {-webkit-transform:translateX(20px);
        }

        50%{-webkit-transform:translateX(-20px);
        }

        100%{-webkit-transform:translateX(20px);
             z-index:200;
        }
    }
    @-moz-keyframes kanan {
        0% {-moz-transform:translateX(20px);
        }

        50%{-moz-transform:translateX(-20px);
        }

        100%{-moz-transform:translateX(20px);
             z-index:200;
        }
    }
    @-o-keyframes kanan {
        0% {-o-transform:translateX(20px);
        }

        50%{-o-transform:translateX(-20px);
        }

        100%{-o-transform:translateX(20px);
             z-index:200;
        }
    }


    @-webkit-keyframes kiri {
        0% {-webkit-transform:translateX(-20px);
            z-index:200;
        }
        50%{-webkit-transform:translateX(20px);
        }
        100%{-webkit-transform:translateX(-20px);
        }
    }

    @-moz-keyframes kiri {
        0% {-moz-transform:translateX(-20px);
            z-index:200;
        }
        50%{-moz-transform:translateX(20px);
        }
        100%{-moz-transform:translateX(-20px);
        }
    }
    @-o-keyframes kiri {
        0% {-o-transform:translateX(-20px);
            z-index:200;
        }
        50%{-o-transform:translateX(20px);
        }
        100%{-o-transform:translateX(-20px);
        }
    }
   
    #customers td, #customers th {
        padding: 0px;
    }

    #customers tr:nth-child(even){background-color: #f2f2f2;}

    #customers tr:hover {background-color: #ddd;}

   
</style>
<?php 
require('../'.DIR_WS_INCLUDES . 'pageheader.php'); 
getlables("1662,1705,1096,1663,2,1214,1388,20,21,24,300,1707,299,298,1197,1706,1384,654,1208,296,1681,1682");
?>
<style>
    
  input[type="numeric"],input[type="text"] {
    margin: 0; 
    font-family: sans-serif;
    font-size: 12px;
    box-shadow: none;

}
</style>
<form id="frmShares" name='frmShares'>
 
   <div class="bigfont1" id="infoBox"></div>
   <input id="action" name="action" type="hidden" value="add">
   <input id="theid" name="theid" type="hidden" value="">
   <input id="client_idno" name="client_idno" type="hidden" value="">   
   <table cellpadding="2" width="100%" border="0" cellspacing="0">
       <tr>        
       <td align="center"> <span class="indicator" id='div_name'> </span><br><?php echo $lablearray['249'];?> <input type="text" id="txtBalance" name="txtBalance" value="0.0" style="background:#EEEEEE;text-align:right;"></span></td>
       <td> <?php echo Common::clientOptions();?> </td>
       <td>
             
             <a class='icons button-clients' id="radioadd" onclick="getClients();" data-balloon="<?php echo $lablearray['1681'];?>" data-balloon-pos="down">&nbsp;</a>     
            
       </td> 
     </table>  
    <fieldset>
        <legend><?php echo $lablearray['1705'];?></legend>   
        <table cellpadding="2" width="100%">
       
        <tr>
            <td><?php echo $lablearray['1197'];?><br><input type="text" id="txtsavaccount" name="txtsavaccount" value="" readonly="readonly" ></td>

                <td ><?php echo $lablearray['654'];?><br><input type="text" id="client_idno" name="client_idno" value=""  readonly="readonly" ></td>
                <td>
                    <?php echo $lablearray['1705'];?><br><?php echo Common::DrawComboFromArray(array(),'product_prodid','S0000','SAVPROD','SAVPROD');?>
                   </td>
               </td>
        </tr>
       </table>
       </fieldset>
     <div id="savdata"></div>
      <fieldset >  
    
    <div id="tab-example" style="margin-top:0px;">
     <div id="LoanApptabs" style="width:100%;padding:0px;float:center;margin:0px;" ></div>
     <div id="tab1" class="tab" style="overflow-x:auto;"> 
    <table cellpadding="2" width="100%" border="0" cellspacing="0">
        <tr>
        <td valign="top">
       
       <table cellpadding="2" width="100%">
       <tr>
            <td><?php echo $lablearray['298'];?><br><input type="us-date" class='date' id="txtDate" name="txtDate" value=""><?php echo TEXT_FIELD_REQUIRED;?></td>
            <td><?php echo $lablearray['299'];?><br>
                   <input type="text" id="txtvoucher" name="txtvoucher" value=""></td>
            <td ><?php echo $lablearray['24'];?><br><?php echo Common::DrawComboFromArray(array(),'PAYMODES','','PAYMODES','','PAYMODES','frmShares');?><?php echo TEXT_FIELD_REQUIRED;?></td>
          </tr>
          
         
         <tr>
            <td>
                <?php echo $lablearray['1208'];?><br>
               <?php echo Common::DrawComboFromArray(array(),'ttype','','SHARETTYPES','','SHARETTYPES','frmShares');?><?php echo TEXT_FIELD_REQUIRED;?>
                 
                
            <td>
                 <?php echo $lablearray['1384'];?><br>
                <input class ="total" type="numeric" id="txtamount" name="txtamount" value="0.0" <?php echo (SETTING_CURRENCY_DENO!=''?'readonly=true':'');?> size="20"><?php echo TEXT_FIELD_REQUIRED;?> 
               
               </td>
            <td>
                <?php echo $lablearray['1707'];?><br>
                    <input type="numeric" id="txtchargeamount" name="txtchargeamount" value="0.0" size="20">
            </td>
          </tr>        
          
        </table>
       
    </td>
    <td  valign="top">        
      <div id="modes"></div>
      <p><?php echo $lablearray['1305'];?><br><span data-balloon="<?php echo $lablearray['1667'];?>" data-balloon-pos="down" data-balloon-length="fit"><?php echo DrawComboFromArray(array(),'product_prodidto','','SAVPROD','','SAVPROD');?></span></p>
      
      <div id="section2" ></div> 
      <div style="float:right;width:360px;">  <button type="reset" class="btn" name="Go"  type="button"><?php echo $lablearray['2'];?></button> <button class="btn" name="Go"  type="button" onClick="CloseDialog(vFloatingPane.id);"  id="btnscancel"><?php echo $lablearray['300'];?></button><button class="btn" name="btnSave"  type="button"   id="btnSave"><?php echo $lablearray['20'];?></button></div>
      <div id="transferacc"></div>
      
    </td> 
     </tr>       
  </table> 
   </div>      
   </fieldset > 
   
   <div id="tab2" class="tab" style="overflow-x:auto;">
       <div id="section1" ></div>     
   </div>
    
   </div>
 
  <div id="toppanel"> 
  </div> 
</form>	
<script type="text/javascript">
$().w2destroy('LoanApptabs');

 function updatetotals(cclass){
            
    var ntotal = 0;

    $( ".AMT").each(function(index) {

        var myClass = $(this).attr("class");

        switch(myClass) {                
        case 'AMT':                 
            ntotal = ntotal + parseFloat($(this).val().replace(/,/g, ''));           
            $("#txtamount").val(ntotal);
            break;
            
        default:
            break;
        }                

    });
 }
$( document ).ready(function() {
    
     var config = {
            tabs: {
                name: 'LoanApptabs',
                active: 'tab1',
                tabs: [
                    {id: 'tab1', caption: '<?php echo $lablearray['1662']; ?>'},
                    {id: 'tab2', caption: '<?php echo $lablearray['1663']; ?>'}
                ],
                onClick: function (event) {
                   $('.tab').hide();
                    $('#' + event.target).show();
//                    if (event.target == 'tab1') {
//                        w2ui['grid_schedule'].refresh();
//                    }
                }
            }
        }

        $(function () {

            $('#LoanApptabs').w2tabs(config.tabs);
            $('#tab1').show();

        });



    
     $('input[type=us-date]').w2field('date',  { format: '<?php echo SETTING_DATE_FORMAT?>'})
    // $( "#PAYMODES" ).trigger( "change" );
      $('#txtamount').blur(function(e) {

        if( $('select#ttype').val()=='SW' || $('select#ttype').val()=='SA'){
            var pageinfo =  JSON.stringify($("#txtamount, #product_prodid, #ttype").serializeArray());      		
            var data1 = JSON.stringify(JSON.parse('{"pageinfo":'+pageinfo+"}"));   
            showValues('frmShares','','loadform',data1,'addedit.php','element');
        }
         
     });
    
     $('#txtsearchterm').keydown(function(e) {
        if(e.keyCode === 13) {
            showValues('frmShares','transferacc','search', $('input[name=radiosclient]:checked').val()+'SAVACC','load.php?act=EXT&searchterm='+$('#txtsearchterm').val());
        }
    });
    
      $('#ttype').change(function() {
        if(this.value=='SA'){
            SelectItemInList("PAYMODES", this.value);
            $('#PAYMODES').attr('disabled', 'disabled');
        }else{
            SelectItemInList("PAYMODES","");
            $('#PAYMODES').removeAttr('disabled');           
        }       
 
    });
    
    // payment mode
    $( "#PAYMODES" ).change(function() {
        showValues('frmShares','modes','search','PAYMODES','load.php',$('#PAYMODES').val());
    });
   
    $( "#btnSave" ).click(function() {
        $('#action').val('add')
        
        $(this).prop('disabled',true); // didable save button
        var pageinfo =  JSON.stringify($("#frmShares").serializeArray());			
        var data1 = JSON.stringify(JSON.parse('{"pageinfo":'+pageinfo+"}"));
        showValues('frmShares','',$('#action').val(),data1,'addedit.php',$('#theid').val()).done(
          
        function(){
             
           
             showValues('frmShares','savdata','search','SHATRAN','load.php?act=edit&account='+$("#txtsavaccount").val()+'&product_prodid='+$("#product_prodid").val()+'&clientidno='+$("#memids").val()).done(
                     
            // showValues('frmShares','savdata','search','SAVTRAN','load.php?act=edit&acc='+$("#txtsavaccount").val()+'&product_prodid='+$("#product_prodid").val()).done(
            function(){  
                $("#toppanel").hide();
                
                $("#txtamount, #txtvoucher, .AMT").val("");
                $("#txtamount, #txtvoucher, .AMT").val('0');
                
                // getinfo('frmShares', $("#txtsavaccount").val(),'add','','addedit.php');
           });
           

        });			
        
        
        // document.getElementById("#frmShares").reset();
         $(this).prop('disabled',false);   
    });	
      
    $('#product_prodidto').on('change', function() {   
        
        var searchtext = returnClientType($('input[name=radiosclient]:checked').val());
        
        if(searchtext=='GRPSAVACC'){
            searchtext ='MEMSAVACC';
        }
        
        showValues('frmShares','transferacc','search',searchtext,'load.php?act=EXT&searchterm='+$('#product_prodidto').val()+'&client_idno='+$('#client_idno').val()+'&acc='+$('#txtsavaccount').val()+'&product_prodid='+$('#product_prodid').val()).done(function (){
             
            $('#transferacc').show();
            
        });
       
    });
    
    
    $(document.body).on('keypress', '#txtsearchterm' ,function(event){
        
       str = "";
        $('input[type=text][name=txtamountto]').each(function (){
          str+=$(this).val() + "$";          
          //alert(str);
        });

   
        if (event.which == 13 ) {
            event.preventDefault();
            showValues('frmShares','transferacc','search',searchtext,'load.php?act=EXT&searchterm='+$('#txtsearchterm').val());
        }
     
    });
    
    
});	 
</script>
</BODY>
</HTML>