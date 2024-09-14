<?php
$_parent = isset($_parent) ? $_parent : '';
if (dirname(__FILE__) == DIR_FS_DOCUMENT_ROOT || $_parent == 'dashboard.php') {
    $fpath = '';
} else {

    $fpath = '../';
}
$lablearray = getlables("218,1166,650");

$modules_array = $_SESSION['modules'];
$modules = array();

array_walk_recursive($modules_array, function ($v, $k) use ($key, &$modules) {
    array_push($modules, $v);
});

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML>

<HEAD>
    <TITLE></TITLE>
    <meta charset="UTF-8">
    <LINK href="stylesheet.css" type=text/css rel=stylesheet>
    <LINK href="baloon.css" type=text/css rel=stylesheet>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script language="javascript" type='text/javascript'>
        var jsonObj = '';
        var htmlString = "";
        var searchtext = '';

        function replaceBackForwardSlash(string, replacement) {
            return string.replace('/\\\//g', replacement);
        }

        function resetForm(id) {
            $('form').each(function() {
                this.reset();
            });
        }

        function EnterNumericOnly(e, elementid) {
            var keynum;
            var keychar;
            var numcheck;
            var haystack;
            var needle = ".";

            if (typeof(e) === 'undefined' || e == null) {
                return;
            }


            if (window.event) // IE
            {
                keynum = e.keyCode
            } else if (e.which) // Netscape/Firefox/Opera
            {
                keynum = e.which
            }

            if (keynum == 8 || typeof(keynum) === 'undefined') {
                return;
            }
            //alert(keynum);
            keychar = String.fromCharCode(keynum)
            numcheck = /\d/
            haystack = document.getElementById(elementid).value;

            // see user does not input more then i full stop
            if (haystack.split(needle).length > 1) {
                //return numcheck.test(keychar);	
            }

            if (keynum == 46) {
                return;
            }

            if (!numcheck.test(keychar) && keynum != 44) {
                displaymessage('', 'Please enter only numeric charaters!', 'INFO')
                return numcheck.test(keychar);
            }

            if (keynum == 44) {
                return keychar
            } else {
                return numcheck.test(keychar)
            }


        }

        function returnClientType(str) {
            var ctype = '';

            switch (str) {
                case "I":
                case "IND":
                    ctype = "INDSAVACC";
                    break;

                case "G":
                case "GRP":
                    ctype = "GRPSAVACC";
                    break;

                case "B":
                case "BUS":
                    ctype = "BUSSAVACC";
                    break;

                case "GM":
                    alert('TO DO: group members')
                    break;

                default:

                    break;
            }
            return ctype;
        }

        function openPopupListWindow(fileName, width, height) {
            // To specify the window characteristics edit the "features" variable below:
            // width - width of the window
            // height - height of the window
            // scrollbar - "yes" for scrollbars, "no" for no scrollbars
            // left - number of pixels from left of screen
            // top - number of pixels from top of screen

            // return;                

            features = "width=" + width + ",height=" + height + ",left=100,top=20,resizable=1, scrollbars=1";
            listwindow = window.open(fileName, "listWin", features);
            listwindow.focus();
        }


        // check see if field empty
        function IsNullEmptyField(element_id, message) {
            if (document.getElementById(element_id).value == "") {
                displaymessage($(this).parents("form").attr("id"), message, "INFO.");
                return false;
            }

            return true;
        }
        // check see if field empty
        function IsNullEmptyField2(fieldname) {
            if (document.getElementById(fieldname).value == "") {
                return false;
            }
            return true;
        }


        function displaymessage(elementId, message, state) {
            let cssClass = 'message success';

            // Adjust elementId if it's empty
            if (elementId === "") {
                elementId = "body";
            } else {
                elementId = "#" + elementId;
            }

            // Define CSS classes based on state
            switch (state.toUpperCase()) {
                case 'MSG':
                case 'S':
                case 'OK':
                    cssClass = 'success information';
                    break;
                case 'INFO':
                    cssClass = 'info information';
                    break;
                case 'WAR':
                case 'WARN':
                    cssClass = 'warning';
                    break;
                case 'ERR':
                case 'ERROR':
                    cssClass = 'error information';
                    break;
                default:
                    break;
            }

            // Append the message div to the specified element
            $(elementId).append(`<div class='notediv ${cssClass}'>${message}<div class='close-btn'>&nbsp;x</div></div>`);

            // If the state suggests fading out, apply fadeOut effect after a delay
            const fadeOutDelay = (state.toUpperCase() === 'ERR' || state.toUpperCase() === 'ERROR') ? 9000 : 6000;
            $(".notediv").last().fadeOut(fadeOutDelay, function() {
                $(this).detach();
            });
        }


        //keyparam: Id of the lement to be loaded
        //frm: the form form which the data is coming from
        //pageparams: f there is predefined page data
        // urlpage; URL to execute

        var globalParameterStorage = {
            frm: null,
            keyparam: null,
            action: null,
            pageparams: null,
            urlpage: null,
            keyparam: null,
            search: {
                value: ''
            },
            ajaxdatadiv: null
        };


        const showValues = (frm, ajaxdatadiv, action, pageparams, urlpage, keyparam, search, canInvokeCallback) => {
            const dfrd3 = jQuery.Deferred();

            // Set default page parameters
            if (typeof pageparams === 'undefined' && action !== 'edit') {
                const formToSerialize = frm ? $("#" + frm) : $("form");
                pageparams = JSON.stringify(formToSerialize.serializeArray());
            }

            // Store global parameters
            Object.assign(globalParameterStorage, {
                frm: frm, // Replace with new frm value
                ajaxdatadiv: ajaxdatadiv, // Replace with new ajaxdatadiv value
                action: action, // Replace with new action value
                pageparams: pageparams, // Replace with new pageparams value
                urlpage: urlpage, // Replace with new urlpage value
                keyparam: keyparam, // Replace with new keyparam value
                search: {
                    value: search
                }
            });

            // Default URL page
            if (!urlpage) {
                urlpage = 'addedit.php';
            }

            $("#ajaxSpinnerImage").show();

            $.post(urlpage, {
                    frm: globalParameterStorage.frm,
                    pageparams: globalParameterStorage.pageparams,
                    keyparam: globalParameterStorage.keyparam,
                    frmid: globalParameterStorage.frm,
                    action: globalParameterStorage.action,
                    ajaxdatadiv: globalParameterStorage.ajaxdatadiv,
                    search: globalParameterStorage.search.value
                })
                .always(data => {



                    try {

                        if (typeof data === 'object' && data.status === '500') {
                            window.location = "<?php echo HTTP_SERVER; ?>"; // Redirect on server error
                            return;
                        }

                        processResponseData(data, frm, action, ajaxdatadiv, dfrd3);

                        // Call showValues again if action is 'edit' or 'add'
                        if (canInvokeCallback) {
                            showValues(frm, ajaxdatadiv, action, pageparams, 'load.php', keyparam, search);
                        }

                        $("#ajaxSpinnerImage").hide();
                        return dfrd3.resolve();
                    } catch (e) {
                        return dfrd3.resolve();
                        console.error('Invalid JSON:', e);
                    }


                });

            return dfrd3.promise();
        };

        const processResponseData = (data, frm, action, ajaxdatadiv, dfrd3) => {

            var trimmedData = data.trim();

            try {
                //debugger;
                var jsonObj = isValidJsonString(trimmedData);

                switch (jsonObj.status) {
                    case 'data':
                        // Handle table data
                        handleDataTable(jsonObj.table, ajaxdatadiv);
                        break;

                    case 'ok':
                        // Display success message
                        displaymessage(frm, "<?php echo $lablearray['218']; ?>", jsonObj.status);
                        break;

                    case 'form':
                        // Load form
                        handleFormActions(action, frm, jsonObj, ajaxdatadiv);
                        break;

                    case 'err':
                    case 'war':
                        // Display error message
                        displaymessage(frm, jsonObj.message, jsonObj.status);
                        break;


                    default:
                        // Handle unexpected status
                        console.log('Unexpected status:', jsonObj.status);
                        break;
                }

                dfrd3.resolve();


            } catch (e) {
                console.log('Parsing error:', e);
            }
        };


        function isValidJsonString(str) {
            if (typeof str !== "string") {
                return null; // Not a string
            }

            try {
                return JSON.parse(str);
            } catch (error) {
                return null;
            }
        };




        const handleFormActions = (action, frm, jsonObj, ajaxdatadiv) => {
            switch (action) {
                case "loadform":
                case "edit":
                case "add":
                    if (data.includes("formObj")) {
                        eval(data);
                    } else {
                        populateForm(frm, jsonObj.data);
                    }
                    break;
                case "eval":
                    eval(data);
                    break;
                case "search":
                case "loadelement":
                default:
                    handleDataTable(data, ajaxdatadiv);
                    break;
            }
        };

        let dataTableInstances = []; // Array to hold multiple DataTable instances

        const handleDataTable = (tableData, ajaxdatadiv) => {
            if (tableData instanceof Object && tableData !== null) {
                // Initialize the DataTable and store the instance
                const tableInstance = $('#grid_' + ajaxdatadiv).DataTable({
                    fixedHeader: true,
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                    responsive: true,
                    scrollResize: true,
                    searchDelay: 100,
                    scrollX: true,
                    order: 3,
                    scrollY: 100,
                    data: tableData.data,
                    columns: tableData.columns,
                    scroller: {
                        loadingIndicator: true
                    },
                    columnDefs: [{
                        defaultContent: "-",
                        targets: "_all",
                        width: '15px',
                        targets: 0,
                    }],
                    orderable: false,
                    pageLength: 20,
                    scrollCollapse: true,
                    bDestroy: true,
                });

                dataTableInstances.push(tableInstance); // Store the instance in the array

                configureDataTableSearch(tableInstance);
                // configureRowSelection(tableInstance, ajaxdatadiv);
            } else {
                tableData = tableData.replace("\\x3C", "<");
                $("#" + ajaxdatadiv).html(tableData);
            }
        };

        // Function to destroy all DataTable instances
        const destroyAllDataTables = () => {
            dataTableInstances.forEach((instance) => {
                if (instance) {
                    const tableNode = instance.table().node(); // Get the table node

                    instance.destroy(); // Destroy the DataTable instance

                    // Clear the table body and optionally reset headers
                    $(tableNode).empty(); // Clear the table
                    $(tableNode).append('<thead></thead><tbody></tbody>'); // Optionally reset headers
                }
            });

            dataTableInstances = []; // Clear the array after destruction
        };

        const configureDataTableSearch = (table) => {
            $(".dataTables_filter").unbind().bind("keyup", function(e) {
                e.preventDefault();
                if (e.keyCode === 13) {
                    globalParameterStorage.search.value = this.querySelector('input').value;
                    showValues(
                        globalParameterStorage.frm,
                        globalParameterStorage.ajaxdatadiv,
                        globalParameterStorage.action,
                        globalParameterStorage.pageparams,
                        globalParameterStorage.urlpage,
                        globalParameterStorage.keyparam,
                        globalParameterStorage.search
                    );
                }
                return false;
            });

            $(table.table().container()).on('keyup', 'tfoot input', function() {
                table.column($(this).data('index')).search(this.value).draw();
            });
        };

        // const configureRowSelection = (table, ajaxdatadiv) => {
        //     const tbody = $(`#grid_${ajaxdatadiv} tbody`);

        //     tbody.on('click', 'tr', function(event) {
        //         const target = $(event.target);

        //         // Check if the target is not the checkbox itself
        //         if (!target.is('.row-checkbox')) {
        //             const checkbox = $(this).find('.row-checkbox');

        //             if (checkbox.length) {
        //                 // Toggle the class based on the checkbox's checked state
        //                 $(this).addClass('selected-row');
        //                 checkbox.trigger('click');
        //                 checkbox.checked = true;

        //             }
        //         }
        //     });
        // };

        function SelectItemInList(elementid, selectedText) {
            var list = document.getElementById(elementid);
            var items = list.options;
            for (var i = 0; i < list.options.length; i++) {
                if (list.options[i].value == selectedText) {
                    document.getElementById(elementid).selectedIndex = i;
                    break;
                }
            }
            return;
        }

        // Function to reset all forms on the page      
        function resetAllForms() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => form.reset());
        }

        function populateForm(frm, jobj) {

            var dfrd1 = $.Deferred();

            if (jQuery.type(jobj) === "undefined") {
                return;
            }

            $.each(jobj, function(key, value) {

                var $ctrl = $('#' + frm + ' [id=' + key + ']');

                switch ($ctrl.prop('type')) {
                    case "text":
                    case "hidden":
                        $ctrl.val(value);
                        break;

                    case "select-one":
                        $ctrl.val(value);
                        break;

                    case "us-date":
                        w2utils.date(new Date());
                        break;

                    case "radio":
                    case "checkbox":
                        $ctrl.each(function() {

                            if ($(this).prop('value') == value) {
                                $(this).prop("checked", true);
                            } else {
                                $(this).prop("checked", false);
                            }

                        });
                        break;

                    default:
                        $("#" + key).html(value);
                        //$ctrl.val(value); 
                        break;
                }

            });

            dfrd1.resolve();
            return dfrd1.promise();

        }
    </script>

    <script type="text/javascript" src="<?php echo $fpath; ?>includes/javascript/jquery-3.3.1.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $fpath; ?>includes/javascript/jquery-ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $fpath; ?>includes/javascript/dataTables.jqueryui.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $fpath; ?>includes/javascript/buttons.jqueryui.min.css" />


    <script type="text/javascript" src="<?php echo $fpath; ?>includes/javascript/datatables.js"></script>
    <script type="text/javascript" src="<?php echo $fpath; ?>includes/javascript/dataTables.jqueryui.min.js"></script>
    <script type="text/javascript" src="<?php echo $fpath; ?>includes/javascript/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="<?php echo $fpath; ?>includes/javascript/buttons.jqueryui.min.js"></script>
    <script type="text/javascript" src="<?php echo $fpath; ?>includes/javascript/jszip.min.js"></script>
    <script type="text/javascript" src="<?php echo $fpath; ?>includes/javascript/pdfmake.min.js"></script>
    <script type="text/javascript" src="<?php echo $fpath; ?>includes/javascript/vfs_fonts.js"></script>
    <script type="text/javascript" src="<?php echo $fpath; ?>includes/javascript/buttons.html5.min.js"></script>
    <script type="text/javascript" src="<?php echo $fpath; ?>includes/javascript/buttons.print.min.js"></script>
    <script type="text/javascript" src="<?php echo $fpath; ?>includes/javascript/buttons.colVis.min.js"></script>
    <script type="text/javascript" src="<?php echo $fpath; ?>includes/javascript/dataTables.cellEdit.js"></script>
    <script type="text/javascript" src="<?php echo $fpath; ?>includes/javascript/dataTables.fixedColumns.js"></script>
    <script type="text/javascript" src="<?php echo $fpath; ?>includes/javascript/fixedColumns.dataTables.js"></script>


    <script type="text/javascript" src="<?php echo $fpath; ?>includes/javascript/jquery-ui-1.8.16.custom.min.js"></script>
    <script src="<?php echo $fpath; ?>includes/javascript/velocity.js" type="text/javascript"></script>

    <link rel="stylesheet" href="<?php echo $fpath; ?>includes/dojo/dijit/themes/claro/claro.css" media="screen" type="text/css">
    <script src="<?php echo $fpath; ?>includes/dojo/dojo/dojo.js" data-dojo-config="async:true, parseOnLoad:true" type="text/javascript">
    </script>
    <script type="text/javascript">
        var vFloatingPane;
        require(["dojo/dnd/Moveable", "dojo/dom", "dojo/dom-style", "dojo/on", "dijit/registry", "dojo/ready", "dijit/MenuBar", "dijit/PopupMenuBarItem", "dijit/Menu", "dijit/MenuItem", "dijit/DropDownMenu", "dijit/MenuSeparator", "dijit/form/Button", "dijit/layout/TabContainer", "dijit/layout/BorderContainer", "dijit/layout/ContentPane", "dojo/parser", "dojo/domReady", "dijit/Editor", "dojox/layout/FloatingPane", "dijit/form/DateTextBox"],
            function(Moveable, dom, domStyle, on, registry, ready, MenuBar, PopupMenuBarItem, Menu, MenuItem, DropDownMenu, MenuSeparator, Button, TabContainer, BorderContainer, ContentPane, parser, domReady, Editor, FloatingPane, DateTextBox) {
                ready(function() {

                    $('tabs').remove();


                    <?php $lablearray = getlables("1693,1691,1692,1170,652,1646,1644,1645,1593,1560,1593,1592,1591,920,1508,1506,1568,1567,1320,1043,1263,1422,1423,141,105,111,1042,1036,1400,1264,1034,1031,98,846,629,612,607,172,87,88,100,109,26,138,139,140,146,155,158,159,163,166,167,168,169,170,231,304,311,312,1023,1025,1026,1207"); ?>

                    var pMenuBar = new MenuBar();

                    var pSubClientMenu = new DropDownMenu({});

                    var showContentPane = function(ctitle, urlad, winid) {

                        // dojo.destroy('win_'+winid);
                        CloseDialog('win_' + winid);


                        vFloatingPane = new dojox.layout.FloatingPane({
                            title: ctitle,
                            url: urlad,
                            id: 'win_' + winid,
                            resizable: true,
                            style: ''
                        }).placeAt("targetID2");

                        vFloatingPane.setAttribute("data-dojo-props", "closable:true;opacity:0.1;");
                        vFloatingPane.setAttribute("style", "position:absolute;opacity:0.1; overflow:auto;top: 2%; left: 2%; width:80%;height:80%;padding:0; margin:0;z-index:999000;");
                        vFloatingPane.set("href", urlad);
                        vFloatingPane.show();
                        $('#win_' + winid).addClass('fixed-header');
                        $('#win_' + winid)
                            .velocity({
                                scale: 0.1,
                                translateX: 30
                            }, 400).velocity("reverse");

                    }

                    // client Menu
                    pSubClientMenu.addChild(new MenuItem({
                        label: "<?php echo $lablearray['1023']; ?>",
                        onClick: function() {
                            //$().w2destroy('grid1');
                            //                                    CloseDialog("myDialogId1");//                                    
                            //                                    var dlg = new dojox.widget.Dialog({id: "myDialogId1", onClick: function () { }, title: "Clients", executeScripts: true, resizeable: true, draggable: true, href: "clients/clients.php", style: "width:70%;height:80%;top:0px;"});
                            //
                            //                                    dlg.resize();
                            //                                    dlg.show();
                            showContentPane("Clients", "clients/clients.php", '1023');



                        }
                    }));



                    <?php if (in_array('CLIENTRPTS', $modules)) { ?>
                        pSubClientMenu.addChild(new MenuSeparator());
                        pSubClientMenu.addChild(new MenuItem({
                            label: "<?php echo $lablearray['1031']; ?>",
                            onClick: function() {
                                showContentPane("<?php echo $lablearray['1031']; ?>", "reports/reportui.php?rcode=CLIENTRPTS", '1031');

                            }
                        }));
                    <?php } ?>
                    <?php if (in_array('CLIENTS', $modules)) { ?>
                        pMenuBar.addChild(new PopupMenuBarItem({
                            label: "<?php echo $lablearray['1023']; ?>",
                            popup: pSubClientMenu
                        }));
                    <?php } ?>

                    var pSubMenu = new DropDownMenu({});
                    pSubMenu.addChild(new MenuSeparator());
                    pSubMenu.addChild(new MenuItem({
                        label: "<?php echo $lablearray['1026']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['1026']; ?>", "savings/open.php", '1026');
                        }

                    }));



                    pSubMenu.addChild(new MenuSeparator());
                    pSubMenu.addChild(new MenuItem({
                        label: "<?php echo $lablearray['1207']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['1207']; ?>", "savings/save.php", '1207');
                        }

                    }));


                    pSubMenu.addChild(new MenuSeparator());
                    pSubMenu.addChild(new MenuItem({
                        label: "<?php echo $lablearray['1034']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['1034']; ?>", "savings/calcint.php", '1034');
                        }

                    }));


                    <?php if (in_array('SAVRPTS', $modules)) { ?>
                        pSubMenu.addChild(new MenuSeparator());
                        pSubMenu.addChild(new MenuItem({
                            label: "<?php echo $lablearray['1264']; ?>",
                            onClick: function() {
                                showContentPane("<?php echo $lablearray['1264']; ?>", "reports/reportui.php?rcode=SAVINGSRPTS", '1264');
                            }
                        }));
                    <?php } ?>

                    <?php if (in_array('SAV', $modules)) { ?>
                        pMenuBar.addChild(new PopupMenuBarItem({
                            label: "<?php echo $lablearray['1025']; ?>", // Savings 
                            popup: pSubMenu
                        }));
                    <?php } ?>


                    var tdSubMenu = new DropDownMenu({});
                    tdSubMenu.addChild(new MenuSeparator());
                    tdSubMenu.addChild(new MenuItem({
                        label: "<?php echo $lablearray['1592']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['1592']; ?>", "savings/tdeposit.php", '1592');
                        }

                    }));


                    tdSubMenu.addChild(new MenuSeparator());
                    tdSubMenu.addChild(new MenuItem({
                        label: "<?php echo $lablearray['1593']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['1593']; ?>", "reports/reportui.php?rcode=TIMEDEPOSITRPTS", '1593');
                        }

                    }));



                    pMenuBar.addChild(new PopupMenuBarItem({
                        label: "<?php echo $lablearray['1591']; ?>", // Fixed Deposits
                        popup: tdSubMenu
                    }));

                    //===============LOANS
                    var pLoans = new DropDownMenu({});

                    pLoans.addChild(new MenuSeparator());
                    pLoans.addChild(new MenuItem({
                        label: "<?php echo $lablearray['1400']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['1400']; ?>", "Loans/loans.php", '1400');
                        }

                    }));

                    var loanrepayoption = new DropDownMenu({});
                    loanrepayoption.addChild(new MenuSeparator());
                    loanrepayoption.addChild(new MenuItem({
                        label: "<?php echo $lablearray['1506']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['1506']; ?>", "Loans/repay.php", '1506');
                        }
                    }));

                    loanrepayoption.addChild(new MenuSeparator());
                    loanrepayoption.addChild(new MenuItem({
                        label: "<?php echo $lablearray['1508']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['1508']; ?>", "Loans/savrepay.php", '1508');
                        }
                    }));
                    loanrepayoption.addChild(new MenuSeparator());
                    loanrepayoption.addChild(new MenuItem({
                        label: "<?php echo $lablearray['920']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['920']; ?>", "Loans/loanfee.php", '920');
                        }
                    }));
                    pLoans.addChild(new MenuSeparator());
                    pLoans.addChild(new MenuItem({
                        label: "<div style='padding:0;width:12px;height:1px;position:relative;margin;0px;'><?php echo $lablearray['1042']; ?></div><div style='padding:0px;width:4px;float:right;margin:0px;'><img src='/images/icons/page-next.gif' border='0' stle='margin:0px;padding:0px;'></div>",
                        popup: loanrepayoption

                    }));


                    <?php if (in_array('LOANRPTS', $modules)) { ?>
                        pLoans.addChild(new MenuSeparator());
                        pLoans.addChild(new MenuItem({
                            label: "<?php echo $lablearray['1043']; ?>",
                            onClick: function() {
                                showContentPane("<?php echo $lablearray['1043']; ?>", "reports/reportui.php?rcode=LOANRPTS", '1043');
                            }
                        }));
                    <?php } ?>
                    <?php if (in_array('LOAN', $modules)) { ?>
                        pMenuBar.addChild(new PopupMenuBarItem({
                            label: "<?php echo $lablearray['1036']; ?>", // Savings 
                            popup: pLoans
                        }));
                    <?php } ?>
                    //END LOANS

                    // ===============Accounting Menu

                    // ===============Accounting Menu

                    // ===========poup up -  Reports menu item



                    var GlTransactionpop1 = new DropDownMenu({});
                    GlTransactionpop1.addChild(new MenuSeparator())
                    GlTransactionpop1.addChild(new MenuItem({
                        label: "<?php echo $lablearray['87']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['87']; ?>", "transactions/managetransactions.php", '87');
                        }
                    }));

                    GlTransactionpop1.addChild(new MenuSeparator())
                    GlTransactionpop1.addChild(new MenuItem({
                        label: "<?php echo $lablearray['88']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['1263']; ?>", "settings/import.php", '88');
                        }
                    }));



                    // cash enrtries
                    var cashentriespop = new DropDownMenu({});
                    cashentriespop.addChild(new MenuSeparator())
                    cashentriespop.addChild(new MenuItem({
                        label: "<?php echo $lablearray['312']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['312']; ?>", "transactions/cashentries.php", '312');

                        }
                    }));


                    var pchartpop = new DropDownMenu({});

                    pchartpop.addChild(new MenuItem({
                        label: "<?php echo $lablearray['109']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['109']; ?>", "accounts/managecoa.php", '109');
                        }

                    }));

                    pchartpop.addChild(new MenuSeparator())
                    pchartpop.addChild(new MenuItem({
                        label: "<?php echo $lablearray['612']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['612']; ?>", "chartofaccounts/importchartofaccounts.php", '612');
                        }
                    }));

                    //=================



                    var pAccounting = new DropDownMenu({});
                    pAccounting.addChild(new MenuSeparator())

                    pAccounting.addChild(new MenuItem({
                        label: "<div><div style='padding:0;width:10px;height:3px;position:relative;'><?php echo $lablearray['98']; ?></div><div style='padding:0px;width:4px;float:right;'><img src='/images/icons/page-next.gif' border='0'></div></div>",
                        popup: GlTransactionpop1

                    }));

                    pAccounting.addChild(new MenuSeparator())

                    pAccounting.addChild(new MenuItem({
                        label: "<div><div style='padding:0;width:10px;height:3px;position:relative;'><?php echo $lablearray['311']; ?></div><div style='padding:0px;width:4px;float:right;'><img src='/images/icons/page-next.gif' border='0'></div></div>",
                        popup: cashentriespop

                    }));


                    pAccounting.addChild(new MenuSeparator())
                    pAccounting.addChild(new MenuItem({
                        label: "<?php echo $lablearray['100']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['100']; ?>", "transactions/closeperiod.php", '100');
                        }
                    }));



                    pAccounting.addChild(new MenuSeparator())
                    pAccounting.addChild(new MenuItem({
                        label: "<?php echo $lablearray['105']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['105']; ?>", "finalaccounts/provisionforbadloans.php", '105');
                        }
                    }));

                    <?php if (in_array('COA', $modules)) { ?>
                        pAccounting.addChild(new MenuSeparator())
                        pAccounting.addChild(new MenuItem({
                            label: "<div><div style='padding:0;width:6px;height:3px;'><?php echo $lablearray['109']; ?></div><div style='padding:0px;width:4px;float:right;'><img src='/images/icons/page-next.gif' border='0'></div></div>",
                            popup: pchartpop
                        }));
                    <?php } ?>
                    <?php if (in_array('ACCNRPTS', $modules)) { ?>
                        pAccounting.addChild(new MenuSeparator())
                        pAccounting.addChild(new MenuItem({
                            label: "<?php echo $lablearray['1320']; ?>",
                            onClick: function() {

                                showContentPane("<?php echo $lablearray['1320']; ?>", "reports/reportui.php?rcode=ACCOUNTSRPTS", '1320');

                            }
                        }));
                    <?php } ?>
                    // main menu
                    <?php if (in_array('ACCN', $modules)) { ?>
                        pMenuBar.addChild(new PopupMenuBarItem({
                            label: "<?php echo $lablearray['111']; ?>",
                            popup: pAccounting
                        }));
                    <?php } ?>

                    // ===============
                    var cashaccpop = new DropDownMenu({});
                    cashaccpop.addChild(new MenuItem({
                        label: "<?php echo $lablearray['26']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['26']; ?>", "cashaccounts/managecashaccounts.php", '26');
                        }
                    }));

                    cashaccpop.addChild(new MenuItem({
                        label: "<?php echo $lablearray['304']; ?>",

                        onClick: function() {
                            showContentPane("<?php echo $lablearray['302']; ?>", "cashaccounts/cashitems.php", '304');
                        }

                    }));

                    var bankspop = new DropDownMenu({});
                    bankspop.addChild(new MenuItem({
                        label: "<?php echo $lablearray['155']; ?>",

                        onClick: function() {
                            showContentPane("<?php echo $lablearray['155']; ?>", "banks/managebanks.php", '155');
                        }

                    }));
                    bankspop.addChild(new MenuItem({
                        label: "<?php echo $lablearray['846']; ?>",


                        onClick: function() {
                            showContentPane("<?php echo $lablearray['846']; ?>", "banks/managebankbranches.php", '846');
                        }

                    }));
                    bankspop.addChild(new MenuSeparator());
                    bankspop.addChild(new MenuItem({
                        label: "<?php echo $lablearray['138']; ?>",

                        onClick: function() {
                            showContentPane("<?php echo $lablearray['138']; ?>", "banks/managebankaccounts.php", '138');
                        }


                    }));
                    bankspop.addChild(new MenuSeparator());
                    bankspop.addChild(new MenuItem({
                        label: "<?php echo $lablearray['139']; ?>",

                        onClick: function() {
                            showContentPane("<?php echo $lablearray['139']; ?>", "banks/managecheques.php", '139');
                        }

                    }));



                    var taxespop = new DropDownMenu({});
                    taxespop.addChild(new MenuItem({
                        label: "<?php echo $lablearray['146']; ?>",


                        onClick: function() {
                            showContentPane("<?php echo $lablearray['146']; ?>", "settings/managetaxes.php", '146');
                        }

                    }));
                    taxespop.addChild(new MenuSeparator());
                    // ================ CURRENCIES
                    var currencypop = new DropDownMenu({});
                    currencypop.addChild(new MenuItem({
                        label: "<?php echo $lablearray['1691']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['1691']; ?>", "settings/currencies.php", '1691');
                        }

                    }));
                    currencypop.addChild(new MenuSeparator());
                    currencypop.addChild(new MenuItem({
                        label: "<?php echo $lablearray['1693']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['1693']; ?>", "settings/deno.php", '1693');
                        }

                    }));
                    currencypop.addChild(new MenuSeparator());
                    currencypop.addChild(new MenuItem({
                        label: "<?php echo $lablearray['1692']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['1692']; ?>", "settings/currencies.php", '1692');
                        }

                    }));
                    //END ================ CURRENCIES  
                    var prodsettingspop = new DropDownMenu({});



                    prodsettingspop.addChild(new MenuItem({
                        label: "<?php echo $lablearray['1036']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['1422']; ?>", "settings/loanproductsetttings.php", '1036');
                        }
                    }));

                    prodsettingspop.addChild(new MenuSeparator());
                    prodsettingspop.addChild(new MenuItem({
                        label: "<?php echo $lablearray['1025']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['1423']; ?>", "settings/savingproductsettings.php", '1025');
                        }
                    }));

                    prodsettingspop.addChild(new MenuSeparator());
                    prodsettingspop.addChild(new MenuItem({
                        label: "<?php echo $lablearray['1560']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['1560']; ?>", "settings/timedepositsettings.php", '1560');
                        }
                    }));
                    var pSettings = new DropDownMenu({});

                    //import data

                    pSettings.addChild(new MenuItem({
                        label: "<?php echo $lablearray['1263']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['1263']; ?>", "settings/import.php", '1263');
                        }
                    }));
                    pSettings.addChild(new MenuSeparator());
                    pSettings.addChild(new MenuItem({
                        label: "<?php echo $lablearray['172']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['172']; ?>", "settings/settingsgeneral.php", '172');
                        }
                    }));

                    pSettings.addChild(new MenuSeparator());
                    pSettings.addChild(new MenuItem({
                        label: "<div><div style='padding:0;width:8px;height:3px;'><?php echo $lablearray['1170']; ?></div><div style='padding:0px;width:4px;float:right;'><img src='/images/icons/page-next.gif' border='0'></div></div>",
                        popup: prodsettingspop
                    }));

                    pSettings.addChild(new MenuSeparator());

                    <?php if (in_array('CASH', $modules)) { ?>
                        pSettings.addChild(new MenuItem({
                            label: "<div><div style='padding:0;width:8px;height:3px;'><?php echo $lablearray['26']; ?></div><div style='padding:0px;width:4px;float:right;'><img src='/images/icons/page-next.gif' border='0'></div></div>",
                            popup: cashaccpop

                        }));
                    <?php } ?>



                    pSettings.addChild(new MenuSeparator());
                    pSettings.addChild(new MenuItem({
                        label: "<div><div style='padding:0;width:6px;height:3px;'><?php echo $lablearray['155']; ?></div><div style='padding:0px;width:4px;float:right;'><img src='/images/icons/page-next.gif' border='0'></div>",
                        popup: bankspop

                    }));

                    pSettings.addChild(new MenuSeparator());

                    // ===========poup up - users

                    var userspop1 = new DropDownMenu({});

                    userspop1.addChild(new MenuItem({
                        label: "<?php echo $lablearray['158']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['158']; ?>", "users/manageusers.php", '158');
                        }
                    }));


                    userspop1.addChild(new MenuSeparator());
                    userspop1.addChild(new MenuItem({
                        label: "<?php echo $lablearray['140']; ?>",

                        onClick: function() {
                            showContentPane("<?php echo $lablearray['140']; ?>", "roles/manageroles.php?action=add", '140');
                        }


                    }));




                    userspop1.addChild(new MenuSeparator());
                    userspop1.addChild(new MenuItem({
                        label: "<?php echo $lablearray['141']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['141']; ?>", "roles/manageuserroles.php?action=add", '141');
                        }
                    }));
                    userspop1.addChild(new MenuSeparator());
                    userspop1.addChild(new MenuItem({
                        label: "<?php echo $lablearray['607']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['607']; ?>", "roles/managerolepermissions.php?action=add", '607');
                        }

                    }));

                    pSettings.addChild(new MenuItem({
                        label: "<div><div style='padding:0;width:6px;height:3px;'><?php echo $lablearray['159']; ?></div><div style='padding:0px;width:4px;float:right;'><img src='/images/icons/page-next.gif' border='0'></div>",
                        popup: userspop1
                    }));


                    <?php if (in_array('TAX', $modules)) { ?>
                        pSettings.addChild(new MenuSeparator());
                        pSettings.addChild(new MenuItem({
                            label: "<div><div style='padding:0;width:6px;height:3px;'><?php echo $lablearray['163']; ?></div><div style='padding:0px;width:4px;float:right;'><img src='/images/icons/page-next.gif' border='0'></div></div>",
                            popup: taxespop
                        }));
                    <?php } ?>


                    pSettings.addChild(new MenuSeparator());
                    pSettings.addChild(new MenuItem({
                        label: "<div><div style='padding:0;width:6px;height:3px;'><?php echo $lablearray['652']; ?></div><div style='padding:0px;width:4px;float:right;'><img src='/images/icons/page-next.gif' border='0'></div></div>",
                        popup: currencypop
                    }));



                    pSettings.addChild(new MenuSeparator());
                    pSettings.addChild(new MenuItem({
                        label: "<?php echo $lablearray['166']; ?>",
                        onClick: function() {
                            document.location.href = '/settings/publicholidays.php'
                        }
                    }));


                    pSettings.addChild(new MenuSeparator());
                    pSettings.addChild(new MenuItem({
                        label: "<?php echo $lablearray['629']; ?>",
                        onClick: function() {
                            document.location.href = '/settings/languages.php'
                        }
                    }));



                    // main menue
                    <?php if (in_array('SETT', $modules)) { ?>
                        pMenuBar.addChild(new PopupMenuBarItem({
                            label: "<?php echo $lablearray['167']; ?>",
                            popup: pSettings
                        }));
                    <?php } ?>
                    // ===============

                    // SMS
                    var pSMS = new DropDownMenu({});
                    pSMS.addChild(new MenuSeparator());

                    var smspop1 = new DropDownMenu({});

                    smspop1.addChild(new MenuItem({
                        label: "<?php echo $lablearray['1644']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['1644']; ?>", "settings/modemsetting.php", '158');
                        }
                    }));

                    // Modems
                    pSMS.addChild(new MenuItem({
                        label: "<div><div style='padding:0;width:auto;height:3px;'><?php echo $lablearray['1645']; ?></div><div style='padding:0px;margin:0px;width:4px;float:right;'><img src='/images/icons/page-next.gif' border='0'></div>",
                        popup: smspop1
                    }));

                    pSMS.addChild(new MenuSeparator());

                    pSMS.addChild(new MenuItem({
                        label: "<?php echo $lablearray['1646']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['1567']; ?>", "sms/sms.php", '1646');
                        }
                    }));

                    var pothers = new DropDownMenu({});
                    pothers.addChild(new MenuItem({
                        label: "<?php echo $lablearray['168']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['168']; ?>", "help/help.php", '168');
                        }
                    }));
                    pothers.addChild(new MenuItem({
                        label: "<?php echo $lablearray['169']; ?>",
                        onClick: function() {
                            showContentPane("<?php echo $lablearray['168']; ?>", "updates/AutoUpdate.php", '169');

                        }
                    }));

                    pothers.addChild(new MenuItem({
                        label: "<div><div style='padding:0;Color:#FF3300;'><?php echo $lablearray['231']; ?></div></div>",
                        onClick: function() {
                            document.location.href = '/index.php?action=off&lang=<?php echo $_SESSION['P_LANG']; ?>'
                        }
                    }));

                    pMenuBar.addChild(new PopupMenuBarItem({
                        label: "<?php echo $lablearray['1567']; ?>",
                        popup: pSMS
                    }));

                    pMenuBar.addChild(new PopupMenuBarItem({
                        label: "<?php echo $lablearray['170']; ?>",
                        popup: pothers
                    }));
                    pMenuBar.placeAt("wrapper");

                    pMenuBar.startup();


                });
            });

        // used to close window/Dialog box
        function CloseDialog(elementID) {

            // this code will destroy element every time onlcil is called
            if (dojo.byId(elementID) != null) {
                dojo.forEach(dijit.findWidgets(dojo.byId(elementID)), function(w) {
                    w.destroyRecursive();
                });
            }
            dojo.destroy(dojo.byId(elementID));
            if (dijit.byId(elementID)) {
                dijit.byId(elementID).destroy();
            }

        }

        // for all buttons with class
        $(document).ready(function() {
            $(".actbutton").click(function() {
                $(this).effect("highlight", {
                    color: "#CCCCCC"
                }, 60);
            });

        });


        // this function i used to store data in ana element
        // used in initmenu
        function checkunckeck(eID, checkmultiple) {

            if (eID == 'hchkall' || eID == 'fchkall') {

                if (document.getElementById(eID).checked) {

                    $('.chkgrd').prop('checked', true);
                } else {
                    $('.chkgrd').prop('checked', false);
                }
                return;
            }


            $("input.chkgrd:checkbox").each(function(checkmultiple) {

                // some checkboxes may have IDs like grid_checkbox_100000G019101
                // some just an ID like 100000G019101
                // We take care of that situation
                var element_id = 'grid_checkbox_' + eID;

                if ($(this).attr('id') != eID && $(this).attr('id') != element_id) {
                    //  alert($(this).attr('id'));  
                    if (checkmultiple === 0) {

                        $(this).prop('checked', false);
                    }
                } else {


                    $("body").data("gridchk", $(this).val());

                }
            });
        }
    </script>

    <script type="text/javascript" src="includes/javascript/jquery.pnotify.js"></script>
    <link href="includes/javascript/w2ui-1.4.3.css" rel="stylesheet" type="text/css" />
    <script src="includes/javascript/w2ui-1.4.3.min.js" type="text/javascript"></script>
    <link href="defaultTheme.css" rel="stylesheet" media="screen" />
    <link href="myTheme.css" rel="stylesheet" type="text/css" />
    <script src="includes/javascript/demo.js" type="text/javascript"></script>
    <script src="includes/javascript/dataTables.cellEdit.js" type="text/javascript"></script>
    <style type="text/css">
        @import "includes/dojo/dojox/layout/resources/FloatingPane.css";
        @import "includes/dojo/dojox/layout/resources/ResizeHandle.css";
    </style>

</HEAD>

<body class="claro">

    <div id="wrapper" style="top:0px;position:relative;margin:0px;text-align:center;padding:0px;">

    </div>
    <div id="appLayout" class="demoLayout" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props="design: 'headline'">


        <div style="background-image: url('images/logomain.jpg');" data-dojo-type="dijit.layout.ContentPane" data-dojo-props="region: 'center'">



            <div style='float:right; color:#CCCCCC;text-shadow:none;padding:4px;width:100%;text-align:right;'><?php echo $lablearray['650']; ?>: <?php echo $_SESSION['user_username']; ?></div>
            <div id="targetID2" style="z-index:9999;"></div>
            <?php
            // echo '<div style="float:right;margin:0px;width:100%;text-align:right;"><div ><h1 class="expirty">' . $numberDays . '</h1>' . $lablearray['1399'] . '</div></div>';
            ?>