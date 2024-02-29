<?php
	
    session_start();
    

?>
<pre>
    XXX
<?php

    function callback($buffer)
	{
	    $pos = strpos($buffer, "<");
		$buffer=substr($buffer, $pos);
		return $buffer;
	    
	    
	}
	
	error_reporting(E_ALL);
    ini_set('display_errors', 1);

    
	echo"<br>\ncurrent server ip<br>\n";
	print $_SERVER['SERVER_ADDR'];
	echo"<br>\ncurrent server<br>\n";
	print $_SERVER['SERVER_NAME']."-<br>";
	$host_name=gethostname();
	echo"<br>\nserver hostname<br>\n";
	print $host_name."-<br>";
	
	$current_dir=pathinfo(__DIR__);
	echo"<br>\ncurrent dir<br>\n";
	print_r($current_dir)."<br>";
	echo"<br>\ncurrent file<br>\n";
	
	print($_SERVER['PHP_SELF']);
    
	//session_destroy();
    //unset($_SESSION);
    $dir="D:/Program Files/Ampps/www/bcms/sessions/";
    session_save_path($dir);
    session_start();
    
    // Check if the session variable 'count' is set
    if (!isset($_SESSION['count'])) {
        // If not set, initialize it to 0
        $_SESSION['count'] = 0;
    }
    
    // Increment the session variable 'count'
    $_SESSION['count']++;
    
    // Display the updated count
    echo "Count: {$_SESSION['count']}";
	print_r($_SESSION);
	
?>
</pre>
<?php
/*
echo "hello hell";
	if (!extension_loaded('sqlite')) {
		$prefix = (PHP_SHLIB_SUFFIX === 'dll') ? 'php_' : '';
		dl($prefix . 'sqlite.' . PHP_SHLIB_SUFFIX);
	}
	
	$output="";
	ob_start();
	phpinfo();
	$info = ob_get_clean();
	$info = preg_replace("/^.*?\<body\>/is", "", $info);
	$info = preg_replace("/<\/body\>.*?$/is", "", $info);
	print $info;
	*/
	phpinfo();
?>