<?php
include_once('connectionfactory.php');
require_once('productconfig.php');
require_once('common.php');
require_once('DataTable.php');

class NewGrid
{

    public static $connObj;
    public static $searchcatparam = '';
    public static $grid_id = '';
    public static $actionlinks = '';
    public static $primaryKey = '';
    public static $request; // = 'client_idno';
    public static $sSQL = '';
    public static $fieldlist = array();
    public static $columntitle = array();
    public static $keyfield = '';
    public static $order = "";
    public static $tableTitle;

    /* DESCRIPTION:  THIS FUNCTION IS TO INITIALISE THE DATATABLE */
    public static function initDatatable(
        $actionlinks = '',
        $keyfield = '',
        $tablecolumntitle = array(),
        $searchcatparam = '',
        $sSQL = '',
        $fieldlist = array(),
        $searchableFields = array(),
        $pagerequest = array(),
        $grid_id = '',
        $fetchdata = false
    ) {


        self::$actionlinks = $actionlinks;
        self::$keyfield = $keyfield;
        self::$columntitle = $tablecolumntitle;
        self::$searchcatparam = $searchcatparam;
        self::$sSQL = $sSQL;

        if (count($searchableFields) > 0):
            self::$fieldlist = $fieldlist;
        endif;

        if (count($searchableFields) > 0):
            Datatable::$searchable = $searchableFields;
        endif;

        self::$request = $pagerequest;
        self::$grid_id = '';


        return self::getData();
    }

    
    public static function getData()
    {

        try {

            if (count(Datatable::$columns) == 0):
                Datatable::prepareFieldList(self::$fieldlist);
            endif;

            self::$request['columntitle'] = self::$columntitle;
            self::$request['columns'] = self::$fieldlist;
            self::$request['actionlinks'] = self::$actionlinks;
            Datatable::$fieldlist = self::$fieldlist;
            Datatable::$order = self::$order;
            Datatable::$keyfield = self::$keyfield;
            Datatable::$sSQL = self::$sSQL;
            Datatable::$tableTitle = self::$tableTitle;           

            $data = Datatable::simple(self::$request);

            $json = Common::createResponse('data', '', $data);

            return $json;
        } catch (Exception $e) {
            return Common::createResponse('err', $e->getMessage());
        }
    }
}
