<?php

    class clsTestServer extends clsFormCreator{
        private $text_data=array();
        private $Message="";

        private $this->output="";
        private $log;

        private $r;

        
        function __construct(){

        }

        function Set_DataBase($r){
			$this->r=$r;
			
		}

        function Set_Log($log){
			$this->log=$log;
			
		}


        function Pre_Login(){
            if(isset($_POST['Submit'])){
                if($_POST['Submit']){
                    //$sql="SELECT users.id,password FROM users,mod_business_categories WHERE users.mod_business_categoriesID=mod_business_categories.id AND domainsID=".$domain_data['id']." AND email='$_POST[email]' AND password='$_POST[password]'";
                    $sql="SELECT users.id,password FROM users,mod_business_categories WHERE users.mod_business_categoriesID=mod_business_categories.id AND email='$_POST[email]' AND password='$_POST[password]'";
                    $this->log->general('Member Pre Login-'.$sql,1);
                    //print $sql;
                    $rslt=$this->r->RawQuery($sql);
                    if($rslt){
                        $data=$this->r->Fetch_Array();
                        if(isset($data)){
                            if(is_array($data)){
                                //$log->general('Member Pre Login-'.var_dump($data,true),1);
                                $this->log->general('Member Pre Login-',1);
                                if(($data[0]>0)&&($data[1]==$_POST['password'])){
                                    //$log->general('Member Login Checked-'.var_dump($data,true),1);
                                    $this->log->general('Member Login Checked-',1);
                                    $_SESSION['membersID']=$data[0];
                                    $membersID=$data[0];
                                    $this->log->general('Member Done-'.$_SESSION['membersID']."\n\n",1);
                                    
                                    if(isset($_SESSION['PAGENAME'])){
                                        $destination=$_SESSION['PAGENAME'];
                                    }else{
                                        $destination="/members/home/";
                                    }
                                    header("Location: ".$destination);
                                    //$TotalPageName="/member/home/";
                                    //include($app_data['MODULEBASEDIR']."content/init.php");
                                }else{
                                    $this->Message="Incorrect Email Or Password";
                                }
                            }else{
                                $this->Message="Incorrect Email Or Password";
                            }
                        }else{
                            $this->Message="Incorrect Email Or Password";
                        }
                    }else{
                        $this->Message="Incorrect Email Or Password";
                    }
                }
            }else{
                $this->Message="Incorrect Email Or Password";
            }
        }

        function Member_Register(){

            $this->output='
            <script type="text/javascript">
                <!--
                function CheckAgree(){
                    var target=document.getElementById("IAgree");
                    if(target.checked){
                        return true;
                    }else{
                        alert("You must agree to the terms and conditions");
                        return false;
                    }
                }

                //-->
                </script>';
                $this->output.='
                <table width="100%">
                    <tr>
                        <td width="20%" valign="top">
                            <a href="/login/">Login</a><br>
                            <a href="/forgot-password/">Forgot Login</a>
                        </td>
                        <td width="80%">
                <form action="'.$_SERVER['REQUEST_URI'].'" method="post" name="form1" />
                '.$Message.'
                <br>
                <table width="434" border=0 align="center" cellpadding=2 cellspacing="1" bgcolor="#97C8F9" id="table">
                    <tr>
                    <td colspan="2" align="center" bgcolor="#E6E6E6"><strong>All Fields Are Mandatory</strong></td>
                    </tr>
                    <tr>
                    <td width="184" bgcolor="#E6E6E6"><strong>Business&nbsp; Name</strong></td>
                    <td width="240" bgcolor="#FFFFFF">
                        <INPUT NAME="name" TYPE="text" id="name" value="'.$_POST['name'].'" SIZE="26">
                    </td>
                    </tr>
                    <tr>
                    <td width="184" bgcolor="#E6E6E6"> <strong>Business Address</strong> </td>
                    <td width="240" bgcolor="#FFFFFF"> 
                        <INPUT NAME="address" TYPE="text" id="address" value="'.$_POST['address'].'" SIZE="40">
                    </td>
                    </tr>
                    <tr>
                    <td width="184" bgcolor="#E6E6E6"> <strong>Suburb / Town / City</strong> </td>
                    <td width="240" bgcolor="#FFFFFF"> 
                        <INPUT NAME="suburb" TYPE="text" id="suburb" value="'.$_POST['suburb'].'" SIZE="26">
                    </td>
                    </tr>
                    <tr>
                    <td width="184" bgcolor="#E6E6E6"> <strong>State</strong> </td>
                    <td width="240" bgcolor="#FFFFFF"> 
                        <SELECT NAME="state" id="state">
                        <OPTION SELECTED VALUE="Queensland">Queensland
                            <OPTION VALUE="New South Wales">New South Wales
                            <OPTION VALUE="Victoria">Victoria
                            <OPTION VALUE="South Australia">South Australia
                            <OPTION VALUE="Western Australia">Western Australia
                            <OPTION VALUE="Tasmania">Tasmania
                            <OPTION VALUE="Australian Capital Territory">Australian Capital Territory
                            <OPTION VALUE="Northern Territory">Northern Territory
                        </SELECT>
                    </td>
                    </tr>
                    <tr>
                    <td width="184" bgcolor="#E6E6E6"> <strong>Postcode </strong> </td>
                    <td width="240" bgcolor="#FFFFFF"> 
                        <INPUT NAME="postcode" TYPE="text" id="postcode"
                 value="'.$_POST['postcode'].'" SIZE="8">
                    </td>
                    </tr>
                    <tr bgcolor="#C6C6CD" >
                        <td width="35%" align="left"><strong>*Country</strong></td>
                        <td align="left"><select name="Country" onChange="setDelivery(this.value)">
                        ';
                        $sql=$this->r->rawQuery("SELECT id,Country_Name FROM countries");
                        while($myrow=$this->r->Fetch_Array($sql)){
                            if($Insert['Country']==$myrow[0]){
                                $this->output='<option value="'$myrow[0]'" selected>'.$myrow[1].'</option>';
                            }else{
                                $this->output='<option value="'.$myrow[0].'">'.$myrow[1].'</option>';
                            };
                        }
                        $this->output='</select></td>
                    <tr>
                    <td width="184" bgcolor="#E6E6E6"> <strong>ABN</strong></td>
                    <td width="240" bgcolor="#FFFFFF"> 
                        <INPUT NAME="abn" TYPE="text" id="abn" value="'.$_POST['abn'].'" SIZE="20">
                    </td>
                    </tr>
                    <tr>
                    <td width="184" bgcolor="#E6E6E6"> <strong>Phone Number</strong> </td>
                    <td width="240" bgcolor="#FFFFFF"> 
                        <INPUT NAME="phone" TYPE="text" id="phone" value="'.$_POST['phone'.'" SIZE="20">
                    </td>
                    </tr>
                    <tr>
                    <td width="184" bgcolor="#E6E6E6"> <strong>Mobile Number</strong> </td>
                    <td width="240" bgcolor="#FFFFFF"> 
                        <INPUT NAME="mobile" TYPE="text" id="mobile" value="'.$_POST['mobile'].'" SIZE="20">
                    </td>
                    </tr>
                    <tr>
                    <td width="184" bgcolor="#E6E6E6"> <strong>Fax Number</strong> </td>
                    <td width="240" bgcolor="#FFFFFF"> 
                        <INPUT NAME="fax" TYPE="text" id="fax" value="'.$_POST['fax'].'" SIZE="20">
                    </td>
                    </tr>
                    <tr>
                    <td width="184" bgcolor="#E6E6E6"> <strong>E-mail Address</strong> </td>
                    <td width="240" bgcolor="#FFFFFF"> 
                        <INPUT NAME="email" TYPE="text" id="email"
                             value="'.$_POST['email'].'" SIZE="40">
                    </td>
                    </tr>
                    <tr>
                    <td width="184" bgcolor="#E6E6E6"> <strong>Web Site</strong> </td>
                    <td width="240" bgcolor="#FFFFFF"> 
                        http://
                        <INPUT NAME="website" TYPE="text" id="website" value="'.$_POST['website'].'" SIZE="30">
                    </td>
                    </tr>
                    <tr>
                    <td width="184" bgcolor="#E6E6E6"> <strong>Contact Person</strong> </td>
                    <td width="240" bgcolor="#FFFFFF"> 
                        <INPUT NAME="contact_name" TYPE="text" id="contact_name" value="'.$_POST['contact_name'].'" SIZE="26">
                    </td>
                    </tr>
                    <tr>
                    <td bgcolor="#E6E6E6"><strong>Password</strong></td>
                    <td bgcolor="#FFFFFF"><input name="password" type="text" id="password" value="'.$_POST['password'].'" size="26" /></td>
                    </tr>
                    <tr>
                    <td width="184" bgcolor="#E6E6E6"> <strong>Business Category</strong> </td>
                    <td width="240" bgcolor="#FFFFFF">
                    
                        <SELECT NAME="mod_business_categoriesID" id="mod_business_categoriesID">';
                        $Count=0;
                        //$r=new ReturnRecord();
                        $sq2=$this->r->rawQuery("SELECT id,CategoryTitle FROM mod_business_categories WHERE domainsID=".$domain_data["db"]['id']." OR domainsID=0 ORDER BY CategoryTitle");  
                        while ($myrow = $this->r->Fetch_Array($sq2)) {
                            $this->output='<option value="'.$myrow[0].'">'.$myrow[1].'</option>';
                        };
                    $this->output='</SELECT>
                        
                    </td>
                    </tr>
                    <tr>
                    <td height="24" bgcolor="#E6E6E6"><p><strong>You Agree To <br />
                    </strong><strong><a href="/terms-and-conditions/" target="_blank">Terms &amp; Conditions</a></strong></p></td>
                    <td bgcolor="#FFFFFF"><input name="IAgree" type="checkbox" id="IAgree" value="1"></td>
                    </tr>
                    
                    <tr>
                    <td colspan="2" align="right" bgcolor="#E6E6E6"><input type="submit" name="Submit" id="Submit" value="Submit" onClick="return CheckAgree()"></td>
                    </tr>
                </table>
                </form>

                </td>
                </table>';
            };
        };


        function Member_Login(){
            if(isset($_SESSION['membersID'])){
                if($_SESSION['membersID']){
                    $membersID=$_SESSION['membersID'];
                    $loggin=true;
                }else{
                    $loggin=false;
                }
            }else{
                $loggin=false;
            }
            
            if($loggin){
                //$text_data['debug'][]="loged in";
                print "You are now logged in!!!";

            }else{
                $this->text_data['debug'][]="show log in";
                print $this->Message;
                $this->output='
                <form name="form1" method="post" action="/login/">
                <table width="300" border="2" align="center" cellpadding="2" cellspacing="1" bgcolor="#97C8F9" id="table">
                    <tr>
                    <td bgcolor="#E6E6E6"><strong>Email:</strong></td>
                    <td bgcolor="#FFFFFF"><input type="text" name="email" id="email"></td>
                    </tr>
                    <tr>
                    <td bgcolor="#E6E6E6"><strong>Password:</strong></td>
                    <td bgcolor="#FFFFFF"><input type="password" name="password" id="password"></td>
                    </tr>
                    <tr>
                    <td colspan="2" align="right" bgcolor="#E6E6E6"><a href="/forgot-password/">Forgotten Your Details?</a>        <input type="submit" name="Submit" id="Submit" value="Submit"></td>
                    </tr>
                    <tr>
                    <td colspan="2" align="right" bgcolor="#E6E6E6"><a href="/register/">Register</a></td>
                    </tr>
                </table>
                </form>
                ';
                
            };
        }

        function Member_Admin_Login(){
            if(isset($_GET['file'])){
                $file_name=$_GET['file'];
                header("Location: http://assets.localhost/".$file_name);
            }
                
                $load_file="./Admin_Start_Include.php";
            if(file_exists($load_file)){
                    include_once($load_file);
            }else{
            }
                
            $login=false;
            
            if(isset($_GET['Message']))$Message=$_GET['Message'];
            if(isset($_GET['hash'])){
                $login=true;
                $sql="UPDATE administrators SET administratorActive=1 WHERE hash='".$_GET['hash']."'";
                $data=$r->rawQuery($sql);
                $sql="SELECT * FROM administrators where hash='".$_GET['hash']."' LIMIT 0,1";
            }

            if(isset($_POST['Submit'])){
                if($_POST['Submit']!=""){
                    $login=true;
                    //$r=new ReturnRecord();
                    $sql="SELECT * FROM administrators where username='$_POST[UserName]' and password='$_POST[Password]' AND administratorActive=1 LIMIT 0,1";
                    //print $sql;
                    
                }
            }

            if($login){
                $data=$r->rawQuery($sql);
                $dataarray=$r->Fetch_Array($data);
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
                            
                            $rslt=$r->RawQuery($sql);
                            if($rslt){
                                if($r->NumRows($rslt)>0){
                                    $data=$r->Fetch_Array($rslt);
                                    if($data[0]>0){
                                        $session_data['original_domainsID']=$data[0];
                                        $_COOKIE['original_domainsID']=$data[0];
                                    }
                                }
                            }
                            $_SESSION=$session_data;
                    
                            $loc="Location: main/logged-in/index.php";
                            header($loc);
                        }else{	//admin login bad
                            $Message="Incorrect Username or Password";
                        };
                    }else{
                        $Message="Incorrect Username or Password";
                    }

                }
                $this->output='
                 
                <form name="form1" method="post" action="">
                        

                <table  border="0" align="center" cellpadding="0" cellspacing="0">
                
                    <tr>
                    <td align="center" ><table width="100%"  border="0" cellspacing="0" cellpadding="10">
                        <tr>
                            <td><table  border="0" align="center" cellpadding="0" cellspacing="6" class="ManagementLoginBox">
                            <tr>
                                <td height="20" colspan="2" align="center" ><span class="blacktextbold">Welcome to Creative Web Logic''s Website Builder</span></td>
                                </tr>
                            <tr>
                                <td height="20" colspan="2" align="center" ><span class="RedText"><?php print $Message; ?></span></td>
                                </tr>
                                <tr>
                                <td width="130" height="20" align="right" ><span class="blacktextbold"><strong>Username:</strong> &nbsp;</span> </td>
                                <td width="212"><input name="UserName" type="text" class="loginfield" id="UserName"></td>
                                </tr>
                                <tr>
                                <td  width="130" height="20" align="right"><span class="blacktextbold"><strong>Password:</strong> &nbsp;</span> </td>
                                <td><input name="Password" type="password" class="loginfield" id="Password"></td>
                                </tr>
                                <tr>
                                <td colspan="2" align="center">
                                <input name="Login_Code" type="hidden" value="123456789"> 	
                                <input name="Submit" type="submit" class="loginbutton" value="Login"> <a href="register.php">Register</a>
                                </td>
                                </tr>
                                
                            </table>
                            
                            </td>
                        </tr>
                    </table></td>
                    <td>
                    
                    </td>
                    </tr>
                    
                    
                </table>
                
                </form>';
        }


        function Pre_Register(){
            if(!isset($_POST['name'])) $_POST['name']="";
            if(!isset($_POST['address'])) $_POST['address']="";
            if(!isset($_POST['suburb'])) $_POST['suburb']="";
            if(!isset($_POST['abn'])) $_POST['abn']="";
            if(!isset($_POST['phone'])) $_POST['phone']="";
            if(!isset($_POST['mobile'])) $_POST['mobile']="";
            if(!isset($_POST['fax'])) $_POST['fax']="";
            if(!isset($_POST['email'])) $_POST['email']="";
            if(!isset($_POST['website'])) $_POST['website']="";
            if(!isset($_POST['contact_name'])) $_POST['contact_name']="";
            if(!isset($_POST['password'])) $_POST['password']="";
            if(!isset($_POST['postcode'])) $_POST['postcode']="";
            
            
            
            if(isset($_POST['Submit'])){
                if($_POST['Submit']){
                    
                    $continue=true;
                    
                    
                    if($continue){
                        $this->log->general('Member Resister',1);
                        $subdomain=dirify(trim(str_replace(" ","-",$_POST['name'])));
                        $sql="INSERT INTO users VALUES ('NULL', '$_POST[name]', '$_POST[address]', '$_POST[suburb]', '$_POST[state]', '$_POST[postcode]', '$subdomain', '$_POST[phone]', '$_POST[mobile]', '$_POST[fax]', '$_POST[email]', '$_POST[website]', '$_POST[contact_name]', '$_POST[password]',  '1', '0', '$_POST[abn]', '', '$_POST[mod_business_categoriesID]','New')";
                        //print $sql;
                        $rslt=$this->r->RawQuery($sql);
                        if($rslt){
                            if($domain_data['AEmail']!=""){
                                $Simple="New user id is ".$this->r->Insert_Id();
                                foreach($_POST as $key=>$val){
                                    $Simple.="\n $key=$val";	
                                }
                                
                                $sql="SELECT id FROM users WHERE email='$_POST[email]'";
                                $rslt=$this->r->RawQuery($sql);
                                if($this->r->NumRows($rslt)>0){
                                    $data=$this->r->Fetch_Array($rslt);
                                    $Simple.="\n WARNING DUPLICATE Email FOUND ON USER $data[0]";
                                }
                                
                                $m=new SendMail();
                                $m->Body($Simple,$Simple);
                                $m->To(array("BCMSL Admin"=>$domain_data['AEmail']));
                                $m->From("BCMSL Bot","info@".DOMAINNAME);
                                $m->Subject("New User Has Registered on ".DOMAINNAME);
                                $m->Send();
                                $Message="Success";
                            }
                            
                            header("Location: /Registration-Complete/");
                        }else{
                            print "error ".$this->r->Error();	
                        }
                    }
                }
            }
        }

        function Pre_User_Details(){
	
            if(isset($_POST['Submit'])){
                if($_POST['Submit']){
                    
                    $membersID=$_SESSION['membersID'];
                    $m= new UpdateDatabase($log);
                    $m->Set_Log($log);
                    $m->Set_Database($r);
                    //$m->Set_Log_All();
                    $m->AddPosts($_POST,$_FILES);
                    $m->AddSkip(array("id"));
                    $m->AddTable("users");
                    $m->AddID($_SESSION['membersID']);
                    $m->DoStuff();
                    
                }
            }
        };

        function Pre_Postal_Address(){
            if(isset($_POST['Submit'])){
                if($_POST['Submit']){
                    
                    $membersID=$_SESSION['membersID'];
                    $m= new AddToDatabase($log);
                    $m->ChangeInsertType("REPLACE");
                    $m->Set_Log($log);
                    $m->Set_Database($r);
                    //$m->Set_Log_All();
                    $m->AddPosts($_POST,$_FILES);
                    $m->AddSkip(array("id"));
                    $m->AddTable("user_details");
                    $m->AddID($_SESSION['membersID']);
                    $m->DoStuff();
                    
                }
            }
        };


        function Postal_Address(){
            //echo "\n\n ===================================================================================== 777 \n\n";
            $membersID=$_SESSION['membersID'];
            //$r=new ReturnRecord();
            $r->AddTable("user_details");
        
            $r->AddSearchVar($_SESSION['membersID']);
            $Memb=$r->GetRecord();
            
            $user_details_array=array('name','address','suburb','state','postcode','abn','phone','mobile','fax','email','website','contact_name','password','business_description');
            foreach($user_details_array as $key=>$val){
                if(!isset($Memb[$val])){
                    $Memb[$val]="";
                } 
            }
            $this->output='
            
            <form method="post" action="'.$content_data["proxy_uri"].'">
            <div align="center">
                <center>
                <?php print $Message;?>
                <table width="391" border="0" alig-="left" cellpadding="3" cellspacing="1" bgcolor="#97C8F9">
                    <tr>
                    <td width="179" align="right" bgcolor="#E6E6E6"><font size="2">Name :</font></td>
                    <td width="197" bgcolor="#FFFFFF"><input name="name" type="text" id="name" value="'.$Memb['name'].'" /></td>
                    </tr>
                    <tr>
                    <td width="179" align="right" bgcolor="#E6E6E6"><font size="2">Address :</font></td>
                    <td width="197" bgcolor="#FFFFFF"><input name="address" type="text" id="address" value="'.$Memb['address'].'" /></td>
                    </tr>
                    <tr>
                    <td width="179" align="right" bgcolor="#E6E6E6"><font size="2">Suburb :</font></td>
                    <td width="197" bgcolor="#FFFFFF"><input name="suburb" type="text" id="suburb" value="'.$Memb['suburb'].'" /></td>
                    </tr>
                    <tr>
                    <td width="179" align="right" bgcolor="#E6E6E6"><font size="2">State :</font></td>
                    <td width="197" bgcolor="#FFFFFF"><input name="state" type="text" id="state" value="'.$Memb['state'].'" /></td>
                    </tr>
                    <tr>
                    <td width="179" align="right" bgcolor="#E6E6E6"><font size="2">Postcode :</font></td>
                    <td width="197" bgcolor="#FFFFFF"><input name="postcode" type="text" id="postcode" value="'.$Memb['postcode'].'" /></td>
                    </tr>
                    <tr bgcolor="#C6C6CD" >
                                <td width="35%" align="left"><strong>*Country</strong></td>
                                <td align="left"><select name="countryID" onChange="setDelivery(this.value)">
                                ';
                                $sql=$this->r->rawQuery("SELECT id,Country_Name FROM countries");
                                while($myrow=$this->r->Fetch_Array($sql)){
                                    if($Memb['countryID']==$myrow[0]){
                                        $this->output.='<option value="'.$myrow[0].'" selected>'.$myrow[1].'</option>';
                                    }else{
                                        $this->output.='"<option value="'.$myrow[0].'">'.$myrow[1].'</option>';
                                    };
                                }
                                $this->output.='</select></td>
                    
                    <tr>
                    <td width="179" align="right" bgcolor="#E6E6E6"><font size="2">Phone Number :</font></td>
                    <td width="197" bgcolor="#FFFFFF"><input name="phone" type="text" id="phone" value="'.$Memb['phone'].'" /></td>
                    </tr>
                    <tr>
                    <td width="179" align="right" bgcolor="#E6E6E6"><font size="2">Mobile Number :</font></td>
                    <td width="197" bgcolor="#FFFFFF"><input name="mobile" type="text" id="mobile" value="'.$Memb['mobile'].'" /></td>
                    </tr>
                    <tr>
                    <td width="179" align="right" bgcolor="#E6E6E6"><font size="2">Fax Number :</font></td>
                    <td width="197" bgcolor="#FFFFFF"><input name="fax" type="text" id="fax" value="'.$Memb['fax'].'" /></td>
                    </tr>
                </table>
                </center>
            </div>
            <div align="center">
                <center>
                <p>
                    <input type="submit" value="Update Details"
            name="Submit" id="Submit" />
                </p>
                </center>
            </div>
            </form>';
        };
   


        function User_Details(){
            //echo "\n\n ===================================================================================== 777 \n\n";
            $membersID=$_SESSION['membersID'];
            //$r=new ReturnRecord();
            $this->r->AddTable("users");
        
            $this->r->AddSearchVar($_SESSION['membersID']);
            $Memb=$this->r->GetRecord();
            
            $user_details_array=array('name','address','suburb','state','postcode','abn','phone','mobile','fax','email','website','contact_name','password','business_description');
            foreach($user_details_array as $key=>$val){
                if(!isset($Memb[$val])){
                    $Memb[$val]="";
                } 
            }

            $this->output='            
            <form method="post" action="'.$content_data["proxy_uri"].'">
            <div align="center">
                <center>
                '.$Message.'
                <table width="391" border="0" alig-="left" cellpadding="3" cellspacing="1" bgcolor="#97C8F9">
                    <tr>
                    <td width="179" align="right" bgcolor="#E6E6E6"><font size="2">Name :</font></td>
                    <td width="197" bgcolor="#FFFFFF"><input name="name" type="text" id="name" value="'.$Memb['name'].'" /></td>
                    </tr>
                    <tr>
                    <td width="179" align="right" bgcolor="#E6E6E6"><font size="2">Address :</font></td>
                    <td width="197" bgcolor="#FFFFFF"><input name="address" type="text" id="address" value="'.$Memb['address'].'" /></td>
                    </tr>
                    <tr>
                    <td width="179" align="right" bgcolor="#E6E6E6"><font size="2">Suburb :</font></td>
                    <td width="197" bgcolor="#FFFFFF"><input name="suburb" type="text" id="suburb" value="'.$Memb['suburb'].'" /></td>
                    </tr>
                    <tr>
                    <td width="179" align="right" bgcolor="#E6E6E6"><font size="2">State :</font></td>
                    <td width="197" bgcolor="#FFFFFF"><input name="state" type="text" id="state" value="'.$Memb['state'].'" /></td>
                    </tr>
                    <tr>
                    <td width="179" align="right" bgcolor="#E6E6E6"><font size="2">Postcode :</font></td>
                    <td width="197" bgcolor="#FFFFFF"><input name="postcode" type="text" id="postcode" value="'.$Memb['postcode'].'" /></td>
                    </tr>
                    <tr bgcolor="#C6C6CD" >
                                <td width="35%" align="left"><strong>*Country</strong></td>
                                <td align="left"><select name="countryID" onChange="setDelivery(this.value)">
                                ';
                                $sql=$this->r->rawQuery("SELECT id,Country_Name FROM countries");
                                while($myrow=$this->r->Fetch_Array($sql)){
                                    if($Memb['countryID']==$myrow[0]){
                                        $this->output.='<option value="'.$myrow[0].'" selected>'.$myrow[1].'</option>';
                                    }else{
                                        $this->output.='<option value="'.$myrow[0].'">'.$myrow[1].'</option>';
                                    };
                                }
                                $this->output.='
                                </select></td>
                    <tr>
                    <td width="179" align="right" bgcolor="#E6E6E6"><font size="2">ABN:</font></td>
                    <td width="197" bgcolor="#FFFFFF"><input name="abn" type="text" id="abn" value="'.$Memb['abn'].'" /></td>
                    </tr>
                    <tr>
                    <td width="179" align="right" bgcolor="#E6E6E6"><font size="2">Phone Number :</font></td>
                    <td width="197" bgcolor="#FFFFFF"><input name="phone" type="text" id="phone" value="'.$Memb['phone'].'" /></td>
                    </tr>
                    <tr>
                    <td width="179" align="right" bgcolor="#E6E6E6"><font size="2">Mobile Number :</font></td>
                    <td width="197" bgcolor="#FFFFFF"><input name="mobile" type="text" id="mobile" value="'.$Memb['mobile'].'" /></td>
                    </tr>
                    <tr>
                    <td width="179" align="right" bgcolor="#E6E6E6"><font size="2">Fax Number :</font></td>
                    <td width="197" bgcolor="#FFFFFF"><input name="fax" type="text" id="fax" value="'.$Memb['fax'].'" /></td>
                    </tr>
                    <tr>
                    <td width="179" align="right" bgcolor="#E6E6E6"><font size="2">Email Address :</font></td>
                    <td width="197" bgcolor="#FFFFFF"><input name="email" type="text" id="email" value="'.$Memb['email'].'" /></td>
                    </tr>
                    <tr>
                    <td width="179" height="24" align="right" bgcolor="#E6E6E6"><font size="2">Web Site :</font></td>
                    <td width="197" bgcolor="#FFFFFF">
                        http://            
                        <input name="website" type="text" id="website" value="'.$Memb['website'].'" /></td>
                    </tr>
                    <tr>
                    <td width="179" align="right" bgcolor="#E6E6E6"><font size="2">Contact Name :</font></td>
                    <td width="197" bgcolor="#FFFFFF"><input name="contact_name" type="text" id="contact_name" value="'.$Memb['contact_name'].'" /></td>
                    </tr>
                    <tr>
                    <td align="right" bgcolor="#E6E6E6">Password :</td>
                    <td bgcolor="#FFFFFF"><input name="password" type="text" id="password" value="'.$Memb['password'].'" /></td>
                    </tr>
                    <tr>
                    <td colspan="2" align="center" bgcolor="#E6E6E6">Directory Description</td>
                    </tr>
                    <tr>
                    <td colspan="2" align="center" bgcolor="#E6E6E6"><textarea name="business_description" cols="50" rows="5" id="business_description">'.$Memb['business_description'].'</textarea></td>
                    </tr>
                </table>
                </center>
            </div>
            <div align="center">
                <center>
                <p>
                    <input type="submit" value="Update Details"
            name="Submit" id="Submit" />
                </p>
                </center>
            </div>
            </form>
                    </td>';
            };


            function Change_Password(){
                $this->output.='
                <p align="center">&nbsp;</p>
                <p><font size="2">To <strong><span class="Morone">update your password</span></strong> simply type in your current password, and then type in your new password twice. This will
                ensure that you did not mis-type your new password.</font></p>
                <form action="'.$_SERVER['REQUEST_URI'].'"  method="post" name="form2" id="form2" >
                <span class="RedText"><?php print $Message; ?></span><br />
                <br />
                <table width="70%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#97C8F9" id="table">
                    <tr>
                    <td width="295" bgcolor="#FFFFFF"><strong> Current Password</strong></td>
                    <td width="241" bgcolor="#FFFFFF"><input name="cpassword" type="password" class="formfield1" id="cpassword" /></td>
                    </tr>
                    <tr>
                    <td bgcolor="#FFFFFF"><strong>New Password</strong></td>
                    <td bgcolor="#FFFFFF"><input name="password" type="password" class="formfield1" id="password" /></td>
                    </tr>
                    <tr>
                    <td bgcolor="#FFFFFF"><strong>Retype New Password</strong></td>
                    <td bgcolor="#FFFFFF"><input name="password2" type="password" class="formfield1" id="password2" /></td>
                    </tr>
                    <tr>
                    <td colspan="2" align="right" bgcolor="#E6E6E6"><input name="Submit" type="submit" class="formbuttons" value="Save" onclick="return confirmSubmit()" /></td>
                    </tr>
                </table>
                </form>
                <p><font size="2">Note:&nbsp; If you would like <strong><span class="Morone">to update
                your User Details</span></strong> you will need to send an email to <a href="mailto:admin@noodnet.com">admin@noodnet.com</a> with your new details and a Nood
                Network Administrator will update your details.</font></p>';
            };


            function Pre_Forgot_Password(){
                if(isset($_POST['Submit'])&&isset($_POST['email'])){
                    $rslt=$this->r->RawQuery('SELECT password,name,email,id FROM users WHERE email="'.$_POST[email].'"');
                    if($rslt){
                        $Simple="Dear Member here are the details you requested.\n";
                        $this->m=new SendMail();
                        while($data=$r->Fetch_Array($rslt)){
                            $m->To(array($data[1]=>$data[2]));
                            $Simple.="Your name is: $data[1] \n Your email is $data[2].\n Your password is $data[0] \n";
                            $Simple.="------------------------------------------------------------------------------\n";
                        }
                        
                        $this->m->Body($Simple,$Simple);
                        
                        $this->m->From(DOMAINNAME." Admin","admin@".DOMAINNAME);
                        $this->m->Subject("Your password reminder");
                        $this->m->Send();
                        $Message="Password sent..";
                    }else{
                        $Message="Email not found";
                    }
                }
            };

            function Forgot_Password(){
                $this->output.="<form name='form1' method='post' action='".$_SERVER['REQUEST_URI']."'>".$Message."
                    <table width='334' border='0' align='center' cellpadding='2' cellspacing='1' bgcolor='#97C8F9' id='table'>
                        <tr>
                        <td colspan='2' align='center' bgcolor='#FFFFFF'><strong>Please enter your email and click Submit and we will email you your password:-</strong></td>
                        </tr>
                        <tr>
                        <td width='131' bgcolor='#E7E7E7'><strong>Email:</strong></td>
                        <td width='192' bgcolor='#FFFFFF'><input type='text' name='email' id='email'></td>
                        </tr>
                        <tr>
                        <td colspan='2' align='right' bgcolor='#E7E7E7'><input type='submit' name='Submit' id='Submit' value='Submit'></td>
                        </tr>
                    </table>
                    </form>
                ";
            };


            function Change_Password(){
                $this->output='<p align="center">&nbsp;</p>
                    <p><font size="2">To <strong><span class="Morone">update your password</span></strong> simply type in your current password, and then type in your new password twice. This will
                    ensure that you did not mis-type your new password.</font></p>
                    <form action="'.$_SERVER['REQUEST_URI'].'"  method="post" name="form2" id="form2" >
                    <span class="RedText"><?php print $Message; ?></span><br />
                    <br />
                    <table width="70%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#97C8F9" id="table">
                        <tr>
                        <td width="295" bgcolor="#FFFFFF"><strong> Current Password</strong></td>
                        <td width="241" bgcolor="#FFFFFF"><input name="cpassword" type="password" class="formfield1" id="cpassword" /></td>
                        </tr>
                        <tr>
                        <td bgcolor="#FFFFFF"><strong>New Password</strong></td>
                        <td bgcolor="#FFFFFF"><input name="password" type="password" class="formfield1" id="password" /></td>
                        </tr>
                        <tr>
                        <td bgcolor="#FFFFFF"><strong>Retype New Password</strong></td>
                        <td bgcolor="#FFFFFF"><input name="password2" type="password" class="formfield1" id="password2" /></td>
                        </tr>
                        <tr>
                        <td colspan="2" align="right" bgcolor="#E6E6E6"><input name="Submit" type="submit" class="formbuttons" value="Save" onclick="return confirmSubmit()" /></td>
                        </tr>
                    </table>
                    </form>
                    <p><font size="2">Note:&nbsp; If you would like <strong><span class="Morone">to update
                    your User Details</span></strong> you will need to send an email to <a href="mailto:admin@noodnet.com">admin@noodnet.com</a> with your new details and a Nood
                    Network Administrator will update your details.</font></p>';

            };



        function HTML_Output(){
            print $this->output;
        }

        function Member_Add(){
            $this->output='
            <table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td valign="top"><form action="index.php"  method="post" name="form2" >
                <span class="pageheading">Add New Member </span><span class="RedText"><?php print $Message; ?></span><br>
                <br>
                Complete the member details below.<br>
                <br>
                <span class="RedText"><strong>*</strong></span><strong> Mandatory Fields </strong>
                <table width="100%" border="0" cellpadding="3" cellspacing="1" id="table">
                <tr>
                    <td class="tabletitle"><strong>Sub Domain</strong></td>
                    <td class="tablewhite"><input name="subdomain" type="text" id="subdomain" size="45"></td>
                </tr>
                <tr>
                    <td width="163" class="tabletitle"><strong> Business Name<span class="RedText">*</span></strong></td>
                    <td width="352" class="tablewhite"><input name="name" type="text" id="name" size="45"></td>
                </tr>
                <tr>
                    <td class="tabletitle"><strong>Contact Name</strong></td>
                    <td class="tablewhite"><input name="contact_name" type="text" id="contact_name" size="45"></td>
                </tr>
                <tr>
                    <td class="tabletitle"><strong> Email<span class="RedText">*</span></strong></td>
                    <td class="tablewhite"><input name="email" type="text" id="email" size="45"></td>
                </tr>
                <tr>
                    <td class="tabletitle"><strong>Address</strong></td>
                    <td class="tablewhite"><input name="address" type="text" id="address" size="45"></td>
                </tr>
                <tr>
                    <td class="tabletitle"><strong>Suburb</strong></td>
                    <td class="tablewhite"><input name="suburb" type="text" id="suburb" size="45"></td>
                </tr>
                <tr>
                    <td class="tabletitle"><strong>State</strong></td>
                    <td class="tablewhite"><input name="state" type="text" id="state" size="45"></td>
                </tr>
                <tr>
                    <td class="tabletitle"><strong>Postcode</strong></td>
                    <td class="tablewhite"><input name="postcode" type="text" id="postcode" size="45"></td>
                </tr>
                <tr>
                    <td class="tabletitle"><strong>Phone</strong></td>
                    <td class="tablewhite"><input name="phone" type="text" id="phone" size="45"></td>
                </tr>
                <tr>
                    <td class="tabletitle"><strong>Mobile</strong></td>
                    <td class="tablewhite"><input name="mobile" type="text" id="mobile" size="45"></td>
                </tr>
                <tr>
                    <td class="tabletitle"><strong>Fax</strong></td>
                    <td class="tablewhite"><input name="fax" type="text" id="fax" size="45"></td>
                </tr>
                <tr>
                    <td class="tabletitle"><strong>Website</strong></td>
                    <td class="tablewhite"><input name="website" type="text" id="website" size="45"></td>
                </tr>
                <tr>
                    <td class="tabletitle"><strong>Password</strong></td>
                    <td class="tablewhite"><input name="password" type="text" id="password" size="45"></td>
                </tr>
                <tr>
                    <td class="tabletitle"><strong>Status</strong></td>
                    <td class="tablewhite">
                    <select name="status" id="accesslvl">
                    <option value="New" ';
                    
                    if($Insert['accesslvl']=="New"){
                    $this->output.='"selected"';
                    }
                    
                    $this->output.='>New Member</option>
                    <option value="Rejected"';
                    if($Insert['accesslvl']=="Rejected"){
                    $this->output.='"selected"';
                    }
                    $this->output.='>Rejected</option>
                    <option value="Approved" ';
                    if($Insert['accesslvl']=="Approved"){
                    $this->output.='"selected"';
                    }
                    $this->output.='>Approved</option>
                </select>
                </td>
                </tr>
                <tr>
                    <td class="tabletitle"><strong>Abn</strong></td>
                    <td class="tablewhite"><input name="abn" type="text" id="abn" size="45"></td>
                </tr>
                <tr>
                    <td class="tabletitle"><strong>Business Category</strong></td>
                    <td class="tablewhite"><SELECT NAME="mod_business_categoriesID" id="mod_business_categoriesID">';

                    $Count=0;
                    $sql="SELECT id,CategoryTitle FROM mod_business_categories WHERE ((domainsID=".$session_data['domainsID'].") OR  (domainsID=0)) ORDER BY CategoryTitle";
                
                            $sq2=$this->r->rawQuery($sql);  
                            while ($myrow = $this->r->Fetch_Array($sq2)) {
                                $this->output.='<option value="'.$myrow[0].'"';
                                if($Insert['mod_business_categoriesID']==$myrow[0]){
                                    $this->output.='"selected"';
                                } 
                                $this->output.='">$myrow[1]</option>"';
                            };
                            $this->output.='
                                </SELECT></td>
                            </tr>
                            <tr>
                                <td class="tabletitle"><strong>Directory Description</strong></td>
                                <td class="tablewhite"><textarea name="business_description" cols="45" rows="4" id="business_description">'.$Insert['business_description'].'</textarea></td>
                            </tr>
                            </table>
                            <p><br>
                            <input name="Submit" type="submit"  class="formbuttons" id="Submit" value="Save" onClick="return confirmSubmit()">
                            </p>
                            </form></td>
                        </tr>
                    </table>';
                        }
                    
    