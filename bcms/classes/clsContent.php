<?php

    class clsContent{

        private $content_data=array();      
        private $domain_data=array();
        private $content_domain_data=array();
        private $domain_user_data=array();
        private $module_data=array();
        private $bizcat_data=array();
        private $app_data=array();
        private $input_data=array();

        private $target=array();

        private $all_data_arrays=array();
        private $all_data_names=array(0=>"app",1=>"module",2=>"domain_user",3=>"domain",4=>"content",5=>"bizcat",6=>"content_domain");
        private $log;

        private $r;
        private $a;
       
        function __construct(){
			
			$this->Set_Input_Variables();
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
            //$this->a->add_tag_array($this->a->tag_replace());
		}

        function Set_Input_Variables(){
            if(isset($_SESSION['membersID'])){
                $this->input_data['membersID']=$_SESSION['membersID'];
            }else{
                $this->input_data['membersID']=0;
            }
            if(isset($_SESSION['LanguagesID'])){
                $this->input_data['LanguagesID']=$_SESSION['LanguagesID'];
            }else{
                $this->input_data['LanguagesID']=1;
            }
            if(isset($_GET['cpid'])){
                $this->input_data['cpid']=$_GET['cpid'];
            }else{
                if(isset($_POST['cpid'])){
                    $this->input_data['cpid']=$_POST['cpid'];
                }else{
                    $this->input_data['cpid']=0;
                }
            }
            if(isset($_GET['dcmsuri'])){
                $this->input_data['dcmsuri']=$_GET['dcmsuri'];
            }else{
                $this->input_data['dcmsuri']=0;
            }
            if(isset($_GET['change'])){
                $this->input_data['change']=$_GET['change'];
            }else{
                $this->input_data['change']=0;
            }           
            $this->input_data['REQUEST_URI']=$_SERVER['REQUEST_URI'];
		}

        function Content_Set_Data_Arrays($content_domain_data,$content_data,$domain_data,$domain_user_data,$module_data,$bizcat_data,$app_data){
			$this->content_data=$content_data;
            $this->domain_data=$domain_data;
            $this->content_domain_data=$content_domain_data;
            $this->domain_user_data=$domain_user_data;
            $this->module_data=$module_data;
			$this->bizcat_data=$bizcat_data;
            $this->app_data=$app_data;
		}

        function Content_Get_Data_Arrays(){
            /*
            $output_array=array($this->all_data_names[6]=>$this->content_domain_data,$this->all_data_names[4]=>$this->content_data,
            $this->all_data_names[3]=>$this->domain_data,$this->all_data_names[5]=>$this->bizcat_data,
            $this->all_data_names[2]=>$this->domain_user_data,$this->all_data_names[1]=>$this->module_data,$this->all_data_names[0]=>$this->app_data);
            */
            $output_array=array($this->content_domain_data,$this->content_data,$this->domain_data,$this->bizcat_data,
            $this->domain_user_data,$this->module_data,$this->app_data);
			return $output_array;
		}

        

        function Content_Init(){
            if(isset($this->content_domain_data["db"])){
                if(count($this->content_domain_data["db"])>0){
                    $this->content_data["db"]=$this->content_domain_data["db"];
                }
            }
            $this->log->general("-Content init Start->",1);
            if(!isset($this->input_data['membersID'])) $this->input_data['membersID']=0;
            $original_page=$this->input_data['REQUEST_URI'];
            $this->content_data["original_uri"]=$original_page;
            $this->content_data['cpid']=0;
            if($this->input_data['cpid']>0){
                $this->content_data['cpid']=$this->input_data['cpid'];
            }
            
            //print_r($this->content_data);
            
            if($this->input_data['dcmsuri']>0){
                $this->content_data["URI"]=$this->input_data['dcmsuri'];
                if($this->input_data['dcmsuri']>0){
                    $PArr=explode("\?",$this->input_data['dcmsuri']);
                    
                }else{
                    $PArr=explode("\?",$original_page);
                }
                $this->content_data["proxy_uri"]=$this->content_data["URI"];
            }else{
                $this->content_data["URI"]="";
                $PArr=explode("\?",$original_page);
            }
            $content_data_uri=array();
            $this->content_data["dcmsuri"]=$this->input_data['dcmsuri'];
                if($this->input_data['change']>0){
                    $this->content_data["change_datetime"]=urldecode($this->input_data['change']);
                    $change_sql=" ,TIMESTAMPDIFF(HOUR,Changed,'".$this->content_data["change_datetime"]."') AS cache_count";
                }else{
                    $change_sql="";
                }
                //if(isset($_GET['cpid'])){
                if(isset($this->content_data['cpid'])){
                    //$this->content_data["content_pagesID"]=$_GET['cpid'
                    $this->content_data["content_pagesID"]=$this->content_data['cpid'];
                    if($this->input_data['change']>0){
                        $sql='SELECT DISTINCT URI'.$change_sql.' FROM content_pages WHERE id='.$this->content_data["content_pagesID"].' LIMIT 0,1';
                    }else{
                        $sql='SELECT DISTINCT URI,Changed AS cache_count FROM content_pages WHERE id='.$this->content_data["content_pagesID"].' LIMIT 0,1';
                    }
                    $rslt=$this->r->RawQuery($sql);
                    $num_rows=0;
                    $num_rows=$this->r->NumRows($rslt);
                    if($num_rows>0){
                        if(isset($this->content_data["change_datetime"])){
                            exit("Use Cached File");
                        }else{
                        }
                        $content_data_uri=$this->r->Fetch_Assoc();
                    }
                    if(isset($content_data_uri['URI'])){
                        $this->content_data["URI"]=$content_data_uri['URI'];
                        $this->content_data["uri"]=$this->content_data["URI"];
                    }
                }else{
                    $this->content_data["content_pagesID"]=0;
                    $this->content_data["uri"]=$this->content_data["URI"];
                }
            $this->content_data["uri_split_array"]=$PArr;
            if(!defined("TOTALPAGENAME")){
                define('RESET',true);
                $this->log->general("-Content Loading->",1);
                $TotalPageName=$PArr[0];
                define('TOTALPAGENAME',$TotalPageName);
                $this->content_data["TOTALPAGENAME"]=$TotalPageName;
            }
            $OriginalPageName=$TotalPageName;
            if(substr($TotalPageName,strlen($TotalPageName)-1)!="/"){
                $TotalPageName.="/";
            }
            $VariableArray=array();
            $csearch=true;
            $notfound=true;
            $csearch=true;
            $segment=0;// times we go around
            $this->log->general("-Content Biz Cats-",1);
            
            if(isset($this->domain_user_data)>0){
                if(count($this->domain_user_data)>0){
                    if(isset($this->domain_data["db"]['id'])){
                        $sql="SELECT * FROM content_pages WHERE module_viewsID='25' AND domainsID=".$this->domain_data["db"]['id']." AND languagesID=".$this->input_data['LanguagesID']." LIMIT 0,1";
                        $rslt=$this->r->RawQuery($sql);
                        if($this->r->NumRows($rslt)>0){
                            $csearch=false;
                            $notfound=false;
                            if(!defined("PAGENAME")){
                                define('PAGENAME',$TotalPageName);
                                $this->content_data["PAGENAME"]=$TotalPageName;
                            }
                            $this->content_data["db"]=$this->r->Fetch_Assoc($rslt);	
                            if(isset($this->domain_user_data['mod_business_categoriesID'])){
                                $sql="SELECT * FROM mod_business_categories WHERE id=".$this->domain_user_data['mod_business_categoriesID'];
                        
                                $rslt=$this->r->RawQuery($sql);
                                $this->bizcat_data["db"]=$this->r->Fetch_Assoc($rslt);	
                                
                                $this->content_data["db"]['Meta_Title']=$this->domain_user_data['name']." - ".$this->bizcat_data['CategoryTitle']." - ".$this->content_data["db"]['Meta_Title'];
                            }
                            
                        }
                    }
                    
                }
            }
            $sql="";
            if(isset($this->content_data["change_datetime"])){
                $change_sql=" ,TIMESTAMPDIFF(HOUR,Changed,'".$this->content_data["change_datetime"]."') AS cache_count";
                $change_sql_where=" AND cache_count<1";
            }else{
                $change_sql_where="";
                $change_sql="";
            }
            if(isset($this->domain_data["db"]["SEOFriendly"])){
                if($this->domain_data["db"]["SEOFriendly"]=="No"){
                    if($this->content_data["content_pagesID"]>0){
                        $sql="SELECT * FROM content_pages WHERE id='".$this->content_data["content_pagesID"]."'  LIMIT 0,1";

                        $sql="SELECT *".$change_sql." FROM content_pages WHERE id='".$this->content_data["content_pagesID"]."'   LIMIT 0,1";
                    }else{
                        $sql="SELECT *".$change_sql." FROM content_pages WHERE  URI='".$this->content_data["URI"]."'  AND domainsID=".$this->domain_data['db']['id']."  LIMIT 0,1";
                    }
                }elseif($this->domain_data["db"]["SEOFriendly"]=="Yes"){
                    $sql="SELECT DISTINCT *".$change_sql." FROM content_pages WHERE URI='".$this->content_data["URI"]."'   AND domainsID=".$this->domain_data['db']['id']."";
                    //print $sql;
                    //print_r($this->domain_data);
                }
            }elseif($this->content_data["content_pagesID"]>0){
                $sql="SELECT *".$change_sql." FROM content_pages WHERE id='".$this->content_data["content_pagesID"]."'  LIMIT 0,1";
            }else{
                $sql="SELECT *".$change_sql." FROM content_pages WHERE HomePage='Yes'   LIMIT 0,1";
            }
            $rslt=$this->r->RawQuery($sql);
            $num_rows=0;
            $num_rows=$this->r->NumRows($rslt);
            
            if($num_rows>0){
                //exit();
                
                $this->content_data['PAGENAME']=$OriginalPageName;
                $this->content_data['db']=$this->r->Fetch_Assoc($rslt);
                $this->content_data["content_pagesID"]=$this->content_data['db']['id'];
                $notfound=false;
                $csearch=false;
                if(isset($this->content_data['db']['cache_count'])){
                    if($this->content_data['db']['cache_count']<0){
                        exit("Use Cached File");
                    }
                }
                //print_r($this->content_data['db']);
                //print_r($_SESSION);
                if($this->content_data['db']['Exposure']=="Member"){
                    if($_SESSION['membersID']==0){
                        $TotalPageName="/";
                        $csearch=true;
                    }else{
                        $TotalPageName="/login/";
                        $csearch=true;
                    }
                    $_SESSION['PAGENAME']=$this->content_data['PAGENAME'];
                }elseif($this->content_data['db']['Exposure']=="Admin"){
                    if(!isset($_SESSION["administratorsID"])){
                        $TotalPageName="//login-management/";
                        $csearch=true;
                    }
                }
                /*
                if(($_SESSION['membersID']==0)&&($this->content_data['db']['Exposure']=="Member")){
                    $TotalPageName="/login/";
                    //define('PAGENAME',$TotalPageName);
                    $csearch=true;
                    //echo"Member Page";
                    //exit();
                    //header("Location: /");
                    $_SESSION['PAGENAME']=$this->content_data['PAGENAME'];
                    print_r($_SESSION);
                }else{
                    //define('PAGENAME',$OriginalPageName);
                }
                */
            }else{
            }
            
            $this->log->general("-Content Search-",1);
            $domain_search=$this->domain_data['db']['id'];
            $max_count=0;
            
            while(($csearch)&&($max_count<10)){
                $max_count++;
                $sql="SELECT * FROM content_pages WHERE URI='".$TotalPageName."' AND domainsID=".$domain_search." AND languagesID=".$this->input_data['LanguagesID']."";
                $rslt=$this->r->RawQuery($sql);
                $num_rows=$this->r->NumRows($rslt);
                
                if($num_rows>0){
                    $csearch=false;
                    $notfound=false;
                    //if(!isset(PAGENAME)) define('PAGENAME',$TotalPageName);
                    $this->content_data['PAGENAME']=$OriginalPageName;
                    $this->content_data['db']=$this->r->Fetch_Assoc($rslt);	
                    //print_r($this->content_data);
                    if(!isset($_SESSION['membersID'])&&($this->content_data['db']['Exposure']=="Member")){
                        //echo"Member Page";
                        exit("Member Page");
                        //header("Location: /");
                    }
                    
                    if(!isset($_SESSION['administratorsID'])&&($this->content_data['db']['Exposure']=="Admin")){
                        //echo"Admin Page";
                        $TotalPageName='/login-management/';
                        $csearch=true;
                        //exit("Admin Page");
                        //header("Location: /");
                    }
                    
                }else{
                    //exit("XX Find Page=>".$sql."  <=>".$TotalPageName);
                    $TArr=explode('/',$TotalPageName);
                    $VariableArray[]=$TArr[count($TArr)-2];
                    $TotalPageName="";
                    for($x=0;$x<(count($TArr)-2);$x++){
                        $TotalPageName.=$TArr[$x]."/";
                    }
                    if($TotalPageName=="/"){
                        if($domain_search>0){
                            $domain_search=0;
                            $csearch=true;
                            $TotalPageName=$OriginalPageName;
                        }else{
                            $csearch=false;
                            $this->content_data['PAGENAME']=$OriginalPageName;
                            define('PAGENAME',TOTALPAGENAME);
                        }
                        
                    }else{
                        $csearch=true;
                    }
                    //print $TotalPageName;
                };
                
                //print "--".$sql."==";
            };
            //print_r($this->content_data);
            
            if($notfound){
                $sql="SELECT * FROM content_pages WHERE URI='".$this->content_data['PAGENAME']."' AND domainsID=".$this->domain_data["db"]['id']."";
                $rslt=$this->r->RawQuery($sql);
                if($this->r->NumRows($rslt)==0){// cant find page so load homepage for language/site
                    $sql="SELECT * FROM content_pages WHERE URI='".$this->content_data['PAGENAME']."' AND domainsID=0";
                    //print $sql;
                    $rslt=$this->r->RawQuery($sql);
                    $num_rows=$this->r->NumRows($rslt);
                    if($num_rows>0){
                        $this->content_data['db']=$this->r->Fetch_Assoc($rslt);
                    }else{
                        if($this->content_data["original_uri"]=="/"){
                            // on homepage
                            $sql="SELECT * FROM content_pages WHERE HomePage='Yes' AND languagesID=".$_SESSION['LanguagesID']." AND domainsID=".$this->domain_data["db"]['id'];
                        }elseif($this->content_data["original_uri"]!="/"){
                            // on homepage
                            $sql="SELECT * FROM content_pages WHERE URI='".$this->content_data['PAGENAME']."' AND languagesID=".$_SESSION['LanguagesID']." AND domainsID=0";
                        }else{
                            // when no page has been created - 404 error page
                            $sql="SELECT * FROM content_pages WHERE module_viewsID='801'";
                        }
                        
                        $rslt=$this->r->RawQuery($sql);
                        $num_rows=$this->r->NumRows($rslt);
                        if($num_rows>0){
                            //http_response_code(404);
                            $this->content_data['db']=$this->r->Fetch_Assoc($rslt);
                        }
                        //print $this->content_data['PAGENAME'];
                        if($this->content_data['PAGENAME']!="/404.shtml"){
                            
                            //http_response_code(404);
                            header("Location: /404.shtml");
                        }
                        
                    }
                }else{
                    $this->content_data['db']=$this->r->Fetch_Assoc($rslt);
                    $_SESSION['LanguagesID']=$this->content_data['languagesID'];
                };
            };
            
            $this->content_data["db"] = $this->a->strip_capitals($this->content_data["db"]);
            //print_r($this->content_data);
            $sql="SELECT * FROM modules,module_views WHERE modules.id=module_views.modulesID AND module_views.id=".$this->content_data['db']['module_viewsid'];
            //$sql="SELECT * FROM modules,module_views WHERE modules.id=module_views.modulesID AND module_views.id=".$module_viewsID;
            $rslt=$this->r->RawQuery($sql);
            $num_rows=$this->r->NumRows($rslt);
            
            if($num_rows==0){
                
                header("Location: /");
            }else{
                clsSystem::$vars->content_data=$this->content_data;
                //echo"321012345555-----------------------------------------------------------------------------\n";
                $this->module_data["db"]=$this->r->Fetch_Assoc($rslt);
                //echo"2234321012345555-----------------------------------------------------------------------------\n";
                $this->module_data["db"] = $this->a->strip_capitals($this->module_data["db"]);
                //echo"112234321012345555-----------------------------------------------------------------------------\n";
                //print_r($this->module_data);
                $target_class=$this->module_data["db"]['class'];
                //echo $target_class."-00001112234321012345555-----------------------------------------------------------------------------\n";
                $this->target=new $target_class();
                //echo"1112234321012345555-----------------------------------------------------------------------------\n";
                //print_r($this->module_data);
                //if($this->module_data["db"]['Pre_FileName']!=""){
                $pre_method=$this->module_data["db"]['Pre_Method'];
                //echo"54321012345555-----------------------------------------------------------------------------\n";
                if($pre_method!=""){
                    $this->target->$pre_method();
                    /*
                    $lfile=$this->app_data['MODULEBASEDIR'].$this->module_data["db"]['Dir']."/".$pre_file;
                    //print $lfile;
                    if (file_exists($lfile)) {
                        include($lfile);
                    }else{
                        $this->log->general("AA error->",1);
                        //echo"AA error";
                    }
                    */
                }
                // check for member session
                if(!isset($_SESSION['membersID'])&&($this->content_data["db"]['Exposure']=="Member")){
                    //echo"Member Page";
                    $this->log->general("Member Page->",1);
                    //header("Location: /");
                }
            }
            if(isset($this->content_data["content_pagesID"])){
                $this->content_data["content_pagesid"]=$this->content_data["content_pagesID"];
            }
            
            $this->log->general("-End Content init->",1);
            if(isset($_GET['ajax'])){
                $this->domain_data["db"]['templatesID']=35;
                $this->content_data["db"]['templatesID']=35;
            }
            
        }


        function Display(){
            $this->log->general("-ab Text Display->",3);
            $return_html="";
            //$target=new $this->module_data["db"]['class']();
            $method=$this->module_data["db"]['Method'];
            if($method!=""){
                $return_html=$this->target->$method();
            }
            return $return_html;
            /*
            if(isset($module_data["db"]['dir'])){
                $module_template_display=$app_data['MODULEBASEDIR'].$module_data["db"]['dir']."/".$module_data["db"]['filename'];
                if(file_exists($module_template_display)){
                    //print $module_template_display;
                    $this->log->general("-ar Text Display->".$module_template_display."-".var_export($module_data,true),3);
                    $text_data['debug'][]=$module_template_display;
                    include($module_template_display);
                }else{
                }
            }
            */
        }
    }