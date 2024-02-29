<?php

    class clsDomain{
        private $content_data=array();      
        private $domain_data=array();
        private $content_domain_data=array();
        private $domain_user_data=array();
        private $app_data=array();

        private $all_data_arrays=array();
        private $all_data_names=array(0=>"app",1=>"domain_user",2=>"domain",3=>"content",4=>"content_domain");
        private $log;

        private $r;
       
        function __construct(){
			
			$this->Set_Log(clsClassFactory::$all_vars['log']);
            $this->Set_DataBase(clsClassFactory::$all_vars['r']);
		}

        function Domain_Set_Data_Arrays($content_domain_data,$content_data,$domain_data,$domain_user_data,$app_data){
			$this->content_data=$content_data;
            $this->domain_data=$domain_data;
            $this->content_domain_data=$content_domain_data;
            $this->domain_user_data=$domain_user_data;
            $this->app_data=$app_data;
		}

        function Domain_Get_Data_Arrays(){
            //$output_array=array($this->all_data_names[4]=>$this->content_domain_data,$this->all_data_names[3]=>$this->content_data,
            //$this->all_data_names[2]=>$this->domain_data,$this->all_data_names[1]=>$this->domain_user_data,$this->all_data_names[0]=>$this->app_data);
            $output_array=array($this->content_domain_data,$this->content_data,$this->domain_data,$this->domain_user_data,$this->app_data);
			return $output_array;
		}

        function Set_DataBase($r){
			$this->r=$r;
			
		}

        function Set_Log($log){
			$this->log=$log;
			
		}
        function Domain_Init(){
            $this->log->general("-Domain Module Loading-",1);
            //echo"--73---------------------------------------------------------------------------\n";
            //$current_domain=eregi_replace("www\.","",$_SERVER['HTTP_HOST']);
            $current_domain= str_replace('www.', "",$_SERVER['HTTP_HOST']);
            
            //$current_domain=$_SERVER['HTTP_HOST'];
            $this->log->general("-Domain Loading-".$current_domain."|",1);
            //define('DOMAINNAME',$current_domain);
            
            if(isset($_GET['dcmshost'])){
                $TargetHost=urldecode($_GET['dcmshost']);
            }else{
                $TargetHost=$current_domain;
            }
            
            if(isset($_GET['ajax'])){
                $this->domain_data["db"]['templatesID']=35;
                $this->content_data["db"]['templatesID']=35;
            }
            
            //print $TargetHost;
            $this->content_data["TargetHost"]=$TargetHost;
            $this->content_data["original_domain"]=$current_domain;
            $TotalDomainName=str_replace("www\.", "", $TargetHost);
            $this->content_data["TOTALDOMAINNAME"]=$TotalDomainName;
            define('TOTALDOMAINNAME',$TotalDomainName);
            $this->log->general("\n-",1);
            $this->log->general("-Domain Total Loading 2-".$TargetHost,1);
            
            $DomainVariableArray=array();
            $this->domain_user_data=array();
            $csearch=true;
            $totalcount=0;
            $num_rows=0;
            $this->domain_data["db"]=array();
            $this->domain_data["dcmshost"]="";
            if(isset($_GET['dcmshost'])){
                $this->domain_data["dcmshost"]=$_GET['dcmshost'];
            }
            
            $this->log->general("1 In Domain Counting Down->".$csearch."->".$TotalDomainName,3);
            //echo"--4411108-------------------------".$csearch."--------------------------------------------------\n";
            while($csearch){
                if($totalcount>10){
                    $csearch=false;
                }	
                if(strlen($TotalDomainName)==0){
                    $csearch=false;
                }
                $totalcount++;
                if($TotalDomainName!=""){
                    
                    //echo"\n\n--22222-------------------------".$csearch."--------------------------------------------------\n";
                    
                    //$sql="SELECT DISTINCT * FROM domains WHERE Name='".$TotalDomainName."' LIMIT 0,1";
                    $sql="SELECT DISTINCT * FROM domains WHERE Name='".$TotalDomainName."'";
                    //$sql="SELECT DISTINCT * FROM clients";
                    //$sql="SELECT COUNT(*) AS total FROM content_pages";
                    
                    //$csearch=false;
                    //echo"\n\n--22222-------------------------".$sql."--------------------------------------------------\n";
                    
                    $this->log->general("1 In Domain Counting Down->".$sql,3);
                    $rslt=$this->r->RawQuery($sql);
                    $num_rows=$this->r->NumRows($rslt);
                    if($num_rows>0){
                        $row = $this->r->Fetch_Assoc();
                        
                        $this->domain_data["db"]=$row;
                        $csearch=false;
                    }
                    
                    /*
                    while ($row = $this->r->Fetch_Array()) {
                        //echo "{$row['id']} {$row['name']} {$row['email']} \n";
                        $num_rows++;
                        $this->domain_data["db"]=$row;
                        echo"yyy";
                        print_r($row);
                    }
                    */
                    //$num_rows=$this->r->NumRows($rslt);
                    
                    
                    //echo"\n\n778xxx-nr->".$num_rows;
                    $this->log->general("Domain Counting Down->".$sql,3);
                    //echo"--4432-------------------------".$csearch."--------------------------------------------------\n";
                }else{
                    $sql="SELECT DISTINCT * FROM domains WHERE Name='ajax.install.me'";
                    $rslt=$this->r->RawQuery($sql);
                    $num_rows=$this->r->NumRows($rslt);
                    if($num_rows>0){
                        $row = $this->r->Fetch_Assoc();
                        $this->domain_data["db"]=$row;
                    }
                    //echo"\n\n--22222112-------------------------".$csearch."--------------------------------------------------\n";
                    $num_rows=0;
                    $csearch=false;
                }
                //echo"--4412345-------------------------".$csearch."--------------------------------------------------\n";
                //if($rslt){
                //print_r($this->domain_data);
                if($num_rows>0){
                    //$this->domain_data["db"]=$this->r->Fetch_Assoc();//reset to mirror site details
                    
                    //print_r($this->domain_data);
                    //echo"--44321-------------------------".$csearch."--------------------------------------------------\n";
                    //$num_rows=$this->r->NumRows($rslt);
                    //echo"11nr->".$num_rows;
                    $this->log->general("Domain Found->".$num_rows,3);
                    //if($num_rows>0){
                    //$this->domain_data["db"]=$this->r->Fetch_Assoc();//reset to mirror site details
                    $csearch=false;
                    $this->log->general("Domain cr->".$num_rows,3);
                    //if(!defined(DOMAINNAME)) define('DOMAINNAME',$TotalDomainName);
                    $this->log->general("Domain ar->".$num_rows,3);
                    //$this->domain_data["db"]=$this->r->Fetch_Assoc();
                    //print_r($this->domain_data);
                    $this->log->general("Domain xr->".var_export($this->domain_data,true),3);
                    //echo"--44666-------------------------".$csearch."--------------------------------------------------\n";
                    if(isset($this->domain_data["db"]['mirrorID'])){
                        // if domain is mirrored reset domain_data to domain referenced
                        if($this->domain_data["db"]['mirrorID']>0){
                            $this->log->general("Domain Mirror->",3);
                            $sql="SELECT * FROM domains WHERE id=".$this->domain_data["db"]['mirrorID'];
                            $rslt=$this->r->RawQuery($sql);
                            $num_rows=$this->r->NumRows($rslt);
                            if($num_rows>0){
                                $this->domain_data["original_db"]=$this->domain_data["db"];
                                $this->domain_data["db"]=$this->r->Fetch_Assoc();//reset to mirror site details
                                $this->log->general("Domain zr->".var_export($this->domain_data,true),3);
                            }
                        }
                    }
                    //print_r($this->domain_data);
                    $this->log->general("Domain br->".var_export($this->domain_data,true),3);
                        
                        //if(!defined(DOMAINSID)) define('DOMAINSID',$this->domain_data['id']);
                    //}	
                }else{
                    $TArr=explode('.',$TotalDomainName);
                    //print_r($TArr);
                    if(!isset($this->domain_data["dcmshost"])){
                        if(count($TArr)>2){
                            for($x=0;$x<(count($TArr)-2);$x++){
                                $this->domain_user_data["sub_domain_items"][$x]=$TArr[$x];
                                //$this->domain_user_data["sub_domain_total"].=($x==0 : '.' ? '').$TArr[$x];
                            }
                        }
                    }
                    
                    
                    $TotalDomainName="";
                    for($x=1;$x<count($TArr);$x++){
                        $tmp=($x!=1 ? '.':""); 
                        $TotalDomainName.=$tmp.$TArr[$x];
                    }
                    //echo"--".$TotalDomainName;
                    //if($TotalDomainName!="localhost"){
                    $count=strpos($TotalDomainName,".");
                    //print_r($matches);
                    if($count==0){
                        $TotalDomainName="install.me";
                    //if(strpos($TotalDomainName,"\.")==false){
                        //if(!pre($TotalDomainName)){
                        //exit($count."Invalid Domain Name->".$TotalDomainName);
                        $this->log->general("Invalid Domain Count DownName->".$sql." ".$TotalDomainName."|",3);
                    }
                    //	}
                };
            }
                //}else{
        //		$this->log->general("Invalid Domain Name None Found->".$sql."  ".$TotalDomainName,3);
            //}
            
            $this->domain_data["TotalDomainName"]=$TotalDomainName;
            $this->domain_data["DomainVariableArray"]=$DomainVariableArray;
            //print_r($this->domain_data);
            //echo"--744------------------------------";//.var_export($DomainVariableArray,true)."---------------------------------------------\n";
            $this->log->general("Domain Ending->",3);
            $this->log->general("Sub Domain Check->".var_export($DomainVariableArray,true),3);
            
            
            if(count($DomainVariableArray)>0){
                //echo $sql."--405---------------------------------------------------------------------------\n";
                //$DName=$DomainVariableArray[0];
                $DName=$this->domain_user_data["sub_domain_total"];
                $sql="SELECT * FROM users WHERE subdomain='".$DName."' LIMIT 0,1";

                $rslt=$this->r->RawQuery($sql);
                $domain_count=$this->r->NumRows($rslt);
                //echo $domain_count."--405---------------------------------------------------------------------------\n";
                if($domain_count>0){
                    $continue=true;
                    //while($myrow=$this->r->Fetch_Assoc($rslt)){
                    $myrow=$this->r->Fetch_Assoc($rslt);
                    $sql="SELECT domainsID FROM mod_business_categories WHERE id='".$myrow['mod_business_categoriesID']."'";
                    $rslt2=$this->r->RawQuery($sql);
                    $data=$this->r->Fetch_Array($rslt2);
                    //print_r($myrow);
                    //flush();
                    if($data[0]==$this->domain_data["db"]['id']){
                        $this->domain_user_data["db"]=$myrow;
                    }
                    //}
                }else{
                    // show 404 error
                    
                    $sql="SELECT * FROM content_pages WHERE  module_viewsID='801'";
                    //echo "--414----------".$sql."--------------------------------454---------------------------------\n";
                    $rslt=$this->r->RawQuery($sql);
                    $this->content_domain_data["db"]=$this->r->Fetch_Assoc($rslt);
                    //echo "--404----------".$sql."-----------------".var_export($this->content_domain_data,true)."---------------414---------------------------------\n";
                }
                //echo $sql."--405---------------------------------------------------------------------------\n";
            }else{
                //echo $sql."--2222---------------------------------------------------------------------------\n";
            }
            //echo "--888---------------------------------".var_export($this->content_domain_data,true)."------------------------------------------\n";
            ////echo"-333-".$TotalDomainName."--".var_export($this->content_data,true)."-123-".var_export($this->domain_data,true)."--".var_export($this->content_domain_data,true);
            //echo"-222-".var_export($this->domain_data,true)."--22--";
            $this->log->general("Domain Complete->",3);
            $this->log->general("\n",3);
            //echo "--8887654321---------------------------------".var_export($this->content_domain_data,true)."------------------------------------------\n";
            if(isset($_GET['ajax'])){
                $this->domain_data["db"]['templatesID']=35;
                $this->content_data["db"]['templatesID']=35;
            }
        }
    }

?>