<?php

    class clsModules{
        private $log;

        private $r;
        private $a;
        private $module_data;
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

        function Set_Assorted($a){
            $this->a=$a;
		}

        function Find_Module($modulesID){
            $sql='SELECT * FROM modules WHERE id="'.$modulesID.'"';
            
            $rslt=$this->r->RawQuery($sql);
            $num_rows=0;
            $num_rows=$this->r->NumRows($rslt);
            if($num_rows>0){
                $this->module_data['db']=$this->r->Fetch_Assoc();
            }
            return $this->module_data['db'];
		}

        function Find_Module_View($modules_viewsID){
            $sql='SELECT * FROM module_views WHERE id="'.$modules_viewsID.'"';
            
            $rslt=$this->r->RawQuery($sql);
            $num_rows=0;
            $num_rows=$this->r->NumRows($rslt);
            if($num_rows>0){
                $this->module_data['views']=$this->r->Fetch_Assoc();
            }
            return $this->module_data['views'];
		}

        public function Module_Get_Data_Arrays(){
            //$output_array=array($this->all_data_names[2]=>$this->template_data,$this->all_data_names[3]=>$this->content_data,
            //$this->all_data_names[1]=>$this->domain_data,$this->all_data_names[0]=>$this->app_data);

            $output_array=array($this->module_data);
			return $output_array;
		}
       
    }


?>