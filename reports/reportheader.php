<?php
    // NB. MAKE SURE YOU ARE PASSING THE CORRECT REPORT KEYS AND ELEMENTS FORM FROMS report.php

 if(isset($parameters['client_idno'])):
    $clientdetails = Common::getClientDetails($parameters['client_idno']);
    $name = $clientdetails[0]['name']." (".$clientdetails[0]['client_idno'].")<p>Address: ".$clientdetails[0]['client_addressphysical']."</p>";
    $parameters['name'] = $clientdetails[0]['NAME']."<br> (".$clientdetails[0]['client_idno'].")<br>Address: ".$clientdetails[0]['client_addressphysical'];
endif;

   switch($parameters['code']){
    case 'SMSMESSAGES':
        Common::getlables("1676", "", "", Common::$connObj);
        $parameters['RPTNAME'] = Common::$lablearray['1676'];
        break;
    
    case 'TDRPT':
        Common::getlables("362,271,1594,1631,1596,1595,882,197,1593", "", "", Common::$connObj);
        $parameters['RPTNAME'] = Common::$lablearray['1593'];
        
    case 'PROFITPERPERIOD':
        Common::getlables("1588", "", "", Common::$connObj);
        $parameters['RPTNAME'] = Common::$lablearray['1588'];
        break;
    case 'CHARTOFACCOUNTS':
        Common::getlables("109", "", "", Common::$connObj);
        $parameters['RPTNAME'] = Common::$lablearray['109'];
        break;
    case 'INTSAVRPT':
        Common::getlables("1573,296,1096,1145,9", "", "", Common::$connObj);
        $report_db_fieldlist_array['9'] = 'name';
        $parameters['RPTNAME'] = Common::$lablearray['1573'];
        break;
    
    case 'CLIENTLOANFREQ':
        Common::getlables("1622,1234", "", "", Common::$connObj);
        $parameters['RPTNAME'] = Common::$lablearray['1670'];
        break;
    
    case 'GETTRAN':
        Common::getlables("301,296,264,289,297,316,1632", "", "", Common::$connObj);
        $report_db_fieldlist_array['1632'] = 'name';
        $parameters['RPTNAME'] = Common::$lablearray['1573'];
        break;
    
    
    case 'TRANINPERIOD':
        $parameters['RPTNAME'] = Common::$lablearray['1541'];
        break;       
       
    case 'DEBITCREDIT':
        $parameters['RPTNAME'] = Common::$lablearray['1538'];
        break;
        
    case 'CLIENTRPTS':
         $parameters['RPTNAME'] = Common::$lablearray['1276'];
         break;
     
    case 'SAVTILL':
        //Common::getlables("1277","","",$Conn);
        $parameters['RPTNAME'] = Common::$lablearray['1277'];
         break;
     
    case 'SAVBALRPT':
        //Common::getlables("1278","","",$Conn);
        $parameters['RPTNAME'] = Common::$lablearray['1278'];
         break;
     
    case 'SAVSTAT': 
        $parameters['RPTNAME'] = Common::$lablearray['1266'];
         break;
     
    case 'OUTBAL':
         $parameters['RPTNAME'] = Common::$lablearray['1280'];
         break;
     
    case 'ARRERPT':
        $parameters['RPTNAME'] = Common::$lablearray['1295'];
        break;
    
      case 'PORTRSK':
        $parameters['RPTNAME'] = Common::$lablearray['1303'];
        break;
    
    case 'BREAKPERACC':
        $parameters['RPTNAME'] = Common::$lablearray['463'];
        break;
    
    case 'TRIALB':
        $parameters['RPTNAME'] = Common::$lablearray['107'];
        break; 
    
    case 'PROVISION':
        Common::replace_key_function($parameters, 'pDate', 'startDate');     
        $parameters['RPTNAME'] = Common::$lablearray['1550'];
        break; 
    
    case 'INCOMEEXP':
        $parameters['RPTNAME'] = Common::$lablearray['1254'];
        break; 
    
    case 'BALANCESHEET':
        $parameters['RPTNAME'] = Common::$lablearray['1337'];
        break;
    
     case 'DISBURSEMENTS':
        $parameters['RPTNAME'] = Common::$lablearray['1234'];
        break;
    
    case 'LOANREP':
        $parameters['RPTNAME'] = Common::$lablearray['1374'];
        break;
    
    case 'PLEDGER':
    case 'PLEDGERMULTIPLE':
        $parameters['RPTNAME'] = Common::$lablearray['1046'].' '.$name;
        break;
    
    case 'SAVINTRPTS':
        $parameters['RPTNAME'] = Common::$lablearray['1432'];
        break;
    
    case 'LLCARD':
        $parameters['RPTNAME'] = Common::$lablearray['1045'];
        break;
    
    case 'DUESLN':
        $parameters['RPTNAME'] = Common::$lablearray['1283'];
        break;
    
    case 'GUARANTORS':
        $parameters['RPTNAME'] = Common::$lablearray['1497'];
        break;
    
    default:
        break;
 } 
 

    if(pdf::getInstance($rtype,$parameters['footnote'])->multiplepages){
//        pdf::getInstance($rtype)->htmlHeader ='<hr style="margin:0px;" ><div style="border: 1px solid #000000;border-radius: 0px 0px 20px 20px;">
//        <table width="100%" border="0" cellpadding="2" cellspacing="0">
//        <tr>
//              <td align="center" colspan="4"><h2 style="padding:3px;background-color:#e6f3ff;"> '.$parameters['RPTNAME'].'</h2></td>  
//
//       </tr>'; 
   }else{
//          pdf::getInstance($rtype)->htmlHeader.='<hr style="margin:0px;" ><div style="border: 1px solid #000000;border-radius: 0px 0px 20px 20px;">
//        <table width="100%" border="0" cellpadding="2" cellspacing="0">
//        <tr>
//              <td align="center" colspan="4"><h2 style="padding:3px;background-color:#e6f3ff;"> '.$parameters['RPTNAME'].'</h2></td>  
//
//       </tr>'; 
       
   }
   // check which report headers to use
    switch($parameters['code']){ 
        case 'BREAKPERACC':
        case 'TRIALB': 
        case 'INCOMEEXP':
        case 'BALANCESHEET':
        case 'DEBITCREDIT':
        case 'TRANINPERIOD':
       
          pdf::getInstance()->rtype =$rtype;
          pdf::getInstance($rtype)->htmlHeader.='<div id= "header"><table cellspacing="0" cellpadding="2" border="0" width="100%" >
            <tr><td colspan="4" align="center"><span style="-moz-border-radius:10px;color:#FFFFFF;border-radius: 10px;background:#000000;color:#FFFFFF;width:150px;padding:2px;font-size: 17px">'.$parameters['RPTNAME'].'</span></td></tr>   
           <tr>
          <td align="left">' . Common::$lablearray['1261'] . ':' . Common::changeMySQLDateToPageFormat($parameters['startDate']) . '</td>  
          <td align="left">' . Common::$lablearray['39'] . ':' . Common::changeMySQLDateToPageFormat($parameters['endDate']) . '</td>  
         <td align="left">' . Common::$lablearray['316'] . ':' . ($parameters['branch_codefr'] == '' ? Common::$lablearray['43'] : $parameters['branch_codefr']) . '</td>
        <td align="left">' . Common::$lablearray['316'] . ':' . ($parameters['branch_codeto'] == '' ? Common::$lablearray['43'] : $parameters['branch_codeto']) . '</td> 
       </tr> 
        <tr>    
            <td align="left">' . Common::$lablearray['1251'] . ':' . ($parameters['accountcodefr'] == '' ? Common::$lablearray['43'] : $parameters['accountcodefr']) . '</td>
            <td align="left">' . Common::$lablearray['1251'] . ':' . ($parameters['accountcodeto'] == '' ? Common::$lablearray['43'] : $parameters['accountcodeto']) . '</td>    
             <td align="left">' . Common::$lablearray['1111'] . ':' . ($parameters['trancodes_codefr'] == '' ? Common::$lablearray['43'] : $parameters['trancodes_codefr']) . '</td>
             <td align="left">' . Common::$lablearray['1111'] . ':' . ($parameters['trancodes_codeto'] == '' ? Common::$lablearray['43'] : $parameters['trancodes_codeto']) . '</td>
        </tr>
        <tr>                     
              <td align="left">' . Common::$lablearray['1107'] . ':' . ($parameters['costcenters_codefr'] == '' ? Common::$lablearray['43'] : $parameters['costcenters_codefr']) . '</td>
              <td align="left">' . Common::$lablearray['1082'] . ':' . ($parameters['costcenters_codeto'] == '' ? Common::$lablearray['43'] : $parameters['costcenters_codeto']) . '</td>    
              <td align="left">' . Common::$lablearray['1251'] . ':' . ($parameters['donor_codefr'] == '' ? Common::$lablearray['43'] : $parameters['donor_codefr']) . '</td>
              <td align="left">' . Common::$lablearray['1251'] . ':' . ($parameters['donor_codeto'] == '' ? Common::$lablearray['43'] : $parameters['donor_codeto']) . '</td>  
        </tr>
         <tr>
             <td align="left">' . Common::$lablearray['1096'] . ':' . ($parameters['product_prodidfr'] == '' ? Common::$lablearray['43'] : $parameters['product_prodidfr']) . '</td>
              <td align="left">' . Common::$lablearray['1096'] . ':' . ($parameters['product_prodidto'] == '' ? Common::$lablearray['43'] : $parameters['product_prodidto']) . '</td> 
             <td align="left">' . Common::$lablearray['1111'] . ':' . ($parameters['costcenters_codefr'] == '' ? Common::$lablearray['43'] : $parameters['costcenters_codefr']) . '</td>
              <td align="left">' . Common::$lablearray['1111'] . ':' . ($parameters['costcenters_codeto'] == '' ? Common::$lablearray['43'] : $parameters['costcenters_codeto']) . '</td>
          </tr>
         <tr>
             <td align="left">' . Common::$lablearray['1096'] . ':' . ($parameters['user_idfr'] == '' ? Common::$lablearray['43'] : $parameters['user_idfr']) . '</td>
            <td align="left">' . Common::$lablearray['1096'] . ':' . ($parameters['user_idto'] == '' ? Common::$lablearray['43'] : $parameters['user_idto']) . '</td>  
            <td align="left">' . Common::$lablearray['1111'] . ':' . ($parameters['currencies_id'] == '' ? Common::$lablearray['43'] : $parameters['currencies_id']) . '</td>
             <td align="left"></td>
          </tr>         
      </table></div>';
        break;

    case 'CHARTOFACCOUNTS':
        break;

    case 'MLLCARD':
        break;

    case 'LLCARD':
     
        // extra header
      
        $name = Common::getClientNames($formarvars["client_idno"]);
        
        if(!isset($formarvars["loan_number"])){
           
            $formarvars["loan_number"] ='';
            $formarvars["intType"] =''; 
            $formarvars["insType"] =''; 
         }
         
        pdf::getInstance($rtype)->htmlHeader.='<div id="header"><table width="100%" border="0" cellpadding="1" cellspacing="0"  >
          <tr><td colspan="4" align="center"><h2>'.$parameters['RPTNAME'].'</h2></td></tr>  
           <tr>
                <td align="left" >'.Common::$lablearray['1443'].': '.$name[0]['name'].'</td>
                <td align="left" >'.Common::$lablearray['611'].': '.$name[0]['client_addressphysical'].'</td>
                <td align="left">'.Common::$lablearray['1097'].': '.$formarvars["loan_number"]. '</td>
                <td align="left">'.Common::$lablearray['1096'].':'.$formarvars["product_prodid"]. '</td>
          </tr>
           <tr>
                <td align="left">'.Common::$lablearray['1100'].': ' . $formarvars["intrate"] . '</td>
                <td align="left">'.Common::$lablearray['1101'].':' .  $formarvars["loan_noofinst"] . '</td>
                <td align="left">'.Common::$lablearray['1102'].': ' . $formarvars["intType"] . '</td>
                <td align="left">'.Common::$lablearray['1103'].': ' . $formarvars["insType"]. '</td>
          </tr >
           <tr>
                <td align="left">'.Common::$lablearray['1107'].': ' . $formarvars["fund_code"] . '</td>
                <td align="left">'.Common::$lablearray['1104'].': ' . $formarvars["grace"] . '</td>
                <td align="left">'.Common::$lablearray['1105'].': ' . $formarvars["comm"] . '</td>
                <td align="left">'.Common::$lablearray['1291'].': ' . $formarvars['lamount'] . '</td>
          </tr>
        </table></div>';  
     
        break;

    case 'SAVINTRPTS':       
        break;
    
    case 'PLEDGER':
    case 'PLEDGERMULTIPLE':
    case 'PROVISION':
       pdf::getInstance($rtype)->htmlHeader.='<div id= "header"><table width="100%" border="0" cellpadding="2" cellspacing="0"  >
         <tr><td colspan="2" align="center"><h2>'.$parameters['RPTNAME'].'</h2></td></tr>
        <tr>
          <td align="left"> '.Common::$lablearray['1261'].': <b>'.Common::changeMySQLDateToPageFormat($parameters['startDate']).'</b></td>  
          <td align="right"> '.Common::$lablearray['484'].': <b>'.Common::changeMySQLDateToPageFormat($parameters['endDate']).'</b></td>       
        </tr>        
        <tr>         
          <td align="center" colspan="2"> '.Common::$lablearray['316'].': <b>'.($parameters['branch_code']==''? Common::$lablearray['43']:$parameters['branch_code']).'</b></td>       
        </tr>  
       </table></div>';
        break;
    default: 
        
        pdf::getInstance($rtype)->htmlHeader.='<table width="100%" border="0" cellpadding="2" cellspacing="0"  >
         <tr><td colspan="4" align="center" style="font-size:25px;">'.$parameters['RPTNAME'].'</td></tr>
         <tr><td colspan="4" align="center">'.($parameters['name']??'').'</td></tr>
         <tr>
          <td align="left" colspan="2"> '.Common::$lablearray['483'].': <b>'.Common::changeMySQLDateToPageFormat($parameters['startDate']).'</b></td>  

         <td align="left" colspan="2"> '.Common::$lablearray['484'].': <b>'.Common::changeMySQLDateToPageFormat($parameters['endDate']).'</b></td>        
        </tr>
        <tr>
           
          <td align="left"> '.Common::$lablearray['316'].': <b>'.($parameters['branch_code']==''? Common::$lablearray['43']:$parameters['branch_code']).'</b></td>
         <td align="left"> '.Common::$lablearray['1251'].': <b>'.($parameters['currencies_id']==''? Common::$lablearray['43']:$parameters['currencies_id']).'</b></td>
         <td align="left"> '.Common::$lablearray['1081'].': <b>'.($parameters['client1_code']==''? Common::$lablearray['43']:$parameters['client1_code']).'</b></td>        
         <td align="left"></td> 
        </tr>
         <tr>
          
              <td align="left"> '.Common::$lablearray['1111'].': <b>'.($parameters['user_id']==''? Common::$lablearray['43']:$parameters['user_usercode']).'</b></td>
              <td align="left"> '.Common::$lablearray['1083'].': <b>'.($parameters['client2_code']==''? Common::$lablearray['43']:$parameters['client2_code']).'</b></td>  
              <td align="left"> '.Common::$lablearray['1253'].': <b>'.($parameters['client3_code']==''? Common::$lablearray['43']:$parameters['client3_code']).'</b></td>  
              <td align="left"></td> 
        </tr>
          
         <tr>  
               <td align="left"> '.Common::$lablearray['1260'].': <b>'.$d_esc_order.'</b></td>   
               <td align="left"> '.Common::$lablearray['1108'].': <b>'.(isset($parameters['loancategory1_code'])? Common::$lablearray['43']:$parameters['loancategory1_code']).'</b></td>  
               <td align="left"> '.Common::$lablearray['1109'].': <b>'.(isset($parameters['loancategory2_code'])? Common::$lablearray['43']:$parameters['loancategory2_code']).'</b></td>
               <td align="left"></td>  
        </tr>
         <tr>
             <td align="left"> '.Common::$lablearray['1262'].': <b>'.$d_esc_grp.'</b></td>  
              <td align="left"> '.Common::$lablearray['1096'].': <b>'.($parameters['product_prodid']==''? Common::$lablearray['43']:$parameters['product_prodid']).'</b></td>
              <td align="left"> '.Common::$lablearray['1107'].': <b>'.($parameters['fund_code']==''? Common::$lablearray['43']:$parameters['fund_code']).'</b></td>
              <td align="left"> '.Common::$lablearray['1246'].': <b>'.($parameters['areacode_code']==''? Common::$lablearray['43']:$parameters['areacode_code']).'</b></td>       
        </tr>
        <tr>  
             <td align="left"></td>  
              <td align="left"> '.Common::$lablearray['1259'].':'.($parameters['bussinesssector_code']==''? Common::$lablearray['43']:$parameters['bussinesssector_code']).'</b></td>
              <td align="left"> '.Common::$lablearray['1082'].':'.($parameters['costcenters_code']==''? Common::$lablearray['43']:$parameters['costcenters_code']).'</b></td>   
              <td align="left"></td>
        </tr></table>';
          break;
    }