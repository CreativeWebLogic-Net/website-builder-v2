<?php
    class clsAutoloader {
      //private $all_classes=array();
        // Define a static method that includes the class file
        public function load($class_name) {
          $db_array=array('clsDatabaseConnect','clsDatabaseInterface','clsBulkDBChange','clsAddToDatabase','clsUpdateDatabase');
          if(in_array($class_name,$db_array)){
            include "./bcms/classes/clsDatabase.php";
          }else{
            include "./bcms/classes/" . $class_name . ".php";
          }
          //self::$all_classes[$class_name]=new $class_name();
        }
        /*
        public static function get_class_object($class_name) {
          return self::$all_classes[$class_name];
        }
        */
      }
