<?php
include_once('connectionfactory.php');
require_once('productconfig.php');
require_once('common.php');
require_once('DataTable.php');

class NewGrid {

    Public static $connObj;
    Public static $searchcatparam = '';
    Public static $grid_id = '';
    Public static $actionlinks = '';
    Public static $primaryKey = 'client_idno';
    Public static $request; // = 'client_idno';
    Public static $sSQL ='';
    Public static $fieldlist=array();
    Public static $columntitle=array();
    Public static $keyfield ='';
    Public static $order ="";
    
    /* DESCRIPTION:  THIS FUNCTION IS TO INITIALISE THE DATATABLE */
    public static function initDatatable(
            $actionlinks = '',
            $keyfield = '',
            $tablecolumntitle = array(),
            $searchcatparam = '',
            $sSQL='',
            $fieldlist = array(),
            $searchableFields = array(),
            $pagerequest = array(),
            $grid_id='',
            $fetchdata =false) {
        
        
            self::$actionlinks = $actionlinks;
            self::$keyfield = $keyfield; 
            self::$columntitle = $tablecolumntitle;
            self::$searchcatparam = $searchcatparam;  
            self::$sSQL = $sSQL;
            
            if(count($searchableFields)>0):
                self::$fieldlist = $fieldlist;
            endif;
            
            if(count($searchableFields)>0):
              Datatable::$searchable = $searchableFields;  
            endif;
            
            self::$request = $pagerequest;  
            self::$grid_id = '';
 
            if($fetchdata):                
                return self::getData();                
            else:
                return self::generateDatatableHTML();
            endif;       
        
    }
    
    /* DESCRIPTION:  THIS FUNCTION IS USED GENERATE HTML FOR A DATATABLE */
    public static function generateDatatableHTML() {

        $html = "<input type='hidden' value='" . self::$searchcatparam . "' id='CODE'>"
        . "<div id='expo_bar_".self::$grid_id ."' style='width:auto;text-align: center;padding:1px;margin: auto;'>".self::$actionlinks."</div>"
        . "<table id='".self::$grid_id ."' border='0' class='fancyTable' cellspacing='0' cellspacing='0' style='width:auto;'>"
        . "<thead>"
        . "<tr>"
        . "<th></th><th>"
        .implode("</th><th>",self::$columntitle)
        . "</th></tr>"
        . "</thead>"
        . "<tfoot>"
        . "<tr>"             
        . "<th></th><th>"
        .implode("</th><th>",self::$columntitle)
        . "</th></tr>"
        . "</tfoot>"
        . "</table>";
                
        return $html;
    }

    public static function getData() {

      //  $fieldlist = array('client_idno', 'client_surname', 'client_firstname', 'client_middlename');
        if(count(Datatable::$columns)==0 ):
            Datatable::prepareFieldList(self::$fieldlist);
        endif;
        
        Datatable::$fieldlist = self::$fieldlist;
        Datatable::$order = self::$order;
        Datatable::$keyfield = self::$keyfield; 
        Datatable::$sSQL = self::$sSQL;
        self::$request['columntitle']= self::$columntitle;
        self::$request['columns']= self::$fieldlist;
        self::$request['actionlinks']= self::$actionlinks;
   
        $json = Datatable::simple(self::$request);

        return $json ;
    }

}
