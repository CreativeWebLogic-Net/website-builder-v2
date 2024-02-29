<?php

    class clsSystem Extends clsClassFactory{
        private $app_data=array();
        private $module_data=array();
        private $domain_user_data=array();
        private $domain_data=array();
        private $template_data=array();
        private $content_data=array();
        private $text_data=array();
        private $bizcat_data=array();
        private $sidebar_data=array();
        private $news_data=array();
        private $content_domain_data=array();

        private $menu_data=array();
        private $all_data_arrays=array();
        private $all_data_names=array(0=>"app",1=>"module",2=>"domain_user",3=>"domain",4=>"template",5=>"content",6=>"text",
        7=>"bizcat",8=>"sidebar",9=>"news",10=>"content_domain",11=>"menu");
        private $all_classes=array();
        private $proxy_classes=array();

        private $sess;
        private $log;
        private $r;
        private $a;

        private $e;
        private $vs;
        public static $vars;

        private $bc;

        private $Current_Dir="";

        private $membersID;
        private $template_code="";

        private $content_output_html="";

        public $name_map=array('clsDomain'=>'dom','clsMembers'=>'mb','clsContent'=>'con','clsLanguage'=>'lang','clsTemplate'=>'tmpt','clsExceptionHandler'=>'exc');

        function __construct(){
            parent::__construct();
            $this->Set_Exception_Handler();
            $this->Set_System_bindings();
            //echo "I Am Legendary 001 \n";
            $this->Set_Headers();
            
            $this->Find_Current_Directory();
            //echo "I Am Legendary 0002 \n";
            $this->Set_Autoloader();
            $this->Set_App_Vars();
            //echo "I Am Legendary 0003 \n";
            $this->Set_Base_Constants();
            //echo "I Am Legendary 300012";
            
            //echo "I Am Legendary 400001";
            
            //echo "I Am Legendary 5000";
            $this->execute_webpage();
            //echo "I Am Legendary 60000";
		}

        public function execute_webpage(){
            
            
            //$this->Set_Variable_Storage();
            //$this->Set_Variable_Array();
            //$this->Set_Data_Array_Vars();
            
            
            
            
            //$this->Set_Exception_Handler();
            //$this->Set_Error_Handler();
            
            
            
            
            //$this->Set_Assorted();
            
			//$this->Set_Log();
			//$this->Set_Variables();
            //echo "I Am Legendary 000200 \n";
            $this->Set_DataBase();
            $this->Set_Session_Handler();
            
            //$this->Set_Each_Data_Vars();
            //echo "I Am Legendary 30000 \n";
            $this->Set_Asset_Servers();
            
            $this->Set_DataBase_Data_Array();
            $this->Set_Memebers();

            $this->Set_Domain_Init();
            $this->Set_Content_Init();
            $this->Set_Language_Init();
            //echo "I Am Legendary 40000 \n";
            $this->Set_Template_Init();
            $this->Set_Language_Definitions();
            $this->Set_Template();

            //echo "I Am Legendary 50000 \n";
            
            //$this->Set_Content();
            $this->Output_HTML();
            //$this->log->output_messages();
            //$this->e->output_errors();
            //echo"xxx";
            //throw new Exception('Uncaught Exception');
            
        }

        function Set_Headers(){
            if (isset($_SERVER['HTTP_ORIGIN'])) {
                $http_origin = $_SERVER['HTTP_ORIGIN'];
                header("Access-Control-Allow-Origin: $http_origin");
            }
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        }

        
        function Set_Autoloader(){
            //include($this->Current_Dir."/classes/clsAutoloader.php");
            // Register the static method as the autoload function
            
        }

        
        function Set_Session_Handler(){
            $this->sess = new clsSession();
            $this->sess->set_database($this->r);
            
            $this->sess->Set_Log($this->log);
            $this->sess->set_new_guid($this->make_guid());
            $this->sess->set_ip_address($_SERVER['REMOTE_ADDR']);
            $this->sess->session_set_globals();
            $this->sess->session_start();
            //$dir=$this->Current_Dir."/sessions/";
            //$this->sess->session_save_path($dir);
            
            //session_set_save_handler($this->sess, true);
            //session_start();
        
        }

        function Set_System_bindings(){
            error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
            //error_reporting(E_ALL);
            ini_set('display_errors', 1);
            
            //$this->Set_Error_Handler();
        }

        function Set_Exception_Handler(){
            //$ex=&parent::$all_vars['ex']->get_object();
            //print_r(parent::$all_vars);
            $ex=parent::$all_vars['ex']->get_object();
            //$this->all_classes["exception_handler"]=new clsExceptionHandler();
			//set_exception_handler([$this->all_classes["exception_handler"], 'handle']);
            set_exception_handler([$ex, 'handle']);
		}
        /*
        function Set_Error_Handler(){
            $e=new clsError();
			$this->e=$e;
            set_error_handler(array($this, 'myErrorHandler'));
            //set_error_handler('clsError::myErrorHandler');
		}
        */
        function Set_Class($class_name,$obj=null){
            if(is_null($obj)){
                $this->all_classes[$class_name]=new $class_name();
            }else{
                $this->all_classes[$class_name]=$obj;
            }
            $this->proxy_classes[$class_name] = new clsGenericProxy($this->all_classes[$class_name]);
		}

        
        
        function Set_DataBase(){
            $r=new clsDatabaseInterface($this->log);
			$this->r=$r;
            $this->r->Set_Vs($this->vs);
            $this->Set_DataBase_Data_Array();
            $this->r->CreateDB();
            //trigger_error("Test Msg", E_USER_ERROR);
            //xxx
            //$this->r->Add_App_Data(self::$vars->app_data);
		}

        function myErrorHandler($errno, $errstr, $errfile, $errline)
        {
            $this->e->myErrorHandler($errno, $errstr, $errfile, $errline);
        }

        function Set_DataBase_Data_Array(){
            //print_r(self::$vars->app_data);
            //$this->log->general("App Data Array ",4,self::$vars->app_data);
            //echo"kkk";
            $this->r->Add_App_Data(self::$vars->app_data);
		}
        /*
        function Set_Log(){
            $log = new clsLog();
			$this->log=$log;
			
		}

        function Set_Variables(){
            $this->vs=new clsVariables();
		    $this->vs->Set_Log($this->log);
			
		}
        */
        function Set_Variable_Storage(){
            self::$vars=new clsObjectAccess();
			
		}

        function Set_Basic_Classes(){
            $this->bc=new clsObjectAccess();
			
		}
        /*
        function Set_Basic_Class_Objects(){
            /*
            $this->bc->r=$this->r;
            parent::$all_vars['a']=$this->a;
            parent::$all_vars['log']=$this->log;
            parent::$all_vars['sess']=$this->sess;
			
            $this->bc->r=parent::$all_vars['r'];
            parent::$all_vars['a']=parent::$all_vars['a'];
            parent::$all_vars['log']=parent::$all_vars['log'];
            parent::$all_vars['sess']=parent::$all_vars['sess'];
		}
        */
        

        function Find_Current_Directory()
        {
            if($this->Current_Dir==""){
                $this->Current_Dir=pathinfo(__DIR__);
            }
            $this->Current_Dir=$this->Current_Dir['dirname'];
            //print_R($this->Current_Dir);
            //return $this->Current_Dir;
            
            
        }
        /*
        function Set_Assorted($tag_match_array=array()){
            $this->a=new clsAssortedFunctions();
            //$this->Current_Dir=$this->a->Find_Current_Directory();
            //print_r($this->Current_Dir);
            //$this->a->add_tag_array($this->a->tag_replace());
		}
        */
        /*
        function Set_Data_Array_Vars(){
			//----------------------------------------------------------------
            // root data types
            //----------------------------------------------------------------
            $this->all_data_arrays[$this->all_data_names[0]."_data"]=$this->app_data;
            $this->all_data_arrays[$this->all_data_names[1]."_data"]=$this->module_data;
            $this->all_data_arrays[$this->all_data_names[2]."_data"]=$this->domain_user_data;
            $this->all_data_arrays[$this->all_data_names[3]."_data"]=$this->domain_data;
            $this->all_data_arrays[$this->all_data_names[4]."_data"]=$this->template_data;
            $this->all_data_arrays[$this->all_data_names[5]."_data"]=$this->content_data;
            $this->all_data_arrays[$this->all_data_names[6]."_data"]=$this->text_data;
            $this->all_data_arrays[$this->all_data_names[7]."_data"]=$this->bizcat_data;
            $this->all_data_arrays[$this->all_data_names[8]."_data"]=$this->sidebar_data;
            $this->all_data_arrays[$this->all_data_names[9]."_data"]=$this->news_data;
            $this->all_data_arrays[$this->all_data_names[10]."_data"]=$this->content_domain_data;
            $this->all_data_arrays[$this->all_data_names[11]."_data"]=$this->menu_data;

            //self::$vars->set_array($this->all_data_arrays);
            //exit("Hello");
		}
        */
        /*
        function Set_Variable_Array(){
            self::$vars->app_data=$this->app_data;
            self::$vars->module_data=$this->module_data;
            self::$vars->domain_user_data=$this->domain_user_data;
            self::$vars->domain_data=$this->domain_data;
            self::$vars->template_data=$this->template_data;
            self::$vars->content_data=$this->content_data;
            self::$vars->text_data=$this->text_data;
            self::$vars->bizcat_data=$this->bizcat_data;
            self::$vars->sidebar_data=$this->sidebar_data;
            self::$vars->news_data=$this->news_data;
            self::$vars->content_domain_data=$this->content_domain_data;
            self::$vars->menu_data=$this->menu_data;

            //print_r(self::$vars->data);
        }
        */
        /*
        function Set_Each_Data_Vars(){
			//----------------------------------------------------------------
            // root data types
            //----------------------------------------------------------------
            $this->app_data=$this->all_data_arrays[$this->all_data_names[0]."_data"];
            $this->module_data=$this->all_data_arrays[$this->all_data_names[1]."_data"];
            $this->domain_user_data=$this->all_data_arrays[$this->all_data_names[2]."_data"];
            $this->domain_data=$this->all_data_arrays[$this->all_data_names[3]."_data"];
            $this->template_data=$this->all_data_arrays[$this->all_data_names[4]."_data"];
            $this->content_data=$this->all_data_arrays[$this->all_data_names[5]."_data"];
            $this->text_data=$this->all_data_arrays[$this->all_data_names[6]."_data"];
            $this->bizcat_data=$this->all_data_arrays[$this->all_data_names[7]."_data"];
            $this->sidebar_data=$this->all_data_arrays[$this->all_data_names[8]."_data"];
            $this->news_data=$this->all_data_arrays[$this->all_data_names[9]."_data"];
            $this->content_domain_data=$this->all_data_arrays[$this->all_data_names[10]."_data"];
            $this->menu_data=$this->all_data_arrays[$this->all_data_names[11]."_data"];
		}
        */

        function Set_App_Vars(){
			//----------------------------------------------------------------
            // root data types
            //----------------------------------------------------------------
            //self::$vars=array();
            if (!is_object(self::$vars)) {
                self::$vars = new stdClass();
              }
              
            
            self::$vars->module_data=array();
            self::$vars->module_data['db']=array();
            self::$vars->domain_data['db']=array();
            self::$vars->template_data['db']=array();
            self::$vars->content_data['db']=array();
            self::$vars->text_data['db']=array();
            self::$vars->content_domain_data=array();
            self::$vars->domain_user_data=array();
            self::$vars->app_data=array();
            self::$vars->bizcat_data=array();
            
		}

        function Set_Asset_Servers(){
            //--------------------------------------------------
            
            //print_r($current_dir);
            //echo"0001-----------------------------------------------------------------------------\n";
            //----------------------------------static asset files------------------------------
            self::$vars->app_data['asset-severs'][0]='https://assets.bubblecms.biz/'; // linode server
            self::$vars->app_data['asset-severs'][1]='https://spaces.auseo.net/'; // digital ocean custom server
            self::$vars->app_data['asset-severs'][2]='https://static-cms.nyc3.cdn.digitaloceanspaces.com/'; // digital ocean cdn server
            self::$vars->app_data['asset-severs'][3]='https://static-cms.nyc3.digitaloceanspaces.com/'; //digital ocean standard server
            self::$vars->app_data['asset-severs'][4]='https://assets.ownpage.club/'; //asura standard server
            self::$vars->app_data['asset-severs'][5]='https://assets.hostingdiscount.club/'; //asura reseller server
            self::$vars->app_data['asset-severs'][6]='https://assets.icwl.me/'; //hostgator reseller server
            self::$vars->app_data['asset-severs'][7]='https://static-assets.w-d.biz/'; //cloud unlimited server
            self::$vars->app_data['asset-severs'][8]='https://assets.i-n.club/'; //ionos unlimited server
            self::$vars->app_data['asset-severs'][9]='https://assets.creativeweblogic.net/'; //ionos unlimited server
            self::$vars->app_data['asset-severs'][10]='https://static-assets.site/'; //ionos unlimited server
            self::$vars->app_data['asset-severs'][11]='https://f005.backblazeb2.com/file/iCWLNet-Website-Assets/';

            self::$vars->app_data['current_asset-sever']=self::$vars->app_data['asset-severs'][11];
            
            self::$vars->app_data["include_callback"]="callback";
            //----------------------------------------------------------------
        }

        function Set_Base_Constants(){
            //echo"xxx";
            //if(isset($_GET['cpid'])){
            $root_array=explode('/',$_SERVER['PHP_SELF']);
            //print_r($root_array);
            /*
            if($root_array[1]=="bcms"){
                self::$vars->app_data['APPBASEDIR']='./';
                self::$vars->app_data['ROOTDIR']='//bcms//';
            }else{
                self::$vars->app_data['APPBASEDIR']='bcms/';
                self::$vars->app_data['ROOTDIR']='/';
            }
            */
            //echo"000222-----------------------------------------------------------------------------\n";
            self::$vars->app_data['APPBASEDIR']='bcms/';
                self::$vars->app_data['ROOTDIR']='/';
            //echo"000101-----------------------------------------------------------------------------\n";
            self::$vars->app_data['APPLICATIONSDIR']=self::$vars->app_data['APPBASEDIR'].'apps';
            self::$vars->app_data['MODULEBASEDIR']=self::$vars->app_data['APPBASEDIR'].'modules/';
            self::$vars->app_data['CLASSESBASEDIR']=self::$vars->app_data['APPBASEDIR'].'classes/';
            self::$vars->app_data['INCLUDESBASEDIR']=self::$vars->app_data['APPBASEDIR'].'includes/';
            //print_r(self::$vars->app_data);
            //exit('dd');
            //----------------------------------------------------------------
        }

        function Set_Memebers(){
            
            if(isset($_SESSION['membersID'])){
                $this->membersID=$_SESSION['membersID'];
            }else{
                
                $this->membersID=0;
            }
        }
        

        function Set_Data_Array_Changes($temp_array){
            echo"500101-----------------------------------------------------------------------------\n";
            //print_r($temp_array);
            self::$vars->print_variable_array();
            foreach($temp_array as $key=>$val){

                self::$vars->set_array(array($key."_data"=>$val));
                //$this->all_data_arrays[$key."_data"]=$val;content_data
            }
           //$this->Set_Each_Data_Vars();
           echo"700666-----------------------------------------------------------------------------\n";
        }

        function Set_Language_Definitions(){
            //$language=new clsLanguage();
            $this->all_classes["language"]->Language_Definitions();
        }

        function Set_Template(){
            $import_template=false;
            //$import_template=true;

            $template_code="";
            if($import_template){
                $this->template_code=$this->all_classes["template"]->Run_Template_Import();
            }else{
                $this->template_code=$this->all_classes["template"]->Run_Template();
            }
            
            
            //print "vvv".base64_decode($this->template_code);
            //print "vvv".$this->template_code;
        }

        function Set_Domain_Init(){
            $this->all_classes["domain"]=new clsDomain();
            $this->all_classes["domain"]->Set_DataBase(parent::$all_vars['r']);
            $this->all_classes["domain"]->Set_Log(parent::$all_vars['log']);
            //$content_domain_data,$content_data,$domain_data,$domain_user_data,$app_data
            $this->all_classes["domain"]->Domain_Set_Data_Arrays(self::$vars->content_domain_data,self::$vars->content_data,self::$vars->domain_data,self::$vars->domain_user_data,self::$vars->app_data);
            $this->all_classes["domain"]->Domain_Init();
            $temp_array=$this->all_classes["domain"]->Domain_Get_Data_Arrays();
            self::$vars->content_domain_data=$temp_array[0];
            self::$vars->content_data=$temp_array[1];
            self::$vars->domain_data=$temp_array[2];
            self::$vars->domain_user_data=$temp_array[3];
            self::$vars->app_data=$temp_array[4];
            //$this->Set_Data_Array_Changes($temp_array);
        }
        function Set_Content_Init(){
            
            $this->all_classes["content"]=new clsContent();
            
            $this->all_classes["content"]->Set_DataBase(parent::$all_vars['r']);
            $this->all_classes["content"]->Set_Log(parent::$all_vars['log']);
            $this->all_classes["content"]->Set_Assorted(parent::$all_vars['a']);
            
            //$content_domain_data,$content_data,$domain_data,$domain_user_data,$module_data,$bizcat_data,$app_data
            $this->all_classes["content"]->Content_Set_Data_Arrays(self::$vars->content_domain_data,self::$vars->content_data,self::$vars->domain_data,self::$vars->domain_user_data,self::$vars->module_data,self::$vars->bizcat_data,self::$vars->app_data);
            //echo"7777-----------------------------------------------------------------------------\n";
            $this->all_classes["content"]->Content_Init();
            //echo"8888-----------------------------------------------------------------------------\n";
            $this->content_output_html=$this->all_classes["content"]->Display();
            
            $temp_array=$this->all_classes["content"]->Content_Get_Data_Arrays();
            
            //print_r($temp_array);
            self::$vars->content_domain_data=$temp_array[0];
            self::$vars->content_data=$temp_array[1];
            self::$vars->domain_data=$temp_array[2];
            self::$vars->bizcat_data=$temp_array[3];
            self::$vars->domain_user_data=$temp_array[4];
            self::$vars->module_data=$temp_array[5];
            self::$vars->app_data=$temp_array[6];
            //$this->Set_Data_Array_Changes($temp_array);
        }
        function Set_Language_Init(){
            $this->all_classes["language"]=new clsLanguage();
            $this->all_classes["language"]->Set_DataBase(parent::$all_vars['r']);
            $this->all_classes["language"]->Set_Log(parent::$all_vars['log']);
            $this->all_classes["language"]->Language_Set_Data_Arrays(self::$vars->app_data);
            $this->all_classes["language"]->Language_Init();
            $temp_array=$this->all_classes["language"]->Language_Get_Data_Arrays();
            self::$vars->app_data=$temp_array[0];
            //$this->Set_Data_Array_Changes($temp_array);
        }

        function Set_Template_Init(){
            $this->all_classes["template"]=new clsTemplate();
            $this->all_classes["template"]->Set_DataBase(parent::$all_vars['r']);
            $this->all_classes["template"]->Set_Log(parent::$all_vars['log']);
            $this->all_classes["template"]->Set_Assorted(parent::$all_vars['a']);
            $this->all_classes["template"]->Template_Set_Data_Arrays(self::$vars->template_data,self::$vars->content_data,self::$vars->domain_data,self::$vars->app_data);
            $this->all_classes["template"]->Template_Init();
            $temp_array=$this->all_classes["template"]->Template_Get_Data_Arrays();

            self::$vars->template_data=$temp_array[0];
            self::$vars->content_data=$temp_array[1];
            self::$vars->domain_data=$temp_array[2];
            self::$vars->app_data=$temp_array[3];
            //$this->Set_Data_Array_Changes($temp_array);
        }

        function Set_Modules(){
            $this->all_classes["module"]=new clsModules();
            $this->all_classes["module"]->Set_DataBase(parent::$all_vars['r']);
            $this->all_classes["module"]->Set_Log(parent::$all_vars['log']);
            /*
            self::$vars->module_data['db']=$this->all_classes["module"]->Find_Module($this->all_data_arrays["content_data"]["db"]['id']);
            self::$vars->module_data['views']=$this->all_classes["module"]->Find_Module_View($this->all_data_arrays["content_data"]["db"]['module_viewsID']);
            */
            self::$vars->module_data['db']=$this->all_classes["module"]->Find_Module(self::$vars->content_data["db"]['id']);
            self::$vars->module_data['views']=$this->all_classes["module"]->Find_Module_View(self::$vars->content_data["db"]['module_viewsID']);
            $temp_array=$this->all_classes["module"]->Module_Get_Data_Arrays();
            self::$vars->module_data=$temp_array[0];
            //$this->Set_Data_Array_Changes($temp_array);
        }

        function Output_HTML(){
            $output_code=$this->template_code;
            //print(base64_encode($output_code));
            
            //$keywords=$this->all_data_arrays["content_data"]["db"];
            //print_r(self::$vars->content_data["db"]);
            $keywords=self::$vars->content_data["db"];
            $menu=new clsMenu(self::$vars->domain_user_data,self::$vars->domain_data,self::$vars->content_data,self::$vars->app_data);

            //$main_menu="uuu";//$menu->Horizontal_Rounded();
            $main_menu=$menu->Horizontal_Rounded();
            $main_content=$this->content_output_html;
            $side_bar=$menu->Vertical_Sub_Page();
            //$main_menu=base64_encode($menu->Horizontal_Rounded());
            //$main_content=base64_encode($this->content_output_html);
            //print "->".$this->content_output_html."<-\n";
            
            //print_r($keywords);
            $tag_match_array=array("asset-sever"=>self::$vars->app_data['current_asset-sever'],"html-title"=>$keywords['title'],"dc-title"=>$keywords['meta_title'],
            "meta_description"=>$keywords['meta_description'],"meta_keywords"=>$keywords['meta_keywords'],"main-menu"=>$main_menu,"meta-title"=>$keywords['meta_title'],
            "main-title"=>$keywords['title'],"main-content"=>$main_content,"side-bar"=>$side_bar);

            //$tag_match_array=array("asset-sever"=>"asset-sever","html-title"=>"html-title","dc-title"=>"dc-title",
            //"meta_description"=>"meta_description","meta_keywords"=>"meta_keywords","main-menu"=>"main-menu","meta-title"=>"meta-title",
            //"main-title"=>"main-title","main-content"=>"I Am God","side-bar"=>"I Am Legend");

            //$tag_match_array=array("main-content"=>"I Am God");

            //print_r($tag_match_array);
            //print "->".$this->template_code."<-\n";
            //$tag_match_array=array();
            //$output_code=$this->template_code;
            //$output_code=clsClassFactory::$all_vars['a']->modify_tags($this->template_code,$tag_match_array);
            $output_arrays=clsClassFactory::$all_vars['a']->modify_tags($this->template_code,$tag_match_array);
            //print_r($output_arrays);
            $output_code=clsClassFactory::$all_vars['a']->swap_tags($this->template_code,$output_arrays[0],$output_arrays[1],$output_arrays[2]);
            //$this->a->modify_tags($this->template_code,$tag_match_array);
            print $output_code;
            //
            //print_r( $this->all_data_arrays);
        }


       

        function make_guid ($length=32) 
		{ 
			$key="";    
            $minlength=$length;
            $maxlength=$length;
            $charset = "abcdefghijklmnopqrstuvwxyz"; 
            $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
            $charset .= "0123456789"; 
            if ($minlength > $maxlength) $length = mt_rand ($maxlength, $minlength); 
            else                         $length = mt_rand ($minlength, $maxlength); 
            for ($i=0; $i<$length; $i++) $key .= $charset[(mt_rand(0,(strlen($charset)-1)))]; 
            return $key;
		}
    }
