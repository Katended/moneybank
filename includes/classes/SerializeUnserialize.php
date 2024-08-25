<?php
Class SerializeUnserialize {
    
    Public static $filename='';
    Public static $validity;
    Public static $content='';
    Public static $unserializedval;
    Public static $_instance;
    
    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        self::$filename = DIR_FS_CATALOG.DIR_WS_TEMP_FILES;
//        if (self::$filename == true) {
//            return true;
//        } else {
//            echo 'File Name Error';
//        }
        return self::$_instance;
    }
    

    
    public static function check_file_validity() {
       try {  
            self::$validity = is_file(self::$filename);
            
            if (self::$validity == true) {
                return true;
            } else {
                throw new Exception('File Not Found!');              
            }
        
        } catch (Exception $ex) {           
             Common::$lablearray['E01'] = $ex->getMessage();
        }
    }

     public static function put_serialized_data($file,&$Obj) {
         try {  

            if (!isset($file)):
                $file = '';
            endif;   

            self::$filename = self::$filename.$file;

           // Write the contents to the file, 
           // using the FILE_APPEND flag to append the content to the end of the file
           // and the LOCK_EX flag to prevent anyone else writing to the file at the same time
           file_put_contents(self::$filename,serialize($Obj));
           
        } catch (Exception $ex) { 
            Common::$lablearray['E01'] = $ex->getMessage();
          //  throw new Exception($ex->getMessage());
        }
    }

    
    public static function getting_file_content() {
        if (self::$validity == true) {
            self::$content = file_get_contents(self::$filename);
            if (self::$content == true) {
                return true;
            } else {
                echo 'We Can\'t Reach to the Data';
            }
        } else {
            echo 'File Not Found !';
        }
    }

    public static function get_unserial_data() {
        
        self::check_file_validity();
        
        self::getting_file_content();
        
        if (!is_null(self::$content)) {
            self::$unserializedval = unserialize(self::$content);
            if (self::$unserializedval == true) {
                return true;
            }
        } else {
            echo 'We Can\'t Reach to the Data';
        }
    }

    public static function get_unserialized_data($file='') {
        
        self::$filename.=$file;
                
        self::get_unserial_data();
        
        return self::$unserializedval;
    }

}?>