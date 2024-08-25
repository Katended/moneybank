<?php require_once('../includes/application_top.php');?>
<style type="text/css">
    #upload_frame {
        display:none;
        height:0;
        width:0;
    }
    .msg {
        background-color:#FFE07E;
        border-radius:5px;
        padding:5px;
        display:none;
        width:auto;
      /**  font:italic 13px/18px arial,sans-serif;**/
    }
   .viewContainer {
    height: 400px important!;
    background: whitesmoke;
    overflow: scroll;
    width: 80%; 
    margin: 0 auto;
    padding: 3em;
    font: 100%/1.4 serif;
    border: 1px solid rgba(0,0,0,0.25)

}
</style>
<?php getlables("1343,1344,1345");?> 
<form id="file_upload_form" action="addedit.php" name='file_upload_form'  >
   
    <table cellpadding="4 " border="0" cellspacing="0" width="100%">
        <tr>
            <td>
               <label for="upload_field"><?php echo $lablearray['1343']; ?></label>
                <input type="file" id="upload_field" name="upload_field" />
                <input type="hidden" id="frmid" name="frmid" value="frmimportdata"/>
            </td>
            <td align="left">  
                <label for="data_code"><?php echo $lablearray['1344']; ?></label>
                <?php echo Common::generateReportControls('IMPORTDATAOPTIONS', $Conn) ?>
                <input type="submit" value="Upload" /> 
            </td>
        </tr>
       <tr>
            <td colspan="2" align="center"></td>
        </tr>
    </table>
    <div class='viewContainer'>
                <span id="server_response"></span>    
   </div>
</form>
</body>
<script>    
    $( document ).ready( function(){
        
        $("#file_upload_form").submit(function(event){

            $('#server_response').html("<span class='msg'><?php echo $lablearray['1345']; ?></span>");
            // show loader [optional line]
            $('#server_response').fadeIn();

            if(document.getElementById('upload_frame') == null) {

                // create iframe
                // Note. There was an error in the statamen below. dashboard.php was being refreshed
                // we add the iframe to  one of the the window elements
                //$('body').append('<iframe id="upload_frame" name="upload_frame"></iframe>');

                $('#file_upload_form').append('<iframe id="upload_frame" name="upload_frame"></iframe>');

                $('#upload_frame').on('load', function () {

                    if ($(this).contents()[0].location.href.match($(this).parent('#file_upload_form').attr('action'))) {

                        // display server response [optional line]
                        $('#server_response').html($(this).contents().find('html').html());

                        // hide loader [optional line]      
                            
                       // $('#msg').hide();

                    }


                });

                $(this).attr('method', 'POST');
                $(this).attr('enctype', 'multipart/form-data');
                $(this).attr('target', 'upload_frame').submit(function(){
                       $('#server_response').html("<span class='msg'>Processing Information...</span>").fadeIn();
                 });


            }
            $('#server_response').html("<?php echo $lablearray['1345']; ?>").fadeIn();
            //  event.preventDefault();

        });
    });

</script>
</html>