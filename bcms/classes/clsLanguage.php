<?php

    class clsLanguage{

        private $app_data=array();
        private $log;

        private $r;
        private $all_data_arrays=array();
        private $all_data_names=array(0=>"app");
       
        function __construct(){
			
			$this->Set_Log(clsClassFactory::$all_vars['log']);
            $this->Set_DataBase(clsClassFactory::$all_vars['r']);
		}

        function Set_DataBase($r){
			$this->r=$r;
			
		}

        function Set_Log($log){
			$this->log=$log;
			
		}

        function Language_Set_Data_Arrays($app_data){
			$this->app_data=$app_data;
		}

        function Language_Get_Data_Arrays(){
            $output_array=array($this->app_data);
			return $output_array;
		}

        function Language_Init(){
            if(isset($_POST['LanguagesID'])){
                if($_POST['LanguagesID']){
                    $_SESSION['LanguagesID']=$_POST['LanguagesID'];
                    $LanguagesID=$_POST['LanguagesID'];
                }elseif(!$_SESSION['LanguagesID']){
                    $_SESSION['LanguagesID']=1;
                    $LanguagesID=1;
                }
            }else{
                $_SESSION['LanguagesID']=1;
                $LanguagesID=1;
            }
            $this->app_data['LANGUAGESID']=$LanguagesID;
            //define('LANGUAGESID',$LanguagesID);
        }

        function Language_Definitions(){
			$this->log->general("-Language Start-",1);
	
            $template_defs=array();
            
            //$query="SELECT Code,Definition FROM languages_definition WHERE languagesID=".LANGUAGESID." AND templatesID=".TEMPLATESID
            //$query="SELECT Code,Definition FROM languages_definition WHERE languagesID=".$app_data['LANGUAGESID']." AND templatesID=".$content_data["db"]['templatesid']
            
            /*
            $rslt=$r->RawQuery($query);
            $log->general("-Language Query-".$query,1);
            while($data=$r->Fetch_Array($rslt)){
                $template_defs[$data[0]]=$data[1];
            }
            */
			
		}



        
    }

?>