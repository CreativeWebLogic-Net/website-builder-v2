<?php

    class clsManagement extends clsFormCreator{
        private $output="";
        public $Message="";

        function __construct(){
			
			parent::__construct();
		}

        function Pre_Login(){
			
			//print "xx22";
		}

        function Set_Output($output){
			$this->output=$output;
		}

        function Admin_Login(){
			$this->Set_Output(parent::Create_Login_Form());
            return  $this->Output_HTML();
            //return  $this->output;
            //print "\n\n Hello World \n\n";
			
		}

        function Admin_Register(){
			$this->Set_Output($this->Create_Admin_Member_Register_Form());
            return  $this->Output_HTML();
            //return  $this->output;
			
		}

        function Output_HTML(){
			return  $this->output;
		}

        function Pre_Admin_Add_Administrator(){
			if(isset($_POST['Submit'])){
                if($_POST['Submit']){
                    $_POST['clientsID']=$session_data['clientsID'];
                    $m= new AddToDatabase($log);
              $m->Set_Database($r);
                    $m->AddPosts($_POST,$_FILES);
                    $m->AddTable("administrators");
                    $m->DoStuff();
                    $NewID=$m->ReturnID();
                    
              if(isset($_POST['domainsID'])){
                foreach($_POST['domainsID'] as $key=>$val){
                    $this->r->RawQuery("INSERT INTO administrators_domains (administratorsID,domainsID) VALUES ($NewID,$val)");
                  }
              }
                    
                    
                    $Message="Administrator Added";
                };
            }
		}

        function Pre_Admin_Add_Domain(){
			if(isset($_POST['Submit'])){
                if($_POST['Submit']){
              
                    //$_POST['ClientsID']=$session_data['original_clientsID'];
                    $m= new AddToDatabase($log);
              $m->Set_Database($r);
                    $m->AddPosts($_POST,$_FILES);
              $m->AddExtraFields(array('ClientsID'=>$session_data['original_clientsID'],"serversID"=>13));
                    $m->AddTable("domains");
                    $m->DoStuff();
                    $NewID=$m->ReturnID();
                    
              if(isset($_POST['modulesID'])){
                if(is_array($_POST['modulesID'])){
                  foreach($_POST['modulesID'] as $moduleID){
                    $this->r->RawQuery("INSERT INTO domains_modules (domainsID,modulesID) VALUES ($NewID,$moduleID)");
                  }
                }
              }
                    
                    
                    
                    $Message="Website Added";
                };
            }
		}

        function Pre_Admin_Edit_Domain(){
			$id=false;
            //$remote_domain_name=$app_data['remote_server']['domain_name'];
                if(isset($_GET['id'])) $id=$_GET['id'];
                elseif (isset($_POST['id'])) $id=$_POST['id'];
            if(isset($_POST['Name'])){
                $Domain_Name=$_POST['Name'];
            }else{
                $Domain_Name="sitemanage.info";
            }
            // print $id;
            //---get domain that is currently being edited--------------------------------------------------------------------------";
            
                if(is_numeric($id)){
                $Domain_Name=$app_data['remote_server']['domain_name'];
                //print $Domain_Name;
                
                //$this->r->Initialise_Remote_Server(true);
                //$this->r->Set_Current_Server($Domain_Name);
                $sql="SELECT COUNT(*) FROM domains WHERE id=$id AND clientsID=".$session_data['original_clientsID'];
                
                    $rslt=$this->r->RawQuery($sql);
                    $data=$this->r->Fetch_Array($rslt);
                
                    if($data[0]==0){
                $error_message="Secuirty Error->".$sql." - ".$id." - ".var_export($data,true);
                        exit($error_message);
                    }else{
                $success_message="--311--Secuirty Error->".$sql." - ".$id." - ".var_export($data,true);
                //print $success_message;
                }
                
                }else{
                    //print_r($_GET);
                $error_message="--303--Secuirty Error->".$id." - \n\n";
                    //exit($error_message);
                }
            
                if(isset($_POST['Submit'])){
                    if($_POST['Submit']){
                //---update sitemanage--------------------------------------------------------------------------";
                //$this->r->Initialise_Remote_Server(true);
                        $m= new UpdateDatabase($log);
                $m->Set_Database($r);
                        $m->AddPosts($_POST,$_FILES);
                        $m->AddSkip(array("id"));
                        $m->AddTable("domains");
                        $m->AddID($id);
                        $m->DoStuff();
                        $Message="Website Updated";
                        $this->r->RawQuery("DELETE FROM domains_modules WHERE domainsID=$id");
                        if(isset($_POST['modulesID'])){
                            if(is_array($_POST['modulesID'])){
                                foreach($_POST['modulesID'] as $moduleID){
                                    $this->r->RawQuery("INSERT INTO domains_modules (domainsID,modulesID) VALUES ($id,$moduleID)");
                                }
                            }
                        }
                        $session_data['ModsPermArr']=GetModulesPermissions();

                //------get remote domain info-----------------------------------------------------------------------------------------------------	
                /*
                $this->r->Set_Current_Server($Domain_Name);
                $sql="SELECT id AS domainsID,Name AS Host,serversID FROM domains WHERE Name='".$Domain_Name."'";
                $rslt=$this->r->rawQuery($sql);
                
                if($this->r->NumRows()>0){
                    $data=$this->r->Fetch_Assoc();
                    $app_data['edit_domain']=$data;
                }$this->r->
                */
                //---update remote server--------------------------------------------------------------------------";
                
                /*
                $m= new UpdateDatabase($log);
                $m->Set_Database($r);
                $m->Set_Remote_Database($Domain_Name);
                        $m->AddPosts($_POST,$_FILES);
                        $m->AddSkip(array("id"));
                        $m->AddTable("domains");
                        $m->AddID($app_data['edit_domain']["domainsID"]);
                        $m->DoStuff();
                if(isset($_POST['modulesID'])){
                            if(is_array($_POST['modulesID'])){
                                foreach($_POST['modulesID'] as $moduleID){
                                    $this->r->RawQuery("INSERT INTO domains_modules (domainsID,modulesID) VALUES ($id,$moduleID)");
                                }
                            }
                        }
                        $Message="Website Updated";
                */
                    };
                };
            
                //---retrieve remote server domain variables to edit--------------------------------------------------------------------------";
            //$Domain_Name=$app_data['remote_server']['domain_name'];
            //$this->r->Initialise_Remote_Server(true);
            $this->r->AddTable("domains");
                $this->r->AddSearchVar($id);
                $Insert=$this->r->GetRecord();
            //print_r($Insert);
                $ModsArr=array();
                $rslt=$this->r->RawQuery("SELECT modulesID FROM domains_modules WHERE domainsID=$id");
                while($myrow=$this->r->Fetch_Array($rslt)){
                    $ModsArr[]=$myrow[0];
                }
		}

        function Pre_Admin_Modify_Domain(){
			$app_data['delete_domain']=array();
            if(isset($_POST['Delete'])){
                if($_POST['Delete']=="Delete"){
            $delete_id_array=$_POST['DFiles'];
            
                    //if(is_array($_POST['DFiles'])){
            if(is_array($delete_id_array)){
                //---delete from sitemanage--------------------------------------------------------------------------";
                $this->r->Initialise_Remote_Server(true);
                        $m= new DeleteFromDatabase($log);
                $m->Set_Database($r);
                        $m->AddIDArray($_POST['DFiles']);
                        $m->AddTable("domains");
                        $Errors=$m->DoDelete();
                $Errors="";
                        if($Errors==""){
                            $Message="Web Sites Deleted";
                        }else{
                            $Message=$Errors;
                        };
                
                //------get remote domain info-----------------------------------------------------------------------------------------------------
                $count=0;	
                //print "\n$$101-".var_export($_POST['DFiles'],true)."--\n";
                $id_array=$delete_id_array;
                $app_data['delete_domain']=array();
                foreach($delete_id_array as $key=>$val){
                //print "\n$$166855-".$val."--".$key."-".$count."--\n";
                
                $id_array[$key]=$val;
                //print "$$10199-".var_export($delete_id_array,true)."--\n";
                $sql="SELECT id AS domainsID,Name AS Host,serversID FROM domains WHERE id='".$val."'";
                $rslt=$this->r->rawQuery($sql);
                //print "\n$$10119-".$val."--".$sql."--\n";
                
                if($this->r->NumRows()>0){
                    //print "$$101-".$data."--".$sql."--\n";
                    $data=$this->r->Fetch_Assoc();
                    //print "\n$$171-".var_export($data,true)."--".$this->r->current_link."--\n";
                    //print_r($data);
                    $delete_local_domain_data=$data;
                    $app_data['delete_domain'][$count]=$delete_local_domain_data;
                    
                    //------get remote domain info-----------------------------------------------------------------------------------------------------	
                    $remote_host=$delete_local_domain_data['Host'];
                    //print "\n4444-".$remote_host."--\n";
                    

                    $remote=new clsRetrieveRecords($log);
                    $remote->CreateDB();
                    $remote->Set_Vs($vs);

                    $remote->Set_Current_Server($remote_host);
                    $sql2="SELECT id AS remote_domainsID,Name AS remote_Host,serversID AS remote_serversID FROM domains WHERE Name='".$remote_host."'";
                    $rslt2=$remote->rawQuery($sql2);
                    
                    if($remote->NumRows()>0){
                    
                    $remote_data=$remote->Fetch_Assoc();
                    $app_data['delete_domain'][$count]=array_merge($delete_local_domain_data, $remote_data);
                    //print "\n$$1000-".var_export($remote_data,true)."--".$sql2."--\n";
                    //$delete_domain_data = array_merge($delete_local_domain_data, $remote_data);
                    //$delete_domain_data=$remote_data;

                    //print_r($app_data['delete_domain']);
                    //$app_data['delete_domain'][$count]=$delete_domain_data;
                    //print_r($app_data['delete_domain']);
                    
                    $count++;
                    }else{
                    //print "\n$$7777777-no domain found->".$val."--".$sql2."--".$remote->current_link."--\n";
                    $remote_data=array();
                    $delete_domain_data =array();
                    }
                    
                    
                    //$this->r->Set_Current_Server($delete_domain_data['Host']);
                    /*
                    print_r($app_data['delete_domain']);
                    $app_data['delete_domain'][]=$delete_domain_data;
                    print_r($app_data['delete_domain']);\*/
                    //$count++;
                }else{
                    //print "$$171334-select error-".var_export($data,true)."--".$sql."-".$count."--\n";
                    //print "\n$$88888-no home domain found->".$val."--".$sql."--".$this->r->current_link."---\n";
                }
                
                }
                //print "\n$$11111111-".var_export($app_data['delete_domain'],true)."----\n";
                //---delete from remote server--------------------------------------------------------------------------";
                
                foreach($app_data['delete_domain'] as $key=>$val){
                //$Domain_Name=$val['remote_Host'];
                if(count($val)>3){
                    $m= new DeleteFromDatabase($log);
                    $m->Set_Database($r);
                    $m->Set_Remote_Database($val['remote_Host']);
                    $m->ClearID();
                    $m->AddID($val['remote_domainsID']);
                    $m->AddTable("domains");
                    $Errors=$m->DoDelete();
                    $Errors="";
                    if($Errors==""){
                    $Message="Web Sites Deleted";
                    }else{
                    $Message=$Errors;
                    };
                }
                
                }
                //-----------------------------------------------------------------------------------------------------------	
                //echo"--3334-\n\n";
                //print $sql;
                //print_r($app_data);
                $rslt=$this->r->rawQuery($app_data['domains_populate']['search_sql']);
                if($this->r->NumRows()>0){
                $app_data['domains']=array();
                while($data=$this->r->Fetch_Array()){
                    $dval=$data[1]." -> ".$data[2];
                    $app_data['domains'][]=array($data[0]=>$dval);
                };
                }
                    }else{
                        $Message="No Web Sites Selected To Delete";
                    };
                };
            };
		}

        function Pre_Admin_Add_Member(){
			if(isset($_POST['Submit'])){
                //echo"yyy";
          
                /*
                public function Set_DB(&$db){
                  $this->r =$db;
                }
                
                
                public function Set_Log(&$log){
                  //$log=$log;
                  //$log->general('M Log Success:',1);
                }
                
                public function Set_Vs(&$vs=false){
                  $this->vs=$vs;
                }
                */
                //echo"10-----------------------------------------------------------".var_export($r,true)."------------------";
                
                $atd= new AddToDatabase($log);
                //echo"688-----------------------------------------------------------".var_export($r,true)."------------------";
                
               // $atd->Set_Log($log);
                $atd->Set_Database($r);
                //echo"689-----------------------------------------------------------".var_export($r,true)."------------------";
                
                $atd->Set_Vs($vs);
                $atd->AddPosts($_POST,$_FILES);
               // echo"690-----------------------------------------------------------".var_export($r,true)."------------------";
                
                $atd->AddTable("users");
                //echo"691-----------------------------------------------------------".var_export($r,true)."------------------";
                
                $atd->DoStuff();
                $NewID=$atd->ReturnID();
                
                /*
                if(isset($_POST['subdomain'])){
                    $subdomain=$this->r->Escape($_POST['subdomain']);
                }else{
                  $subdomain="";
                }
                
                if(isset($_POST['name'])){
                    $name=$this->r->Escape($_POST['name']);
                }else{
                    $name="";
                }
                if(isset($_POST['contact_name'])){
                    $contact_name=$this->r->Escape($_POST['contact_name']);
                }else{
                    $contact_name="";
                }
                if(isset($_POST['email'])){
                  $email=$this->r->Escape($_POST['email']);
                }else{
                  $email="";
                }
                if(isset($_POST['address'])){
                  $address=$this->r->Escape($_POST['address']);
                }else{
                    $address="";
                }
                if(isset($_POST['suburb'])){
                  $suburb=$this->r->Escape($_POST['suburb']);
                }else{
                    $suburb="";
                }
                if(isset($_POST['state'])){
                  $state=$this->r->Escape($_POST['state']);
                }else{
                    $state="";
                }
                if(isset($_POST['postcode'])){
                    $postcode=$this->r->Escape($_POST['postcode']);
                }else{
                    $postcode="";
                }
                if(isset($_POST['phone'])){
                    $phone=$this->r->Escape($_POST['phone']);
                }else{
                    $phone="";
                }
                if(isset($_POST['mobile'])){
                    $mobile=$this->r->Escape($_POST['mobile']);
                }else{
                    $mobile="";
                }
                if(isset($_POST['fax'])){
                    $fax=$this->r->Escape($_POST['fax']);
                }else{
                    $fax="";
                }
                if(isset($_POST['website'])){
                  $website=$this->r->Escape($_POST['website']);
                }else{
                    $website="";
                }
                if(isset($_POST['password'])){
                  $password=$this->r->Escape($_POST['password']);
                }else{
                    $password="";
                }
                if(isset($_POST['accesslvl'])){
                    $accesslvl=$this->r->Escape($_POST['accesslvl']);
                }else{
                    $accesslvl="";
                }
                if(isset($_POST['abn'])){
                  $abn=$this->r->Escape($_POST['abn']);
                }else{
                    $abn="";
                }
                if(isset($_POST['mod_business_categoriesID'])){
                  $mod_business_categoriesID=$this->r->Escape($_POST['mod_business_categoriesID']);
                }else{
                    $mod_business_categoriesID="";
                }
                if(isset($_POST['business_description'])){
                    $business_description=$this->r->Escape($_POST['business_description']);
                }else{
                    $business_description="";
                }
                  
          
                $sql="INSERT INTO users (subdomain,name,contact_name,email,address,suburb,state,postcode,phone,mobile,";
                $sql.="fax,website,password,accesslvl,abn,mod_business_categoriesID,business_description)";
                $sql.=" VALUES (".$subdomain.",".$name.",".$contact_name.",".$email.",".$address.",".$suburb.",".$state.",".$postcode.",".$phone.",".mobile.",";
                $sql.=$fax.",".$website.",".$password.",".$accesslvl.",".$abn.",".$mod_business_categoriesID.",".$business_description.")";
                print $sql."<br>";
                //$loggeneral("1 In Add User->".$sql,3);
                $rslt=$this->r->RawQuery($sql);
                */
                
                $Message="Member Added";
              };
              if(!isset($Insert['accesslvl'])) $Insert['accesslvl']="";
              
              if(!isset($Insert['mod_business_categoriesID'])) $Insert['mod_business_categoriesID']=0;
              if(!isset($Insert['business_description'])) $Insert['business_description']="";
          
		}

        function Pre_Admin_Modify_Member(){
			if(isset($_GET['Delete'])){
                if($_GET['Delete']=="Disable"){
                  if(isset($_GET['DFiles'])){
                    if(is_array($_GET['DFiles'])){
                      $m= new BulkDBChange();
                      $m->Set_Database($r);
                      $m->AddIDArray($_GET['DFiles']);
                      $m->WhatToChange("status","Rejected",false);
                      $m->AddTable("users");
                      $Errors=$m->DoChange();
                      
                      if($Errors==""){
                        $Message="Accounts Disabled";
                      }else{
                        $Message=$Errors;
                      };
                    }else{
                      $Message="No Accounts Selected To Disable";
                    };
                  }
                };
            
                if($_GET['Delete']=="Enable"){
                  if(isset($_GET['DFiles'])){
                    if(is_array($_GET['DFiles'])){
                      $m= new BulkDBChange();
                      $m->Set_Database($r);
                      $m->AddIDArray($_GET['DFiles']);
                      $m->WhatToChange("status","Approved",false);
                      $m->AddTable("users");
                      $Errors=$m->DoChange();
                      
                      if($Errors==""){
                        $Message="Accounts Enabled";
                      }else{
                        $Message=$Errors;
                      };
                    }else{
                      $Message="No Accounts Selected To Disable";
                    };
                  };
                };
              };
                
                
                
                
                
                if(isset($_GET['SText'])) $SText=$_GET['SText'];
                elseif (isset($_POST['SText'])) $SText=$_POST['SText'];
                if(isset($_GET['SType'])) $SType=$_GET['SType'];
                elseif (isset($_POST['SType'])) $SType=$_POST['SType'];
                if(isset($_GET['OType'])) $OType=$_GET['OType'];
                elseif (isset($_POST['OType'])) $OType=$_POST['OType'];
                else $OType="id";
                if(isset($_GET['OOType'])) $OOType=$_GET['OOType'];
                elseif (isset($_POST['OOType'])) $OOType=$_POST['OOType'];
                else $OOType="ASC";
                if(isset($_GET['NumRows'])) $NumRows=$_GET['NumRows'];
                elseif (isset($_POST['NumRows'])) $NumRows=$_POST['NumRows'];
                else $NumRows=10;
                if(isset($_GET['Page'])) $Page=$_GET['Page'];
                elseif(isset($_POST['Page'])) $Page=$_POST['Page'];
                else $Page=1;
                
              $SearchSQL="";
                $RecordsPerPage=$NumRows;
                $DynField="email";
                if(!empty($SText)){
                    $SearchSQL="AND $SType LIKE '%$SText%'";
                    if(($SType!="id")&&($SType!="name")) $DynField=$SType;
                };
                
                
                $SQL1="SELECT COUNT(*) FROM users,mod_business_categories ";
              $SQL1.="WHERE users.mod_business_categoriesID=mod_business_categories.id ";
              //$SQL1.="AND domainsID=".$session_data['domainsID']." ".$SearchSQL;
              $SQL1.="AND domainsID=0 ".$SearchSQL;
              //$SQL1="SELECT COUNT(*) FROM users,mod_business_categories WHERE users.mod_business_categoriesID=mod_business_categories.id AND domainsID=$session_data[domainsID] $SearchSQL";
                
              $rset=$this->r->rawQuery($SQL1);
              
                $rdata=$this->r->Fetch_Array($rset);
                if(isset($rdata[0])) $rcount=$rdata[0];
              else $rcount=0;
              
                $MaxPages=ceil($rcount/$RecordsPerPage);
                if($Page>$MaxPages) $Page=$MaxPages;
                $StartRecord=($Page-1)*$RecordsPerPage;
                if($StartRecord<0) $StartRecord=0;
                $SQL2="SELECT users.id,users.name,$DynField,status FROM users,mod_business_categories";
              $SQL2.=" WHERE users.mod_business_categoriesID=mod_business_categories.id ";
              $sql_domains="AND domainsID=$session_data[domainsID]";
              $sql_domains2="AND domainsID=0";
              $SQL3=$SQL2;
              $SQL2.=$sql_domains." $SearchSQL  ORDER BY $OType $OOType LIMIT $StartRecord,$RecordsPerPage";
              $SQL3.=$sql_domains2." $SearchSQL  ORDER BY $OType $OOType LIMIT $StartRecord,$RecordsPerPage";
                $rset=$this->r->rawQuery($SQL2);
              
              $SQL_table=$SQL2;
              //print $SQL2."\n\n<br>";
              $nrows=$this->r->NumRows();
              if($nrows<1){
                $rset=$this->r->rawQuery($SQL3);
                $nrows=$this->r->NumRows();
                $rcount=$nrows;
                if($nrows<1){
                  $rcount=0;
                }else{
                  $SQL_table=$SQL3;
                }
                
              }
              print "numrows=>".$nrows."--\n\n".$SQL3;
             
              if(!isset($SText)) $SText="";
              if(!isset($SType)) $SType="";
              if((isset($NumRows))&&(isset($SType))&&(isset($OType))&&(isset($OOType))&&(isset($SText))){
                  $NPPage="NumRows=".$NumRows."&SType=".$SType."&OType=".$OType."&OOType=".$OOType."&SText=".urlencode($SText);
              }else{
                $NPPage="";
              }
              $RecTo=($StartRecord+$RecordsPerPage);
                if($RecTo>$rcount) $RecTo=$rcount;
		}

        function Pre_Admin_Modify_Administrators(){
			if(isset($_POST['Delete'])){
                if($_POST['Delete']=="Delete"){
                    if(is_array($_POST['DFiles'])){
                        $m= new DeleteFromDatabase($log);
                print "654->->".var_export($m,true);
                $m->Set_Database($r);
                        $m->AddIDArray($_POST['DFiles']);
                        $m->AddTable("administrators");
                        $Errors=$m->DoDelete();
                        if($Errors==""){
                            $Message="Administrators Deleted";
                        }else{
                            $Message=$Errors;
                        };
                    }else{
                        $Message="No Administrators Selected To Delete";
                    };
                };
            };
		}

        function Pre_Admin_Edit_Administrators(){
			if(isset($_POST['Submit'])){
                if($_POST['Submit']){
                  $m= new UpdateDatabase($log);
                  $m->Set_Database($r);
                  $m->AddPosts($_POST,$_FILES);
                  $m->AddSkip(array("id"));
                  $m->AddTable("administrators");
                  $m->AddID($_POST['id']);
                  $m->DoStuff();
                  $Message="Administrator Updated";
                  
                  $this->r->RawQuery("DELETE FROM administrators_domains WHERE administratorsID=$_POST[id]");
                  if(isset($_POST['domainsID'])){
                      foreach($_POST['domainsID'] as $key=>$val){
                        $this->r->RawQuery("INSERT INTO administrators_domains (administratorsID,domainsID) VALUES ($_POST[id],$val)");
                      }
                  }
                  
                  
                };
            }
                
            if(isset($_GET['id'])){
                $id=$_GET['id'];
            }else{
                $id=0;
            }
            if (isset($_POST['id'])) $id=$_POST['id'];
                
                
                
                
            $this->r->AddTable("administrators");
            $this->r->AddSearchVar($id);
            $Insert=$this->r->GetRecord();
            $DomArr=array();
            $sql="SELECT domainsID FROM administrators_domains WHERE administratorsID=".$id;
            //print $sql;
            $rslt=$this->r->RawQuery($sql);
            if($rslt){
                if($this->r->NumRows($rslt)>0){
                    while($myrow=$this->r->Fetch_Array($rslt)){
                        $DomArr[]=$myrow[0];
                    }
                }
            }
		}

        function Pre_Admin_Add_Sub_Domain(){
			//-----------------------------------------------------------------------------------------------------------	
            // Get public domains to add subdomains
            //-----------------------------------------------------------------------------------------------------------
            try{
                //$this->r->Initialise_Remote_Server(true);
                $sql="SELECT id AS domainsID,domains.Name AS Host,ClientsID FROM domains WHERE Public='Yes'";
                $rslt=$this->r->rawQuery($sql);
                
                if($this->r->NumRows()>0){
                    //$domain_name=$data[1];
                    while($data=$this->r->Fetch_Assoc($rslt)){
                    //print_r($data);
                        $app_data['public_domains'][]=$data;
                    }
                }
            }catch(Exception $e){
                //print_r($e);
            }
            //--------Add new domain---------------------------------------------------------------------------------------------------	
            if(isset($_POST['Submit'])){
                if($_POST['Submit']){
            
                    //------add domain to sitemanage.info-----------------------------------------------------------------------------------------------------	
                    $this->r->Initialise_Remote_Server(true);
                    $sql="SELECT id AS domainsID,Name AS Host,serversID FROM domains WHERE id='".$_POST['DomainsID']."'";
                    $rslt=$this->r->rawQuery($sql);
                    
                    if($this->r->NumRows()>0){
                        $data=$this->r->Fetch_Assoc();
                        $app_data['selected_domain']=$data;
                    }
                    //------add domain to sitemanage.info-----------------------------------------------------------------------------------------------------	
                    $_POST['Name']=$_POST['Name'].".".$app_data['selected_domain']['Host'];
                    
                    //------add domain to sitemanage.info-----------------------------------------------------------------------------------------------------	
                    $this->r->Initialise_Remote_Server(true);
                    $m= new AddToDatabase($log);
                    $m->Set_Database($r);
                    $m->AddPosts($_POST,$_FILES);
                    $m->AddExtraFields(array("ClientsID"=>$session_data['clientsID']));
                        $m->AddExtraFields(array("serversID"=>$app_data['selected_domain']['serversID']));
                    $m->AddTable("domains");
                    $m->DoStuff();
                    $NewID=$m->ReturnID();

                    
                    if(isset($_POST['modulesID'])){
                        if(is_array($_POST['modulesID'])){
                            foreach($_POST['modulesID'] as $moduleID){
                            $this->r->RawQuery("INSERT INTO domains_modules (domainsID,modulesID) VALUES ($NewID,$moduleID)");
                            }
                        }
                    }
                    $Message="Website Added";    
                };
            }
		}

        function Pre_Admin_Set_Password(){
			if(isset($_POST['Submit'])){
                if($_POST['Submit']){
                    $this->r->AddTable("administrators");
                    $this->r->AddSearchVar($session_data['administratorsID']);
                    $Insert=$this->r->GetRecord();
                    if(isset($_POST['cpassword'])){
                  if(isset($Insert['password'])){
                    if($_POST['cpassword']==$Insert['password']){
                      $m= new UpdateDatabase($log);
                      $m->Set_Database($r);
                      $m->AddPosts($_POST,$_FILES);
                      $m->AddTable("administrators");
                      $m->AddID($session_data['administratorsID']);
                      $str=$app_data['administrators']['username'].$app_data['administrators']['password'];
                      $FieldArray=array("hash"=>md5($str));
                      $m->AddExtraFields($FieldArray);
                      $m->DoStuff();
                      $Message="Password Updated";
                    }else{
                      $Message="Current Password Incorrect";
                    }
                  }
              }
                    
                };
            }
		}

        function Pre_Admin_Register(){
			if(isset($_GET['Message']))$Message=$_GET['Message'];
	
                if(isset($_POST['Submit'])){
                    if($_POST['contact_name']!=""){
                    $client_name=$_POST['contact_name'];
                    }else{
                    $client_name=$_POST['name'];
                    }
                    $sql="INSERT INTO clients (Name) VALUES  ('".$client_name."')";
                    
                    //print $sql;
                    $rslt=$this->r->RawQuery($sql);
                    $Insert_Id=$this->r->Insert_Id();
                
                    $hash=md5($_POST['username'].$_POST['password']);
                    $sql="INSERT INTO administrators (name,email,username,password,administratorActive,SU,clientsID,hash) VALUES  ('".$_POST['contact_name']."','".$_POST['email']."'";
                    $sql.=",'".$_POST['username']."','".$_POST['password']."','1','Yes','".$Insert_Id."','".$hash."')";
                        
                    //print $sql;
                
                    //print_r($_POST);
                    $rslt=$this->r->RawQuery($sql); 
                    $AdministratorsID=$this->r->Insert_Id();
                    //echo"10-----------------------------------------------------------".var_export($r,true)."------------------";
                    if($_POST['subdomain']==""){
                    $_POST['subdomain']=str_replace(" ","-",$client_name);
                    }else{
                    $_POST['subdomain']=str_replace(" ","-",$_POST['subdomain']);
                    }
                    
                    $atd= new AddToDatabase($log);
                    //echo"688-----------------------------------------------------------".var_export($r,true)."------------------";
                    
                // $atd->Set_Log($log);
                    $atd->Set_Database($r);
                    //echo"689-----------------------------------------------------------".var_export($r,true)."------------------";
                    
                    $atd->Set_Vs($vs);
                    $atd->AddPosts($_POST,$_FILES);
                    
                // echo"690-----------------------------------------------------------".var_export($r,true)."------------------";
                    
                    $atd->AddTable("users");
                    //echo"691-----------------------------------------------------------".var_export($r,true)."------------------";
                    //$FieldArray=array("administratorsID"=>$AdministratorsID,"clientsID"=>$Insert_Id,"status"=>"New","administratorActive"=>0);
                    $FieldArray=array("administratorsID"=>$AdministratorsID,"clientsID"=>$Insert_Id,"status"=>"New");
                    $atd->AddExtraFields($FieldArray);
                    //$atd->AddSkip(array("administratorActive"));
                    $atd->DoStuff();
                    $NewID=$atd->ReturnID();
                    /* 2023-03-20
                    2023-03-20 */
                    $econtent="\n\n Welcome to iCWLNet website builder. \n";
                    $econtent.="You must activate your account. Please follow the below link \n";
                    $econtent.="https://sitemanage.info/index.php?hash=".$hash." \n";
                    $econtent.="Contact Us: https://creativeweblogic.net \n";
                    $to      = $_POST['email'];
                    $subject = 'New User Register';
                    $message = $econtent;
                    $headers = array(
                        'From' => 'admin@sitemanage.info',
                        'Reply-To' => 'admin@sitemanage.info',
                        'X-Mailer' => 'PHP/' . phpversion()
                    );
                
                    mail($to, $subject, $message, $headers);
            }
		}

        function Pre_Admin_Add_Page(){
			if(isset($_POST['Submit'])){
                if($_POST['Submit']){
                    if(($_POST['URI']=="")||($_POST['URI']=="/example-page-address/")){
                        $_POST['URI']=$_POST['Title'];
                    }
                    $_POST['URI']=dirify($_POST['URI']);// remove reserved characters
                    if(substr($_POST['URI'],0,1)!="/") $_POST['URI']="/".$_POST['URI']; // if start of string not /
                    if(substr($_POST['URI'],strlen($_POST['URI'])-1,1)!="/") $_POST['URI']=$_POST['URI']."/";// if end of string not /
                    if(($_POST['MenuTitle']=="")||($_POST['MenuTitle']=="Example Menu Title")){
                        $_POST['MenuTitle']=$_POST['Title'];
                    }
                    if($_POST['Meta_Title']=="") $_POST['Meta_Title']=$_POST['Title'];
                    if($_POST['HomePage']=="Yes"){
                        $this->r->RawQuery("UPDATE content_pages SET HomePage='No' WHERE domainsID=".$app_data['domainsID']." AND languagesID=".$app_data['languagesID']);
                    }
                    // check if no homepage
                    $rslt=$this->r->RawQuery("SELECT COUNT(*) FROM content_pages WHERE domainsID=".$app_data['domainsID']." AND languagesID=".$app_data['languagesID']);
                    $data=$this->r->Fetch_Array($rslt);
                    if($data[0]==0){// if none set current to home
                        $_POST['HomePage']="Yes";
                        $_POST['URI']="/";
                    }
                    $m= new AddToDatabase($log);
              $m->Set_Database($r);
                    $m->AddPosts($_POST,$_FILES);
                    $m->AddTable("content_pages");
                    $m->AddExtraFields(array("languagesID"=>$app_data['languagesID']));
                    $m->AddExtraFields(array("domainsID"=>$app_data['domainsID']));
                    $m->AddExtraFields(array("module_viewsID"=>1));
                    $m->AddFunctions(array("Changed"=>"NOW()"));
                    $m->DoStuff();
                    $NewID=$m->ReturnID();
                    $m= new AddToDatabase($log);
              $m->Set_Database($r);
                    $m->AddPosts($_POST,$_FILES);
                    $m->AddTable("mod_text");
                    $m->AddExtraFields(array("content_pagesID"=>$NewID,"sidebar_content"=>"No"));
                    $m->DoStuff();
              /* removed 2023-03-19
                    if($_POST['sidebar_module_viewsID']==11){
                        $_POST['content_text_sidebar']=$this->r->Escape($_POST['content_text_sidebar']);
                        $this->r->RawQuery("INSERT INTO mod_text (content_pagesID,content_text,sidebar_content) VALUES ($NewID,'$_POST[content_text_sidebar]','Yes')");
                    }
                    removed 2023-03-19 */
                    $Message="Page Added";
                };
            }
          
            $this->r->AddTable("domains");
            $this->r->AddSearchVar($app_data['domainsID']);
            $DInsert=$this->r->GetRecord();
		}

        function Pre_Admin_Modify_Page(){
			if(isset($_POST['Delete'])){
                if($_POST['Delete']=="Delete"){
                    if(is_array($_POST['DFiles'])){
                $this->r->Set_Current_Server($app_data['remote_server']['domain_name']);
                        $m= new DeleteFromDatabase();
                $m->Set_Database($r);
                        $m->AddIDArray($_POST['DFiles']);
                        $m->AddTable("content_pages");
                        $Errors=$m->DoDelete();
                        if($Errors==""){
                            $Message="Pages Deleted";
                        }else{
                            $Message=$Errors;
                        };
                    }else{
                        $Message="No Pages Selected To Delete";
                    };
                };
            }else{
                //$Message="No Pages Selected To Delete";
            }
            if(isset($_POST['Sort'])){
                if($_POST['Sort']){
                    //print_r($_POST);
                    if(is_array($_POST['SFiles'])){
                //$this->r->Set_Current_Server($app_data['remote_server']['domain_name']);
                        $m= new BulkDBChange();
                $m->Set_Database($r);
                        $m->AddIDMultiArray($_POST['SFiles']);
                        $m->WhatToChange("Sort_Order");
                        $m->AddTable("content_pages");
                        $Errors=$m->DoChange();
                        
                        if($Errors==""){
                            $Message="Sort Orders Changed";
                        }else{
                            $Message=$Errors;
                        };
                    }else{
                        $Message="No Available Items";
                    };
                };
            }
		}

        function Pre_Admin_Edit_Page(){
			$this->r->Set_Current_Server($app_data['remote_server']['domain_name']);
            if(isset($_POST['Submit'])){
                if($_POST['Submit']){
                    if($_POST['HomePage']=="Yes"){
                        $this->r->RawQuery("UPDATE content_pages SET HomePage='No' WHERE languagesID=$session_data[languagesID] AND domainsID=$session_data[domainsID]");
                    }
                    $_POST['URI']=dirify($_POST['URI']);// remove reserved characters
                    if(substr($_POST['URI'],0,1)!="/") $_POST['URI']="/".$_POST['URI']; // if start of string not /
                    if(substr($_POST['URI'],strlen($_POST['URI'])-1,1)!="/") $_POST['URI']=$_POST['URI']."/";// if end of string not /
                    $m= new UpdateDatabase($log);
            $m->Set_Database($r);
                    $m->AddPosts($_POST,$_FILES);
                    $m->AddSkip(array("id"));
                    $m->AddTable("content_pages");
                    $m->AddFunctions(array("Changed"=>"NOW()"));
                    $m->AddID($_POST['id']);
                    $m->DoStuff();
                    //change main text content
                    $_POST['content_text']=$this->r->Escape($_POST['content_text']);
                    $this->r->RawQuery("UPDATE mod_text SET content_text='$_POST[content_text]' WHERE content_pagesID=$_POST[id] AND sidebar_content='No'");
                    //change sidebar text content
            /* removed 2023-03-19
                    if($_POST['sidebar_module_viewsID']==11){
                        $rslt=$this->r->RawQuery("SELECT COUNT(*) FROM mod_text WHERE content_pagesID=$_POST[id] AND sidebar_content='Yes'");
                        if($rslt){
                            $_POST['content_text_sidebar']=$this->r->Escape($_POST['content_text_sidebar']);
                            $data=$this->r->Fetch_Array();
                            if($data[0]>0){
                                $this->r->RawQuery("UPDATE mod_text SET content_text='$_POST[content_text_sidebar]' WHERE content_pagesID=$_POST[id] AND sidebar_content='Yes'");//echo "xx";
                            }else{
                                $this->r->RawQuery("INSERT INTO mod_text (content_pagesID,content_text,sidebar_content) VALUES ($_POST[id],'$_POST[content_text_sidebar]','Yes')");//echo "yy";
                            }
                        }else{
                            //echo "zz";	
                        }
                    }

            removed 2023-03-19 */
                    $Message="Page Updated";
                };
            }else{
                
            }
            
            if(isset($_GET['id'])){
                if($_GET['id']) $id=$_GET['id'];
            }
            if(isset($_POST['id'])){
                if ($_POST['id']) $id=$_POST['id'];
            }
            
            
            
            //echo"=> 1add search var=>".var_export($_POST,true)."<=\n\n";
            $this->r->AddTable("content_pages");
            $this->r->AddSearchVar($id);
        //echo"=>2 add search var=>".var_export($_POST,true)."<=\n\n";
            $Insert=$this->r->GetRecord();
            $this->r->AddTable("mod_text");
            $this->r->AddSearchVar($id);
            $this->r->AddNewSearchVar("sidebar_content","No");
            $this->r->ChangeTarget("content_pagesID");
            $TInsert=$this->r->GetRecord();
        //print_r($TInsert);
            if(!isset($TSInsert['content_text'])){
            $TSInsert['content_text']="";
        }
        //$TSInsert['content_text']="";
        if(isset($Insert['sidebar_module_viewsID'])){
            if($Insert['sidebar_module_viewsID']==11){
            $this->r->AddTable("mod_text");
            $this->r->AddSearchVar($id);
            //$this->r->AddNewSearchVar("sidebar_content","Yes");
            $this->r->AddNewSearchVar("sidebar_content","No");
            $this->r->ChangeTarget("content_pagesID");
            $TSInsert=$this->r->GetRecord();
            //echo"=>content_text=>".var_export($TSInsert,true)."<=\n\n";
            }
        }
		}

        function Pre_Admin_Login(){
            /*
			if(isset($_GET['file'])){
                $file_name=$_GET['file'];
                header("Location: http://assets.localhost/".$file_name);
            }
            */  
            $login=false;
                
            if(isset($_GET['Message']))$Message=$_GET['Message'];
            if(isset($_GET['hash'])){
                $login=true;
                $sql="UPDATE administrators SET administratorActive=1 WHERE hash='".$_GET['hash']."'";
                $data=$this->r->rawQuery($sql);
                $sql="SELECT * FROM administrators where hash='".$_GET['hash']."' LIMIT 0,1";
            }

            if(isset($_POST['Submit'])){
                if($_POST['Submit']!=""){
                    
                    //$sql="SELECT * FROM mod_user_accounts,mod_login_details where mod_user_accounts.mod_login_detailsID=mod_login_details.id AND username='$_POST[UserName]' and password='$_POST[Password]' LIMIT 0,1";
                    $sql="SELECT id FROM mod_login_details where username='".$_POST['username']."' AND password='".$_POST['password']."' LIMIT 0,1";
                    $data=$this->r->rawQuery($sql);
                    $login_array=$this->r->Fetch_Array($data);
                    if(isset($login_array[0])){
                        if($login_array[0]>0){
                            $login=true;
                            $login_id=$login_array[0];
                        }else{
                            $login=false;
                        }
                    }else{
                        $login=false;
                    }
                    
                    
                }
            }
            
            if($login){
                //echo"\n\n0000----------------------------||-------------------------------------------------\n\n";
                $sql="SELECT * FROM mod_user_accounts WHERE mod_login_detailsID='".$login_id."'";
                $data=$this->r->rawQuery($sql);
                
                $dataarray=$this->r->Fetch_Array($data);
                print_r($dataarray);
                /*
                if(isset($dataarray[0])){
                    if($dataarray[0]>0){ //admin login ok
                            
                        $session_data["administratorsID"]=$dataarray[0];
                        $session_data["SU"]=$dataarray[6];
                        $session_data["clientsID"]=$dataarray[7];
                        $session_data["username"]=$dataarray[3];
        
                        $session_data['original_clientsID']=$session_data["clientsID"];//$dataarray[2];
                        $session_data['original_administratorsID']=$session_data["administratorsID"];//$dataarray[0];
                            
                        if($session_data["SU"]=="CWL"){
                            $sql="SELECT MIN( domains.id) FROM domains WHERE  clientsID=".$session_data['clientsID'];
                        }else{
                            $sql="SELECT MIN( domains.id) FROM domains,administrators_domains WHERE domains.id=administrators_domains.domainsID";
                            $sql.=" AND administratorsID=$session_data[administratorsID] AND clientsID=$session_data[clientsID]";
                        }

                        $rslt=$this->r->RawQuery($sql);
                        if($rslt){
                            if($this->r->NumRows($rslt)>0){
                                $data=$this->r->Fetch_Array($rslt);
                                if($data[0]>0){
                                    $session_data['original_domainsID']=$data[0];
                                    $_COOKIE['original_domainsID']=$data[0];
                                }
                            }
                        }
                    
                        $_SESSION=$session_data;
                    
                        //$loc="Location: main/logged-in/index.php";
                        //---------------------------2023-07-04-----------------------------
                        //header($loc);
                        //print $loc;
                    }else{	//admin login bad
                        $Message="Incorrect Username or Password";
                    };
                }else{
                    $Message="Incorrect Username or Password";
                }
                */
            }else{
                $this->Message="Incorrect Username or Password";
            }
            //return $Message;
		}

        function Pre_Admin_Edit_Member(){
			
		}

        function Pre_Admin_Add_News(){
			
		}

        function Pre_Admin_Modify_News(){
			
		}

        function Pre_Admin_Edit_News(){
			
		}

        function Pre_Admin_Add_Links(){
			
		}

        function Pre_Admin_Modify_Links(){
			
		}

        function Pre_Admin_Edit_Links(){
			
		}

        function Pre_Admin_Add_Testimonials(){
			
		}

        function Pre_Admin_Modify_Testimonials(){
			
		}

        function Pre_Admin_Edit_Testimonials(){
			
		}

        function Pre_Admin_Add_Gallery(){
			
		}

        function Pre_Admin_Modify_Gallery(){
			
		}

        function Pre_Admin_Edit_Gallery(){
			
		}

        
        
        function Create_Country_Select($countryID=0){
            $output="";
                $output='<SELECT NAME="countryID" id="countryID">';
                
                
                $sql=$this->r->rawQuery("SELECT id,Country_Name FROM countries");
                while($myrow=$this->r->Fetch_Array($sql)){
                    if($countryID==$myrow[0]){
                        $output.="<option value='".$myrow[0]."' selected>$myrow[1]</option>";
                    }else{
                        $output.="<option value='".$myrow[0]."'>".$myrow[1]."</option>";
                    };
                }
                $output.="</SELECT>";
                return $output;
        }

        function Create_Business_Categories_Select($domainsID=0,$mod_business_categoriesID=0){
            $output="";
            $output='<SELECT NAME="mod_business_categoriesID" id="mod_business_categoriesID">';
            
            $sql=$this->r->rawQuery("SELECT id,CategoryTitle FROM mod_business_categories WHERE domainsID=".$domainsID." OR domainsID=0 ORDER BY CategoryTitle");
            while($myrow=$this->r->Fetch_Array($sql)){
                if($mod_business_categoriesID==$myrow[0]){
                    $output.="<option value='".$myrow[0]."' selected>$myrow[1]</option>";
                }else{
                    $output.="<option value='".$myrow[0]."'>".$myrow[1]."</option>";
                };
            }
            $output.="</SELECT>";
            return $output;
        }
        
        
    }

