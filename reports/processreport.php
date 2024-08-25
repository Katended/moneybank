<?php
session_start(); 
require_once('../includes/application_top.php');
$arr = $_POST['pageparams']['fields']??array();
$parameters = $_POST['pageparams']['parameters'];

 try {
     
    $report_selected_fieldlist_array = array();
    $key=''; /// has to be intialised else it causes a bug  
    //get selected fields from report
    array_walk_recursive($arr, function($bv, $bk) use($key,&$report_selected_fieldlist_array){
        if($bk=='value'){ 
            array_push($report_selected_fieldlist_array, $bv);
        }

    });
    
    $db_fields = unserialize($_SESSION['db_report_columns']);
       
    // selected field versus database fields
    $final_selected_fields_array = array_intersect_key($db_fields,array_flip($report_selected_fieldlist_array));

    // main the order in which the filed are selected ont he interface
    $final_selected_fields_array =  array_replace(array_flip($report_selected_fieldlist_array), $final_selected_fields_array);
      
    $report_sub_fieldlist = array();
   
    $rpt= Common::searchArray($parameters, 'name', 'code');
    
    $_SESSION['rpt'] = $rpt['value'];
    
    $footnote = Common::searchArray($parameters, 'name', 'footnote');
    
    unset($_SESSION['report_columns']);
    unset($_SESSION['parameters']);     
    
    $parameters[] = array('name'=>'plang','value'=>$_SESSION['P_LANG']??'EN');
    //endDate,startDate
    // format dates
    foreach ($parameters as $bkey => $bval) {
        if (($bval['name'] == 'startDate' || $bval['name'] == 'endDate') && ($parameters[$bkey]['value'] != "")) {
            $parameters[$bkey]['value'] = Common::changeDateFromPageToMySQLFormat($parameters[$bkey]['value'], false);
        }
    }
    
    // DO TO: Add code to add extra parametes that you need to be bound when calling the stored procedure
    // The are paramers that re not part of the report colmns
    // Also add mandory fields/columns. Column that MUST be selected

    switch ($rpt['value']) {
        case 'CLIENTRPTS':
            $parameters[] = array('name'=>'group_by','value'=>'1093');
            $final_selected_fields_array['1093'] ='client_idno';
            break;
        
        case 'GETTRAN':
            
           // $objects = (array) json_decodeData($_POST['pageparams'], true);
          //  $array1 = Common::convertobjectToArray($objects['pageinfo']);
           // $formdata = Common::array_flatten($array1);
           
            
            
        //    Common::replace_key_function($parameters, 'searchterm', 'tcode');
       //     $parameters = array(Common::array_flatten($parameters));
            $final_selected_fields_array['301'] ='transactioncode';
            $final_selected_fields_array['296'] ='chartofaccounts_accountcode';
            $final_selected_fields_array['264'] ='generalledger_description';
            $final_selected_fields_array['289'] ='generalledger_debit';
            $final_selected_fields_array['297'] ='generalledger_credit';
            $final_selected_fields_array['316'] ='branch_code';
            
           // $parameters [] = array('branch_code'=>'');
            // Common::addKeyValueToArray($parameters, 'branch_code', '');
            
            break;
        
        case 'TRIALB':
        case 'INCOMEEXP':
            
//               $report_db_fieldlist_array['373'] = 'opening_balances';
//                $report_db_fieldlist_array['1325'] = 'period_balances';
//                $report_db_fieldlist_array['465'] = 'closing_balances';
                
            // check see if mandatory field are selected
            if(empty($final_selected_fields_array['296']) && $rpt['value']=='INCOMEEXP'){
                $final_selected_fields_array['296'] ='account';
            }
              
            // opening balances
            if(!empty($final_selected_fields_array['373'])){
               
                unset($final_selected_fields_array['373']);
                
              
                $final_selected_fields_array['1329'] ='odebit'; // Opening Debit
                $final_selected_fields_array['1330'] ='ocredit'; // Opening Credit
            }
            
            // period balances
            if(!empty($final_selected_fields_array['1325'])){
                
                 unset($final_selected_fields_array['1325']);
                 
                $final_selected_fields_array['1331'] ='pdebit'; // Period Debits 
                $final_selected_fields_array['1332'] ='pcredit'; // Period Credits
            }
            
             // closing balances
            if(!empty($final_selected_fields_array['465'])){
                
                unset($final_selected_fields_array['465']);
                
                $final_selected_fields_array['1333'] ='cdebit'; // Period Debits 
                $final_selected_fields_array['1334'] ='ccredit'; // Period Credits
                
            }
            
            break;
            
        case 'PLEDGER':          
        case 'PLEDGERMULTIPLE':  
            $parameters[] = array('name'=>'plang','value'=>SESSION['P_LANG']);
         
            $report_db_fieldlist_array['1093'] = 'client_idno';
            $final_selected_fields_array['299'] ='voucher';
            $report_db_fieldlist_array['1208'] = 'ttcode';
            // $report_db_fieldlist_array['264'] = 'descr';
            
            if(empty($final_selected_fields_array['1382'])){
                $final_selected_fields_array['1208'] = 'ttcode';
                $final_selected_fields_array['299'] ='voucher';
              //  $final_selected_fields_array['264'] = 'descr';
            }
            
            break;
        case 'BALANCESHEET':            
        
              // set mandatory fields if non is selected
            if(!empty($final_selected_fields_array['443']) && !empty($final_selected_fields_array['1338'])){
              $final_selected_fields_array['1338'] ='account_label'; // Account Name  
            }
            
            if(!empty($final_selected_fields_array['1335']) && !empty($final_selected_fields_array['1336'])){
              $final_selected_fields_array['1336'] ='clast'; // As at report end date
            } 
                        
            $parameters[] = array('name'=>'plang','value'=>SESSION['P_LANG']);
            break;
            
        case 'PROVISION': 
            $parameters[] = array('name'=>'vpost','value'=>0);
            $parameters[] = array('name'=>'currencies_id','value'=>'');            
            break;
        
        case 'SAVBALRPT':
            $parameters[] = array('name'=>'startDate','value'=>'');
            break;
        
        case 'BREAKPERACC':  
            // check see if mandatory field are selected
            if(empty($final_selected_fields_array['296'])){
                $final_selected_fields_array['296'] ='account';
            }           
           break;
           
        case 'DEBITCREDIT':           
            // WE WANT TO GROUP BY TCODE
            $parameters[] = array('name'=>'group_by','value'=>'1524');
            // check see if mandatory field are selected
            if(empty($final_selected_fields_array['1524'])){
                $final_selected_fields_array['1524'] ='tcode';
            } 
            break;
           
        case 'PORTRSK':

            $class1a = Common::searchArray($parameters, 'name', 'class1a');
            if(empty($class1a)):
                $class1a = array("name"=>"class1a","value"=>"1");
                array_push($parameters, $class1a);
            endif;
           
            $class1b = Common::searchArray($parameters, 'name', 'class1b');
            if(empty($class1b)):
                $class1b = array("name"=>"class1b","value"=>"60");
                array_push($parameters, $class1b);
            endif;

            $class2a = Common::searchArray($parameters, 'name', 'class2a');
            if(empty($class2a)):
                $class2a = array("name"=>"class2a","value"=>"61");
                array_push($parameters, $class2a);
            endif;

            $class2b = Common::searchArray($parameters, 'name', 'class2b');
            if(empty($class2b)):
                $class2b = array("name"=>"class2b","value"=>"90");
                array_push($parameters, $class2b);
            endif;
                        
            $class3a = Common::searchArray($parameters, 'name', 'class3a');
            if(empty($class3a)):
                $class3a = array("name"=>"class3a","value"=>"91");
                array_push($parameters, $class3a);
            endif;

            $class3b = Common::searchArray($parameters, 'name', 'class3b');
            if(empty($class3b)):
                $class3b = array("name"=>"class3b","value"=>"120");
                array_push($parameters, $class3b);
            endif;

            $class4a = Common::searchArray($parameters, 'name', 'class4a');
            if(empty($class4a)):
                $class4a = array("name"=>"class4a","value"=>"121");
                array_push($parameters, $class4a);
            endif;

            $class4b = Common::searchArray($parameters, 'name', 'class4b');
            if(empty($class4b)):
                $class4b = array("name"=>"class4b","value"=>"150");
                array_push($parameters, $class4b);
            endif;

            $class5a = Common::searchArray($parameters, 'name', 'class5a');
            if(empty($class5a)):
                $class5a = array("name"=>"class5a","value"=>"151");
                array_push($parameters, $class5a);
            endif;
            
            $class5b = Common::searchArray($parameters, 'name', 'class5b');
            if(empty($class5b)):
                $class5b = array("name"=>"class5b","value"=>"180");
                array_push($parameters, $class5b);
            endif;
            
            $class6a = Common::searchArray($parameters, 'name', 'class6a');
            if(empty($class6a)):
                $class6a = array("name"=>"class6a","value"=>"181");
                array_push($parameters, $class6a);
            endif;

            $class6b = Common::searchArray($parameters, 'name', 'class6b');
            if(empty($class6b)):
                $class6b = array("name"=>"class6b","value"=>"211");
                array_push($parameters, $class6b);
            endif;

            $class7 = Common::searchArray($parameters, 'name', 'class7');
            if(empty($class7)):
                $class7 = array("name"=>"class7","value"=>($class6b['value']+1));
                array_push($parameters, $class7);
            endif;


            $report_sub_fieldlist['1296'] = '<br>('.$class1a['value'] . ' - ' . $class1b['value'].')';
            $report_sub_fieldlist['1297'] = '<br>('.$class2a['value'] . ' - ' . $class2b['value'].')';
            $report_sub_fieldlist['1298'] = '<br>('.$class3a['value'] . ' - ' . $class3b['value'].')';
            $report_sub_fieldlist['1299'] = '<br>('.$class4a['value'] . ' - ' . $class4b['value'].')';
            $report_sub_fieldlist['1300'] = '<br>('.$class5a['value'] . ' - ' . $class5b['value'].')';
            $report_sub_fieldlist['1301'] = '<br>('.$class6a['value'] . ' - ' . $class6b['value'].')';
            $report_sub_fieldlist['1735'] = '<br>('.$class7['value'].'+)';
            break;
        
        default:
            break;
    }
       
    $_SESSION['report_sub_fieldlist'] = serialize($report_sub_fieldlist);
    
    // initialise the field to group by/Order by
    // fieldname is assigned to $grouporder
   
    $grouporder= array();
    
     foreach ($parameters as $bkey => $bval) {
        if ($bval['name'] == 'group_by' || $bval['name'] == 'order_by') {
            // echo $parameters[$bkey]['value'];
            if (isset($bval['value']) && isset($parameters[$bkey]['value'])) {

                if (!empty($parameters[$bkey]['value'])) {
                    $grouporder[$bval['name']] = $db_fields[$parameters[$bkey]['value']];
                    $parameters[$bkey]['value'] = $db_fields[$parameters[$bkey]['value']];
                }else{
                    $grouporder[$bval['name']] = '';
                  // $parameters[$bkey]['value'] = $db_fields[$parameters[$bkey]['value']]; 
                }
            }
        }
    }
  
    $_SESSION['parameters'] = serialize($parameters);
    $_SESSION['grouporder'] = serialize($grouporder);
    $_SESSION['report_columns'] = serialize($final_selected_fields_array);    


 //  echo '11111';
} catch (Exception $e) {    
    echo '000000'; 
}
exit();