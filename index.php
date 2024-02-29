<?php
  //include("bcms/classes/clsAutoloader.php");
  //spl_autoload_register(array("clsAutoloader", "load"));
  //include("bcms/classes/clsDatabase.php");
  function my_autoloader($class) {
      /*
      $db_array=array('clsDatabaseConnect','clsDatabaseInterface','clsBulkDBChange','clsAddToDatabase','clsUpdateDatabase');
      if(in_array($class,$db_array)){
        $filename="bcms/classes/clsDatabase.php";
      }else{
        $filename="bcms/classes/" . $class . ".php";
      }
      */
      $filename="bcms/classes/" . $class . ".php";
      if (file_exists($filename)) {
        include($filename);
      }else{
        print " \n ".$class." \n";
        $Current_Dir=pathinfo(__DIR__);
        print(" \n ".var_export($Current_Dir,true)." \n");
      }
  }

  spl_autoload_register('my_autoloader');
 //echo "I Am Legendary 00";
 
  include("bcms/classes/clsClassFactory.php");
  //echo "I Am Legendary 001";
  include("bcms/classes/clsSystem.php");
  //echo "I Am Legendary 002";
  $s=new clsSystem();