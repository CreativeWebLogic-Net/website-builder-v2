<?php

    class clsMenu{
        private $log;

        private $r;

        private $data=array();
       
        function __construct($domain_user_data,$domain_data,$content_data,$app_data){
			
			$this->Set_Log(clsClassFactory::$all_vars['log']);
            $this->Set_DataBase(clsClassFactory::$all_vars['r']);

            $this->data['domain_user_data']=$domain_user_data;
            $this->data['domain_data']=$domain_data;
            $this->data['content_data']=$content_data;
            $this->data['app_data']=$app_data;
		}

        

        function Set_DataBase($r){
			$this->r=$r;
			
		}

        function Set_Log($log){
			$this->log=$log;
			
		}

        function Pre_Menu(){
			
		}

        function Menu_Base(){
            
            $domain_user_data=$this->data['domain_user_data'];
            $domain_data=$this->data['domain_data'];
            $content_data=$this->data['content_data'];
            $app_data=$this->data['app_data'];
            //print_r($_SESSION);
            $menu_data=array();
            if(count($domain_user_data)==0){
                if(isset($domain_data["original_db"])){
                    $domain_name=$domain_data["original_db"]['Name'];
                    $SEOFriendly=$domain_data["original_db"]['SEOFriendly'];
                }else{
                    $domain_name=$domain_data["db"]['Name'];
                    $SEOFriendly=$domain_data["db"]['SEOFriendly'];
                }
                
                if(!isset($_SESSION['administratorsID'])) $_SESSION['administratorsID']=0;
                if(!isset($_SESSION['membersID'])) $_SESSION['membersID']=0;
                
                 if($_SESSION['membersID']>0){
                    $member_type="Member";
                    $exposure="(Exposure='".$member_type."' OR Exposure='Both')";
                }elseif($_SESSION['administratorsID']>0){
                    $member_type="Admin";
                    $exposure="Exposure='".$member_type."'";
                }else{
                    $member_type="Public";
                    $exposure="(Exposure='".$member_type."' OR Exposure='Both')";
                }
        
                $menu_hide_sql=" Menu_Hide='No' AND ";
                $side_menu_sql=" AND content_pages.parentID=0";
                if($content_data['db']['domainsID']==0){
                    $admin_menu_sql=" AND domainsID=0";
                    
                }else{
                    $admin_menu_sql=" AND domainsID=".$domain_data["db"]['id'];
                }
                
                
                $sql="SELECT URI AS uri,MenuTitle AS menutitle,id AS content_pagesid FROM content_pages WHERE ".$menu_hide_sql." ";
                    $sql.=$exposure." AND languagesID=".$app_data['LANGUAGESID']." ".$admin_menu_sql." ".$side_menu_sql." ORDER BY Sort_Order";
                //print $sql;	
                $rslt=$this->r->RawQuery($sql);
                $first=true;
                $menu_data['spacers']="|";
                if($this->r->NumRows($rslt)>0){
                    
                
                    while($data=$this->r->Fetch_Assoc($rslt)){
                        // show spacers in menu
                        if($menu_data['spacers']){
                            if($first){
                                $first=false;
                                $data['first']=true;
                            }else{
                                $data['first']=false;
                            }
                        }else{
                            // no show spacers in menu
                            $data['first']=true;
                        }
                        
                        if($SEOFriendly=="No"){
                            $data['link_address']='http://'.$domain_name.$app_data['ROOTDIR'].'index.php?guid=1&cpid='.$data["content_pagesid"];
                        }else{
                            $data['link_address']='http://'.$domain_name.$data["uri"];
                        }
                        $menu_data["db"][]=$data;
                    }
                }
            }else{
                $data=array();
                $data['first']=true;
                $data['link_address']='http://'.$domain_data["db"]['Name'];
                $data["menutitle"]="Directory Home";
                $menu_data["db"][]=$data;
            }
            return $menu_data;
        }

        function Vertical_Menu_Base(){
            $menu_data=array();
            $domain_user_data=$this->data['domain_user_data'];
            $domain_data=$this->data['domain_data'];
            $content_data=$this->data['content_data'];
            $app_data=$this->data['app_data'];

            if(count($domain_user_data)==0){
                if(isset($domain_data["original_db"])){
                    $domain_name=$domain_data["original_db"]['Name'];
                    $SEOFriendly=$domain_data["original_db"]['SEOFriendly'];
                }else{
                    $domain_name=$domain_data["db"]['Name'];
                    $SEOFriendly=$domain_data["db"]['SEOFriendly'];
                }
                $side_menu_sql="";
                $menu_hide_sql=" Menu_Hide='No' AND ";
                //$menu_hide_sql="";
                //$side_menu_sql=" AND content_pages.parentID=".$content_data['db']['id'];
                $group_by="GROUP BY URI";
                /*
                if(isset($_SESSION['membersID'])){
                    $member_type="Member";
                }elseif(isset($_SESSION['administratorsID'])){
                    $member_type="Admin";
                }else{
                    $member_type="Public";
                }
                */
                if(!isset($_SESSION['administratorsID'])) $_SESSION['administratorsID']=0;
                if(!isset($_SESSION['membersID'])) $_SESSION['membersID']=0;
                
                if($_SESSION['membersID']>0){
                    $member_type="Member";
                    $exposure="(Exposure='".$member_type."' OR Exposure='Both')";
                }elseif($_SESSION['administratorsID']>0){
                    $member_type="Admin";
                    $exposure="Exposure='".$member_type."'";
                }else{
                    $member_type="Public";
                    $exposure="(Exposure='".$member_type."' OR Exposure='Both')";
                }
                
                if($content_data['db']['domainsID']==0){
                    //$admin_menu_sql=" AND domainsID=0";
                    $admin_menu_sql="AND domainsID=0";
                    /*
                    if(isset($show_side_menu)){
                        $side_menu_sql=" AND parentID=".$content_data['db']['id'];
                    }else{
                        $side_menu_sql="";
                    }
                    */
                 
                }else{
                    /*
                    if(isset($show_side_menu)){
                        $side_menu_sql=" AND parentID=".$content_data['db']['id'];
                    }else{
                        $side_menu_sql="";
                    }
                    */
                    $admin_menu_sql="AND domainsID=".$domain_data["db"]['id']." ";
                    //$side_menu_sql="";
                }
                
                //$sql="SELECT DISTINCT URI AS uri,MenuTitle AS menutitle,id AS content_pagesid FROM content_pages WHERE ".$menu_hide_sql;
                    //$sql.=$exposure." AND languagesID=".$app_data['LANGUAGESID']." ".$admin_menu_sql." ".$side_menu_sql."   ORDER BY Sort_Order";
                
                $sql="SELECT URI AS uri,MenuTitle AS menutitle,id AS content_pagesid FROM content_pages WHERE Menu_Hide='No' AND ".$exposure." ".$side_menu_sql." ".$admin_menu_sql." ORDER BY Sort_Order;";
                
                
                //$sql="SELECT URI AS uri,MenuTitle AS menutitle,id AS content_pagesid FROM content_pages WHERE Menu_Hide='No' AND Exposure='Admin' AND parentID=959 AND domainsID=0 ORDER BY Sort_Order;";
                
                //print $sql."\n".$sql2." \n";
                $rslt=$this->r->RawQuery($sql);
                $first=true;
                $row_count=$this->r->NumRows($rslt);
                /*
                while($row_count==0){
                    $side_menu_sql=" AND content_pages.parentID=".$content_data['db']['parentID'];
                
                    
                    $sql="SELECT DISTINCT URI AS uri,MenuTitle AS menutitle,id AS content_pagesid FROM content_pages WHERE ".$menu_hide_sql." (Exposure='".$member_type."' OR Exposure='Both')";
                    $sql.=" AND languagesID=".$app_data['LANGUAGESID']." AND (domainsID=".$domain_data["db"]['id']." OR domainsID=0) ".$side_menu_sql."  GROUP BY URI ORDER BY Sort_Order";
                    $rslt=$r->RawQuery($sql);
                    $row_count=$r->NumRows($rslt);
                }
                */
                $menu_data['spacers']='<br>';
                $sub_menu_source=$this->RecursiveMenuUp($content_data['db']['id']);
                //print($sub_menu_source);
                $pages=$this->RecursiveMenuDown($sub_menu_source);
                //print_r($pages);
                foreach($pages as $key=>$val){
                    $data["menutitle"]=$val['menutitle']." ".$menu_data['spacers'];
                    $data['link_address']=$val['uri'];
                    $menu_data["vb"][]=$data;
                }
                
                    //uri,MenuTitle AS menutitle
                /*
                $menu_data['spacers']="<br>";
                //$menu_data['spacers']=false;
                while($data=$this->r->Fetch_Assoc($rslt)){
                    // show spacers in menu
                    if($menu_data['spacers']){
                        if($first){
                            $first=false;
                            $data['first']=true;
                        }else{
                            $data['first']=false;
                        }
                    }else{
                        // no show spacers in menu
                        $data['first']=true;
                    }
                    

                    if($SEOFriendly=="No"){
                        $data['link_address']='http://'.$domain_name.$app_data['ROOTDIR'].'index.php?guid=1&cpid='.$data["content_pagesid"];
                    }else{
                        $data['link_address']='http://'.$domain_name.$data["uri"];
                    }
                    $menu_data["vb"][]=$data;
                }
                */
            }else{
                $data=array();
                $data['first']=true;
                $data['link_address']='http://'.$domain_data["db"]['Name'];
                $data["menutitle"]="Directory Home";
                $menu_data["vb"][]=$data;
            }
            return $menu_data;
        }

        public function RecursiveMenuUp($current_pageID){
            $member_type="Public";
            $exposure="(Exposure='".$member_type."' OR Exposure='Both')";
            $sql="SELECT id AS content_pagesID,parentID FROM content_pages WHERE Menu_Hide='No' AND ".$exposure." AND id='".$current_pageID."' ORDER BY Sort_Order;";
            $rslt=$this->r->RawQuery($sql);
            $row_count=$this->r->NumRows($rslt);
            $origin_pageID=0;
            while($data=$this->r->Fetch_Assoc($rslt)){
                if($data['parentID']==0){
                    $origin_pageID=$data['content_pagesID'];
                }else{
                    $origin_pageID=$this->RecursiveMenuUp($data['parentID']);
                }            }
            return $origin_pageID;
        }

        public function RecursiveMenuDown($current_pageID){
            $member_type="Public";
            $exposure="(Exposure='".$member_type."' OR Exposure='Both')";
            $sql="SELECT URI AS uri,MenuTitle AS menutitle,id AS content_pagesID,parentID FROM content_pages WHERE Menu_Hide='No' AND ".$exposure." AND parentID='".$current_pageID."' ORDER BY Sort_Order;";
            //print "\n =>".$sql." | \n";
            $rslt=$this->r->RawQuery($sql);
            $row_count=$this->r->NumRows($rslt);
            
            $pages=array();
            while($data=$this->r->Fetch_Assoc($rslt)){
                $pages[]=$data;
                //print_r($data);
                $origin=$this->RecursiveMenuDown($data['content_pagesID']);
                if(count($origin)>0){
                    
                    //print_r($origin);
                    $pages = array_merge($origin, $pages);
                }
                
            }            
            return $pages;
        }

        function Vertical_Menu_Base_New(){
            $menu_data=array();
            $domain_user_data=$this->data['domain_user_data'];
            $domain_data=$this->data['domain_data'];
            $content_data=$this->data['content_data'];
            $app_data=$this->data['app_data'];

            if(count($domain_user_data)==0){
                if(isset($domain_data["original_db"])){
                    $domain_name=$domain_data["original_db"]['Name'];
                    $SEOFriendly=$domain_data["original_db"]['SEOFriendly'];
                }else{
                    $domain_name=$domain_data["db"]['Name'];
                    $SEOFriendly=$domain_data["db"]['SEOFriendly'];
                }
                $side_menu_sql="";
                $menu_hide_sql=" Menu_Hide='No' AND ";
                $group_by="GROUP BY URI";
                
                if(!isset($_SESSION['administratorsID'])) $_SESSION['administratorsID']=0;
                if(!isset($_SESSION['membersID'])) $_SESSION['membersID']=0;
                
                if($_SESSION['membersID']>0){
                    $member_type="Member";
                    $exposure="(Exposure='".$member_type."' OR Exposure='Both')";
                }elseif($_SESSION['administratorsID']>0){
                    $member_type="Admin";
                    $exposure="Exposure='".$member_type."'";
                }else{
                    $member_type="Public";
                    $exposure="(Exposure='".$member_type."' OR Exposure='Both')";
                }
                
                if($content_data['db']['domainsID']==0){
                    $admin_menu_sql="AND domainsID=0";
                }
                $sql="SELECT URI AS uri,MenuTitle AS menutitle,id AS content_pagesid FROM content_pages WHERE Menu_Hide='No' AND ".$exposure." ".$side_menu_sql." ".$admin_menu_sql." ORDER BY Sort_Order;";
                $rslt=$this->r->RawQuery($sql);
                $first=true;
                $row_count=$this->r->NumRows($rslt);
                
                $menu_data['spacers']="<br>";
                while($data=$this->r->Fetch_Assoc($rslt)){
                    // show spacers in menu
                    if($menu_data['spacers']){
                        if($first){
                            $first=false;
                            $data['first']=true;
                        }else{
                            $data['first']=false;
                        }
                    }else{
                        // no show spacers in menu
                        $data['first']=true;
                    }
                    
                    if($SEOFriendly=="No"){
                        $data['link_address']='http://'.$domain_name.$app_data['ROOTDIR'].'index.php?guid=1&cpid='.$data["content_pagesid"];
                    }else{
                        $data['link_address']='http://'.$domain_name.$data["uri"];
                    }
                    $menu_data["vb"][]=$data;
                }
            }else{
                $data=array();
                $data['first']=true;
                $data['link_address']='http://'.$domain_data["db"]['Name'];
                $data["menutitle"]="Directory Home";
                $menu_data["vb"][]=$data;
            }
            return $menu_data;
        }

        function Horizontal_Install(){
            //include($app_data['MODULEBASEDIR']."menu/menu_base.php");
            $menu_data=$this->Menu_Base();
            //print_r($menu_data);
            $output_data="";
            foreach($menu_data["db"] as $key=>$val){
                if($menu_data['spacers']){
                    if($val['first']==false){
                        $output_data.=' | ';
                    }
                }
                $output_data.='<a id="link-item-id" class="link-item-cl" href="'.$val['link_address'].'"><span>'.$val["menutitle"].'</span></a>';
            }
            return $output_data;
        }

        function Horizontal_Rounded_Install(){
            //include($app_data['MODULEBASEDIR']."menu/menu_base.php");
            $menu_data=$this->Menu_Base();
            //print_r($menu_data);
            $output_data="";
            if(isset($menu_data["db"])){
                foreach($menu_data["db"] as $key=>$val){
                    if($menu_data['spacers']){
                        if($val['first']==false){
                            $output_data.=' | ';
                        }
                    }
                    $output_data.='<a id="link-item-id" class="link-item-cl" href="'.$val['link_address'].'"><span>'.$val["menutitle"].'</span></a>';
                }
            }
            
            return $output_data;
        }


        function Horizontal_Rounded(){
            //include($app_data['MODULEBASEDIR']."menu/menu_base.php");
            $menu_data=$this->Menu_Base();
            //print_r($menu_data);
            $output_data="";
            $menu_data['spacers']="|";
            if(isset($menu_data["db"])){
                foreach($menu_data["db"] as $key=>$val){
                    if($menu_data['spacers']){
                        if($val['first']==false){
                            $output_data.=' | ';
                        }
                    }
                    $output_data.='<a id="link-item-id" class="link-item-cl" href="'.$val['link_address'].'"><span>'.$val["menutitle"].'</span></a>';
                }
            }
            return $output_data;
        }

        function Horizontal(){

        }

        function LI_Menu(){
            //include($app_data['MODULEBASEDIR']."menu/menu_base.php");
            $menu_data=$this->Menu_Base();
            //print_r($menu_data["db"]);
            $output_data="";
            foreach($menu_data["db"] as $key=>$val){
                if($val['first']==false){
                    $output_data.=' | ';
                }
                $output_data.='<li><a id="link-item-id" class="link-item-cl" href="'.$val['link_address'].'"><span>'.$val["menutitle"].'</span></a></li>';
            }
            return $output_data;
        }
        function LI_Rounded(){

        }
        function LI(){
            //include($app_data['MODULEBASEDIR']."menu/menu_base.php");
            $menu_data=$this->Menu_Base();
            //print_r($menu_data);
            $output_data="";
            foreach($menu_data["db"] as $key=>$val){
                if($menu_data['spacers']){
                    if($val['first']==false){
                        $output_data.=' | ';
                    }
                }
                $output_data.='<li><a id="link-item-id" class="link-item-cl" href="'.$val['link_address'].'">'.$val["menutitle"].'</a></li>';
            }
            return $output_data;
        }

        function Vertical_Sub_Page(){
            $show_side_menu=true;
            //include($app_data['MODULEBASEDIR']."menu/vertical_menu_base.php");
            $menu_data=$this->Vertical_Menu_Base();
            //print_r($menu_data);
            //print $sql;
            $output_data="";
            if(isset($menu_data["vb"])){
                foreach($menu_data["vb"] as $key=>$val){
                    if($menu_data['spacers']){
                        if(isset($val['first'])){
                            if($val['first']==false){
                                $output_data.='<br>';
                            }
                        }
                        
                    }
                    $output_data.='<a id="link-item-id" class="link-item-cl" href="'.$val['link_address'].'"><span class="link-item-cl">'.$val["menutitle"].'</span></a>';
                }
                //print $output_data;
            }
            return $output_data;
        }

    }