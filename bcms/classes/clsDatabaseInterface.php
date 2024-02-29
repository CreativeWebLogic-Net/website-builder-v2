<?php
class clsDatabaseInterface{
		var $SQL;
		var $Table;
		var $TargetField="id";
		var $SearchVar;
		var $NewSearchVar=array();
		public $m;
		var $vs;
		var $links=false;
		var $result;
		var $DBFile="db-local.php";
		var $default_db="bubblelite2";
		public $log="";
		var $log_text="";
		var $db_type_list=array("MySQL","Sqlite","pgSQL");
		var $current_db_type="MySQL";
		//var $current_db_type="Sqlite";
		//var $current_db_type="pgSQL";
		var $num_rows=0;
		var $Retreive_All_Variables=false;
		var $app_data=array();

		var $server_name="Hostgator Cloud";
		
		
		function __construct(&$log=false){
			if($log){
				///$this->log=$log;
				
			}
			$this->Set_Log();
			$this->CreateDB();
		}

		public function Add_App_Data(&$app_data){
			
			$this->app_data=$app_data;
		}
		
		public function Set_Log(&$log=null){
			$this->log=clsClassFactory::$all_vars['log']->get_object();

			//$this->log=$log;
			$this->log->general('M Log Success:',1);
				
		}
		
		public function Set_Vs(&$vs=false){
			$this->vs=$vs;
			//$this->log->general('Vs Success: ".var_export($this->vs,true),1);
				
		}

		
		public function Set_Links($links=false){
			if($links){
				//print "<br>\n\n Set_Links-----|-".var_export($links,true)."--|-- \n\n";
				$this->links=$links;
			}else{
				//exit("<br>\n\n Exit_Links-----|-".var_export($links,true)."--|-- \n\n");
			}
			
			//$this->log->general('Vs Success: ".var_export($this->vs,true),1);
			//print "\n\n 12345 Set_Links-----|-".var_export($links,true)."--|-12345- \n\n";
		}
		public function Get_Links(){
			//print "<br>\n\n Get_Links-----|-".var_export($this->links,true)."--|-- \n\n";
			if(!$this->links){
				return false;
			}else{
				return $this->links;
			}
			
			//$this->log->general('Vs Success: ".var_export($this->vs,true),1);
				
		}


		public function Set_Result($result=false){
			//print "<br>\n\n Set_Result-----|-".var_export($result,true)."--|-- \n\n";
			$this->result=$result;
			//$this->log->general('Vs Success: ".var_export($this->vs,true),1);
				
		}
		public function Get_Result(){
			//print "<br>\n\n Get_Result-----|-".var_export($this->result,true)."--|-- \n\n";
			if(!$this->result){
				return false;
			}else{
				return $this->result;
			}
			
			//$this->log->general('Vs Success: ".var_export($this->vs,true),1);
				
		}



		
		
		public function CreateDB(){
			
			//try{
				//echo"3-----------------------------------------------------------------------------\n\n";
				$this->log->general("CreateDB Start Success: ",1);
					
				$this->m = new clsDatabaseConnect($this->log);
				$this->m->Add_App_Data($this->app_data);
				$this->current_db_type=$this->m->current_db_type;
				//$this->m->Set_Log($this->log);
				$this->log->general("CreateDB M Success: ",1);
				
				//$this->m->Startup();
				//echo"3-------------------".$this->current_db_type."----------------------------------------------------------\n\n";
				//$this->links = $this->m->Connect($this->DBFile);
				$this->log->general("\n\n\n\nCurrent Position\n\n\n\n");
				//$this->m->test_pgsql();
				//echo"321-------------------".$this->current_db_type."----------------------------------------------------------\n\n";
				//$link = $this->m->Connect("",$this->current_db_type);
				$link = $this->m->Connect();
				//echo"\n\n DB Set Link----------".var_export($link,true)."-------|".$this->current_db_type."|------\n\n";
				
				//print_r($link);
				$this->Set_Links($link);
				//echo"\n\n DB Set Link----------".var_export($link,true)."-------------\n\n";
				$this->server_name=$this->m->server_name;
				//$result =$this->test_mysql();
				//echo"\n\n 321-------------------".var_export($result,true)."----------------------------------------------------------\n\n";
				/*
				$query = "SELECT * FROM administrators";
				$result =$this->rawQuery($query,$link);
				$row = $this->Fetch_Assoc($result);
				echo"\n\n 43210----------".var_export($result,true)."-------------".var_export($row,true)."----------------------------------------------------------\n\n";
				*/
				/*
				$res = pg_query("SELECT * FROM administrators");
				
				while ($row = $res->fetchArray()) {
					echo "{$row['id']} {$row['name']} {$row['email']} \n";
				}
				*/
				
				//echo"987654321-------------------|-".$this->current_db_type."-|----------------------------------------------------------\n\n";
				
				if($this->current_db_type=="MySQL"){
					if($this->Error()) {
						$this->log->general("Connection failed: " . $this->Error(),3);
					}else{
						$this->log->general("m->Connection Success: ".var_export($link,true),1);
					}
				}elseif($this->current_db_type=="Sqlite"){
					//echo"987654321-0-----------------------------------------------------------------------------";
				}elseif($this->current_db_type=="pgSQL"){
					//echo"987654321-1------------------------------|-".$this->current_db_type."-|-----------------------------------------------\n\n";
					
					//$this->test_pgsql();
				}
				
				//$this->m->Set_Log("clsDBCon Success: ",1);
				//echo"5-----------------------------------------------------------------------------";
			//}catch(MySQLErrorException $e){
			//	$this->log->general("MySQL Connection Error: ".var_export($e,true),3);
			//}
			return $link;
			
		}

		public function Initialise_Remote_Server($original=false){
			//echo"\n\n 54321-----------------------------------------------------------------------------\n\n";
			$this->m->Initialise_Remote_Server(array(),true);
			/*
			if($original){
				$this->current_server_tag=$this->original_server_tag;
			}else{
				$remote_server=array();
				/*foreach($server_login as $server_key){
					$remote_server[$server_key]=$server_login[$server_key];
					$this->server_login[$server_key]=$remote_server[$server_key];
					$this->current_server_tag=$server_key;
				}*/
				/*
				$server_key=$server_login['server_tag'];
				$remote_server[$server_key]=$server_login;
				$this->server_login[$server_key]=$remote_server[$server_key];
				$this->current_server_tag=$server_key;
			}
			*/
			
		}

		//-----------------------------------------------------------------------------------------------------------
		public function Set_Current_Server($Domain_Name){
			//$this->links[$TArr]
			$data=array();
			$sql="SELECT username AS usernamedb,password AS passworddb,dbname,Main_Url AS hostname";
			$sql.=" ,servers.Name AS server_desc,servers.id AS server_number,ServerName As server_tag ";
			$sql.=" FROM domains,servers,servers_databases WHERE domains.serversID=servers.id AND ";
			$sql.=" servers.id=servers_databases.serversID AND domains.Name='".$Domain_Name."' LIMIT 0,1";
			//$sql="SELECT username,password,dbname,Main_Url,ServerName,servers.Name AS server_desc,servers.id AS server_number FROM domains,servers,servers_databases WHERE domains.serversID=servers.id";
			//$sql.=" AND servers.id=servers_databases.seeversID AND dommains.Name='".$Domain_Name."' LIMIT 0,1";
			$rslt=$this->rawQuery($sql);
			$data=$this->Fetch_Assoc($rslt);
			//echo"\n\n91234--------------------------|--".var_export($data,true)."--|---------|----".$sql."----|-----------------------------91234-\n\n";
			//echo"9992-----------".$Domain_Name."-----------------".$sql."-------------------------------------------------\n\n";
			
			//print_r($data);
			//echo"999210-----------".$Domain_Name."-----------------".$sql."-------------------------------------------------\n\n";
			if(is_array($data)){
				if(count($data)>0){
					$this->DBFile=$data["server_tag"];
					$this->current_link=$this->DBFile;
					//echo"9991----------------------------".$this->DBFile."-------------------------------------------------\n\n";
					$server_found=false;
					if(isset($this->server_login[$data["server_tag"]])){
						//$server_login[$this->$DBFile]=$this->server_login[$this->$DBFile];
						//$server_login[$this->$DBFile]=$data;
						//echo"\n\n 0--Server Dupe--------".var_export($rslt,true)."-------------".var_export($data,true)."----------------------------------------------------------\n\n";
				
					}else{
						//$DB=array();$data
						$server_found=true;
						$DB=$data;
						/*
						$DB['hostname']=$data["Main_Url"];
						$DB['usernamedb']=$data["username"];
						$DB['passworddb']=$data["password"];
						$DB['dbName']=$data["dbname"];
						$DB['server_tag']=$data["ServerName"];
						$DB['server_desc']=$data["server_desc"];
						$DB['server_number']=$data["server_number"];
						$DB['current_dir']="./";
						*/
						$DB['current_dir']="./";
						$DB['dbNames']=array();
						$server_login[$DB['server_tag']]=array('server_tag'=>$DB['server_tag'],'usernamedb'=>$DB['usernamedb'],'passworddb'=>$DB['passworddb'],'server_desc'=>$DB['server_desc'],'current_dir'=>$DB['current_dir'],'server_number'=>$DB['server_number'],'hostname'=>$DB['hostname'],'dbName'=>$DB['dbname'],'dbNames'=>$DB['dbNames']);
						$this->server_login=$server_login;
						
					}
					
					//-----------------------------------------------------------------------------------------------------------
					//echo"9991----------------------------".var_export($this->server_login,true)."-------------------------------------------------\n\n";
					if($server_found){
						$this->m->Initialise_Remote_Server($server_login[$this->DBFile]);
						//$this->links[$this->DBFile] = $this->m->Connect($this->DBFile);
						$this->links = $this->m->Connect($this->DBFile);
						if($this->Error()) {
							$this->log->general("Connection failed: " . $this->links->connect_error,3);
							return array();
						}else{
							return $this->links;
							//$this->log->general("m->Connection Success: ".var_export($this->links,true),1);
						}
					}else{
						return array();
					}
					
				}
			}
			
			
		}
		
		function Reset(){
			$this->Table="";
			$this->TargetField="id";
			$this->SearchVar="";
			$this->NewSearchVar=array();
		}
		
		function AddTable($Table){
			$this->Table=$Table;
		}
		/*
		function AddTables($Tables=array(),$Where_Intersect=array()){
			$this->Tables=$Tables;
			$this->Where_Intersect=$Where_Intersect;
		}
		function Retreive_All_Variables(){
			$this->Retreive_All_Variables=true;
		}
		function Retreive_Variables($variables=array()){
			$this->Retreive_Variables=$variables;
		}
		function Retreive_Variables_Functions($variable_functions=array()){
			$this->Retreive_Variables_Functions=$variable_functions;
		}
		function Retreive_Variables_Altered($variables=array()){
			$this->Retreive_Variables_Altered=$variables;
		}
		function Retreive_Functions($variables=array()){
			$this->Retreive_Functions=$variables;
		}
		function Retreive_Functions_Altered($variables=array()){
			$this->Retreive_Functions_Altered=$variables;
		}
		function Retreive_Num_Records($start,$number){
			$this->Num_Records_Start=$start
			$this->Num_Records_Number=$number;
		}
		function Sort_By($sort_fields=array()){
			$this->Sort_Fields=$sort_fields;
		}
		function Unique_Fields($fields=array()){
			$this->Unique_Fields=$fields;
		}
		*/
		function ChangeTarget($to){
			$this->TargetField=$to;
		}
		function AddSearchVar($id){
			$this->SearchVar=$id;
		}
		function AddNewSearchVar($key,$id){
			$this->NewSearchVar[$key]=$id;
		}
		function Add_Sub_Query($Variable,$Parameter){
			//$this->NewSearchVar[$key]=$id;
		}
		/*
		public function Add_App_Data(&$app_data){
			//print("ddd");
			$this->app_data=$app_data;
			//print_r($this->app_data);
			
		}
		*/
		/*
		function Make_SQL(){
			$select_variables=array();
			if($this->Retreive_All_Variables){
				$select_variables[]="*";
			}
			if(is_array($this->Retreive_Variables)){
				foreach($this->Retreive_Variables as $key=>$val){
					if(in_array($val,$this->Retreive_Variables_Altered)){
						$vkeys_arr=array_keys($val, $this->Retreive_Variables_Altered);
						$sel_var_equal=$vkeys_arr[0];
						$val=" AS ".$this->Retreive_Variables_Altered[$sel_var_equal];
					}
					$select_variables[]=$val;
				}
			}
			$table_variables=array();
			$table_variables_condition=array();
			if(is_array($this->Tables)){
				foreach($this->Tables as $key=>$val){
					
					$table_variables[]=$val;
					$vkeys_arr=array_keys($val,$this->Where_Intersect);
					if(count($vkeys_arr)>0){
						$sel_var_equal=$vkeys_arr[0];
						if($this->Where_Intersect){
							$table_variables_condition[]=$this->Where_Intersect[$sel_var_equal];
						}
					}
					
				}
			}
			$this->Tables=$Tables;
			$this->Where_Intersect=$Where_Intersect;
			
			$m_arg = "SELECT * FROM $this->Table where $this->TargetField='$this->SearchVar'";
			
		}
		*/
		function GetRecord(){
			//print "ll";
			$link=$this->Get_Links();
			if(!$link) $link=$this->CreateDB();
			$m_arg = "SELECT * FROM $this->Table where $this->TargetField='$this->SearchVar'";
			
			foreach($this->NewSearchVar as $key=>$val){
				$m_arg .= " AND $key='$val'";
			}
			
			//print("d11d");
			$this->SQL=$m_arg;
			//$result=$this->rawQuery($m_arg);
			$result = $this->rawQuery($this->SQL);
			$this->Set_Result($result);
			if($result){
				$m_rows = $this->Fetch_Assoc();
				//print_r($m_rows);
				if(is_array($m_rows)){
					foreach($m_rows as $key => $value){
					    if(isset($m_rows[$key])){
					        $m_rows[$key]=stripslashes($m_rows[$key]);
					    }
						
					};
				};
				//print("ddd");
				return $m_rows;
			}else{
				//$this->log->general("Multi MySQL Error->".var_export($result,true)." ".$query,3);
				//print "ERROR: $m_arg";
			}
		}
		function GetMultiRecord(){
			$count=0;
			$link=$this->Get_Links();
			if(!$link) $link=$this->CreateDB();
			$m_arg = "SELECT * FROM $this->Table where $this->TargetField='$this->SearchVar'";
			
			$result=$this->rawQuery($m_arg);
			$this->Set_Result($result);
			if($this->result){
				while($m_rows = $this->Fetch_Array());
				{
					if(is_array($m_rows)){
						foreach($m_rows as $key => $value){
							$m_rows[$count][$key]=stripslashes($m_rows[$key]);
						};
					};
					$count++;
				}
			}else{
				$this->log->general("Multi MySQL Error->".var_export($result,true)." ".$m_arg,3);
				//print "ERROR: $m_arg";
			}
			return $m_rows;
		}
		
		//function rawQuery($query="",$links=false)
		function rawQuery($query="",$link=false)
		{
			$result=false;
			if($query!=""){
				$this->SQL=$query;
				if(!$link){
					$link=$this->Get_Links();
				}
				
				
				try{
					if($link){
						//echo"999XX----------------------------".$query."-------------------------------------------------\n\n";
				
						if($this->current_db_type=="pgSQL"){
							$result = pg_query($query);
						}elseif($this->current_db_type=="MySQL"){
							//$this->test_mysql_db_link($links);
							$result = $link->query($query);
							$this->log->general("MySQL Query->".$query,9);
							
						}elseif($this->current_db_type=="Sqlite"){
							//echo"454-----------------------------------------------------------------------------";
						}
					}else{
						//echo"454----Link Failed-------------------------------------------------------------------------";
					}
					
					
					if(!$result){
						$this->log->general("No MySQL Result->".$query,9);
						//echo"\n\n9001---rawQuery Error------------------------|-".$query."-|---\n----|-".$this->current_db_type."-|---------------------------------------\n\n";
					
						return false;
					}else{
						//echo"\n\n8888---rawQuery Error------------------------|-".$query."-|---\n----|-".$this->current_db_type."-|---------------------------------------\n\n";
					}
					
				}catch(Exception $e){
					$this->log->general("MySQL Exception->".var_export($e,true)." ".$query,3);
				
				}
				//echo"\n\n666555-Success-------------|-".$query."-|-----------------".var_export($result,true)."-------------------------------------------------\n\n";
				
				//$this->links=$links;
				$this->Set_Links($link);
				$this->Set_Result($result);
			}else{
				//echo"\n\n666444-No SQL-------------|-".$query."-|------------------------------------------------------------------\n\n";
				
			}
			
			//$this->result=$result;
			return $result;
		}
		
		function NumRows($result=false){
			if(!$result){
				$result=$this->Get_Result();
			}
			$link=$this->Get_Links();
			$num_rows=0;
			if($result){
				try{
					//$this->log->general("Start Num Rows->",3);
					
					//$this->log->general("Row Count->".$num_rows,3);
					//$this->log->general("\n",3);
					$num_rows=0;
					if($this->current_db_type=="MySQL"){
						$this->log->general("Connection failed: " .$this->Error(),3);
						$this->log->general("m->Connection Success: ".var_export($link,true),1);
						//$this->result->reset();
						$num_rows=$result->num_rows;
						//echo"9875654321-----------------%-".$num_rows."-%--|--".$this->SQL."--|----------------------------------------------------\n\n";
				
					}elseif($this->current_db_type=="Sqlite"){
						
						//$this->num_rows=$this->result->num_rows;
						$result->reset();
						$nrows = 0;
						
						while ($this->Fetch_Array($result)){
							$nrows++;
						}
							
						$result->reset();
						$num_rows=$nrows;
						//return $nrows;
					}elseif($this->current_db_type=="pgSQL"){
						$num_rows = pg_num_rows($result);
					}
					
					//echo"454-----------------%-".$this->num_rows."-%----------------------------------------------------------";
					
				}catch(Exception $e){
					$this->log->general("MySQL NumRows Exception->".var_export($e,true)." ".$this->SQL,3);
					return 0;
				}
			}
			//////echo"98756543210-----------------%-".$num_rows."-%---------------|--".$this->SQL."--|-------------------------------------------\n\n";
				
			$this->num_rows=$num_rows;

			//echo"9875654321000-----------------%-".$this->num_rows."-%----------|--".$this->SQL."--|------------------------------------------------\n\n";
			
			return $num_rows;
		}
		/*
		function Fetch_Array($result=false)
		{
			$row=array();
			if(!$result){
				//echo"4321-----------------%--%----------------------------------------------------------";
				
				$result=$this->result;
			}
			try{
				if($this->current_db_type=="MySQL"){
					
					if($result){
						$row = $result->fetch_array(MYSQLI_NUM);
						if($this->NumRows()==0){
							$row=array();	
						}
						//echo"9875654321-----------------%-".var_export($row,true)."-%----------------------------------------------------------";
				
					}else{
						
						$row=false;
					}
					
					
				}elseif($this->current_db_type=="Sqlite"){
					$row = $result->fetchArray();
				}elseif($this->current_db_type=="pgSQL"){
					$row = pg_fetch_array($result, 0, PGSQL_NUM);
					//$row = $this->result->fetchArray();
				}
			}catch(Exception $e){
				$this->log->general("MySQL Fetch Array Exception->".var_export($e,true),3);
				$row=array();
			}
			$this->log->general("667 =>\n".var_export($row,true)."<================================\n\n".$this->SQL,3);
			//echo"2211-----------------------------------------------------------".var_export($row,true)."----xx--------------";
			return $row;
			
		}
		*/
		function Fetch_Array($result=false)
		{
			
			$row=array();
			if(!$result) $result=$this->Get_Result();

			//echo"\n\n 9988811----------".var_export($result,true)."-------------".$this->SQL."---------------------------------------------------------\n\n";
			
			//echo"\n\n 99888----------".var_export($result,true)."----------------------------------------------------------------------\n\n";
			

			if($result){
				//if(!$result) $result=$this->result;
				if($this->current_db_type=="MySQL"){
					$row = $result->fetch_array(MYSQLI_NUM);
					//echo"\n\n 1234-Fetch Arau----\n\n----".var_export($result,true)."----\n\n----|-".$this->SQL."--|--\n\n-data--".var_export($row,true)."-----\n\n----------------------------------------------9943210-2--\n\n";
					
				}elseif($this->current_db_type=="Sqlite"){
					$row = $result->fetchArray(SQLITE3_NUM);
				}elseif($this->current_db_type=="pgSQL"){
					$row =pg_fetch_array($result);
				}
				if(is_array($row)){
					if(count($row)>0){
						//echo"\n\n 9943210-Return Array Success------\n\n---".var_export($result,true)."-----\n\n--------".var_export($row,true)."-----------".$this->SQL."-----------------------------------------------\n\n";
						//return $row;
					}else{
						//echo"\n\n 9943210-1--Error----\n\n----".var_export($result,true)."----\n\n-----|-".$this->SQL."--|-------".var_export($row,true)."-----\n\n------".$this->SQL."-----------------------------------------------\n\n";
						$row=array();
					}
				}else{
					//echo"\n\n 9943210-2-No Array on row Error----\n\n----".var_export($result,true)."----\n\n----|-".$this->SQL."--|-----".var_export($row,true)."-----\n\n----------------------------------------------9943210-2--\n\n";
					$row=array();
				}
				
			}else{
				//echo"\n\n 9943210-3--Error---\n\n-------".var_export($result,true)."-------\n\n------|-".$this->SQL."--|--------\n\n--------------------------------------------------\n\n";
				$row=array();
			}
				
			return $row;
			
		}
		
		function Fetch_Assoc($result=false)
		{
			//echo"fff-----------------------------------------------------------------------------";
			$row=array();
			if(!$result) $result=$this->Get_Result();
			if($result){
				if($this->current_db_type=="MySQL"){
					$row = $result->fetch_array(MYSQLI_ASSOC);
					
				}elseif($this->current_db_type=="Sqlite"){
					$row = $result->fetchArray(SQLITE3_ASSOC);
				}elseif($this->current_db_type=="pgSQL"){
					$row =pg_fetch_assoc($result);
				}
				//if(count($row)>0){
				if(is_array($row)){
					//echo"\n\n 994321-XXX-1-------\n\n-|-".var_export($result,true)."-|--------\n\n--|-".$this->SQL."-|-------\n\n-".var_export($row,true)."--------------------------------------------------\n\n";
					return $row;
				}else{
					//echo"\n\n 9943210--XXX-2-Error------\n\n-|-".var_export($result,true)."--|-----|-".$this->SQL."-|--------\n\n-|-".var_export($row,true)."-|------\n\n---------------------------------------------\n\n";
					$row=array();
				}
			}else{
				//echo"\n\n 9943210--XXX-3--No Result Error-----\n\n---|--".var_export($result,true)."-|-------\n\n---|--".$this->SQL."-|-----\n\n----------------------------------------------------\n\n";
				$row=array();
			}
			//echo"2233----------------------------------------------------------|-".var_export($row,true)."-|-----------------";
			//echo"\n\n 19943210----------".var_export($result,true)."-------------".var_export($row,true)."----------------------------------------------------------\n\n";
			
			return $row;
		}

		function Fetch_Both($result=false)
		{
			//echo"fff-----------------------------------------------------------------------------";
			$row=array();
			if(!$result) $result=$this->Get_Result();
			if($this->current_db_type=="MySQL"){
				$row = $result->fetch_array(MYSQLI_BOTH);
				
			}elseif($this->current_db_type=="Sqlite"){
				$row = $result->fetchArray(SQLITE3_BOTH);
			}elseif($this->current_db_type=="pgSQL"){
				$row=pg_fetch_array($result,Null,PGSQL_BOTH);
			}
			
			//echo"2233----------------------------------------------------------|-".var_export($row,true)."-|-----------------";
			return $row;
			
		}

		
		function Fetch_Row($type="Both",$result=false)
		{
			//echo"fff-----------------------------------------------------------------------------";
			$row=array();
			if(!$result) $result=$this->Get_Result();
			switch($type){
				case "Both":
					$row =$this->Fetch_Both($result);
				break;
				case "Array":
					$row =$this->Fetch_Array($result);
				break;
				case "Assoc":
					$row =$this->Fetch_Assoc($result);
				break;
			}
			//echo"2233----------------------------------------------------------|-".var_export($row,true)."-|-----------------";
			return $row;
			
		}
		
		function Error()
		{
			$return_error=false;
			$link=$this->Get_Links();
			if (!$link) {
				$return_error=mysqli_connect_error();
				return $return_error;
			}else{
				return $return_error;
			}
			/*			
			$result=$this->Get_Result();
			$er = $result->error;
			return $er;
			*/
			
		}
		
		
		function Escape($string)
		{
			//echo"20-----------------------------------------------------------".var_export($this->links,true)."------------------";
			
			if(isset($string)){
				if(strlen($string)>0){
					if($this->links){
						$st = $this->links->real_escape_string($string);
					}else{
						$st="";
					}
					
				}else{
					$st="";
				}
			}else{
				$st="";
			}
			
			return $st;
			
		}
		
		function Insert_Id(){
			try{
				$InsertID = $this->links->insert_id;
				return $InsertID;
			}catch(ErrorException $e){
				$this->log->general("-Insert_Id failed--".var_export($e,true),3);
			}
		}
		
		function rawQueryX($query)
		{
			
			$temp = $this->rawQuery($query);
			return $temp;
		}
		
		function otherRawQuery($query)
		{
			
			
			$temp = $this->rawQuery($query);
			return $temp;
		}
		
		function returnDBLink()
		{
			return $this->links;
		}
		
	}