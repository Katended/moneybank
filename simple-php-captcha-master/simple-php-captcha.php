<?php
    function simple_php_captcha($config = array(),$modules_code="") {

        // We start a session to access
        // the captcha externally!
       //  session_start();
        
        // Generate a random number
        // from 1000-9999
        $captcha = rand(1000, 9999);
        
        // The captcha will be stored
        // for the session
        $captcha_config["code"] = $captcha;
        
            // Generate HTML for image src
        $image_src = substr(__FILE__, strlen( realpath($_SERVER['DOCUMENT_ROOT']) )) . '?_CAPTCHA&amp;t=' . urlencode(microtime());

        $image_src = '/' . ltrim(preg_replace('/\\\\/', '/', $image_src), '/');

        $_SESSION['_CAPTCHA']['code'] = serialize($captcha_config);
            
        if($captcha_config["code"]!=""){
        
            tep_db_query(" DELETE FROM ".TABLE_MODULEACCESSCODES."  WHERE modules_code='".$modules_code."' AND session_id='".tep_session_id()."'");
            
            tep_db_query("INSERT INTO ".TABLE_MODULEACCESSCODES." (accesscode,modules_code,moduleaccescodes_verified,session_id) VALUES ('".fnEncrypt($captcha_config['code'],'LOGIN')."','".$modules_code."','N','".tep_session_id()."')");                    
                        
        }        

        return array(
            'code' => $captcha_config["code"],
            'image_src' => $image_src
        );
        
    }

    if( isset($_GET['_CAPTCHA']) ) {

        session_start();

        $captcha = unserialize($_SESSION['_CAPTCHA']['code']);

        if(!$captcha) exit();  

        unset($_SESSION['_CAPTCHA']);

        // Generate a 50x24 standard captcha image
        $im = imagecreatetruecolor(50, 24);  

        $background_color = imagecolorallocate($im, 255, 255, 255);
         
        // Blue color
        $bg = imagecolorallocate($im, 22, 86, 165);

                 
        // White color
        $text_color = imagecolorallocate($im, 0, 0, 0);

        imagefilledrectangle($im, 0, 0, 199, 49, $background_color);
        
        // Print the captcha text in the image
        // with random position & size
        imagestring($im, 5, 5, 5, $captcha['code'], $text_color);
       
        // The PHP-file will be rendered as image
        header('Content-type: image/png');
        
        // Finally output the captcha as
        // PNG image the browser
        imagepng($im);   
        
        imagedestroy($im);
  
    }    
?>