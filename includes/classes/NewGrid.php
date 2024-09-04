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

        if ($fetchdata):
            return self::getData();
        else:
            return self::generateDatatableHTML();
        endif;
    }

    /* DESCRIPTION:  THIS FUNCTION IS USED GENERATE HTML FOR A DATATABLE */
    public static function generateDatatableHTML()
    {

        $html = "<input type='hidden' value='" . self::$searchcatparam . "' id='CODE'>"
        . "<div id='expo_bar_" . self::$grid_id . "' style='width:auto;text-align: center;padding:1px;margin: auto;'>" . self::$actionlinks . "</div>"
        . "<table id='" . self::$grid_id . "' border='0' class='fancyTable' cellspacing='0' cellspacing='0' style='width:auto;'>"
        . "<thead>"
        . "<tr>"
        . "<th></th><th>"
        . implode("</th><th>", self::$columntitle)
            . "</th></tr>"
            . "</thead>"
            . "<tfoot>"
            . "<tr>"
            . "<th></th><th>"
        . implode("</th><th>", self::$columntitle)
            . "</th></tr>"
            . "</tfoot>"
            . "</table>";

        return $html;
    }

    public static function getData()
    {

        try {

            if (count(Datatable::$columns) == 0):
                Datatable::prepareFieldList(self::$fieldlist);
            endif;

            Datatable::$fieldlist = self::$fieldlist;
            Datatable::$order = self::$order;
            Datatable::$keyfield = self::$keyfield;
            Datatable::$sSQL = self::$sSQL;
            self::$request['columntitle'] = self::$columntitle;
            self::$request['columns'] = self::$fieldlist;
            self::$request['actionlinks'] = self::$actionlinks;

            $data = Datatable::simple(self::$request);

            $json = Common::createResponse('data', '', $data);

            return $json;
        } catch (Exception $e) {
            return Common::createResponse('err', $e->getMessage());
        }
    }
}
