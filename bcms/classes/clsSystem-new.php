<?php

    class clsSystem{
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

        private $Current_Dir="";

        private $membersID;
        private $template_code="";

        function __construct(){
            
		}

        static function execute_webpage(){
            self::Find_Current_Directory();
            self::Set_Autoloader();
            self::Set_Exception_Handler();
            self::Set_Error_Handler();
            
            self::Set_Headers();
            
            
            self::Set_Assorted();
            
			self::Set_Log();
			self::Set_Variables();
            self::Set_Base_Constants();
            self::Set_DataBase();
            self::Set_Session_Handler();
            self::Set_Data_Array_Vars();
            self::Set_Each_Data_Vars();
            self::Set_App_Vars();
            self::Set_Asset_Servers();
            
            self::Set_DataBase_Data_Array();
            self::Set_Memebers();

            self::Set_Domain_Init();
            self::Set_Content_Init();
            self::Set_Language_Init();
            self::Set_Template_Init();
            self::Set_Language_Definitions();
            self::Set_Template();
            self::Set_Content();
            self::Output_HTML();
            self::log->output_messages();
            self::e->output_errors();
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
            include(self::Current_Dir."/classes/clsAutoloader.php");
            // Register the static method as the autoload function
            spl_autoload_register(array("clsAutoloader", "load"));
        }

        
        function Set_Session_Handler(){
            $sess = new clsSession();
            self::sess->set_database(self::r);
            
            self::sess->Set_Log(self::log);
            self::sess->set_new_guid(self::make_guid());
            self::sess->set_ip_address($_SERVER['REMOTE_ADDR']);
            self::sess->session_set_globals();
            self::sess->session_start();
            //$dir=self::Current_Dir."/sessions/";
            //self::sess->session_save_path($dir);
            
            //session_set_save_handler(self::sess, true);
            //session_start();
        
        }

        function Set_Class($class_name,$obj=null){
            if(is_null($obj)){
                self::all_classes[$class_name]=new $class_name();
            }else{
                self::all_classes[$class_name]=$obj;
            }
            self::proxy_classes[$class_name] = new clsGenericProxy(self::all_classes[$class_name]);
		}

        function Set_Exception_Handler(){
            self::all_classes["exception_handler"]=new clsExceptionHandler();
			set_exception_handler([self::all_classes["exception_handler"], 'handle']);
		}

        function Set_Error_Handler(){
            $e=new clsError();
			self::e=$e;
            set_error_handler(array($this, 'myErrorHandler'));
            //set_error_handler('clsError::myErrorHandler');
		}
        
        function Set_DataBase(){
            $r=new clsDatabaseInterface(self::log);
			self::r=$r;
            self::r->Set_Vs(self::vs);
            self::Set_DataBase_Data_Array();
            self::r->CreateDB();
            //trigger_error("Test Msg", E_USER_ERROR);
            //xxx
            //self::r->Add_App_Data(self::app_data);
		}

        function myErrorHandler($errno, $errstr, $errfile, $errline)
        {
            self::e->myErrorHandler($errno, $errstr, $errfile, $errline);
        }

        function Set_DataBase_Data_Array(){
            //print_r(self::app_data);
            self::log->general("App Data Array ",4,self::app_data);
            //echo"kkk";
            self::r->Add_App_Data(self::app_data);
		}

        function Set_Log(){
            $log = new clsLog();
			self::log=$log;
			
		}

        function Set_Variables(){
            self::vs=new clsVariables();
		    self::vs->Set_Log(self::log);
			
		}

        function Find_Current_Directory()
        {
            if(self::Current_Dir==""){
                self::Current_Dir=pathinfo(__DIR__);
            }
            self::Current_Dir=self::Current_Dir['dirname'];
            //print_R(self::Current_Dir);
            //return self::Current_Dir;
            
            
        }

        function Set_Assorted($tag_match_array=array()){
            self::a=new clsAssortedFunctions();
            //self::Current_Dir=self::a->Find_Current_Directory();
            //print_r(self::Current_Dir);
            //self::a->add_tag_array(self::a->tag_replace());
		}

        function Set_Data_Array_Vars(){
			//----------------------------------------------------------------
            // root data types
            //----------------------------------------------------------------
            self::all_data_arrays[self::all_data_names[0]."_data"]=self::app_data;
            self::all_data_arrays[self::all_data_names[1]."_data"]=self::module_data;
            self::all_data_arrays[self::all_data_names[2]."_data"]=self::domain_user_data;
            self::all_data_arrays[self::all_data_names[3]."_data"]=self::domain_data;
            self::all_data_arrays[self::all_data_names[4]."_data"]=self::template_data;
            self::all_data_arrays[self::all_data_names[5]."_data"]=self::content_data;
            self::all_data_arrays[self::all_data_names[6]."_data"]=self::text_data;
            self::all_data_arrays[self::all_data_names[7]."_data"]=self::bizcat_data;
            self::all_data_arrays[self::all_data_names[8]."_data"]=self::sidebar_data;
            self::all_data_arrays[self::all_data_names[9]."_data"]=self::news_data;
            self::all_data_arrays[self::all_data_names[10]."_data"]=self::content_domain_data;
            self::all_data_arrays[self::all_data_names[11]."_data"]=self::menu_data;
		}

        function Set_Each_Data_Vars(){
			//----------------------------------------------------------------
            // root data types
            //----------------------------------------------------------------
            self::app_data=self::all_data_arrays[self::all_data_names[0]."_data"];
            self::module_data=self::all_data_arrays[self::all_data_names[1]."_data"];
            self::domain_user_data=self::all_data_arrays[self::all_data_names[2]."_data"];
            self::domain_data=self::all_data_arrays[self::all_data_names[3]."_data"];
            self::template_data=self::all_data_arrays[self::all_data_names[4]."_data"];
            self::content_data=self::all_data_arrays[self::all_data_names[5]."_data"];
            self::text_data=self::all_data_arrays[self::all_data_names[6]."_data"];
            self::bizcat_data=self::all_data_arrays[self::all_data_names[7]."_data"];
            self::sidebar_data=self::all_data_arrays[self::all_data_names[8]."_data"];
            self::news_data=self::all_data_arrays[self::all_data_names[9]."_data"];
            self::content_domain_data=self::all_data_arrays[self::all_data_names[10]."_data"];
            self::menu_data=self::all_data_arrays[self::all_data_names[11]."_data"];
		}

        function Set_App_Vars(){
			//----------------------------------------------------------------
            // root data types
            //----------------------------------------------------------------
            
            self::module_data=array();
            self::module_data['db']=array();
            self::domain_data['db']=array();
            self::template_data['db']=array();
            self::content_data['db']=array();
            self::text_data['db']=array();
		}

        function Set_Asset_Servers(){
            //--------------------------------------------------
            
            //print_r($current_dir);
            //echo"0001-----------------------------------------------------------------------------\n";
            //----------------------------------static asset files------------------------------
            self::app_data['asset-severs'][0]='https://assets.bubblecms.biz/'; // linode server
            self::app_data['asset-severs'][1]='https://spaces.auseo.net/'; // digital ocean custom server
            self::app_data['asset-severs'][2]='https://static-cms.nyc3.cdn.digitaloceanspaces.com/'; // digital ocean cdn server
            self::app_data['asset-severs'][3]='https://static-cms.nyc3.digitaloceanspaces.com/'; //digital ocean standard server
            self::app_data['asset-severs'][4]='https://assets.ownpage.club/'; //asura standard server
            self::app_data['asset-severs'][5]='https://assets.hostingdiscount.club/'; //asura reseller server
            self::app_data['asset-severs'][6]='https://assets.icwl.me/'; //hostgator reseller server
            self::app_data['asset-severs'][7]='https://static-assets.w-d.biz/'; //cloud unlimited server
            self::app_data['asset-severs'][8]='https://assets.i-n.club/'; //ionos unlimited server
            self::app_data['asset-severs'][9]='https://assets.creativeweblogic.net/'; //ionos unlimited server
            self::app_data['asset-severs'][10]='https://static-assets.site/'; //ionos unlimited server
            self::app_data['asset-severs'][11]='https://f005.backblazeb2.com/file/iCWLNet-Website-Assets/';

            self::app_data['current_asset-sever']=self::app_data['asset-severs'][11];
            
            self::app_data["include_callback"]="callback";
            //----------------------------------------------------------------
        }

        function Set_Base_Constants(){
            //echo"xxx";
            //if(isset($_GET['cpid'])){
            $root_array=explode('/',$_SERVER['PHP_SELF']);
            //print_r($root_array);
            if($root_array[1]=="bcms"){
                self::app_data['APPBASEDIR']='./';
                self::app_data['ROOTDIR']='//bcms//';
            }else{
                self::app_data['APPBASEDIR']='bcms/';
                self::app_data['ROOTDIR']='/';
            }
            //echo"000101-----------------------------------------------------------------------------\n";
            self::app_data['APPLICATIONSDIR']=self::app_data['APPBASEDIR'].'apps';
            self::app_data['MODULEBASEDIR']=self::app_data['APPBASEDIR'].'modules/';
            self::app_data['CLASSESBASEDIR']=self::app_data['APPBASEDIR'].'classes/';
            self::app_data['INCLUDESBASEDIR']=self::app_data['APPBASEDIR'].'includes/';
            //----------------------------------------------------------------
        }

        function Set_Memebers(){
            
            if(isset($_SESSION['membersID'])){
                self::membersID=$_SESSION['membersID'];
            }else{
                
                self::membersID=0;
            }
        }
        function Set_Domain_Init(){
            self::all_classes["domain"]=new clsDomain();
            self::all_classes["domain"]->Set_DataBase(self::r);
            self::all_classes["domain"]->Set_Log(self::log);
            //$content_domain_data,$content_data,$domain_data,$domain_user_data,$app_data
            self::all_classes["domain"]->Domain_Set_Data_Arrays(self::content_domain_data,self::content_data,self::domain_data,self::domain_user_data,self::app_data);
            self::all_classes["domain"]->Domain_Init();
            $temp_array=self::all_classes["domain"]->Domain_Get_Data_Arrays();
            self::Set_Data_Array_Changes($temp_array);
        }
        function Set_Content_Init(){
            self::all_classes["content"]=new clsContent();
            self::all_classes["content"]->Set_DataBase(self::r);
            self::all_classes["content"]->Set_Log(self::log);
            self::all_classes["content"]->Set_Assorted(self::a);
            //$content_domain_data,$content_data,$domain_data,$domain_user_data,$module_data,$bizcat_data,$app_data
            self::all_classes["content"]->Content_Set_Data_Arrays(self::content_domain_data,self::content_data,self::domain_data,self::domain_user_data,self::module_data,self::bizcat_data,self::app_data);
            self::all_classes["content"]->Content_Init();
            $temp_array=self::all_classes["content"]->Content_Get_Data_Arrays();
            self::Set_Data_Array_Changes($temp_array);
        }
        function Set_Language_Init(){
            self::all_classes["language"]=new clsLanguage();
            self::all_classes["language"]->Set_DataBase(self::r);
            self::all_classes["language"]->Set_Log(self::log);
            self::all_classes["language"]->Language_Set_Data_Arrays(self::app_data);
            self::all_classes["language"]->Language_Init();
            $temp_array=self::all_classes["language"]->Language_Get_Data_Arrays();
            self::Set_Data_Array_Changes($temp_array);
        }

        function Set_Template_Init(){
            self::all_classes["template"]=new clsTemplate();
            self::all_classes["template"]->Set_DataBase(self::r);
            self::all_classes["template"]->Set_Log(self::log);
            self::all_classes["template"]->Set_Assorted(self::a);
            self::all_classes["template"]->Template_Set_Data_Arrays(self::template_data,self::content_data,self::domain_data,self::app_data);
            self::all_classes["template"]->Template_Init();
            $temp_array=self::all_classes["template"]->Template_Get_Data_Arrays();
            self::Set_Data_Array_Changes($temp_array);
        }

        function Set_Data_Array_Changes($temp_array){
            foreach($temp_array as $key=>$val){
                self::all_data_arrays[$key."_data"]=$val;
            }
            self::Set_Each_Data_Vars();
        }

        function Set_Language_Definitions(){
            //$language=new clsLanguage();
            self::all_classes["language"]->Language_Definitions();
        }

        function Set_Template(){
            $template_code="";
            self::template_code=self::all_classes["template"]->Run_Template();
            
            //print $template_code;
        }

        function Set_Modules(){
            self::all_classes["module"]=new clsModules();
            self::all_classes["module"]->Set_DataBase(self::r);
            self::all_classes["module"]->Set_Log(self::log);
            self::module_data['db']=self::all_classes["module"]->Find_Module(self::all_data_arrays["content_data"]["db"]['id']);
            self::module_data['views']=self::all_classes["module"]->Find_Module_View(self::all_data_arrays["content_data"]["db"]['module_viewsID']);
            $temp_array=self::all_classes["module"]->Language_Get_Data_Arrays();
            self::Set_Data_Array_Changes($temp_array);
        }

        function Output_HTML(){
            $output_code=self::template_code;
            $keywords=self::all_data_arrays["content_data"]["db"];
            $main_menu="";
            $main_content="";
            $tag_match_array=array("asset-sever"=>self::app_data['current_asset-sever'],"html-title"=>$keywords['title'],"dc-title"=>$keywords['meta_title'],
            "meta_description"=>$keywords['meta_description'],"meta_keywords"=>$keywords['meta_keywords'],"main-menu"=>$main_menu,
            "main-title"=>$keywords['title'],"main-content"=>$main_content);
            $output_code=self::a->modify_tags(self::template_code,$tag_match_array);
            print $output_code;
            //print_r( self::all_data_arrays);
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
