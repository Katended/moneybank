<?php
include_once('connectionfactory.php');
require_once('productconfig.php');
require_once('common.php');
// Research and Modified By: Mr. Jake R. Pomperada, MAED-IT
// November 24, 2014 9:27 PM
//SMS via GSM Modem - A PHP class to send SMS messages via a GSM modem attached to the computers serial port.
//Windows only (tested on XP with PHP 5.2.6)
//Tested with Sun Broadband Internet GSM Modem
//Requires that PHP has permission to access "COM" system device, and system "mode" command

// error_reporting(ALL);

//Example here my gsm modem in assigned in com3 in my device manager

//$mobile_number=$_POST['mobile_number'];
//$messages=$_POST['messages'];
//
//$gsm_send_sms = new gsm_send_sms();
//$gsm_send_sms->debug = false;
//$gsm_send_sms->port = 'COM2';
//$gsm_send_sms->baud = 115200;
//$gsm_send_sms->init();
//
//$status = $gsm_send_sms->send($mobile_number,$messages);
//if ($status) {
//    echo "<body bgcolor='lightgreen'>";
//	echo "<br><br><br>";
//	echo "<h3 align='center'>Message Successfully Send... </h3>\n";
//	echo "</body>";
//} else {
//     echo "<body bgcolor='lightgreen'>";
//	 echo "<br><br><br>";
//	echo "<h3 align='center'>Message not sent Successfully... </h3>\n";
//	echo "</body>";
//}
//
//
//$gsm_send_sms->close();

//Send SMS via serial SMS modemat
class Sms {

	public $port = 'COM2';
	public $baud = 9600;
       // public $baud =9600;
	public $debug = false;

	private $fp;
	private $buffer;
        private static $_instance ;

	//Setup COM port
	public function __construct($port='') {
            
            try {
                $this->port = $port;
                exec("mode {$this->port} BAUD={$this->baud} PARITY=n DATA=8 STOP=1",$output, $retval);         

		self::debugmsg(implode("\n", $output));

		self::debugmsg("Opening port");

//	       Open COM port
//             $this->fp = fopen($this->port . ':', 'r+');
               $this->fp = dio_open($this->port, O_RDWR);
              
//		//Check port opened
		if (!$this->fp) {
                    throw new Exception("Unable to open port \"{$this->port}\"");
		}
              self::debugmsg("Port opened");
		
                //AT+CSCA="+85290000000"
               // dio_write($this->fp, "AT+CSCA='+256771100020'\r\n");
               // sleep(1);
                                
                
               // if (!$status) {
		//	throw new Exception('Could not set number');
		//}
                                
                
              } catch (Exception $ex) {  
                    print_r($ex);
                    throw new Exception($ex->getMessage());
             }
        
	}
        
        private static function startModem(){
            
                
		self::debugmsg("Checking for response from modem");

		//Check if modem is connected
                dio_write(self::$_instance->fp, "AT\r\n");
                
                sleep(1);
		//fputs($this->fp, "AT\r");

		//Wait for ok
		$status = self::wait_reply("OK",180);

		if (!$status) {
                    throw new Exception('Did not receive responce from modem');
		}

		self::debugmsg('Modem connected');

		//Set modem to SMS text mode
		self::debugmsg('Setting text mode');
                
		dio_write(self::$_instance->fp, "AT+CMGF=1\r\n");
                
                sleep(1);
                
		$status = self::wait_reply("OK", 180);

		if (!$status) {
                    throw new Exception('Unable to set text mode');
		}

                
        }
            
	//Wait for reply from modem
	private static function wait_reply($expected_result, $timeout) {

		self::debugmsg("Waiting {$timeout} seconds for expected result");

		//Clear buffer
		self::$_instance->buffer = '';

		//Set timeout
		$timeoutat = time() + $timeout;

		//Loop until timeout reached (or expected result found)
		do{
                          
                   //   self::debugmsg('Now: ' . time() . ", Timeout at: {$timeoutat}");

			// $buffer = fread($this->fp, 128);
                       $buffer = dio_read(self::$_instance->fp, 2);
                       
		       self::$_instance->buffer.= $buffer;

			// usleep(200000);//0.2 sec
//
                        self::debugmsg("Received: {$buffer}");
//
//			//Check if received expected responce
                      //  $res = preg_match('/'.$expected_result.'/', $this->buffer,$matches);
			if (preg_match('/'.preg_quote($expected_result, '/').'$/', self::$_instance->buffer)|| preg_match('/'.preg_quote($expected_result, '/').'$/', '+CMGS')) {
				self::debugmsg('Found match');                               
				return true;
				break;
			} else if (preg_match('/\+CMS ERROR\:\ \d{1,3}\r\n$/',self::$_instance->buffer)) {                            
				return false;
                                break;
                        }else{
                            
			}

		} while ($timeoutat > time());

		self::debugmsg('Timed out');

		return false;
    

	}

	//Print debug messages
	private static function debugmsg($message) {

		if (self::$_instance->debug == true) {
			$message = preg_replace("%[^\040-\176\n\t]%", '', $message);
			echo $message . "\n";
		}

	}

	//Close port
	public static function close() {

		self::debugmsg('Closing port');
                dio_close(self::$_instance->fp);
		

	}

	//Send message
	public static function send($tel, $message,$mid,$port) {
            
                
                    
                Bussiness::$Conn->AutoCommit = true;
				Bussiness::$isBulkInsert = false;
                Bussiness::$Conn->beginTransaction();
                // we must create a new object to access on static function 
                //(new self)->fun1();
            
                // open modem port
                if (!self::$_instance  instanceof Sms):
                    self::$_instance = new self($port); 
                endif;
              
                
               self::startModem(); 
                
		//Filter tel
		$tel = preg_replace("%[^0-9\+]%", '', $tel);

		//Filter message text
		$message = preg_replace("%[^\040-\176\r\n\t]%", '', $message);

		//Start sending of message
                dio_write(self::$_instance->fp, "AT+CMGS=\"{$tel}\"\r");
                sleep(1);
		
                //Start sending of message
                dio_write(self::$_instance->fp,$message.chr(26));
              
		
		//Wait for confirmation
		$status = self::wait_reply("OK", 180);

		if (!$status) {                                      
                    self::debugmsg('Did not receive confirmation of message sent');
                    return false;
		}else{
                    $messages[] =array('DMID'=>$mid,'STAT'=>'S','RES'=>self::$_instance->buffer,'TABLE'=>TABLE_DEVICEMESSAGE,'ACTION'=>'add');
                    Bussiness::covertArrayToXML($messages, true);                  
                    return true;
                }

	}
 }

?>
