<?php
    //class clsSession extends clsMySessionHandler
    class clsSession
    {   
        private $ip_address="";
        private $ip_address_type="ip4";
        private $session_data=array();

        private $use_db=true;

        private $use_cookie=true;
        private $id;
        private $r;
        private $log;
        private $guid;
        private $new_guid;
        private $server_variables=array();

        private $session_vars_data=array();
        private $data;

        private $serialized_data;

        private $unserialized_data;
        private $save_path;
    
        public function __construct()
        {
            //$this->set_ip_address($ip_address);
            //print "xx";
            $_SESSION['new_membersID']=666;
            //print("todo 00001");
        }
    
        public function read($id)
        {
            $this->id=$id;
            if($this->use_db){
                $options = [];
                
                $data =$this->database_read($id);
                $this->set_data($data,"serialize");
                //$this->data=unserialize((string) $data,$options);
            }else{
                /*
                $data = parent::read($this->id);
                $this->data=$data;
                if (!$this->data) {
                    $this->data="";
                }
                */
            }
            return $this->data;
            
        }

        public function session_set_globals()
        {
            $map_array=array(0=>"SESSION",1=>"SERVER",2>"GET",3=>"POST",4=>"FILES",5=>"RERQUEST",6=>"ENV",7=>"COOKIE");
            $input_array=array($_SESSION,$_SERVER,$_GET,$_POST,$_FILES,$_REQUEST,$_ENV,$_COOKIE);
                        //$input_array=$GLOBALS;
            //$input_array=array($_SESSION,$_SERVER);
            foreach ($input_array as $name => $value) {
                //$this->log->general("Map ",9,array($name,$value));
                
                foreach ($value as $var_name => $var_value) {
                    
                    //$this->log->general("Map ",9,array($map_array,$name));
                    $this->session_vars_data[$map_array[$name]][$var_name]=$var_value;
                }
            
            }
            $this->set_data($this->session_vars_data,$serialize="serialize");
            $this->server_variables['HTTP_USER_AGENT']=$_SERVER['HTTP_USER_AGENT'];
            $this->log->general("All Globals ",9,array($_SERVER,$this->session_vars_data,$input_array));
        }
        

        public function session_set_variable($variable)
        {
            
            $vars = get_defined_vars (); // returns array ("x" => 10, "y" => 20, "vars" => array (...))
            foreach ($vars as $name => $value) {
                //echo "$name = $value\n";
                $this->session_vars_data[$name]=$value;
            }
        }

        public function session_get_variable($variable_name)
        {
            $value="";
            if(isset($this->session_vars_data[$variable_name])){
                $value=$this->session_vars_data[$variable_name];
            }
            
            return $value;
        }

        public function Set_Log($log){
            $this->log=clsClassFactory::$all_vars['log'];
            //print_r($this->log);
            //$this->log->general('Boot Success: ',9,array());
                    
        }

        public function set_database($r=null)
        {
            $this->r=&clsClassFactory::$all_vars['r'];
            
        }

        public function set_session()
        {
            $this->sess=&clsClassFactory::$all_vars['sess'];
            
        }

        public function set_assorted()
        {
            $this->a=&clsClassFactory::$all_vars['sess'];
            
        }

        public function requested_classes()
        {
            $class_names=array("clsLog","clsDatabaseInterface","clsSession","clsAssortedFunctions");
            
        }

        public function Get_Current_Time(){
            date_default_timezone_set('Australia/Sydney');
            $today = date("Y-m-d H:i:s"); 
            return $today;   
        }

        

        public function set_ip_address($ip_address)
        {   
            
            $pos = stripos($ip_address, ':');
            
            if($pos>-1){
                $this->ip_address_type="ip6";
            }else{
                $this->ip_address_type="ip4";
            }
            
            //if($this->ip_address==""){
                $this->ip_address=$ip_address;
            //}
            

            $this->log->general('Set IP: ',9,array($pos,$this->ip_address_type,$this->ip_address,$ip_address));
            //echo $ipv4; // prints 127.0.0.1
            
        }

        public function set_data($data,$serialize="none")
        {
            (string) $new_data="";
            $created_data="";
            if($serialize!="none"){
                if($serialize=="unserialize"){
                    $options = [];
                    $data=(string) $data;
                    
                    $new_data=base64_decode($data);
                    $new_data=unserialize($new_data,$options);
                    $this->unserialized_data=$new_data;
                    $this->session_set_variable($this->unserialized_data);
                    $tag="Base 64 Decode";
                    $created_data=$this->unserialized_data;
                }else{//if($serialize=="serialize"){
                    $data=serialize($data);
                    if(strlen($data)>strlen($this->serialized_data)){
                        $new_data=base64_encode($data);
                        $this->serialized_data=$new_data;
                        $tag="Use New Data";
                        $created_data=$this->serialized_data;
                    }else{
                        $tag="Use Old Bigger Data";
                        $created_data=$this->serialized_data;
                        $this->database_update();
                    }
                    
                }
            }else{
                $this->data=$data;
                $tag="none";
                $created_data=$this->data;
            }
            $this->log->general($tag,9,array("output"=>$created_data));
        }

        public function get_data($serialize="none")
        {
            if($serialize!="none"){
                if($serialize=="unserialize"){
                    $data=$this->unserialized_data;
                }elseif($serialize=="serialize"){
                    $data=$this->serialized_data;
                }
            }else{
                $data=$this->data;
            }
            return $data;
        }

        

        public function set_id($id)
        {
            $this->id=$id;
        }

        public function get_id()
        {
            if($this->id==""){
                $this->set_id($this->get_guid());
            }else{

            }
            return $this->id;
        }

        

        public function set_new_guid($guid)
        {
            if($this->new_guid==""){
                $this->new_guid=$guid;
            }
            
        }

        public function set_guid_details($guid)
        {
            if($this->guid==""){
                $this->guid=$guid;
            }
            $this->set_id($this->guid);
        }

        public function get_guid()
        {
            $return_val="";
            if($this->guid!=""){
                $return_val=$this->guid;
            }else{
                $this->guid=$this->new_guid;
                $return_val=$this->guid;
            }
            return $return_val;
        }

        public function session_start()
        {
            if(isset($_COOKIE["Session"])){
                $this->use_cookie=true;
                $session_cookie=$_COOKIE["Session"];
                $this->set_id($session_cookie);
                $this->set_guid_details($_COOKIE["Session"]);
                $this->database_read_id();
                $this->database_update();
                $tag="Use Cookie 1";
            }else{
                $id=$this->get_id();
                setcookie("Session", $id, time()+3600);
                if(isset($_COOKIE["Session"])){
                    $session_cookie=$_COOKIE["Session"];
                    $this->use_cookie=true;
                    $this->set_id($_COOKIE["Session"]);
                    $this->set_guid_details($_COOKIE["Session"]);
                    $this->database_read_id();
                    $this->database_update();
                    $tag="Use Cookie 2";
                }else{
                    $session_cookie="";

                    $this->use_cookie=false;
                    $this->database_read_ip();
                    $this->database_update();
                    $tag="No Cookie 2";

                }
            }
            //print"ggg".$_COOKIE["Session"].'  -'.$this->id.'  -'.$this->guid.'- \n\n';
            //print_r($this->id);
            $this->log->general("session_start->",5,array($tag,$this->use_cookie,$session_cookie));
        }
    
        public function write($id, $data)
        {
            $this->id=$id;
            $this->data=$data;   
            if($this->use_db){ 
                $this->database_write();
            }else{
                //$data=parent::write($this->id, $this->data);
            }
            return $data;
        }

        public function database_write()
        {
            //$this->id=$id;
            //$this->data=serialize($data);
            //$this->unserialize_data=$this->set_data($data,"serialize");  
            $serialized_data=$this->r->Escape($this->serialized_data);
            $current_time=$this->Get_Current_Time();
            $browser_type=base64_encode($this->server_variables['HTTP_USER_AGENT']);
            //if($this->use_cookie){
                $sql='INSERT INTO mod_session (session_name,session_data,ip_address,session_time,browser_type) VALUES ("'.$this->id.'","'.$serialized_data.'","'.$this->ip_address.'","'.$current_time.'","'.$browser_type.'")';
            //}else{
            //    $sql='INSERT INTO mod_session (session_name,session_data,ip_address) VALUES ("'.$this->id.'","'.$serialized_data.'","'.$this->ip_address.'")';
           //}
            
            $rslt=$this->r->RawQuery($sql);
            if(!$rslt){
                $error=$this->r->Error();
                $this->log->general("Database Inserting Error ",9,array($sql,$error,$this->serialized_data));
            }
            
            $this->log->general("Database Inserting ",9,array($sql,time()));
            //return $this->data;
        }

        public function database_update()
        {
            //$this->id=$id;
            //$this->data=serialize($data);
            //$this->unserialize_data=$this->set_data($data,"serialize");  
            $serialized_data=$this->r->Escape($this->serialized_data);
            $current_time=$this->Get_Current_Time();
            $browser_type=base64_encode($this->server_variables['HTTP_USER_AGENT']);
            if($this->use_cookie){
                $sql='UPDATE mod_session SET session_name="'.$this->id.'",session_data="'.$serialized_data.'",ip_address="'.$this->ip_address.'",session_time="'.$current_time.'" WHERE session_name="'.$this->id.'" AND browser_type="'.$browser_type.'"';
            }else{
                $sql='UPDATE mod_session SET session_name="'.$this->id.'",session_data="'.$serialized_data.'",ip_address="'.$this->ip_address.'",session_time="'.$current_time.'" WHERE ip_address="'.$this->ip_address.'" AND browser_type="'.$browser_type.'"';
            }
            
            $rslt=$this->r->RawQuery($sql);
            if(!$rslt){
                $error=$this->r->Error();
                $this->log->general("DB Update Error ",9,array($sql,$error,$this->serialized_data));
            }
            
            $this->log->general("Database Update ",9,array($sql,time()));
            
            //return $this->data;
        }

        public function database_read_ip()
        {
            try{   
                $return_array=array();
                $browser_type=base64_encode($this->server_variables['HTTP_USER_AGENT']);
                $sql='SELECT session_name,session_data FROM mod_session WHERE ip_address="'.$this->ip_address.'" AND browser_type="'.$browser_type.'"';
                $return_array=$this->database_read($sql);
                if(count($return_array)>0){
                    $session_name=$return_array['session_name'];
                    if(strlen($session_name)>0){
                        $this->set_guid_details($return_array['session_name']);
                    }
                    $return_array=$return_array['session_data'];
                    //$this->data=unserialize((string) $return_array,$options);
                    $this->set_data($return_array,"unserialize");
                    $this->session_set_variable($this->data);
                }else{
                    $this->database_write();
                    $this->data=array();
                }
                $this->log->general("DB Read IP ",9,array($sql,$return_array));
                return $this->data;
            }catch(Exception $e){
                //print"ddd=>".$sql.'  -'.$this->data.'  -'.$this->guid.'- \n\n';
                //print_r($e);
                return array();
            }
        }

        public function database_read_id()
        {
            try{
                //$this->log->general("App Data Array ",9,$this->data);
                $return_array=array();
                $browser_type=base64_encode($this->server_variables['HTTP_USER_AGENT']);
                $sql='SELECT session_data,ip_address FROM mod_session WHERE session_name="'.$this->id.'" AND browser_type="'.$browser_type.'"';
                $return_array=$this->database_read($sql);
                if(count($return_array)>0){
                    
                    $session_data=$return_array["session_data"];
                    $ip_address=$return_array["ip_address"];
                    $this->set_ip_address($ip_address);
                    //$this->data=$this->set_data($return_array,"unserialize");
                    $this->set_data($session_data,"unserialize");
                    //$this->data=unserialize((string) $return_array,$options);
                    //$this->session_set_variable($this->data);
                }else{
                    $this->database_write();
                    //$this->log->general("DB Empty ",9,array($sql,$this->guid,$return_array));
                    $this->data=array();
                }
		        $this->log->general("DB Read ID ",9,array($sql,$return_array));
                return $this->data;
            }catch(Exception $e){
                //print"ddd=>".$sql.'  -'.$this->data.'  -'.$this->guid.'- \n\n';
                //print_r($e);
                return array();
            }
        }

        public function database_read($sql)
        {
            $this->log->general("DB Read ",9,array($sql,$this->session_data));
            try{
                $rslt=$this->r->RawQuery($sql);
                $num_rows=$this->r->NumRows($rslt);
                if($num_rows>0){
                    $this->session_data=$this->r->Fetch_Assoc($rslt);
                }else{
                    $this->session_data=array();
                }
                $error=$this->r->Error();
                $this->log->general("Database Error ",9,$error);
                return $this->session_data;
            }catch(Exception $e){
                //print"ddd=>".$sql.'  -'.$this->data.'  -'.$this->guid.'- \n\n';
                //print_r($e);
                return array();
            }
            
            
            
        }

        

        public function session_save_path($save_path)
        {
            $this->save_path=$save_path;
            session_save_path($this->save_path);
        }
    }

?>