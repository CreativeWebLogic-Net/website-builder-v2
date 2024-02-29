<?php

    class clsText{
        private $text_data=array();
        private $log;

        private $r;
        private $content_pagesID=0;

        private $content_data=array();

        
        function __construct(){
            //echo"777666-----------------------------------------------------------------------------\n";
            //print_r(clsSystem::$vars->content_data);
            $this->content_data=clsSystem::$vars->content_data;
            $this->Set_Log(clsClassFactory::$all_vars['log']);
            $this->Set_DataBase(clsClassFactory::$all_vars['r']);
        }

        function Set_DataBase($r){
			$this->r=$r;
			
		}

        function Set_Log($log){
			$this->log=$log;
			
		}



        function Pre_Display(){
            //$sql="SELECT content_text FROM mod_text WHERE content_pagesID=".PAGESID;
            //$sql="SELECT content_text FROM mod_text WHERE content_pagesID=".$content_data["db"]['id'];
            //echo"700666-----------------------------------------------------------------------------\n";
            //print_r($this->content_data);
            $content_pagesID=$this->content_data['db']['id'];

            $sql="SELECT * FROM mod_text WHERE content_pagesID=".$content_pagesID;
            //print "\n".$sql."\n";
            $this->log->general("-yyy Text Display->".$sql,3);
            $rows=array();
            $rslt=$this->r->RawQuery($sql);
            //print_r($rslt);
            //$num_rows=$this->r->NumRows($rslt);
            //echo"\n -".$num_rows."-0001-----------------------------------------------------------------------------\n";
            $rows=$this->r->Fetch_Assoc($rslt);
            //print_r($rows);
            //echo"\n 10001-----------------------------------------------------------------------------\n";
            if(count($rows)>0){
                $this->text_data["db"]=$rows;
            }else{
                $this->text_data["db"]=array();
            }
            //return $this->text_data;
            //print_r($text_data);
            //$this->log->general("-yx Text Display->".var_export($this->text_data["db"],true),3);
            //print_r($this->text_data);
        }
        /*
        function Display_Text(){
            $this->log->general("-yxz Text Display->",3);
            //print "-x-";
            if(isset($this->text_data["db"]['content_text'])){
                
                $cur_str=ltrim($this->text_data["db"]['content_text'],"\n\r\t\v\x00");
            }else{
                $cur_str="";
            }

            
            print $cur_str;
        }
        */
        /*
        function Pre_Display(){
            $this->log->general("-yxz Text Display->",3);
            //print "-x-";
            if(isset($this->text_data["db"]['content_text'])){
                
                $cur_str=ltrim($this->text_data["db"]['content_text'],"\n\r\t\v\x00");
            }else{
                $cur_str="";
            }

            
            print $cur_str;
        }
        */
        function Main_Display(){
            //print_r($this->text_data);
            $ret_value="";
            if(isset( $this->text_data["db"]['content_text'])){
                $ret_value=$this->text_data["db"]['content_text'];
            }
                
            return $ret_value;
        }
	
    }