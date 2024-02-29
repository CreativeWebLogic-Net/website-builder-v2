<?php

    class clsTestServer{
        private $Base_Directory="";

        function __construct(){

        }

        public function Test_File_System(){
            $output="";
            $current_dir=pathinfo(__DIR__);
            $output.="<br>\ncurrent dir<br>\n";
            $output.=var_export($current_dir,true)."<br>";
            $this->Base_Directory=$current_dir['dirname'].'\\'.$current_dir['basename'];
            $output.="<br>\ncurrent file<br>\n";
            $output.=$_SERVER['PHP_SELF'];
            return $output;
        }


        public function Test_Server_System(){
            $output="";
            $output.="<br>\ncurrent server<br>\n";
            $output.=$_SERVER['SERVER_NAME']."-<br>";
            $output.=gethostname();
            $output.="<br>\nserver hostname<br>\n";
            return $output;
        }

        public function Test_Environmental_Variables(){
            $output="";
            $php_ini=array();
            $php_ini[] = getenv('PHP_INI_SCAN_DIR');
            $php_ini[] = getenv('PHPRC');
            $output.=var_export($php_ini,true);
            return $output;
        }

        public function Test_PHP_Extensions(){
            $output="";
            $extensions_array=get_loaded_extensions();
	        $output.=var_export($extensions_array,true);
            return $output;
        }

        public function Test_Apache_Extensions(){
            $output="";
            $extensions_array=apache_get_modules();
	        $output.=var_export($extensions_array,true);
            return $output;
        }

        public function Test_PHP_Pear_Extensions(){
            $output="";
            $dir_name=realpath('../../../');
            $output=null;
	        $retval=null;

            $filename='pear';
            if (file_exists($filename)) {
                $output.="The file $filename exists";
            } else {
                $output.="The file $filename does not exist";
            }
            $exec_command=$filename.' list';
            //exec($exec_command, $output, $retval);
            $exec_output =shell_exec($exec_command);
            //$output.=var_export($extensions_array,true);
            $output.="<br>\n\n".$dir_name."<br>\n\n".$exec_command." <br>\n\nReturned with status $retval and output:\n";
            $output.=var_export($exec_output,true);
            return $output;
        }

        
        private function php_info()
        {
            $output="";
            ob_start();
            phpinfo();
            $info = ob_get_clean();
            $info = preg_replace("/^.*?\<body\>/is", "", $info);
            $info = preg_replace("/<\/body\>.*?$/is", "", $info);
            $output.=$info;
            return $output;
        }

        public function Test_Server_Details(){
            $output="";
            $output.=phpversion();
            $output.=php_sapi_name();
            $output.=$this->php_info();
            return $output;
        }

        public function Retrieve_All_Variables(){
            $output="";
            $output.=var_export($GLOBALS,true);
            return $output;
        }


        public function getDirContents($dir, &$results = array()) {
            $files = scandir($dir);
            foreach ($files as $key => $value) {
              $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
              if (!is_dir($path)) {
                $results[] = $path;
              } else if ($value != "." && $value != "..") {
                $this->getDirContents($path, $results);
                $results[] = $path;
              }
            }
            return $results;
          }
          
                    
          public function Retrieve_All_Files(){
                $output="";
                $output.=var_export($this->getDirContents($this->Base_Directory),true);
                return $output;
           }
	
    }

?>