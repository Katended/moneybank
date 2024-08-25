<?php
require('includes/application_top.php');
require('includes/functions/password_funcs.php');
include('simple-php-captcha-master/simple-php-captcha.php');

//phpinfo();
//exit();

//function get_client_ip() {
//            $ipaddress = '';
//            if (getenv('HTTP_CLIENT_IP'))
//                $ipaddress = getenv('HTTP_CLIENT_IP');
//            else if(getenv('HTTP_X_FORWARDED_FOR'))
//                $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
//            else if(getenv('HTTP_X_FORWARDED'))
//                $ipaddress = getenv('HTTP_X_FORWARDED');
//            else if(getenv('HTTP_FORWARDED_FOR'))
//                $ipaddress = getenv('HTTP_FORWARDED_FOR');
//            else if(getenv('HTTP_FORWARDED'))
//               $ipaddress = getenv('HTTP_FORWARDED');
//            else if(getenv('REMOTE_ADDR'))
//                $ipaddress = getenv('REMOTE_ADDR');
//            else
//                $ipaddress = 'UNKNOWN';
//                
//            return $ipaddress;
// }
//        

//if ($ipaddress=='62.56.249.57'):
//   header("Location: https://www.google.com"); /* Redirect browser */
//    exit();
//endif;
//if ($_SERVER['REMOTE_ADDR']=='192.168.0.23' || $_SERVER['REMOTE_ADDR']=='127.0.0.1'):
//    //header("Location: https://www.google.com"); /* Redirect browser */
//    //exit();
//else:
//    header("Location: https://www.google.com"); /* Redirect browser */
//    exit();
//endif;


//// Require the bundled autoload file - the path may need to change
//// based on where you downloaded and unzipped the SDK
//require __DIR__ . '/twilio-php-master/Twilio/autoload.php';
//
//// Use the REST API Client to make requests to the Twilio REST API
//use Twilio\Rest\Client;
//
//// Your Account SID and Auth Token from twilio.com/console
//$sid = 'ACfe6fb67d65c75cdb7707b56a891fdd3e';
//$token = 'b381848b3026b6584074fe500d807668';
//$client = new Client($sid, $token);
//
//// Use the client to do fun stuff like send text messages!
//$client->messages->create(
//    // the number you'd like to send the message to
//    '+256773397960',
//    array(
//        // A Twilio phone number you purchased at twilio.com/console
//        'from' => '+13854550028',
//        // the body of the text message you'd like to send
//        'body' => "Test SMS Service-Moneybank"
//    )
//);

//$ivector = bin2hex(random_bytes(8));
//echo $ivector;
// echo fnEncrypt('Admin', 'PASSWORD');
//$password = fnEncrypt(trim('Admin'), 'PASSWORD');
//echo $password;
//
//$password = fnDecrypt($password, 'PASSWORD');
//
//echo $password;
getLicenceCounts();
$error = false;

$_SESSION['captcha'] = simple_php_captcha(array(), 'LOGIN');

if (!isset($_POST['lang'])) {
    $_POST['lang'] = '';
}

if (!isset($_GET['lang'])) {
    $_GET['lang'] = '';
}

if ($_POST['lang'] == "") {

    if ($_GET['lang'] != "") {

        $_SESSION['P_LANG'] = $_GET['lang'];
    } else {
        $_SESSION['P_LANG'] = "EN";
    }
} else {
    $_SESSION['P_LANG'] = $_POST['lang'];
}
if (!defined("P_LANG")) {
    define("P_LANG", $_SESSION['P_LANG']);
}
getlables("1,295,2,3,929,4,9,5,642,643,645,646,260,647,648,649,643,644");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="<?php echo $_GET['lang']; ?>">
<head>

  <title><?php echo NAME_OF_INSTITUTION; ?></title>
  
       
        <script type="text/javascript" src="includes/javascript/de.css"></script>
        <script type="text/javascript" src="includes/javascript/jquery-1.6.2.min.js"></script>
        <script type="text/javascript" src="includes/javascript/jquery-ui-1.8.16.custom.min.js"></script>

        <script type="text/javascript" src="includes/javascript/commonfunctions.js"></script>
            

      <style>
      /* NOTE: The styles were added inline because Prefixfree needs access to your styles and they must be inlined if they are on local disk! */
      * {
  box-sizing: border-box;
}

body {
  font-family: "HelveticaNeue-Light","Helvetica Neue Light","Helvetica Neue","Lucida Grande",sans-serif;
  color: white;
  font-size: 12px;
  background:#88b0d6;
}

form {
  background: #111;
  width:30%;
  margin: 30px auto;
  border-radius: 0.5em;
  overflow: hidden;
  position: relative;
  box-shadow: 0 5px 10px 5px rgba(0, 0, 0, 0.2);
  opacity: 0.7;
  filter: alpha(opacity=50);
}

form:after {
  content: "";
  display: block;
  position: absolute;
  height: 1px;
  background: linear-gradient(to right, #111111, #444444, #b6b6b8, #444444, #111111);
  top: 0;
}
form:hover {
    opacity: 1.0;
    filter: alpha(opacity=100); /* For IE8 and earlier */
}

form:before {
  content: "";
  display: block;
  position: absolute;
  width: 8px;
  height: 5px;
  border-radius: 50%;
  left: 34%;
  top: -7px;
  box-shadow: 0 0 6px 4px #fff;
}

.inset {
  padding: 7px;
  border-top: 1px solid #19191a;
}

form h1 {
  font-size: 18px;
  text-shadow: 0 1px 0 black;
  text-align: center;
  padding: 5px 0;
  border-bottom: 1px solid black;
  position: relative;
}

form h1:after {
  content: "";
  display: block;
  width: 250px;
  height: 100px;
  position: absolute;
  top: 0;
  left: 50px;
  pointer-events: none;
  transform: rotate(70deg);
  background: linear-gradient(50deg, rgba(255, 255, 255, 0.15), rgba(0, 0, 0, 0));
}

label {
  color: #666;
  display: block;
  padding-bottom: 9px;
}

input[type=text],
input[type=password] {
  width: 100%;
  padding: 7px 4px;
  background: linear-gradient(#1f2124, #27292c);
  border: 0.1px solid #222;
  box-shadow: 0 1px 0 rgba(255, 255, 255, 0.1);
  border-radius: 0.5em;
  margin-bottom: 1px;
}

label[for=remember] {
  color: white;
  display: inline-block;
  padding-bottom: 0;
  padding-top: 2px;
}

input[type=checkbox] {
  display: inline-block;
  vertical-align: top;
}

.p-container {
  padding: 0 15px 15px 15px;
}

.p-container:after {
  clear: both;
  display: table;
  content: "";
}
a.active{
  color: #0d93ff;  
}
a.hover{
  color: #0d93ff;  
  text-decoration:underline;
}
.p-container span, a{
  display: block;
  float: left;
  color: #0d93ff;
  padding-top: 10px;
}
input[type=text],
input[type=password]{
    color: white;
}

input[type=reset],
input[type=submit] {
  padding: 5px 20px;
  margin: 5px;
  border: 1px solid rgba(0, 0, 0, 0.4);
  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.4);
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.3), inset 0 10px 10px rgba(255, 255, 255, 0.1);
  border-radius: 0.3em;
  background: #0184ff;
  
  float: right;
  cursor: pointer;
  font-size: 13px;
}

input[type=submit]:hover {
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.3), inset 0 -10px 10px rgba(255, 255, 255, 0.1);
}

input[type=text]:hover,
input[type=password]:hover,
label:hover ~ input[type=text],
label:hover ~ input[type=password] {
  background: #27292c;
}
</style>
<script>
            var url = '';
            var iface = '';

            url = "addedit.php";

            function updateLanguage() {

                window.location.href = "index.php?lang=" + document.getElementById('lang').value;

            }

            function changePassword() {

                if (IsNullEmptyField('old_password', "<?php echo $lablearray['647']; ?>") && IsNullEmptyField('new_password', "<?php echo $lablearray['648']; ?>") && IsNullEmptyField('username', "<?php echo $lablearray['649']; ?>")) {

                    showResult('frmid=frmlogin&old_password=' + document.getElementById('old_password').value + '&user_password=' + document.getElementById('new_password').value + '&user_username=' + document.getElementById('username').value + '&action=update', 'txtHint');
                }
            }


            $(document).ready(function () {

                $("#login").click(function () {

                    var username = $("#username").val();
                    var password = $("#password").val();
                    var user_accesscode = $("#user_accesscode").val();
                    var captchcode = $("#captchcode").val();

                    $.post(url, {frmid: 'frmlogin', username: username, password: password, user_accesscode: user_accesscode, passcode: captchcode}, function (data) {

                        if (data.trim() == '1') {
                            $(location).attr('href', 'dashboard.php');
                        } else {

                            $("#message").html(data);
                            $("#message").slideDown("slow");
                        }

                    });

                });

            });


            $(document).ready(function () {
                $('#password').keypress(function (e) {
                    if (e.keyCode == 13){
                        $('#login').click();
                        
                    }
                });
            });

        </script>

</head>

<body style="text-align:center;">
    <span id="status" style='color:#006600;'></span>
     <span  id="message"></span> 
  <form>
      
  <h1><?php echo $lablearray['1']; ?></h1>
  <div class="inset">
      <span id="status" style='color:#006600;'></span>
      <span  id="message"></span> 
  <p>
    <label for="email"><?php echo $lablearray['295']; ?></label>
     <select  id="lang"  name="lang" onChange="updateLanguage()" style="padding: 7px;margin:7px;">
                                        <option value='EN' id="EN" <?php if ($_GET['lang'] == "EN") {
    echo "selected";
} ?>>English(EN)</option>
        <option value='FR' id="FR" <?php if ($_GET['lang'] == "FR") {
        echo "selected";
    } ?>>français(FR)</option>
        <option value='JA' id="JA" <?php if ($_GET['lang'] == "JA") {
        echo "selected";
    } ?>>日本語(JA)</option>
        <option value='SP' id="SP" <?php if ($_GET['lang'] == "SP") {
        echo "selected";
    } ?>>Español(SP)</option>
        <option value='LUG' id="LUG" <?php if ($_GET['lang'] == "LUG") {
        echo "selected";
    } ?>>Luganda(LUG)</option>
    </select>
  </p>    
      
  <p>
    <label for="email"><?php echo $lablearray['3']; ?></label>
    <input type="text" name="username" id="username" value="Admin">
  </p>
  <p>
    <label for="password"><?php echo $lablearray['4']; ?></label>
    <input type="password" name="password2" id="password" value="Admin">
  </p>
  <p>
    <label for="password"><?php echo $lablearray['929']; ?></label>
    <input type="password" name="user_accesscode" id="user_accesscode" value="567">
  </p>
   <?php
      echo '<img src="'.$_SESSION['captcha']['image_src'].'" style="margin:0px;">';

      if (!isset($_GET['action'])) {
          $_GET['action'] = '';
      }

      if ($_GET['action'] == 'off') {
          unset($_SESSION);
      }

    ?>
   <p>    
    <input type="text" name="captchcode" id="captchcode" size="20">
  </p>
  
   <div id="changepassword" style="display:none;"> 
    <label for="password"><?php echo $lablearray['643']; ?></label>
    <input type="password" name="old_password" id="old_password" size="18">
    <label for="password"><?php echo $lablearray['644']; ?></label>
    <input type="password" name="new_password" id="new_password" size="18">
    <input type="reset" name="go" id="go" value="<?php echo $lablearray['645']; ?>" onClick="changePassword()">
    </div>  
  </div>  
  <p class="p-container">
    <a href="#" onClick="$('div#changepassword').show();"><?php echo $lablearray['646']; ?></a><p></p>
    <input type="reset" name="go" id="login" value="<?php echo $lablearray['1']; ?>">
    <input type="reset" name="reset" id="reset" value="<?php echo $lablearray['2']; ?>">
    
  </p>
</form>  
</body>
</html>
