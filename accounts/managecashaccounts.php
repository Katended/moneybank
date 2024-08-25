<?php
 require_once('../includes/application_top.php');
 require_once("../simple-php-captcha-master/simple-php-captcha.php");
 require_once('../includes/classes/common.php');
 $_SESSION['captcha'] = simple_php_captcha(array(),'TRANSFER');
 
spl_autoload_register(function ($class_name) {
    include '../includes/classes/'.$class_name . '.php';
});


$_parent = basename(__FILE__);
$code_array = unserialize($_SESSION['_CAPTCHA']['config']);//['code'];
 
 // if the user is not logged on, redirect them to the login page
  if(AuthenticateAccess('LOGIN')==0){
	//tep_redirect(tep_href_link(FILENAME_DEFAULT));
	//tep_redirect(tep_href_link(FILENAME_LOGIN));
 }

//session_start();
// here you can perform all the checks you need on the user submited variables
$_SESSION['security_number']=rand(10000,99999);



?>
<link href="includes/javascript/w2ui-1.4.3.css" rel="stylesheet" type="text/css"/>
<script src="includes/javascript/w2ui-1.4.3.min.js" type="text/javascript"></script>
<script src="includes/javascript/jquery.validate.min.js" type="text/javascript"></script>
<style>
.tab {
    width: auto;
    height:auto;
    border: 1px solid silver;
    display: none;
    padding: 1px;
    overflow: hidden;	
}
  #toppanel {
    width: 30%;
    height:auto;  

	float:right;
	position: relative;	
	z-index: 5000 ;
	padding:5px;
	position: relative;
	display:none;

  }
  
  
  .loader{
	margin:200px;
	padding:10px;
	}
h1{
	font-family: 'Actor', sans-serif;
	color:#CCCCCC;
	font-size:16px;
	letter-spacing:1px;
	font-weight:200;
	text-align:center;
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
	
</style>

<fieldset>
<?php require('../'.DIR_WS_INCLUDES . 'pageheader.php'); ?>
<?php 
$lablearray = getlables("42,350,442,579,579,306,242,21,442,306,242,2,20,21,267");
?>
<form action="#"  style="width:100%;height:auto;" id='frmcashaccounts' name='frmcashaccounts' >
			
<input name="cashaccounts_id" type="hidden"  id="cashaccounts_id" value="">
<input name="action" type="hidden"  id="action" value="add">
<div style="float:right;"><?php echo $lablearray['21']?><?php echo tep_draw_input_field('searchterm','','',false,'text','','20'); ?><button class="btn" name="Go"  type="button" onKeyUp="showResult('searchterm='+this.value+'&frmid=frmfeecategories&action=search','txtHint')"/><?php echo $lablearray['21']?></button></div>
 <table width="100%" border="0" cellspacing="10" cellpadding="0">
				<tr>
				<td  colspan="2">
				<table width="100%" border="0" cellspacing="2" cellpadding="0">
					<tr>
					<td colspan="2" align="center"><span id="status" style='color:#006600;'></span></td>
				  </tr>
				 			  
				   <tr>
					<td align="right" ><?php echo $lablearray['442']?></td>
					<td ><?php echo tep_draw_input_field('cashaccounts_name','','',false,'text','','60'); ?><?php echo TEXT_FIELD_REQUIRED;?></td>
				  </tr>
				  <tr>
					<td align="right"><?php echo $lablearray['306']?></td>
					<td ><?php echo DrawComboFromArray(array(),'chartofaccounts_accountcode','','COACOMBO','','COACOMBO');?><?php echo TEXT_FIELD_REQUIRED;?></td>
				  </tr>
                 
				  <tr height="25">
					<td  align="right"><?php echo $lablearray['350'];?></td>
					<td >
					<?php echo DrawComboFromArray(array(),'currencies_id','','CURRENCIES','','CURRENCIES');?>
					
					</td>
				</tr>
							 
				</table>				
				<tr>
					<td colspan="2" align="center">
						<table width="100%" border="0" cellspacing="1" cellpadding="0">						 
						  <tr>
							<td   align="right"> <button class="btn" name="Go"  type="reset" ><?php echo $lablearray['2']?></button><button class="btn" name="Go"  type="button" id="btn20" ><?php echo $lablearray['20']?></button><button class="btn" name="Go"  type="button"  onClick="parent.document.location='downloadlist.php?columncheck=7&Fee_Categories&timestamp=<?php echo strtotime("now"); ?>'"><?php echo $lablearray['267']?></button ></td>
							
						  </tr>
						</table>					
					 </td>
				</tr>
				<tr>
					<td colspan="2" id='txtHint' align="center"></td>
				</tr>							
				</table> 


			</form>					
			</fieldset>
		  <script language="JavaScript"  type="text/javascript">
				showValues('frmcashaccounts','txtHint','search','CASHACCOUNTS','load.php','');
			// showResult('frmid=frmcashaccounts','txtHint')
			
			$( "#btn20" ).click(function() {		
			
					var pageinfo =  JSON.stringify($("#frmcashaccounts").serializeArray());			
					var data1 = JSON.stringify(JSON.parse('{"pageinfo":'+pageinfo+"}"));			
					showValues('frmcashaccounts',$('#cashaccounts_id').val(),$('#action').val(),data1,'',$('#cashaccounts_id').val()).done(function(){		
											
						showValues('frmcashaccounts','txtHint','search','CASHACCOUNTS','load.php','');
						
						//$('#frmcashaccounts').reset()
				});
			  
			});
			
			
		  </script>	  
</BODY>
</HTML>
