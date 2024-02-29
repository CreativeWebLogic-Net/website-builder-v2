<?php
    

    class clsContent{
        private $r;
        private $log;
        private $output;

        function __construct(){
			
			
		}
        function Set_DataBase($r){
			$this->r=$r;
			
		}

        function Set_Log($log){
			$this->log=$log;
			
		}
        
        public function Pre_Contact(){

        
            if($_POST['Submit']){
                if ($_SESSION['code'] == $_POST['code'] && !empty($_SESSION['code'])) 
                {
                    unset($_SESSION['code']);
                    $continue=true;
                }
                else
                {
                    $message = "Security Code Wrong.";
                    $continue=false;
                }
                
                
                
                if($continue){
                    $sql="SELECT SiteTitle,AEmail FROM domains WHERE id=".$domain_data['id'];
                    $rslt=$r->RawQuery($sql);
                    $domdata=mysql_fetch_array($rslt);
                    $From['name']=$domdata[0]." Message Bot";
                    $From['email']=$domdata[1];
                    $Subject=$domdata[0]." Contact Form Message";
                    $To=$From;
                    if(is_numeric($_GET['mid'])){
                        $sql="SELECT name,email FROM users WHERE id=$_GET[mid]";
                        $rslt=$r->RawQuery($sql);
                        $data=mysql_fetch_array($rslt);
                        $To['name']=$data[0];
                        $To['email']=$data[1];
                    }
                    
                    
                    $Simple="";
                    foreach($_POST as $key=>$val){
                        $key=mysql_real_escape_string($key);
                        $val=mysql_real_escape_string($val);
                        $Simple.="\n $key=$val";	
                    }
                    
                    
                    
                    $m=new SendMail();
                    $m->Body($Simple,$Simple);
                    $m->To(array($To['name']=>$To['email']));
                    //$m->To(array("dan"=>"dan@iwebbiz.com.au"));
                    $m->From($From['name'],$From['email']);
                    $m->Subject($Subject);
                    $m->Send();
                    $message="Message Sent";
                        
                    
                }
            }
        }


        public function Contact_Form(){
            if(!isset($Message)) $Message="";

            $this->output='
            <form name="form1" method="post" action="'.$_SERVER['REQUEST_URI'].'" class="contact-form">
            <?php print $Message;?>
            <table width="90%" border="0" align="center" cellpadding="2" cellspacing="1" id="contact-table">
                <tr align="center" bgcolor="#FFFFFF">
                <td colspan="2">';
                
                    if(isset($_GET['mid'])){
                        if(is_numeric($_GET['mid'])){
                            $sql="SELECT name FROM users WHERE id=$_GET[mid]";
                            $rslt=$this->r->RawQuery($sql);
                            $data=$this->r->Fetch_Array($rslt);
                            $this->output.="Contacting Member: ".$data[0];
                        }else{
                            $this->output.="Contacting Admin";
                        }
                    }
                    
                    $this->output.='
                </td>
                </tr>
                <tr align="center" >
                <td colspan="2"><strong>Please enter your details</strong> <br>
            <?php print $Message;?></td>
                </tr>
                <tr>
                <td><strong>Name:</strong></td>
                <td><input name="name" type="text" id="name" size="50"></td>
                </tr>
                <tr>
                <td width="131"><strong>Email:</strong></td>
                <td width="366"><input name="email" type="text" id="email" size="50"></td>
                </tr>
                <tr>
                <td><strong>Phone:</strong></td>
                <td><input name="phone" type="text" id="phone" size="50"></td>
                </tr>
                <tr>
                <td><strong>Comments:</strong></td>
                <td><textarea name="comments" cols="50" rows="7" id="comments"></textarea></td>
                </tr>
                <tr align="center">
                <td colspan="2"><img src="/classes/captcha/captcha.class.php?length=4&font=&size=24&angel=5&file="><br />
                <strong>Please Enter Security Code:</strong>        <input type="text" name="code" size="35" id="code" /></td>
                </tr>
                <tr>
                <td colspan="2" align="right"><input type="submit" name="Submit" id="Submit" value="Submit"></td>
                </tr>
            </table>
            </form>';
        }



    }