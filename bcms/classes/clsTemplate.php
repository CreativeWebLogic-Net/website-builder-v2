<?php

    class clsTemplate{

        //private $templatesID=0;
        //private $domains_templatesID=0;

        private $template_data=array();
        private $content_data=array();
        private $domain_data=array();
        private $app_data=array();

        private $all_data_arrays=array();
        private $all_data_names=array(0=>"app",1=>"domain",2=>"template",3=>"content");
        private $log;

        private $r;
        private $a;
       
        function __construct(){
			
			$this->Set_Log(clsClassFactory::$all_vars['log']);
            $this->Set_DataBase(clsClassFactory::$all_vars['r']);
		}
        public function Set_DataBase($r){
			$this->r=$r;
			
		}

        public function Set_Log($log){
			$this->log=$log;
			
		}

        function Set_Assorted($a){
            $this->a=$a;
            //$this->a->add_tag_array($this->a->tag_replace());
		}

        public function Template_Set_Data_Arrays($template_data,$content_data,$domain_data,$app_data){
			$this->template_data=$template_data;
            $this->content_data=$content_data;
            $this->domain_data=$domain_data;
			$this->app_data=$app_data;
		}

        public function Template_Get_Data_Arrays(){
            //$output_array=array($this->all_data_names[2]=>$this->template_data,$this->all_data_names[3]=>$this->content_data,
            //$this->all_data_names[1]=>$this->domain_data,$this->all_data_names[0]=>$this->app_data);

            $output_array=array($this->template_data,$this->content_data,$this->domain_data,$this->app_data);
			return $output_array;
		}

        
        public function Run_Template_Import(){
            $template_code="";
            if(isset($this->template_data["db"]['dir'])){
				$this->template_data['My_Dir']=$this->app_data['APPBASEDIR']."templates/".$this->template_data["db"]['dir'];
				//$load_file=$this->template_data['My_Dir']."/index.php";
                $load_file=$this->template_data['My_Dir'];
				//$this->log->general("-End line-".$load_file,9);
				//print $load_file;
				$this->log->general("-ar Loading Template->",9,$this->template_data["db"]);
				//echo"\n\n-10----".$load_file."----------------------------------------------------\n\n";
                
				if(file_exists($load_file)){
					$this->app_data["include_callback"]="callback_template";
					$filepath=$load_file;
		            $template_code=$this->Load_File($load_file);
                    //echo"\n\n-1001----".$template_code."----------------------------------------------------\n\n";
				}else{
					throw new Exception('Template not loading.');
				}
                
			}else{
				exit("No Template File");
			}
            //$template_code = wordwrap($template_code, 50, "\n<br>");
            //echo"\n\n-1001----\n\n".base64_decode($template_code)."\n\n----------------------------------------------------\n\n";
            //echo"\n\n-1001----\n\n".$template_code."\n\n----------------------------------------------------\n\n";
            
            return $template_code;
        }
        
        public function Run_Template(){
            
            
            $template_size=strlen($this->template_data["db"]['filedata']);
            $this->log->general("-RT Loading Template->".$template_size,9,$this->template_data["db"]);
            if($template_size>0){
                
				return base64_decode($this->template_data["db"]['filedata']);
                //return $this->template_data["db"]['filedata'];
				
				
			}else{
				exit("No Template File");
			}
        }

        private function Load_File($file_wrapper){
            $template_code="";
            $normal=$file_wrapper."/index.php";
            $new=$file_wrapper."/index-new.php";
            $run_file="";
            if(file_exists($new)){
                $run_file=$new;
            }elseif(file_exists($normal)){
                $run_file=$normal;
            }
            
            //include($file_wrapper);
            $template_code = base64_encode(file_get_contents($run_file));

            $this->log->general("-FFF Loading Template->".$run_file,9,$template_code);
            return $template_code;
        }

        public function Template_Init(){
            $this->templatesID=0;
            $this->domains_templatesID=0;
            //set sql result non capitalized
            //print_r($content_data);
            
            if(isset($this->content_data["db"])){
                
                if(isset($this->content_data["db"]['templatesID'])){
                    if($this->content_data["db"]['templatesID']==0){
                        if(isset($this->domain_data["db"]['templatesID'])){
                            if($this->domain_data["db"]['templatesID']>0){
                                $templatesID=$this->domain_data["db"]['templatesID'];
                            }else{
                                $templatesID=0;
                            }				
                        }else{
                            $templatesID=0;
                        }
                    }else{
                        $templatesID=$this->content_data["db"]['templatesID'];
                    }			
                }elseif(isset($this->domain_data["db"]['templatesID'])){
                    if($this->domain_data["db"]['templatesID']>0){
                        $templatesID=$this->domain_data["db"]['templatesID'];
                    }else{
                        $this->templatesID=0;
                    }
                
                }else{
                    $templatesID=0;
                }
                //if content page has a custom template then overwrite the domain template
                if($templatesID>0){
                    $sql="SELECT * FROM templates WHERE id='".$templatesID."'";
                    //$sql="SELECT * FROM templates WHERE id='27'";
                    //$sql="SELECT * FROM templates";
                    $rslt=$this->r->RawQuery($sql);
                    $num_rows=$this->r->NumRows($rslt);
                    if($num_rows>0){
                        $this->template_data["db"]=$this->r->Fetch_Assoc($rslt);
                        //print_r($sql);
                        $this->log->general("DDD HTML Template",9,strlen($this->template_data["db"]['filedata']));
                        $this->template_data["db"] = $this->a->strip_capitals($this->template_data["db"]);
                        if(count($this->template_data["db"])==0){
                            //exit("No Template->".$sql);
                            //$error_message="No template found=>".$sql;
                            //echo $error_message;
                            //print_r($this->template_data["db"]);
                            
                            $this->log->general("No template found=>",4,$this->template_data["db"]);
                        }
                        if(strlen($this->template_data["db"]['filedata'])==0){
                            $this->log->general("No HTML Template",9,strlen($this->template_data["db"]['filedata']));
                        }
                        //echo "\n\n 123-------\n\n";
                        //print_r($template_data);
                        $this->template_data['TEMPLATEPATH']=$this->app_data['APPBASEDIR']."templates/".$this->template_data["db"]['dir'];
                        $this->template_data['TEMPLATEDIR']=$this->template_data['TEMPLATEPATH'];
                    }else{
                        exit("No Template->".$sql);
                    }
                }
                
                
                
                //echo "xxx";
            }
        }
        
    }

?>