<?php
    class clsTestDatabase(){
        public function test_pgsql(){
			
						
			$dbconn = pg_connect("host=localhost dbname=cwy0ek0e_bubblelite2 user=postgres password=DickSux5841");
			// Performing SQL query
			$query = 'SELECT * FROM administrators';
			//$result = pg_query($query) or die('Query failed: ' . pg_last_error());
			//echo"43210000555-------------------|-".var_export($dbconn,true)."-|----------------------------------------------------------\n\n";
			$result = pg_query($query);
			//echo"432100001-------------------|-".$query."-|----------------------------------------------------------\n\n";
			// Printing results in HTML
			echo "<table>\n";
			while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
				echo "\t<tr>\n";
				foreach ($line as $col_value) {
					echo "\t\t<td>$col_value</td>\n";
				}
				echo "\t</tr>\n";
			}
			echo "</table>\n";

			// Free resultset
			pg_free_result($result);

			// Closing connection
			pg_close($dbconn);
			
		}

		public function test_mysql($query=false){

			include("classes/db.php");
			//echo"43210-------------------|-".var_export($server_DB,true)."-|----------------------------------------------------------\n\n";
			$DB=$server_DB;
			$current_server_tag=$DB['server_tag'];
			$server_desc=$DB['server_desc'];
			$db_login=$DB;
			//echo"<br>-110----------------------".var_export($db_login,true)."-------------------------------------";
			$new_links = new mysqli($db_login['hostname'], $db_login['usernamedb'], $db_login['passworddb'],$db_login['dbName'] );
			//print "99--|--".$db_login['hostname']."--|--".$db_login['usernamedb']."--|--".$db_login['passworddb']."--|--".$db_login['dbName']."--|--\n\n";
			//echo"\n\n<br>-110001----------------------".var_export($new_links,true)."-------------------------------------";
			$result =$this->test_mysql_db_link($new_links,$query);
			//$this->result=$result;
			
			/*
			$query = 'SELECT * FROM administrators';
			//$result = $new_links->query($query);
			echo"\n\n<br>-ZZZZ----------------------".var_export($new_links,true)."-------------------------------------";
			
			$result =$this->rawQuery($query,$new_links);
			//echo"666----------------------------".var_export($result,true)."-------------------------------------------------\n\n";
			//while($row = $this->Fetch_Assoc($result)){
			while($row = $this->Fetch_Array($result)){
			//while($row = $result->fetch_array(MYSQLI_NUM)){
				//print_r($row);
				echo"\n\n<br>-ZZZZAAA----------------------".var_export($row,true)."-------------------------------------\n\n";
			
			};
			*/
			
			return $new_links;
		}

		public function test_mysql_db_link($link=false,$query=false){
			//echo"DDD-test_mysql_db_link---------".$query."------------------".var_export($link,true)."-------------------------------------------------\n\n";
			
			$result =false;
			if(!$query){
				$query = "SELECT * FROM administrators";
			}
			if(!$link){
				//$link = $this->links;
				$link=$this->Get_Links();
			}
			
			$result =$this->rawQuery($query,$link);
			if($result){
				//$result==$result;
				$this->Set_Result($result);
			}
			//$result = $link->query($query);
			/*
			while($row = $this->Fetch_Array($result)){
				print_r($row);
			};
			*/
			$result_array=array();
			$result_array=$this->test_mysql_db_result($result);
			//echo"EEE-last---------------------------||-".var_export($result_array,true)."---||----------------------------------------------\n\n";
			return $result;
		}

		
		public function test_mysql_db_result($result=false){
			
			//$query = 'SELECT * FROM administrators';
			//$result = $link->query($query);
			//echo"\n\n AAA------------test_mysql_db_result----------------".var_export($result,true)."-------------------------------------------------\n\n";
			$result_array=array();
			if(!$result){
				//$result=$this->result;
				$result=$this->Get_Result();
				//echo"\n\n FFF----------------------------".var_export($result,true)."-------------------------------------------------\n\n";
			
			}else{
				
				//echo"AAB----------------------------".var_export($result,true)."-------------------------------------------------\n\n";
				//while($row = $this->Fetch_Array($result)){
				while($row = $this->Fetch_Assoc($result)){
					$result_array[]=$row;
					//echo"\n\n 123456----------------------------".var_export($row,true)."-------------------------------------------------\n\n";
					//print_r($row);
				};
				//echo"AABBCC-last----------------------------".var_export($result,true)."-------------------------------------------------\n\n";
				
			}
			return $result_array;
		}

        public function test_pgsql(){
			//echo"888800001-------------------|-99-|----------------------------------------------------------\n\n";
			
			//echo"888800001-------------------|-".$this->current_db_type."-|----------------------------------------------------------\n\n";
			/*
			try{
				$dbconn = pg_connect("host=localhost dbname=cwy0ek0e_bubblelite2 user=postgres password=DickSux5841");
				$v = pg_version($dbconn);
				print "\n++|==".$v."=|++\n\n";
			}catch(Exception $e){
				exit("xx".var_export($e,true));
			}
			*/
			
			$dbconn = pg_connect("host=localhost dbname=cwy0ek0e_bubblelite2 user=postgres password=DickSux5841");
			// Performing SQL query
			$query = 'SELECT * FROM administrators';
			//$result = pg_query($query) or die('Query failed: ' . pg_last_error());
			//echo"43210000555-------------------|-".var_export($dbconn,true)."-|----------------------------------------------------------\n\n";
			$result = pg_query($query);
			//echo"432100001-------------------|-".$query."-|----------------------------------------------------------\n\n";
			// Printing results in HTML
			echo "<table>\n";
			while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
				echo "\t<tr>\n";
				foreach ($line as $col_value) {
					echo "\t\t<td>$col_value</td>\n";
				}
				echo "\t</tr>\n";
			}
			echo "</table>\n";

			// Free resultset
			pg_free_result($result);

			// Closing connection
			pg_close($dbconn);
			
		}

        /*
		public function test_mysql(){
			
			
			$DB=array();
			//$DB['server_type']="pgSQL";
			$DB['server_type']="MySQL";
			//$DB['server_type'] = "Sqlite";
				
			//if($DB['server_type']=="MySQL"){
			
				$DB['server_tag']="db-sm-w-d.php";
				$DB['server_desc']="Hosted Fire | SiteManage";
				$DB['current_dir']="/home/sitemanage/public_html";
				$DB['server_number']=13;
				$DB['hostname']="142.132.144.12";
				$DB['usernamedb']='sitemanage_danielruul78';
				$DB['passworddb']='DickSux5841';
				$DB['dbName']='sitemanage_bubblelite2';
				
			//}

			print_r($DB);
			$links = new mysqli($DB['hostname'], $DB['usernamedb'], $DB['passworddb'],$DB['dbName']);
			//$links->select_db($connect['dbName']);
			// Check connection
			if($links->connect_error) {
				//die("Connection failed: " . $links->connect_error);

				print("Connection failed: " . $links->connect_error);
			}else{
				print("Connected successfully: " .var_export($DB,true));

			}
			echo 'Connected successfully';
			$query='SELECT * FROM domains LIMIT 0,1';
			$result = $links->query($query);
			$myrow=$result->fetch_row();
			print_r($myrow);
			
		}
		*/
		
		public function test_mysql(){
			$DB=$this->server_login;
			$DB['server_type']="MySQL";
			$this->log->general("App Data Array ",4,$DB);
			//print_r($DB);
			$links = new mysqli($DB['hostname'], $DB['usernamedb'], $DB['passworddb'],$DB['dbName']);
			if($links->connect_error) {
				print("Connection failed: " . $links->connect_error);
			}else{
				print("Connected successfully: " .var_export($DB,true));
			}
			echo 'Connected successfully';
			$query='SELECT * FROM domains LIMIT 0,1';
			$result = $links->query($query);
			$myrow=$result->fetch_row();
			//print_r($myrow);
			$this->log->general("App Data Array ",4,$myrow);
			
		}
    }


?>