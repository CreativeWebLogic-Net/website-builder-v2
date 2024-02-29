<?php
	
	class clsDCMS{
		var $ConfigServer="https://git.creativeweblogic.net/Server-Config-File.html";
		var $RemoteServer="access.bubblecms.biz/";
		var $BaseCacheDirectory="cache/";
		var $BaseDomainCacheDirectory="../cache/";
		var $current_dir="";
		var $current_back_dir="";
		var $LocalServer;
		var $CacheText="Use Cached File";
		var $Current_File="";
		var $Current_Full_Cached_File="";
		var $RequestUnCachedFiles=true;
		var $RemoteServerIP="142.132.144.12";
		var $ForbiddenExtensions=array();
		var $useragent="curl";
		var $cookieFile = "cookies.txt";
		var $guid="";
		var $domain_folders=array("html","images","linked","cookies");
		var $domain_folder_index="";
		var $file_extensions=array();
		var $current_file_extension=array();
		var $Current_Full_Directories=array();
		var $Current_Full_Files=array();
		var $Current_Full_File_Index=0;
		var $Current_Cache_Dir="";
		var $Current_Opened_Cache_File_Location="";
		var $error_count=0;
		var $absolute_cache_dir="";
		var $cache_domain_dir="";
		var $server_guid="";
		var $serverFile ="";
		var $current_original_dir="";
		var $server_ini_file="";
		
		function __construct(){
		    //print_r($_SESSION);
			
		    $this->create_constants();
			$this->create_domain_cache();
		    $this->create_domain_folders();
		    $this->create_file_extensions();
			$this->set_server_guid();
		    
		}
		
		function create_domain_cache() 
		{ 
			//$current_domain_folder="D:\Program Files\Ampps\www\dcms\cache\localhost8765";
			$this->cache_domain_dir=$this->current_original_dir['dirname']."/cache/".$this->LocalServer;
			//$this->cache_domain_dir=$current_domain_folder."/";
			//$current_domain_folder=$this->cache_domain_dir;
			//$current_domain_folder=".\cache\\".$this->LocalServer;
			//print "\n\n error abc ->".$this->cache_domain_dir."-- \n\n";
			//$this->show_info($current_domain_folder);
			if (!file_exists($this->cache_domain_dir)) {
				if(!mkdir($this->cache_domain_dir)){
					//echo "\n\n error AAA ->".$current_domain_folder."--\n\n";
					
				}else{
					//echo "\n\n Folder Created ->".$current_domain_folder."--\n\n";
				}
			}else{
				//echo "\n\n Folder Exists ->".$current_domain_folder."--\n\n";
			}
		}
		
		function create_domain_folders() 
		{ 
			foreach($this->domain_folders as $key=>$val){
			    
				//$current_domain_folder='./dcms/cache/'.$this->BaseDomainCacheDirectory;
				/*
				$current_domain_folder=$this->cache_domain_dir;
				print $current_domain_folder;
				
				if (!file_exists($current_domain_folder)) {
					if(!mkdir($current_domain_folder)){
						echo "error cdf ->".$current_domain_folder."--".$val."-\n\n";
						
					}
				}
				*/
				
				//$this->Current_Cache_Dir=$current_domain_folder;
				$current_folder=$this->cache_domain_dir."/".$val;
				if (!file_exists($current_folder)) {
					if(!mkdir($current_folder)){
						//echo "error AA cdf ->".$current_domain_folder."-\n\n";
					}else{
						$this->Current_Full_Directories[$key]=$current_folder;
					}
				}else{
				    $this->Current_Full_Directories[$key]=$current_folder;
				}
			}
		}
		
		function create_file_extensions() 
		{ 
			$this->file_extensions[$this->domain_folders[0]]=array("html","htm","php","py","pl","ci","aspx","/");
			$this->file_extensions[$this->domain_folders[1]]=array("jpg","png","gif","svg","tiff","eps","psd","ico");
			$this->file_extensions[$this->domain_folders[2]]=array("css","js","xml","txt","csv");
			$this->file_extensions[$this->domain_folders[3]]=array("txt");
			$this->Current_Full_File_Index=0;
		}
		
		function show_info($dir='./'){
				echo 'Current script owner: ' . get_current_user()."\n";
			print $_SERVER['SERVER_NAME']."-<br>";
			$host_name=gethostname();
			print $host_name."-<br>";
			$current_dir=pathinfo(__DIR__);
			print_r($current_dir)."<br>";
			print($_SERVER['PHP_SELF']);
			
			$dir    = $dir.'/';
			if(is_dir($dir)){
				
			}else{
				echo "Not Dir cdf ->".$dir."-\n\n";
			}
			$files1 = scandir($dir);
			print_r($files1);
			
			$file = $dir.$files1[3];
			print $file;
			//$handle = fopen($file, "r");
			readfile($file);
			
			$lines = file($file);

			// Loop through our array, show HTML source as HTML source; and line numbers too.
			foreach ($lines as $line_num => $line) {
				echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
			}
		}
		
		
		function create_constants() 
		{ 
		    
		    if(substr($_SERVER['HTTP_HOST'], 0, 4)=='www.'){
			    $string_size=strlen($_SERVER['HTTP_HOST']);
			    $base_name=substr($_SERVER['HTTP_HOST'], 4, $string_size-4);
			}else{
			    $base_name=$_SERVER['HTTP_HOST'];
			}
			//$this->LocalServer=urlencode($base_name);
			$this->LocalServer=str_replace(':', "_", $base_name);
			//print $this->LocalServer;
			$this->domain_folder_index=$this->BaseCacheDirectory;
			$this->Current_File=$_SERVER['REQUEST_URI'];//$_SERVER['REQUEST_URI']
			//$this->LocalServer=urlencode($_SERVER['HTTP_HOST']);
			$this->current_original_dir=pathinfo(__DIR__);
			$current_dir=$this->current_original_dir;
			$this->current_back_dir=$current_dir["dirname"].'/';
			$this->current_dir=$current_dir['dirname'].'/'.$current_dir['basename']."/";
			$this->BaseDomainCacheDirectory=$this->domain_folder_index.$this->LocalServer."/";
			$this->cache_domain_dir=$this->current_dir.$this->domain_folder_index.$this->LocalServer;
			//print "\n\n\n ff->".$this->current_original_dir["dirname"]."-DD-".$this->BaseDomainCacheDirectory."--".$this->cache_domain_dir."-- \n\n";
			
			//$this->absolute_cache_dir="D:\Program Files\Ampps\www\dcms\cache\";
		}
		
		function make_guid ($length=32) 
		{ 
			if (function_exists('com_create_guid') === true)
			{
					return trim(com_create_guid(), '{}');
			}else{
				$key="";    
				$minlength=$length;
				$maxlength=$length;
				$charset = "abcdefghijklmnopqrstuvwxyz"; 
				$charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
				$charset .= "0123456789"; 
				if ($minlength > $maxlength) $length = mt_rand ($maxlength, $minlength); 
				else                         $length = mt_rand ($minlength, $maxlength); 
				for ($i=0; $i<$length; $i++) $key .= $charset[(mt_rand(0,(strlen($charset)-1)))]; 
				return $key;
			}	
		}
		
		function make_server_guid(){
				/*
				$disk = disk_total_space(__DIR__);
				$fs = filectime('/');
				*/
				$os = php_uname();
				
				$host = gethostname();
				$ip = gethostbyname($host);
				echo"\n $os \n $host \n $ip \n";
				// Concatenate and hash the information
				//$server_id = md5($os . $disk . $fs . $host . $ip);
				$server_id = md5($os . $host . $ip);

				// A server ID, like: 3f5d6c2c4b0f2f89f3c8f6d1c0a9e9d8
				return $server_id;
		}
		
		function get_file_date($filename){
			$ret_val=date ("Y-m-d H:i:s");
			if(file_exists($filename)) {
				$ret_val=date ("Y-m-d H:i:s", filemtime($filename));
			}else{
			}
			return urlencode($ret_val);
		}

		
		function get_file_directory($file_type){
		    $cache_variables=array();
		    if(in_array($file_type,$this->domain_folders)){
				/*
		        if(substr($_SERVER['HTTP_HOST'], 0, 4)=='www.'){
    			    $string_size=strlen($_SERVER['HTTP_HOST']);
    			    $base_name=substr($_SERVER['HTTP_HOST'], 4, $string_size-4);
    			}else{
    			    $base_name=$_SERVER['HTTP_HOST'];
    			}
				*/
				$base_name=$this->LocalServer;
    			$cache_variables['session_guid']=$this->guid;
    			$cache_variables['file_tag_extension']="-cookies.txt";
    			$cache_variables['server']=$base_name;
    			$cache_variables['base_cache_dir']=$this->BaseCacheDirectory;
    			$cache_variables['base_dir_type']=$file_type;
    			$cache_variables['complete_address']=$cache_variables['base_cache_dir'].$cache_variables['server'].'/'.$cache_variables['base_dir_type'];
		        switch($file_type){
		            case "html":
		                $cache_variables['cache_file_name']=$this->slash_wrap($_SERVER['REQUEST_URI']);
		                $cache_variables['complete_address'].="/".$cache_variables['cache_file_name'];
		            break;
		            case "images":
		                
		            break;
		            case "linked":
		                
		            break;
		            case "cookies":
		                
		                $cache_variables['complete_address'].="/".$cache_variables['session_guid']."-cookies.txt";
		            break;
		            
		        }
		        
    			
    			$this->set_log(array($cache_variables['complete_address']));
		    }
			
			return $cache_variables['complete_address'];
		}

		
		function set_log($var_array="",$message=""){
		    //print_r($var_array);
		    $output="";
		    if(is_array($var_array)){
		        $output=var_export($var_array,true);
		    }else{
		        $output=$var_array;
		    }
		    if($message!=""){
		        $output.="-".$message;
		    }
		    $output="\n".$output."\n";
		    
			//print $output;
		}

		
		function set_cookie(){
			if(!isset($_SESSION['guid'])){
				$this->guid=$this->make_guid();
				$_SESSION['guid']=$this->guid;
			}else{
				$this->guid=$_SESSION['guid'];
			}
			//$this->cookieFile =$this->get_file_directory("cookies")."/".$this->guid."-cookies.txt";
			//$this->cookieFile =$this->get_file_directory("cookies");
			$this->cookieFile =$this->cache_domain_dir."/cookies/".$this->guid."-cookies.txt";
			//$this->cookieFile =$this->BaseCacheDirectory.$this->Current_Full_Directories[3]."/".$this->guid."-cookies.txt";
			//print $this->cookieFile;
			/*
			if(!isset($_SESSION['counter'])){
			    $_SESSION['counter']=1;
			}else{
			    $_SESSION['counter']++;
			}
			*/
			$this->set_log($_SESSION);
			$this->set_log(array($this->cookieFile,$this->BaseCacheDirectory,$this->guid));
			if(!file_exists($this->cookieFile)) {
				$fh = fopen($this->cookieFile, "w");
				fwrite($fh, "");
				fclose($fh);
				if(!file_exists($this->cookieFile)) {
				}else{
				}
			}else{
			}
			return $this->cookieFile;
		}
		
		function set_server_guid(){
			
			$this->server_guid=$this->make_guid();
			//$this->server_guid=$this->make_server_guid();
			$this->server_ini_file =$this->current_original_dir["dirname"]."/server.ini";
			//print $this->server_ini_file;
			if(!file_exists($this->server_ini_file)) {
				$fh = fopen($this->server_ini_file, "w");
				fwrite($fh, $this->server_guid);
				fclose($fh);
				if(!file_exists($this->server_ini_file)) {
				}else{
				}
			}else{
				$handle = fopen($this->server_ini_file, "r");
				$contents = fread($handle, filesize($this->server_ini_file));
				fclose($handle);
				$this->server_guid=$contents;
				//print "Mew file=>".$contents;
			}
			return $this->server_ini_file;
		}
		
		function slash_wrap($DisplayPage){
			return urlencode(base64_encode($DisplayPage));
		}
		
		function CacheDirectory(){
			$dir=$this->current_back_dir.$this->BaseCacheDirectory;
			return $this->current_back_dir.$this->BaseCacheDirectory;
		}
		function CheckIfHTMLFile($DisplayPage){
			$ret_val=false;
			$BSlashEncoded='/';
			$end_of_string=substr($DisplayPage,strlen($DisplayPage)-strlen($BSlashEncoded));
			
			if($end_of_string==$BSlashEncoded){
				$ret_val=true;
			}else{
				$ret_val=false;
			}
			return $ret_val;
		}
		
		function CheckFilesDuplicates($DisplayPage){
			$ret_val=false;
			foreach($this->Current_Full_Files as $current_index=>$values){
				if($values["filename"]==$DisplayPage){
					$ret_val=true;
				}else{
					$ret_val=false;
				}
				
			}
			return $ret_val;
		}
		
		function Set_Full_Files($dimensions_array=array(),$files_index=0){
			if(!$this->CheckFilesDuplicates($dimensions_array["filename"])){
				if(count($dimensions_array)>0){
					$current_index=count($this->Current_Full_Files);
					$this->Current_Full_File_Index=$current_index;
					$this->Current_Full_Files[$current_index]["filename"]=$dimensions_array["filename"];
					$this->Current_Full_Files[$current_index]["encoded_filename"]=$dimensions_array["encoded_filename"];
					$this->Current_Full_Files[$current_index]["extension"]=$dimensions_array["extension"];
					$this->Current_Full_Files[$current_index]["extension_type"]=$dimensions_array["extension_type"];
					$this->Current_Full_Files[$current_index]["directory"]=$dimensions_array["directory"];
					$this->Current_Full_Files[$current_index]["complete_cache_location"]=$dimensions_array["directory"].$dimensions_array["encoded_filename"];
					return false;
				}else{
					return $this->Current_Full_Files[$files_index];
				}
			}else{
				return false;
			}
			
		}
		
		function CheckFileDestination($DisplayPage){
		$ret_val=false;
			
			foreach($this->file_extensions as $key=>$val){
				foreach($val as $ext_key=>$extension){
					$end_of_string=substr($DisplayPage,strlen($DisplayPage)-strlen($extension));
					if($end_of_string==$extension){
						$array_dims["filename"]=$DisplayPage;
						$array_dims["encoded_filename"]=$this->slash_wrap($DisplayPage);
						$array_dims["extension"]=$extension;
						$array_dims["extension_type"]=$key;
						$array_dims["directory"]=$this->BaseDomainCacheDirectory.$key.$extension;
						
						$this->Set_Full_Files($array_dims);
						$ret_val=true;
						break;
						break;
						
					}else{
						if(!$ret_val) $ret_val=false;
					}
					
				}
				
			}
			return $ret_val;
		}
		
		function LocalFileName($DisplayPage){
			if($this->CheckFileDestination($DisplayPage)){
				$filename =$this->Current_Full_Files[$this->Current_Full_File_Index]["encoded_filename"];
			}else{
				$filename ="404 Error";
			}
			return $filename;
		}
		
		function url_get_contents($url){//,$DisplayPage) {
			$this->cookieFile=$this->set_cookie();
			$all_globals=array_merge($_GET,$_POST);
			$encoded_get="";
			$encoded_post="";
			$encoded="";
			
			$get_array=array();
			$post_array=array();
			//echo"\n post \n";
			//print_r($_POST);
			//echo"\n get \n";
			//print_r($_GET);
			if(count($_GET)>0){
				foreach($_GET as $key=>$val){
					if(is_array($val)){
						foreach($val as $product_key=>$product_val){
							if($product_val!=""){
								$get_array[$product_key]=$product_val;
							}
						}
					}else{
						if($val!=""){
							$get_array[$key]=$val;
						}
					}
					//echo $key."xxx".$val;
					
				}
				//$encoded_get=http_build_query($_GET);
				//$encoded_get=$_GET;
			}
			
			$post_array=array("server_guid"=>$this->server_guid);
			foreach($_POST as $key=>$val){
			    if(is_array($val)){
			        foreach($val as $product_key=>$product_val){
			            if($product_val!=""){
        			        $post_array[$product_key]=$product_val;
        			    }
			        }
			    }else{
			        if($val!=""){
    			        $post_array[$key]=$val;
    			    }
			    }
			    //echo $key."xxx".$val;
			    
			}
			//echo"xxx";
			//print_r($post_array);
			if(count($post_array)>0){
			  $encoded_post =http_build_query($post_array);
			  
			}
			//echo"\n all post 11 \n->".$encoded_post." \n";
			if(count($get_array)>0){
			  $encoded_get =http_build_query($get_array);
			  
			}
			//print_r($all_globals);
			//echo"\n all get \n->".$encoded_get." \n";
			$encoded =$encoded_post."&".$encoded_get;
			//$encoded = substr($encoded, 0, strlen($encoded)-1);
			//print  $this->cookieFile;
			//echo"\n all \n->".$encoded." \n";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_POSTFIELDS,  $encoded);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_USERAGENT, $this->useragent);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile); // Cookie aware
			curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookieFile); // Cookie aware
			$result=curl_exec($ch);
			curl_close($ch);
			return $result;
		}
		
		function WriteCacheFile($DisplayPage,$content){
			//try{
				$filename = "./".$this->LocalFileName($DisplayPage);
				if(count($this->Current_Full_Files)>0){
					if(isset($this->Current_Full_Files[$this->Current_Full_File_Index]["encoded_filename"])){
						$filename =$this->Current_Full_Files[$this->Current_Full_File_Index]["encoded_filename"];
						$filename =$this->slash_wrap($DisplayPage);
						if(strlen($content)>0){
							$this->Current_Opened_Cache_File_Location=$filename;
							//$filename=$this->absolute_cache_dir="xxx.txt";
							$filename=$this->cache_domain_dir."/html/".$filename;
							//$filename="../".$this->Current_Full_Files[$this->Current_Full_File_Index]["complete_cache_location"];
							//print "\n\n g->".$filename."- \n";
							$fh = fopen($filename, "w");
							fwrite($fh, $content);
							fclose($fh);
						}else{
						}
					}else{
					}
				}else{
				}
			//}catch(Exception $e){
				//print "\n\n g->".$filename."- \n";
			//}
		}
		
		function CheckIfCacheExists($DisplayPage){
			//$filename=$this->BaseDomainCacheDirectory."html/".$this->slash_wrap($DisplayPage);
			$filename="html/".$this->slash_wrap($DisplayPage);
			
			if($filename!=""){
				if(file_exists($filename)){
					if(filesize($filename)!=0){
					    //print "Exists ".$filename;
						return true;
					}else{
					    //print "1 No File".$filename;
						return false;
					}
				}else{
				    //print "2 No File".$filename;
					return false;
				}
			}else{
			    //print "3 No File".$filename;
				return false;
			}
		}
		
		function DisplayCacheFile($DisplayPage){
			$filename = $this->LocalFileName($DisplayPage);
			if($this->CheckIfCacheExists($DisplayPage)){
				$handle = fopen($filename, "r");
				$contents = fread($handle, filesize($filename));
				fclose($handle);
				if(strlen($contents)==0){
					unlink($filename);
					$ContType=mime_content_type($filename);
					header($ContType);
				}else{
					return $contents;
				}
			}else{
			    return false;
			}
		}
		
		function Error($error_text,$error_type=-1,$error_array=Array()){
		   
		}
		
		function IsValidFile($DisplayPage){
			
			return true;
		}
		
		function DisplayRealtime($DisplayPage="/"){
		    $filename=$this->get_file_directory("html");
			//$filename=$this->BaseDomainCacheDirectory."html/".$this->slash_wrap($DisplayPage);
			$last_cache_date=$this->get_file_date($filename);
			//$urldetails=$this->RemoteServer."?x=1&dcmshost=".urlencode($this->LocalServer)."&dcmsuri=".urlencode($this->Current_File)."&change=".urlencode($last_cache_date);
			$urldetails=$this->RemoteServer."?x=1&dcmshost=".urlencode($this->LocalServer)."&dcmsuri=".urlencode($this->Current_File);
			//print $urldetails;
			$retdata=$this->url_get_contents($urldetails);
			$mystring = $retdata;
			$findme   = 'Use Cached File';//'Use Cached File';
			$pos = strpos($mystring, $findme);
			$string_size=strlen($retdata);
			if($pos>-1){
				$retdata=$this->DisplayCacheFile($DisplayPage);
			}else{
				$this->WriteCacheFile($DisplayPage,$retdata);
			}
			//print $retdata;
			return $retdata;
		}
		
		function CommandInterface($DisplayPage){
			print $this->DisplayRealtime($DisplayPage);
		}
		
		
	}
?>