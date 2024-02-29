<?php
	
	
	
	class clsDatabaseConnect{
		public $log=false;
		var $log_text="";
		var $links = array();
		var $connect=array();
		var $mysqli=false;
		var $Insert_Id=false;
		var $db_logins=array();
		var $dbss=array();
		var $current_dir="";
		var $default_db=array();
		var $def_db="";
		var $server_names=array();
		var $server_name="Hostgator Cloud";
		var $server_num=0;
		var $db_num=array(0=>0,1=>0,2=>0);
		var $def_db_num=array(0=>0,1=>0,2=>0);
		var $datab_name="";
		var $server_login=array();
		var $all_databases_array=array();
		var $server_tags=array();
		var $all_db_login_data=array();
		var $local_db=array();
		var $host_name="localhost";
		//var $db_name_serv=array(0=>0,1=>0,2=>0);
		var $db_name_def_num=array(0=>0,1=>0,2=>0);
		var $db_num_ser=array(0=>0,1=>0,2=>0);
		var $current_server_tag="";
		var $current_db_type="MySQL";
		var $original_server_tag="";
		var $app_data=array();
		//-----------------------------------------------------------------------------------------------------------
	
		//function ConnectDbase(){
		
		function __construct(&$log=false){
			if($log){
				//$this->log=$log;
				$this->log=clsClassFactory::$all_vars['log']->get_object();
			}
			//$this->Initialise_Current_Server();
			//$this->get_login_details();
			$this->set_data_arrays();
			$this->get_login_details();
		}
		//-----------------------------------------------------------------------------------------------------------
		
		
		public function Set_Log(&$log){
			$this->log=$log;
			$this->log->general('-Set Log Boot Success: $r->'.var_export($log,true),3);
			//$this->log->general('Set Log Boot Success: $r->',1);
				
		}

		public function Add_App_Data(&$app_data){
			
			$this->app_data=$app_data;
			//print_r($this->app_data);
			$this->get_login_details();
		}

		//-----------------------------------------------------------------------------------------------------------
		
		/*
		public function Check_zzzzdb(&$log){
			$this->log=$log;
			$this->log->general("-Set Log Boot Success: $r->".var_export($log,true),3);
			//$this->log->general('Set Log Boot Success: $r->',1);
				
		}
		*/
		//-----------------------------------------------------------------------------------------------------------
		private function Initialise_Current_Server(){
			
			$this->current_dir=pathinfo(__DIR__);
			$current_directory=$this->current_dir['dirname'];
			//$DB_Login_Data=$this->all_db_login_data;
			$this->host_name=gethostname();
			$DB=array();
			
			$DB['server_tag']="db-default.php";
			$DB['server_desc']="Private Server";
			$DB['current_dir']="/var/www/html";
			$DB['server_number']=4;
			$DB['hostname']="localhost";
			$DB['usernamedb']="Edit This";
			$DB['passworddb']="Edit This";
			$DB['dbName']="bubblelite2";
			$DB['dbNames']=array('bubblelite2','takebookings','partnerspro','smsg');
			$server_login["db-linode.php"]=array('server_tag'=>$DB['server_tag'],'server_desc'=>$DB['server_desc'],'current_dir'=>$DB['current_dir'],'server_number'=>$DB['server_number'],'hostname'=>$DB['hostname'],'usernamedb'=>$DB['usernamedb'],'passworddb'=>$DB['passworddb'],'dbName'=>$DB['dbName'],'dbNames'=>$DB['dbNames']);

						
				
				
			if(count($DB)>0){
				$this->current_server_tag=$DB['server_tag'];
				$this->current_dir=$DB['current_dir'];
				//$this->server_name=$server_name;
				$this->server_desc=$DB['server_desc'];
				$this->log_text.$this->current_dir;
				//$this->def_db_num[$server_num]=0;
				$this->server_login=$server_login;
				//$this->db_logins[$server_name]=$server_login;
			}
			//-----------------------------------------------------------------------------------------------------------	
			
		}
		
		
		

		

		public function set_data_arrays($data_arrays=array()){
			//$this->app_data=$data_arrays['app_data'];
			//$this->app_data=clsSystem::$vars->app_data;
		}

		public function get_login_details(){
			//echo"<br>\n\n\n-1102122--------------------|---".var_export($this->app_data,true)."--|----------------------------------\n\n";
			//print "<br>\n\n DBDetails--End---|-".var_export($server_login,true);
			//$DB=array();	
			//print_r($this->app_data);
			//$this->log->general("App Data Array ddd",4,$this->app_data);
			//echo"ddd";
			//$load_file=$this->app_data['CLASSESBASEDIR']."db.php";
			$load_file="bcms/classes/db.php";
			//echo"<br>\n\n\n-1102122--------------------|---\n\n";
			//print $load_file;
			//echo"<br>\n\n\n-1666122--------------------|---\n\n";
			if (file_exists($load_file)) {
				include($load_file);
				

			
				//$this->current_server_tag=$DB['server_tag'];
				//$this->current_dir=$DB['current_dir'];
				//$this->server_name=$DB['server_desc'];
				//$this->server_desc=$DB['server_desc'];
				$this->server_login=$server_login;
				$this->current_db_type=$DB['server_type'];
				//exit("<br>\n\n DBDetails--exit---|-".var_export($server_login,true));
				
			}else{
				//print "<br>\n\n get_login_details-----|-".var_export($server_login,true);
			}
			if($this->original_server_tag=="") $this->original_server_tag=$this->current_server_tag;
			/////exit("login");			//$this->server_login=get_details();
			//print_r($this->current_server_tag=$this->server_login);
			/*
			$this->current_server_tag=$this->server_login[0]['server_tag'];
			$this->server_desc=$this->server_login[0]['server_tag'];
			*/

			//print "<br>\n\n get_login_details--final---|-".var_export($server_login,true);
			//exit("\n xxx11 \n");
			//print "<br>\n\n DBDetails--End---|-".var_export($server_login,true);
		}
		
		//-----------------------------------------------------------------------------------------------------------
		public function Initialise_Remote_Server($server_login=array(),$original=false){
		//public function Initialise_Remote_Server($original=false){
			//echo"\n\n-1234------Remote_Server--------------|--".var_export($server_login,true)."--|-----------------------------------\n";
			//print_r($server_login);
			if($original){
				$this->current_server_tag=$this->original_server_tag;
			}else{
				$remote_server=array();
				$this->server_login=$server_login;
				/*foreach($server_login as $server_key){
					$remote_server[$server_key]=$server_login[$server_key];
					$this->server_login[$server_key]=$remote_server[$server_key];
					$this->current_server_tag=$server_key;
				}*/
				//print_r($this->server_login);
				$server_key=$this->server_login['server_tag'];
				$remote_server[$server_key]=$this->server_login;
				$this->server_login[$server_key]=$remote_server[$server_key];
				$this->current_server_tag=$server_key;
			}
			
			//exit("Initialise_Remote_Server");
		}
		
		public function Connect($TArr=""){
			
			//$this->test_pgsql();
			//exit("yy");
			//$this->test_mysql();
			
			try{	
				 //$db_ser_num=$this->Initialise_Current_Server();
				//$TArr=array();
				//if($TArr==""){
				//}
				//exit($db_type);
				//echo"\ -1------Connect----------------".$db_type."-------------------------------------\n";
				if(isset($this->links[$TArr]))
				{
					return $this->links[$TArr];
				}
				else
				{
					
					if($TArr==""){
						$TArr=$this->current_server_tag;
					}
				}

				//echo"\n\n\n-Connect-111----|-".$TArr."-|-------------|--".var_export($this->server_login,true)."---|----------------------------------\n\n";
			

				$db_type=$this->current_db_type;
				//echo"\n\n\n-Connect-111----|-".$db_type."-|--------------------------------------------\n\n";
			

				if($db_type=="MySQL"){
					//print_r($this->server_login);
					//$db_login=$this->server_login[$TArr];
					$db_login=$this->server_login;
					$this->log->general("DB Login ddd",4,$db_login);
					//echo"\n\n-110----------------------".var_export($db_login,true)."-------------------------------------\n\n";
					try{
						/*
						echo"\n\n-119000--------------------------------------------------------119--\n\n";
						$new_links = new mysqli($db_login['hostname'], $db_login['usernamedb'], $db_login['passworddb'],$db_login['dbName'] );

						$query='SELECT * FROM domains LIMIT 0,1';
						$result = $$new_links->query($query);
						$myrow=$result->fetch_row();
						print_r($myrow);

						echo"\n\n-1198-------|-".var_export($new_links,true)."-|------------------------------------------------1198-\n\n";
						//print "99--|--".$db_login['hostname']."--|--".$db_login['usernamedb']."--|--".$db_login['passworddb']."--|--".$db_login['dbName']."--|--\n\n";
						$this->links[$TArr]=$new_links;
						echo"\n\n-120---------------------------------------------------------120-\n\n";
						*/
						$DB=$this->server_login;
						$DB['server_type']="MySQL";
						//print_r($DB);
						$this->log->general("DB Login ",9,$DB);
						//print_r($DB);
						$links = new mysqli($DB['hostname'].":".$DB['port'], $DB['usernamedb'], $DB['passworddb'],$DB['dbName']);
						if($links->connect_error) {
							//print("Connection failed: " . $links->connect_error);
						}else{
							//print("Connected successfully: " .var_export($DB,true));
						}
						/*
						echo 'Connected successfully';
						$query='SELECT * FROM domains LIMIT 0,1';
						$result = $links->query($query);
						$myrow=$result->fetch_row();
						print_r($myrow);
						*/
						$this->links[$TArr]=$links;
					}catch(Exception $e){
						
						$this->links[$TArr]=&$this->links[$this->original_server_tag];
						$TArr=$this->original_server_tag;
						//echo"\n\n<br>-110001----------".$TArr."------------".var_export($this->links[$TArr],true)."-------------------------------------";
						exit("Connect Error-1");
						//unset($this->links[$TArr]);
					}
					
					//echo"\n\n<br>-000001----------------------".var_export($links,true)."-------------------------------------";
					

					// Check connection
					if($links->connect_error) {
						//$this->log->general("-Connection Error-".$new_links->connect_error."\n vars:=".var_export($db_login),3);
						//echo"\n\n\n-CError--------------------|--".var_export($db_login,true)."---|----------------------------------\n\n";
						//exit("Connect Error-2");
						throw new Exception("Connect Error-32");
					}else{
						$this->log->general("-Connection Success->".$TArr,1);
						$this->log->general("\n",1);
						$this->links[$TArr]=$links;
						//echo"\n\n-7778-".var_export($links,true)."\n\n";

						
					}
					
					$this->log->general("-Return Connection Success->".$TArr,1);
					return $this->links[$TArr];

				}elseif($db_type=="Sqlite"){
					$DB['server_tag']="db-sqlite3.php";
					$this->current_server_tag=$DB['server_tag'];
					$TArr=$this->current_server_tag;
					$server_login[$DB['server_tag']]=array();
						
					$db = new SQLite3('./db/bubblelite.db');
					$this->links[$TArr]=$db;
					//echo"-2----------------------".$db_type."-------------------------------------";

					return $this->links[$TArr];
				}elseif($db_type=="pgSQL"){
					//exit("--|-".$db_type."-|--\n\n");
					$db_login=$this->server_login[$TArr];
					/*
					$DB['server_tag']="db-pgSQL.php";
					$this->current_server_tag=$DB['server_tag'];
					$TArr=$this->current_server_tag;
					*/
					$login_txt = "host=".$db_login['hostname']." dbname=".$db_login['dbName'];
					$login_txt.=" user=".$db_login['usernamedb']." password=".$db_login['passworddb'];
					
					$db = pg_connect($login_txt);// die('Could not connect: ' . pg_last_error("db-errror"));
					$this->links[$TArr]=$db;
					//echo"-210----------------------".$db_type."-------------------------------------";
					
					return $this->links[$TArr];
				}
			}catch(Exception $e){
				exit("Connect Error-3".var_export($e,true));
			}
		}
		
	}