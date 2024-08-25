<?php
require_once('includes/application_top.php'); 
$_parent = basename(__FILE__);
if(AuthenticateAccess('LOGIN')==0){
    tep_redirect(tep_href_link(FILENAME_LOGIN));
}
require_once(DIR_WS_INCLUDES . 'initmenu.php'); 
?>
<form id="frmDash" name="frmDash">
<div id="targetID2" style="z-index:5000;"></div>
<div class="metroblock buysblock left ">
  <span class="icon fontawesome-briefcase left"></span>
  <span class="indicator" id="h_0">0</span>
  <div class="clear"></div>
  <p id="lbl_0"></p>
</div>
<div class="metroblock commentsblock left ">
  <span class="icon fontawesome-comments left"></span>
  <span class="indicator" id="h_1">0</span>
  <div class="clear"></div>
  <p id="lbl_1"></p>
</div>
<div class="metroblock commentsgrey left ">
  <span class="icon fontawesome-comments left"></span>
  <span id="h_2" class="indicator">0</span>
  <div class="clear"></div>
  <p id="lbl_2"></p>
</div>

 <div class="metroblock buysblock left">
  <span class="icon fontawesome-comments left"></span>
  <span id="h_3" class="indicator">0</span>
  <div class="clear"></div>
  <p id="lbl_3"></p>
</div>
</form>
<?php 
require_once(DIR_WS_INCLUDES . 'userfooter.php');
?>
</BODY>
<script type="text/javascript">
    // $( document ).ready(function() {           
    
    //     showValues('frmDash','','loadform','','load.php','').done(     
    //      function(){            
    //     });
    // });
</script>
</HTML>